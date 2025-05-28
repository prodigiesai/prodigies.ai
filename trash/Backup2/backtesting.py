import pandas as pd
from binance_api import fetch_binance_data
from indicators import calculate_technical_indicators
from model_training import train_predictive_model
from rss import analizar_sentimiento_rss
import warnings
from binance.client import Client as BinanceAPI
import json
import time

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

# Cargar datos de Binance y calcular indicadores
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
    max_items=20,
    palabras_clave=["BTC", "Bitcoin"]
)

# Definir características y entrenar el modelo
features = ['rsi', 'sma', 'ema', 'macd', 'macd_signal', 'sentiment_score']
df = df.dropna(subset=features)  # Asegurarse de no tener NaNs

# Variables para backtesting
results = []
total_buy_predictions = 0
correct_buy_predictions = 0

train_data = df.iloc[:-15]
model, scaler = train_predictive_model(train_data, features, 'close')

# Iterar sobre el conjunto de datos
for i in range(len(df) - 15):
    current_data = df.iloc[i:i+15]
    future_data = df.iloc[i + 15]
    
    # Escalar y predecir
    scaled_data = scaler.transform(current_data[features].iloc[[-1]])
    predicted_price = model.predict(scaled_data)[0]
    actual_price = future_data['close']
    current_price = current_data['close'].iloc[-1]

    # Decisión basada en sentimiento
    sentiment_score = current_data['sentiment_score'].iloc[-1]
    if sentiment_score > 60:
        decision = "BUY" if predicted_price > current_price else "SKIP"
    elif sentiment_score < 40:
        decision = "SKIP"
    else:
        decision = "BUY" if predicted_price > current_price else "SKIP"

    was_correct = (decision == "BUY" and actual_price > current_price)

    # Registrar decisiones de compra
    if decision == "BUY":
        total_buy_predictions += 1
        if was_correct:
            correct_buy_predictions += 1

    # Guardar resultados
    results.append(f"{current_data.index[-1]}, {current_price:.2f}, {predicted_price:.2f}, {actual_price:.2f}, {decision}, {'CORRECT' if was_correct else 'WRONG'}")

# Calcular precisión
accuracy = (correct_buy_predictions / total_buy_predictions) * 100 if total_buy_predictions > 0 else 0

# Guardar resultados en archivo
log_path = 'backtest_results.log'
with open(log_path, 'w') as log_file:
    log_file.write("timestamp, current_price, predicted_price, actual_price, decision, result\n")
    log_file.write("\n".join(results))
    log_file.write(f"\n\nAccuracy (BUY decisions): {accuracy:.2f}%\n")

print(f"Backtesting completed. Accuracy (BUY decisions): {accuracy:.2f}%")
print(f"Results saved to: {log_path}")