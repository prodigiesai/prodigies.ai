#!/usr/bin/env python3
from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split, GridSearchCV
from sklearn.preprocessing import StandardScaler
from coinbase.rest import RESTClient as CoinbaseAPI
import pandas as pd
import warnings
import json
import time
import numpy as np
from tensorflow.keras.models import Sequential  # type: ignore
from tensorflow.keras.layers import LSTM, Dense, Dropout  # type: ignore
from sklearn.preprocessing import MinMaxScaler
from sklearn.metrics import mean_absolute_error
from datetime import datetime, timedelta
import uuid  
import feedparser
from vaderSentiment.vaderSentiment import SentimentIntensityAnalyzer
import threading
import websocket
# Desactivar warnings
import warnings
import os
import argparse

# Command-line arguments
parser = argparse.ArgumentParser(description="Crypto Trading Bot")
parser.add_argument("--product", type=str, required=True, help="Trading pair (e.g., BTC-USD)")
parser.add_argument("--hashtags", type=str, required=True, help="Comma-separated hashtags (e.g., BTC,Bitcoin)")
parser.add_argument("--prediction_timeframe", type=int, required=True, help="Prediction timeframe in minutes (e.g., 15, 60)")

args = parser.parse_args()


warnings.filterwarnings("ignore")

# Leer configuración
with open('/Users/obarrientos/Documents/Prodigies/apps/crypto/config.json') as config_file:
    config = json.load(config_file)

# Map prediction timeframe to granularity and sequence settings
def get_granularity_and_sequence(prediction_timeframe):
    if prediction_timeframe <= 15:
        return "ONE_MINUTE", 60, prediction_timeframe
    elif prediction_timeframe <= 60:
        return "FIVE_MINUTE", 48, prediction_timeframe // 5
    elif prediction_timeframe <= 240:
        return "FIFTEEN_MINUTE", 64, prediction_timeframe // 15
    else:
        return "ONE_HOUR", 72, prediction_timeframe // 60


# Adjust granularity, sequence length, and future steps based on prediction timeframe
granularity, sequence_length, future_steps = get_granularity_and_sequence(args.prediction_timeframe)

# Global variable to hold the current price
current_price = None

coinbase_api_key = config['coinbase']['api_key']
coinbase_api_secret = config['coinbase']['api_secret']

usd_account = "2f70928e-3697-5ea8-ad26-00ac7d92f569"  # Cuenta en USD

# Inicializar Coinbase
coinbase_client = CoinbaseAPI(coinbase_api_key, coinbase_api_secret)


product = args.product # Use command-line arguments
hashtags = args.hashtags.split(",")  # Convert comma-separated string to list
risk_percentage = 1
hours = 6  # Para análisis de sentimiento
# max_candles = 350           # Coinbase API limit
start_time = datetime.now()
end_time = start_time + timedelta(minutes=15)
granularity_minutes = 1

# Report decision
print(f"------------- Crypto Bot V1 --------------------")
print(f"Trading Pair: {product}")
print(f"Hashtags: {hashtags}")
print(f"Start Time: {start_time.strftime("%Y-%m-%d %H:%M:%S")}")
print(f"End Time: { end_time.strftime("%Y-%m-%d %H:%M:%S")}")


# Inicializar el analizador VADER
analyzer = SentimentIntensityAnalyzer()

