import os
import time
import pandas as pd
from datetime import datetime
from pytz import timezone
from binance.client import Client
from sklearn.model_selection import train_test_split, cross_val_score, GridSearchCV
from sklearn.ensemble import RandomForestRegressor
from sklearn.preprocessing import StandardScaler
from sklearn.metrics import mean_absolute_error
import ta
from ta.volatility import BollingerBands
from ta.momentum import RSIIndicator, StochasticOscillator
from ta.trend import MACD, SMAIndicator, EMAIndicator
from ta.volume import OnBalanceVolumeIndicator
import numpy as np
# import matplotlib.pyplot as plt
import warnings
import requests
from coinbase.rest import RESTClient
from ta.volatility import AverageTrueRange

LOCK_FILE = "/tmp/trading_bot.lock"

def check_lock():
    if os.path.exists(LOCK_FILE):
        print("Otra instancia está ejecutándose. Saliendo...")
        exit()
    else:
        open(LOCK_FILE, 'w').close()

def release_lock():
    if os.path.exists(LOCK_FILE):
        os.remove(LOCK_FILE)

try:
    check_lock()

    api_key_coinbase = "organizations/64d6b82e-d84a-4359-a5e1-02c45d6d5a94/apiKeys/eb358a7e-0963-41bf-94e4-871228021a10"
    api_secret_coinbase = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIFhGsJvjGro9vkBk9O+LOyBTSP2T419zGqYho+g73A9HoAoGCCqGSM49\nAwEHoUQDQgAEGCBbxSHe3pScIvvTlwSbauoeP9HMLvHW+iKpdAGjtKqBehZ5Q+s8\ntqdcmGlMqgXrSt/S27WLC4SvxcDFZ3VFeQ==\n-----END EC PRIVATE KEY-----\n"

    vfilename = 'historical_data_2.csv'

    api_key = "organizations/64d6b82e-d84a-4359-a5e1-02c45d6d5a94/apiKeys/eb358a7e-0963-41bf-94e4-871228021a10"
    api_secret = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIFhGsJvjGro9vkBk9O+LOyBTSP2T419zGqYho+g73A9HoAoGCCqGSM49\nAwEHoUQDQgAEGCBbxSHe3pScIvvTlwSbauoeP9HMLvHW+iKpdAGjtKqBehZ5Q+s8\ntqdcmGlMqgXrSt/S27WLC4SvxcDFZ3VFeQ==\n-----END EC PRIVATE KEY-----\n"

    client = Client(api_key=api_key, api_secret=api_secret, tld='us')

    def update_historical_data(symbol, interval='1h', filename=vfilename):
        # Step 1: Load existing file if it exists; otherwise, create a new one
        try:
            df_existing = pd.read_csv(filename, index_col='timestamp', parse_dates=True)
        except FileNotFoundError:
            print("Archivo no encontrado, creando uno nuevo.")
            df_existing = pd.DataFrame(columns=['timestamp', 'open', 'high', 'low', 'close', 'volume', 'close_time',
                                                'quote_asset_volume', 'number_of_trades', 'taker_buy_base_asset_volume',
                                                'taker_buy_quote_asset_volume', 'ignore'])
            df_existing.set_index('timestamp', inplace=True)

        last_timestamp = df_existing.index[-1] if not df_existing.empty else pd.Timestamp("NaT")

        # Check if last_timestamp is NaT; if so, set a default start date
        if pd.isna(last_timestamp):
            last_timestamp = datetime(2024, 11, 11, tzinfo=timezone('UTC'))
            print("No existing data found; starting from default date 2024-11-11.")
        else:
            print(f"Último dato registrado: {last_timestamp}")

        # Step 2: Retrieve data from Binance starting from the last recorded timestamp
        new_data = []
        start_time = int(last_timestamp.timestamp() * 1000) + 1  # Start just after the last record

        while True:
            klines = client.get_klines(symbol=symbol, interval=interval, startTime=start_time, limit=1000)

            if not klines:
                break  # Stop if there are no more data

            # Convert retrieved data to a DataFrame
            temp_df = pd.DataFrame(klines, columns=[
                'timestamp', 'open', 'high', 'low', 'close', 'volume', 'close_time',
                'quote_asset_volume', 'number_of_trades', 'taker_buy_base_asset_volume',
                'taker_buy_quote_asset_volume', 'ignore'
            ])

            # Format only new data from API
            temp_df['timestamp'] = pd.to_datetime(temp_df['timestamp'], unit='ms', utc=True)
            temp_df['timestamp'] = temp_df['timestamp'].dt.tz_convert('America/New_York')
            temp_df.set_index('timestamp', inplace=True)

            # Append the DataFrame to the list
            new_data.append(temp_df)

            # Update the start time for the next API call
            start_time = int(temp_df.index[-1].timestamp() * 1000) + 1

            # Pause to avoid API rate limits
            time.sleep(1)

        # Step 3: Concatenate and remove duplicates if necessary
        if new_data:
            df_new = pd.concat(new_data)
            df_combined = pd.concat([df_existing, df_new]).drop_duplicates()

            # Save the updated file
            df_combined.to_csv(filename)
            print(f"Archivo {filename} actualizado con {len(df_new)} registros nuevos.")
        else:
            print("No hay datos nuevos para añadir.")

    # Call the function to update the file
    update_historical_data('BTCUSDT', interval='1m', filename=vfilename)


    warnings.filterwarnings("ignore", category=FutureWarning)

    # Load the dataset
    df = pd.read_csv('historical_data_2.csv', index_col='timestamp', parse_dates=True)


    # Resample to 15-minute intervals if needed
    df = df.resample('15T').agg({
        'open': 'first',
        'high': 'max',
        'low': 'min',
        'close': 'last',
        'volume': 'sum'
    }).dropna()

    # Add technical indicators
    bb = BollingerBands(close=df['close'], window=20, window_dev=2)  # Bollinger Bands adjusted for 15T
    df['bb_high'] = bb.bollinger_hband()
    df['bb_low'] = bb.bollinger_lband()

    df['rsi'] = RSIIndicator(close=df['close'], window=14).rsi()
    df['sma'] = SMAIndicator(close=df['close'], window=14).sma_indicator()
    df['ema'] = EMAIndicator(close=df['close'], window=14).ema_indicator()
    macd = MACD(close=df['close'], window_slow=26, window_fast=12, window_sign=9)
    df['macd'] = macd.macd()
    df['macd_signal'] = macd.macd_signal()
    df['stoch'] = StochasticOscillator(close=df['close'], high=df['high'], low=df['low'], window=14).stoch()
    df['obv'] = OnBalanceVolumeIndicator(close=df['close'], volume=df['volume']).on_balance_volume()

    # Additional features
    df['price_change'] = (df['close'] - df['open']) / df['open']
    df['high_low_diff'] = df['high'] - df['low']
    df['volume_change'] = df['volume'].diff()

    # Define Buy/Sell signals
    df['buy_signal'] = np.where((df['rsi'] < 30) & (df['close'] < df['bb_low']), 1, 0)
    df['sell_signal'] = np.where((df['rsi'] > 70) & (df['close'] > df['bb_high']), -1, 0)

    # Add prediction target (15 minutes ahead)
    df['future_close'] = df['close'].shift(-1)

    # Drop rows with NaNs created by shifting
    data = df[['rsi', 'sma', 'ema', 'macd', 'macd_signal', 'stoch', 'obv', 
            'price_change', 'high_low_diff', 'volume_change', 'future_close']].dropna()

    # Define features and target
    features = ['rsi', 'sma', 'ema', 'macd', 'macd_signal', 'stoch', 'obv', 'price_change', 'high_low_diff', 'volume_change']
    X = data[features]
    y = data['future_close']

    # Scale features
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X)

    # Train/test split
    X_train, X_test, y_train, y_test = train_test_split(X_scaled, y, test_size=0.3, random_state=42)

    # Hyperparameter tuning for Random Forest Regressor
    param_grid = {
        'n_estimators': [50, 100, 200],
        'max_depth': [None, 10, 20],
        'min_samples_split': [2, 5],
        'min_samples_leaf': [1, 2],
    }
    rf = RandomForestRegressor(random_state=42)
    grid_search = GridSearchCV(rf, param_grid, cv=3, scoring='neg_mean_absolute_error', n_jobs=-1)
    grid_search.fit(X_train, y_train)

    # Best model
    best_model = grid_search.best_estimator_

    # Evaluate model
    y_pred = best_model.predict(X_test)
    mae = mean_absolute_error(y_test, y_pred)

    # print(f"Mean Absolute Error (Optimized RF): {mae:.2f}")
    # print(f"Best Hyperparameters: {grid_search.best_params_}")

    # Feature importance
    feature_importance = pd.DataFrame({
        'feature': features,
        'importance': best_model.feature_importances_
    }).sort_values(by='importance', ascending=False)

    # print("\nFeature Importance:")
    # print(feature_importance)

    # Prediction for the latest data
    latest_data = scaler.transform(X.iloc[[-1]])  # Scale the latest data
    predicted_price = best_model.predict(latest_data)[0]
    current_price = df['close'].iloc[-1]

    # print(f"Predicted price for the next 15m: ${predicted_price:.2f}")
    # print(f"Current price: ${current_price:.2f}")
    # print(f"Expected Gain: ${predicted_price-current_price:.2f}")


    # Visualize Bollinger Bands and Buy/Sell signals
    # plt.figure(figsize=(12, 6))
    # plt.plot(df.index, df['close'], label='Close Price', color='blue')
    # plt.plot(df.index, df['bb_high'], linestyle='--', label='Bollinger High', color='orange')
    # plt.plot(df.index, df['bb_low'], linestyle='--', label='Bollinger Low', color='green')
    # plt.scatter(df.index[df['buy_signal'] == 1], df['close'][df['buy_signal'] == 1], marker='^', color='g', label='Buy Signal')
    # plt.scatter(df.index[df['sell_signal'] == -1], df['close'][df['sell_signal'] == -1], marker='v', color='r', label='Sell Signal')
    # plt.legend()
    # plt.title('Buy/Sell Signals with Bollinger Bands and RSI')
    # plt.xlabel('Time')
    # plt.ylabel('Price')
    # plt.show()


    # Calculate ATR if not already done
    if 'atr' not in df.columns:
        atr = AverageTrueRange(high=df['high'], low=df['low'], close=df['close'], window=14)
        df['atr'] = atr.average_true_range()

    # Explanation based on prediction and signals
    def trading_strategy_explanation(current_price, predicted_price, atr_value):
        try:
            if np.isnan(atr_value):
                raise ValueError("ATR value is missing or invalid. Ensure ATR is calculated correctly.")

            if predicted_price > current_price:
                action = "BUY"
                expected_gain = predicted_price - current_price
                stop_loss = current_price - (atr_value * 1.5)  # Using 1.5x ATR for stop-loss
                risk_reward_ratio = expected_gain / (current_price - stop_loss)
                reason = (
                    f"The model predicts the price will increase from ${current_price:.2f} to "
                    f"${predicted_price:.2f}, suggesting an opportunity to buy. "
                    f"Set a stop-loss at ${stop_loss:.2f} to manage risk. "
                    f"The risk/reward ratio is approximately {risk_reward_ratio:.2f}."
                )
            else:
                action = "SELL"
                expected_gain = current_price - predicted_price
                stop_loss = current_price + (atr_value * 1.5)  # Using 1.5x ATR for stop-loss
                risk_reward_ratio = expected_gain / (stop_loss - current_price)
                reason = (
                    f"The model predicts the price will decrease from ${current_price:.2f} to "
                    f"${predicted_price:.2f}, suggesting an opportunity to sell. "
                    f"Set a stop-loss at ${stop_loss:.2f} to manage risk. "
                    f"The risk/reward ratio is approximately {risk_reward_ratio:.2f}."
                )

            # Print the summary explanation
            print("\n=== Trading Strategy Recommendation ===")
            print(f"Action: {action}")
            print(f"Expected Gain: ${expected_gain:.2f}")
            print(f"Recommended Stop-Loss: ${stop_loss:.2f}")
            print(f"Risk/Reward Ratio: {risk_reward_ratio:.2f}")
            print("Reason:", reason)

            # Optionally return values for logging or further use
            return {
                "action": action,
                "expected_gain": expected_gain,
                "stop_loss": stop_loss,
                "risk_reward_ratio": risk_reward_ratio,
                "reason": reason,
            }

        except ValueError as e:
            print(f"Error in trading strategy explanation: {e}")

    # Call the function to print the explanation
    try:
        current_price = df['close'].iloc[-1]  # Latest closing price
        predicted_price_value = predicted_price  # Ensure predicted_price is calculated
        atr_value = df['atr'].iloc[-1]  # Latest ATR value for stop-loss calculation

        trading_strategy_explanation(current_price, predicted_price_value, atr_value)

    except Exception as e:
        print(f"Error in calculating trading strategy: {e}")


    client = RESTClient(api_key=api_key_coinbase, api_secret=api_secret_coinbase)

    account = client.get_account('070b18bc-7098-5515-bbdd-ced28f7b7733');
    account_info = account.account  # Access the nested account details directly

    # Prepare the data for the DataFrame using attribute access
    account_data = {
        'UUID': account_info['uuid'],
        'Name': account_info['name'],
        'Currency': account_info['currency'],
        'Available Balance': account_info['available_balance']['value'],
        'Balance Currency': account_info['available_balance']['currency'],
        'Default': account_info['default'],
        'Active': account_info['active'],
        'Type': account_info['type'],
        'Created At': account_info['created_at'],
        'Updated At': account_info['updated_at']
    }

    # Convert the dictionary to a DataFrame
    df_account = pd.DataFrame([account_data])

    # print(df_account)

    # Configurar porcentaje de riesgo (por defecto 1%)
    def calcular_cantidad_a_riesgar(balance_disponible, porcentaje_riesgo=1):
        """
        Calcula la cantidad a arriesgar basada en el saldo disponible y el porcentaje de riesgo.
        """
        cantidad_a_riesgar = balance_disponible * (porcentaje_riesgo / 100)
        return round(cantidad_a_riesgar, 8)  # Redondear a 8 decimales (estándar para BTC)

    # Obtener el saldo disponible en BTC
    saldo_disponible_btc = float(account_info['available_balance']['value'])

    # Calcular la cantidad a arriesgar
    porcentaje_riesgo = 1  # Cambia a 2 si prefieres un riesgo más alto
    cantidad_a_riesgar = calcular_cantidad_a_riesgar(saldo_disponible_btc, porcentaje_riesgo)
    print(f"Saldo disponible en BTC: {saldo_disponible_btc:.8f}")
    print(f"Cantidad a arriesgar (1%): {cantidad_a_riesgar:.8f} BTC")


    import uuid

    def generate_client_order_id():
        return str(uuid.uuid4())

    # Función para comprar Bitcoin
    def comprar_bitcoin(quote_size_usd, precio_stop=None, precio_limit=None):
        """
        Realiza una orden de compra de mercado.
        
        :param quote_size_usd: Monto en USD para comprar BTC.
        :param precio_stop: No usado aquí (placeholder para órdenes avanzadas).
        :param precio_limit: No usado aquí (placeholder para órdenes avanzadas).
        """
        
        client_order_id = generate_client_order_id()
        # Ajustar precisión del tamaño de la orden
        quote_size_usd = round(quote_size_usd, 2)  # Limitar a 2 decimales
        try:
            response = client.market_order_buy(
                client_order_id=client_order_id,
                product_id="BTC-USD",  # Par de mercado
                quote_size=str(quote_size_usd)  # Monto en USD
            )
            print("Orden de compra completada:", response)
        except Exception as e:
            print("Error al realizar la compra:", e)

    # Función para vender Bitcoin
    def vender_bitcoin(base_size_btc, precio_stop=None, precio_limit=None):
        """
        Realiza una orden de venta de mercado.
        
        :param base_size_btc: Cantidad de BTC a vender.
        :param precio_stop: No usado aquí (placeholder para órdenes avanzadas).
        :param precio_limit: No usado aquí (placeholder para órdenes avanzadas).
        """
        client_order_id = generate_client_order_id()
        try:
            response = client.market_order_sell(
                client_order_id=client_order_id,
                product_id="BTC-USD",  # Par de mercado
                base_size=str(base_size_btc)  # Cantidad de BTC
            )
            print("Orden de venta completada:", response)
        except Exception as e:
            print("Error al realizar la venta:", e)

    # Implementar lógica de compra/venta con gestión de riesgos e interés compuesto
    def ejecutar_transaccion(predicted_price, current_price, atr_value, saldo_disponible, porcentaje_riesgo=1):
        """
        Ejecuta una transacción usando un porcentaje del saldo disponible con stop-loss dinámico.
        """
        cantidad_a_riesgar = calcular_cantidad_a_riesgar(saldo_disponible, porcentaje_riesgo)

        # Calcular precios para stop-loss y objetivo
        stop_loss = current_price - (atr_value * 1.5) if predicted_price > current_price else current_price + (atr_value * 1.5)
        objetivo = predicted_price

        if predicted_price > current_price:
            print("\n=== Estrategia de Compra ===")
            print(f"Predicción de precio: ${predicted_price:.2f}")
            print(f"Precio actual: ${current_price:.2f}")
            print(f"Cantidad a arriesgar: {cantidad_a_riesgar:.8f} BTC")
            print(f"Stop-Loss: ${stop_loss:.2f}")
            print(f"Objetivo: ${objetivo:.2f}")
            time.sleep(15 * 60)  # Pausa de 15 minutos
            # Colocar orden de compra y órdenes relacionadas
            comprar_bitcoin(cantidad_a_riesgar * current_price, precio_stop=current_price, precio_limit=current_price * 1.01)

            # Colocar stop-loss para controlar el riesgo
            vender_bitcoin(cantidad_a_riesgar, precio_stop=stop_loss, precio_limit=stop_loss * 0.99)
            

            # Colocar orden de venta para capturar ganancias
            vender_bitcoin(cantidad_a_riesgar, precio_stop=objetivo, precio_limit=objetivo * 0.99)

        else:
            print("\n=== Estrategia de Venta ===")
            print(f"Predicción de precio: ${predicted_price:.2f}")
            print(f"Precio actual: ${current_price:.2f}")
            print(f"Cantidad a arriesgar: {cantidad_a_riesgar:.8f} BTC")
            print(f"Stop-Loss: ${stop_loss:.2f}")
            print(f"Objetivo: ${objetivo:.2f}")
            time.sleep(15 * 60)  # Pausa de 15 minutos
            # Colocar orden de venta y órdenes relacionadas
            vender_bitcoin(cantidad_a_riesgar, precio_stop=current_price, precio_limit=current_price * 0.99)

            # Colocar orden de compra para capturar ganancias
            comprar_bitcoin(cantidad_a_riesgar * current_price, precio_stop=objetivo, precio_limit=objetivo * 1.01)

            # Colocar stop-loss para limitar pérdidas
            comprar_bitcoin(cantidad_a_riesgar * current_price, precio_stop=stop_loss, precio_limit=stop_loss * 1.01)

    # Obtener datos necesarios para la estrategia
    current_price = df['close'].iloc[-1]  # Último precio actual
    predicted_price_value = predicted_price  # Precio predicho del modelo
    atr_value = df['atr'].iloc[-1]  # Último valor del ATR

    # Ejecutar la estrategia con el saldo disponible y el porcentaje de riesgo
    ejecutar_transaccion(predicted_price_value, current_price, atr_value, saldo_disponible_btc, porcentaje_riesgo)


    print("Iniciando operación de trading...")
    time.sleep(15 * 60)  # Espera de 15 minutos
    print("Operación completada.")
finally:
    release_lock()

# %%



