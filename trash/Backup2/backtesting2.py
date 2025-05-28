import pandas as pd
import numpy as np
from tensorflow.keras.models import Sequential  # type: ignore
from tensorflow.keras.layers import LSTM, Dense, Dropout  # type: ignore
from sklearn.preprocessing import MinMaxScaler
from sklearn.metrics import mean_absolute_error
from rss import analizar_sentimiento_rss
import json
import time
from binance.client import Client as BinanceAPI
from indicators import calculate_technical_indicators

# Desactivar warnings
import warnings
warnings.filterwarnings("ignore")

# Leer configuración
with open('config.json') as config_file:
    config = json.load(config_file)

binance_api_key = config['binance']['api_key']
binance_api_secret = config['binance']['api_secret']

# Inicializar cliente Binance
binance_client = BinanceAPI(binance_api_key, binance_api_secret, tld='us')
symbol = 'BTCUSDT'
interval = '1m'
profit_margin = 0.009  # 0.9%
current_time_ms = int(time.time() * 1000)
twelve_hours_ms = 12 * 60 * 60 * 1000
start_time = current_time_ms - twelve_hours_ms

# Función para obtener datos de Binance
def fetch_binance_data(client, symbol, interval, start_time):
    candles = client.get_klines(symbol=symbol, interval=interval, startTime=start_time)
    df = pd.DataFrame(candles, columns=[
        'timestamp', 'open', 'high', 'low', 'close', 'volume', 'close_time',
        'quote_asset_volume', 'number_of_trades', 'taker_buy_base_volume', 'taker_buy_quote_volume', 'ignore'
    ])
    df = df[['timestamp', 'open', 'high', 'low', 'close', 'volume']]
    df[['open', 'high', 'low', 'close', 'volume']] = df[['open', 'high', 'low', 'close', 'volume']].astype(float)
    df['timestamp'] = pd.to_datetime(df['timestamp'], unit='ms')
    return df

# Cargar datos de Binance
df = fetch_binance_data(binance_client, symbol, interval, start_time)

df = calculate_technical_indicators(df)


# Cálculo dinámico del Sentiment Score (simulado)
df['sentiment_score'] = analizar_sentimiento_rss(
    feeds=[
        "https://cointelegraph.com/rss/tag/bitcoin",
        "https://www.coindesk.com/arc/outboundfeeds/rss",
        "https://blog.bitcoin.com/feed",
        "https://bitcoinmagazine.com/.rss/full/"
    ],
    max_items=100,
    palabras_clave=["BTC", "Bitcoin"]
)


# Escalado de datos
scaler = MinMaxScaler()
scaled_data = scaler.fit_transform(df[['close', 'rsi', 'sma', 'ema', 'macd', 'macd_signal', 'sentiment_score']].dropna())

# Preparar datos para LSTM
sequence_length = 60  # Usar los últimos 60 minutos
X, y = [], []

for i in range(sequence_length, len(scaled_data)):
    X.append(scaled_data[i-sequence_length:i])
    y.append(scaled_data[i, 0])  # Precio de cierre como objetivo

X, y = np.array(X), np.array(y)

# Dividir en conjunto de entrenamiento y prueba
split = int(0.8 * len(X))
X_train, X_test = X[:split], X[split:]
y_train, y_test = y[:split], y[split:]

# Construir modelo LSTM
def build_lstm_model(input_shape):
    model = Sequential([
        LSTM(50, return_sequences=True, input_shape=input_shape),
        Dropout(0.2),
        LSTM(50, return_sequences=False),
        Dropout(0.2),
        Dense(25),
        Dense(1)
    ])
    model.compile(optimizer='adam', loss='mean_squared_error')
    return model

model = build_lstm_model((X_train.shape[1], X_train.shape[2]))

# Entrenar el modelo
model.fit(X_train, y_train, batch_size=32, epochs=10, validation_data=(X_test, y_test))

# Predicted Prices
predicted_prices = model.predict(X_test)
placeholder_predicted = np.zeros((len(predicted_prices), scaler.n_features_in_))
placeholder_predicted[:, 0] = predicted_prices[:, 0]
predicted_prices = scaler.inverse_transform(placeholder_predicted)[:, 0]

# Actual Prices
placeholder_actual = np.zeros((len(y_test), scaler.n_features_in_))
placeholder_actual[:, 0] = y_test
actual_prices = scaler.inverse_transform(placeholder_actual)[:, 0]

mae = mean_absolute_error(actual_prices, predicted_prices)

print(f"Mean Absolute Error: {mae:.2f}")

# Resultados detallados para backtesting
detailed_results = []

# Iterar en el conjunto de prueba
for i in range(len(predicted_prices) - 15):
    current_price = actual_prices[i]  # Precio original (inicio del intervalo de 15 minutos)
    predicted_price = predicted_prices[i]  # Predicción del modelo
    actual_price = actual_prices[i + 15]  # Precio real después de 15 minutos


    # Extraer valores de indicadores técnicos para este momento
    rsi = df['rsi'].iloc[i + sequence_length]
    sma = df['sma'].iloc[i + sequence_length]
    ema = df['ema'].iloc[i + sequence_length]
    macd = df['macd'].iloc[i + sequence_length]
    macd_signal = df['macd_signal'].iloc[i + sequence_length]
    sentiment_score = df['sentiment_score'].iloc[i + sequence_length]

    # Lógica de decisión
    if predicted_price > current_price:
        decision = "BUY"
        # Explicación basada en indicadores técnicos
        reason = (
            f"Predicted price ({predicted_price:.2f}) > current price ({current_price:.2f}). "
            f"RSI={rsi:.2f} (neutral{' - overbought' if rsi > 70 else ' - oversold' if rsi < 30 else ''}), "
            f"SMA={sma:.2f}, EMA={ema:.2f}, MACD={macd:.2f}, MACD signal={macd_signal:.2f}, "
            f"Sentiment score={sentiment_score:.2f} (positive{' - very high' if sentiment_score > 70 else ' - negative' if sentiment_score < 40 else ''})."
        )
    else:
        decision = "SKIP"
        reason = "Decision was SKIP; no detailed explanation provided."

    # Determinar si fue correcto
    was_correct = (decision == "BUY" and actual_price > current_price) or \
                  (decision == "SKIP" and actual_price <= current_price)

    # Registrar solo las decisiones "BUY"
    if decision == "BUY":
        detailed_results.append({
            "timestamp": i,
            "current_price": current_price,
            "predicted_price": predicted_price,
            "actual_price": actual_price,
            "decision": decision,
            "result": "CORRECT" if was_correct else "WRONG",
            "reason": reason  # Agregar la explicación de la decisión
        })

# Convertir a DataFrame y guardar en CSV
detailed_results_df = pd.DataFrame(detailed_results)
detailed_results_df.to_csv('backtesting_detailed_results.csv', index=False)

print("Detailed backtesting results saved to backtesting_detailed_results.csv")

# Calcular porcentaje de aciertos
correct_predictions = sum(1 for result in detailed_results if result["result"] == "CORRECT")
accuracy_percentage = (correct_predictions / len(detailed_results)) * 100

print(f"Accuracy (Direction Matching): {accuracy_percentage:.2f}%")