def analizar_sentimiento_rss(feeds, max_items=50, palabras_clave=None):
    """
    Analiza el sentimiento de las noticias en múltiples feeds RSS que cumplan ciertos criterios:
    - Contengan palabras clave en el título o contenido.
    - Sean de las últimas 24 horas.
    Retorna el porcentaje de noticias con sentimiento positivo.
    """
    try:
        positivos = 0
        total_items = 0
        ahora = datetime.utcnow()
        hace_24_horas = ahora - timedelta(hours=24)

        # print("\n--- Noticias analizadas ---\n")
        for feed_url in feeds:
            # print(f"\nAnalizando feed: {feed_url}\n")
            feed = feedparser.parse(feed_url)
            entries = feed.entries[:max_items]

            if not entries:
                # print(f"No se encontraron noticias en el feed: {feed_url}")
                continue

            for entry in entries:
                # Filtrar por fecha (últimas 24 horas)
                if 'published_parsed' in entry:
                    fecha_noticia = datetime(*entry.published_parsed[:6])
                    if fecha_noticia < hace_24_horas:
                        continue

                # Buscar palabras clave en el título o contenido
                title = entry.title
                # content = limpiar_html(entry.get('summary', ''))  # Limpiar contenido HTML
                content = entry.get('summary', '')
                if palabras_clave and not any(keyword.lower() in (title + content).lower() for keyword in palabras_clave):
                    continue

                # Analizar sentimiento
                texto_analizar = title + " " + content
                sentiment = analyzer.polarity_scores(texto_analizar)

                # Ponderar noticias más recientes
                horas_desde_publicacion = (ahora - fecha_noticia).total_seconds() / 3600
                peso = max(0, 1 - (horas_desde_publicacion / 24))  # Peso decae linealmente hasta 24 horas

                # print(f"Fecha: {fecha_noticia}")
                # print(f"Noticia: {title}")
                # print(f"Contenido: {content}")
                # print(f"Sentimiento: {sentiment}")
                # print(f"Peso asignado: {peso:.2f}\n")

                if sentiment['compound'] > 0:  # Sentimiento positivo
                    positivos += peso
                total_items += peso

        # Calcular porcentaje de sentimiento positivo
        return (positivos / total_items) * 100 if total_items > 0 else 0
    except Exception as e:
        print(f"Error al analizar: {e}")
        return None

def calculate_technical_indicators(df):
    # Asegúrate de que las columnas sean numéricas
    numeric_columns = ['close', 'open', 'high', 'low', 'volume']
    for col in numeric_columns:
        df[col] = pd.to_numeric(df[col], errors='coerce')

    # Elimina filas con valores no válidos
    df = df.dropna(subset=numeric_columns)

    # Cálculo de RSI
    delta = df['close'].diff(1)
    gain = delta.clip(lower=0)
    loss = -delta.clip(upper=0)

    avg_gain = gain.rolling(window=14, min_periods=14).mean()
    avg_loss = loss.rolling(window=14, min_periods=14).mean()

    rs = avg_gain / avg_loss
    df['rsi'] = 100 - (100 / (1 + rs))

    # Cálculo de SMA y EMA
    df['sma'] = df['close'].rolling(window=14).mean()
    df['ema'] = df['close'].ewm(span=14, adjust=False).mean()

    # Cálculo de MACD y señal MACD
    macd_line = df['close'].ewm(span=12).mean() - df['close'].ewm(span=26).mean()
    df['macd'] = macd_line
    df['macd_signal'] = macd_line.ewm(span=9).mean()

    # Cálculo de Bollinger Bands
    bb_period = 20  # Usualmente 20 períodos
    df['bb_middle'] = df['close'].rolling(window=bb_period).mean()  # Banda media
    df['bb_stddev'] = df['close'].rolling(window=bb_period).std()   # Desviación estándar
    df['bb_upper'] = df['bb_middle'] + (2 * df['bb_stddev'])        # Banda superior
    df['bb_lower'] = df['bb_middle'] - (2 * df['bb_stddev'])        # Banda inferior

    # Elimina filas con NaN después de los cálculos
    df = df.dropna()

    return df

