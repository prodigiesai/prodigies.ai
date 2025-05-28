#!/usr/bin/env python3
from binance_api import fetch_binance_data
from parallel_processing import process_data_in_parallel
from indicators import calculate_technical_indicators
from model_training import train_predictive_model
from trading_strategy import execute_trade
from coinbase.rest import RESTClient as CoinbaseAPI
from binance.client import Client as BinanceAPI
import pandas as pd
import warnings
import os
import json
import time
from reddit import analizar_sentimiento_vader

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

def main():
    # try:
        # check_lock()
        
        # Suppress UserWarning
        warnings.filterwarnings("ignore", category=UserWarning)

        # Suppress FutureWarning
        warnings.filterwarnings("ignore", category=FutureWarning)    


        with open('config.json') as config_file:
            config = json.load(config_file)

        binance_api_key = config['binance']['api_key']
        binance_api_secret = config['binance']['api_secret']
        coinbase_api_key = config['coinbase']['api_key']
        coinbase_api_secret = config['coinbase']['api_secret']

        # btc_account = "070b18bc-7098-5515-bbdd-ced28f7b7733"  #BTC
        usd_account = "2f70928e-3697-5ea8-ad26-00ac7d92f569"  #USD

        # Initialize APIs
        binance_client = BinanceAPI(binance_api_key, binance_api_secret, tld='us')
        coinbase_client = CoinbaseAPI(coinbase_api_key, coinbase_api_secret)

        symbol = 'BTCUSDT'
        interval = '1m'
        filename = 'prices.log'
        product = "BTC-USD"
        risk_percentage = 1
        profit_margin = 0.009  # 1.5%
        jobs=4
        subreddit_name = "Bitcoin"  # Cambia si deseas otro subreddit
        max_posts = 100  # Número máximo de posts a analizar
        horas = 6  # Cambia para ajustar el rango de tiempo

        
        # Calculate 12 hours in milliseconds
        current_time_ms = int(time.time() * 1000)
        twelve_hours_ms = 12 * 60 * 60 * 1000
        start_time = current_time_ms - twelve_hours_ms

        print("Updating Prices...")
        df = fetch_binance_data(binance_client, symbol, interval, start_time)

        # Format only new data from API
        df['timestamp'] = pd.to_datetime(df['timestamp'], unit='ms', utc=True)
        df['timestamp'] = df['timestamp'].dt.tz_convert('America/New_York')
        df.set_index('timestamp', inplace=True)


        print("Technical Analysis...")
        df = process_data_in_parallel(df,jobs)  # Parallel processing of indicators       
        df = calculate_technical_indicators(df)
        
        features = ['rsi', 'sma', 'ema', 'macd', 'macd_signal']
        model, scaler = train_predictive_model(df, features, 'close') # Train predictive model

        # Prediction and strategy
        latest_data = scaler.transform(df[features].iloc[[-15]])  # Use the latest 15-minute window
        predicted_price = model.predict(latest_data)[0]
        current_price = df['close'].iloc[-1]               
        atr = df['high'].iloc[-1] - df['low'].iloc[-1]
        stop_loss = current_price - 1.5 * atr

        # Obtener el saldo disponible
        account = coinbase_client.get_account(usd_account);
        account_info = account.account 
        saldo_disponible = float(account_info['available_balance']['value'])
        risk_amount = saldo_disponible * (risk_percentage / 100)

        # # Calcular el precio objetivo para una ganancia de 1.5%
        target_price = current_price * (1 + profit_margin)
        profit = float(predicted_price) - float(current_price)

        porcentaje_aceptacion = analizar_sentimiento_vader(subreddit_name, max_posts, horas)

        decision = "BUY" if predicted_price > current_price and risk_amount>=5 and porcentaje_aceptacion >= 60 else "SKIP"

        print("Product:" + product)
        print("Price:"      + str(current_price))  #if decision == 'BUY' else None
        print("Balance: "    + str(saldo_disponible)) #if decision == 'BUY' else None
        print("Amount:"     + str(risk_amount))

        print("Prediction:" + decision)
        print("Profit: " +  str(round(profit,2)))

        print("Predicted:" + str(predicted_price)) #if decision == 'BUY' else None
        print("Stop Loss:" + str(stop_loss))  #if decision == 'BUY' else None
        # print("Target:"     + str(target_price)) #if decision == 'BUY' else None

        execute_trade(coinbase_client, product, 'BUY', risk_amount, current_price, target_price, stop_loss) if decision == 'BUY' else print("Process Skipped ")
        

    # finally:
    #     release_lock()
if __name__ == "__main__":
    main()