def fetch_and_process_data():
   
    max_candles = sequence_length + future_steps  # Ensure enough data for prediction
    # Calculate the maximum duration in seconds based on the limit
    max_duration_seconds = max_candles * granularity_minutes * 60

    # Get the current time as end_time (in seconds)
    end_time_unix = int(time.time())

    # Calculate the start_time (in seconds)
    start_time_unix = max(end_time_unix - max_duration_seconds, end_time_unix - (4 * 60 * 60))

    try:
        # Fetch candles data
        candles_response = coinbase_client.get_candles(
            product_id=product,
            start=str(end_time_unix),
            end=str(end_time_unix),
            granularity=granularity,
        )

        # Ensure candles data is in the expected format
        if hasattr(candles_response, "candles"):
            candles = candles_response.candles
        else:
            raise ValueError("The response does not contain a 'candles' attribute.")

        if not isinstance(candles, list) or not candles:
            raise ValueError("No candle data found!")

        # Flatten the data if necessary
        candles = [
            [
                int(c['start']),
                float(c['low']),
                float(c['high']),
                float(c['open']),
                float(c['close']),
                float(c['volume'])
            ] for c in candles
        ]

        # Convert to DataFrame
        df = pd.DataFrame(candles, columns=['start', 'low', 'high', 'open', 'close', 'volume'])

        # Convert timestamp to datetime and numeric columns to appropriate types
        df['start'] = pd.to_datetime(df['start'], unit='s')
        for column in ['low', 'high', 'open', 'close', 'volume']:
            df[column] = pd.to_numeric(df[column])

        # print("Candles DataFrame:")
        # print(df.head())
       
        # Perform additional processing
        df = calculate_technical_indicators(df)
        df['sentiment_score'] = analizar_sentimiento_rss(
            feeds=[
                "https://cointelegraph.com/rss/tag/bitcoin",
                "https://www.coindesk.com/arc/outboundfeeds/rss",
                "https://blog.bitcoin.com/feed",
                "https://bitcoinmagazine.com/.rss/full"
            ],
            max_items=500,
            palabras_clave=hashtags
        )
        return df

    except Exception as e:
        print(f"Error fetching or processing data: {e}")
        return None

    except Exception as e:
        print(f"Error fetching or processing data: {e}")
        return None

def train_predictive_model(df, features, target):
    # Verifica que las características existan en el DataFrame
    missing_features = [feature for feature in features if feature not in df.columns]
    if missing_features:
        raise ValueError(f"Missing features in DataFrame: {missing_features}")

    # Elimina filas con valores NaN en las columnas de interés
    df = df.dropna(subset=features + [target])
    
    # Verifica si el DataFrame sigue teniendo datos
    if df.empty:
        raise ValueError("DataFrame is empty after dropping NaN values.")

    # Define las características (X) y la variable objetivo (y)
    X = df[features]
    y = df[target]

    # Escalado de datos
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X)

    # División de datos en entrenamiento y prueba
    X_train, X_test, y_train, y_test = train_test_split(X_scaled, y, test_size=0.3, random_state=42)

    # Configuración de hiperparámetros para Random Forest
    param_grid = {
        'n_estimators': [50, 100],  # En lugar de [50, 100, 200, 300]
        'max_depth': [3, 5],       # En lugar de [3, 5, 10, None]
        'min_samples_split': [2, 5]
    }

    # Grid Search para encontrar los mejores parámetros
    rf = RandomForestRegressor(random_state=42)
    grid_search = GridSearchCV(rf, param_grid, cv=3, n_jobs=-1)  # Menos combinaciones y folds
    grid_search.fit(X_train, y_train)

    # Retorna el mejor modelo y el escalador
    return grid_search.best_estimator_, scaler

def prepare_model_and_scaler(df):
    features = ['rsi', 'sma', 'ema', 'macd', 'macd_signal', 'sentiment_score']
    close_scaler = MinMaxScaler()    
    df['close_scaled'] = close_scaler.fit_transform(df[['close']])
    
    scaler = MinMaxScaler()
    scaled_features = scaler.fit_transform(df[features].dropna())
    
    # Prepare training data
    X, y = [], []
    scaled_features = MinMaxScaler().fit_transform(df[features].dropna())
    for i in range(sequence_length, len(scaled_features) - future_steps):
        X.append(scaled_features[i-sequence_length:i])
        y.append(df['close_scaled'].iloc[i + future_steps])
    
    X, y = np.array(X), np.array(y)
    split = int(0.8 * len(X))
    X_train, X_test = X[:split], X[split:]
    y_train, y_test = y[:split], y[split:]

    # Build and train LSTM model
    model = Sequential([
        LSTM(50, return_sequences=True, input_shape=(X_train.shape[1], X_train.shape[2])),
        Dropout(0.2),
        LSTM(50, return_sequences=False),
        Dropout(0.2),
        Dense(25),
        Dense(1)
    ])

    model.compile(optimizer='adam', loss='mean_squared_error')
    history = model.fit(X_train, y_train, batch_size=16, epochs=20, validation_data=(X_test, y_test), verbose=0   )

     # Calculate confidence based on training and validation loss
    training_loss_avg = np.mean(history.history['loss'])
    validation_loss_avg = np.mean(history.history['val_loss'])

    # Calculate the difference between validation and training loss
    loss_difference = validation_loss_avg - training_loss_avg
    # Adjust scaling factor and add a threshold
    if loss_difference < 0:
        confidence = 100  # Overfitting scenario
    else:
        # Scale the loss difference to a confidence value
        confidence = max(0, min(100, 100 - (loss_difference * 500)))  # Adjust scaling factor

    print(f"------------- Model Prediction ----------------")
    # print(f"Training Loss: {history.history['loss']}")
    # print(f"Validation Loss: {history.history['val_loss']}")
    # print(f"Loss Difference: {loss_difference:.6f}")
    print(f"Model Confidence: {confidence:.2f}%")
    
    return model, scaler, close_scaler, confidence

def generate_client_order_id():
    return str(uuid.uuid4())

def fetch_current_price(product_id):
    """
    Connects to the Coinbase WebSocket feed to fetch the current price in real-time.
    """
    global current_price

    def on_message(ws, message):
        global current_price
        data = json.loads(message)
        if data.get("type") == "ticker" and data.get("product_id") == product_id:
            current_price = float(data["price"])
            print(f"Updated price for {product_id}: {current_price}")

    def on_error(ws, error):
        print(f"WebSocket error: {error}")

    def on_close(ws, close_status_code, close_msg):
        print("WebSocket closed")

    def on_open(ws):
        subscription_message = {
            "type": "subscribe",
            "channel": "ticker",
            "product_ids": [product_id]
        }
        ws.send(json.dumps(subscription_message))

    ws_url = "wss://advanced-trade-ws.coinbase.com"
    ws = websocket.WebSocketApp(
        ws_url,
        on_message=on_message,
        on_error=on_error,
        on_close=on_close
    )
    ws.on_open = on_open
    ws.run_forever()

# Start WebSocket connection in a separate thread
def start_websocket(product_id):
    ws_thread = threading.Thread(target=fetch_current_price, args=(product_id,))
    ws_thread.daemon = True
    ws_thread.start()

def execute_trade(product, amount, target_price, stop_loss, start_time, end_time):
    global current_price

    # Start WebSocket for real-time price updates
    start_websocket(product)

    with open('/Users/obarrientos/Documents/Prodigies/apps/crypto/config.json') as config_file:
        config = json.load(config_file)

    # Adjust precision of the order size
    quote_size_usd = round(amount, 2)  # Limit to 2 decimals

    try:
        client_order_id = generate_client_order_id()
        # Execute market buy order
        response = coinbase_client.market_order_buy(
            client_order_id=client_order_id,
            product_id=product,
            quote_size=str(quote_size_usd)  # Amount in USD
        )
        print("Buy order executed:", response)
    except Exception as e:
        print("Error executing buy order:", e)
        return

    while datetime.now() < end_time:
        # Wait for the WebSocket to update the current price
        if current_price is None:
            print("Waiting for price update...")
            time.sleep(5)
            continue

        # Check if profit target or stop loss is hit
        if current_price >= target_price:
            print("Profit target reached. Placing market sell order...")
            base_size_btc = round(quote_size_usd / current_price, 8)
            client_order_id = generate_client_order_id()
            sell_order = coinbase_client.market_order_sell(
                client_order_id=client_order_id,
                product_id=product,
                base_size=str(base_size_btc)
            )
            print("Sell order executed:", sell_order)
            break
        elif current_price <= stop_loss:
            print("Stop loss triggered. Placing market sell order...")
            base_size_btc = round(quote_size_usd / current_price, 8)
            client_order_id = generate_client_order_id()
            sell_order = coinbase_client.market_order_sell(
                client_order_id=client_order_id,
                product_id=product,
                base_size=str(base_size_btc)
            )
            print("Sell order executed:", sell_order)
            break
        else:
            print(f"Current price {current_price:.2f} is within the range.")
            time.sleep(60)
    else:
        print("Time limit reached. Placing market sell order...")
        base_size_btc = round(quote_size_usd / current_price, 8)
        client_order_id = generate_client_order_id()
        sell_order = coinbase_client.market_order_sell(
            client_order_id=client_order_id,
            product_id=product,
            base_size=str(base_size_btc)
        )
        print("Sell order executed:", sell_order)

def execute_trading(df, model, scaler, close_scaler, confidence ):

    try:
        # Prepare the latest data for prediction
        latest_data = df[['rsi', 'sma', 'ema', 'macd', 'macd_signal', 'sentiment_score']].iloc[-sequence_length:].values
        latest_data_scaled = scaler.transform(latest_data)
        latest_data_scaled = np.expand_dims(latest_data_scaled, axis=0)

        # print("Latest Data Scaled (Input to Model):")
        # print(latest_data_scaled)

        # Predict
        raw_prediction = model.predict(latest_data_scaled, verbose=0)
        scaled_prediction = close_scaler.inverse_transform([[raw_prediction[0][0]]])[0][0]

        # print(f"Raw Prediction (Normalized): {raw_prediction}")
        # print(f"Scaled Prediction (Original Price Range): {scaled_prediction}")
    except Exception as e:
        print(f"Error during prediction: {e}")
        return

    # Trading logic
    current_price = df['close'].iloc[-1]
    sentiment_score = df['sentiment_score'].iloc[-1]

    if scaled_prediction <= 0 or abs(current_price - scaled_prediction) > 0.1 * current_price:
        print("Unrealistic Prediction. Skipping Trade.")
        return

    atr = df['high'].iloc[-1] - df['low'].iloc[-1]
    stop_loss = current_price - (1.5 * atr) if atr > 0 else current_price * 0.99  # Set fallback in case of invalid ATR
    price_difference = scaled_prediction - current_price

    # Retrieve account balance
    try:
        account = coinbase_client.get_account(usd_account)
        account_info = account.account
        available_balance = float(account_info['available_balance']['value'])
    except Exception as e:
        print(f"Error fetching account balance: {e}")
        available_balance = 0

    risk_amount = available_balance * (risk_percentage / 100)

    percentage_change = (price_difference / scaled_prediction) * 100
    # Calculate gain opportunity
    gains_opportunity = (percentage_change / 100) * risk_amount


    decision = "BUY" if scaled_prediction > current_price and risk_amount >= 5 and sentiment_score >= 40 and gains_opportunity >= 10 and confidence >= 70 else "SKIP"


    print(f"----------------- Analysis --------------------")
    print(f"Model Prediction: {decision} ({confidence:.2f}%)")
    print(f"Sentiment Score: {sentiment_score}")
    print(f"Market Price: {current_price:.2f}")
    print(f"Predicted Price: {scaled_prediction:.2f} ({price_difference:.2f}, {percentage_change:.2f}%)")    
    print(f"Gain Opportunity: {gains_opportunity:.2f} ({percentage_change:.2f}%)")   
    print(f"Risk Capital: {risk_amount:.2f} of {available_balance:.2f}")   
    
    print(f"------------------- Actions -------------------")

    if decision == "BUY":
        execute_trade(product, risk_amount, current_price, scaled_prediction, stop_loss, start_time, end_time)
    else:
        print("No trade executed.")

# Main
def main():
    df = fetch_and_process_data()

    if df is None:
        print("Failed to fetch data. Exiting...")
        return

    try:
        model, scaler, close_scaler, confidence = prepare_model_and_scaler(df)
        execute_trading(df, model, scaler, close_scaler, confidence)
    except Exception as e:
        print(f"Error in trading pipeline: {e}")


if __name__ == "__main__":
    main()