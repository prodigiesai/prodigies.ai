import pandas as pd
import time
from datetime import datetime
from pytz import timezone
from binance.client import Client


api_key = "organizations/64d6b82e-d84a-4359-a5e1-02c45d6d5a94/apiKeys/eb358a7e-0963-41bf-94e4-871228021a10"
api_secret = "-----BEGIN EC PRIVATE KEY-----\nMHcCAQEEIFhGsJvjGro9vkBk9O+LOyBTSP2T419zGqYho+g73A9HoAoGCCqGSM49\nAwEHoUQDQgAEGCBbxSHe3pScIvvTlwSbauoeP9HMLvHW+iKpdAGjtKqBehZ5Q+s8\ntqdcmGlMqgXrSt/S27WLC4SvxcDFZ3VFeQ==\n-----END EC PRIVATE KEY-----\n"

client = RESTClient(api_key=api_key, api_secret=api_secret)

def update_historical_data(symbol, interval='1h', filename='historical_data.csv'):
    try:
        # Step 1: Load the existing data, or create a new DataFrame if file not found
        df_existing = pd.read_csv(filename, parse_dates=['timestamp'])
        df_existing['timestamp'] = pd.to_datetime(df_existing['timestamp'], unit='ms', utc=True)
        df_existing.set_index('timestamp', inplace=True)

        # Convert to the desired timezone
        df_existing.index = df_existing.index.tz_convert('America/New_York')

        last_timestamp = df_existing.index[-1] if not df_existing.empty else pd.Timestamp("NaT")
        print(f"Último dato registrado: {last_timestamp}")

    except (FileNotFoundError, pd.errors.EmptyDataError):
        # Handle case where the file does not exist or is empty
        print("Archivo no encontrado o vacío. Creando un nuevo archivo de datos históricos.")
        df_existing = pd.DataFrame(columns=[
            'timestamp', 'open', 'high', 'low', 'close', 'volume', 'close_time',
            'quote_asset_volume', 'number_of_trades', 'taker_buy_base_asset_volume',
            'taker_buy_quote_asset_volume', 'ignore'
        ])
        df_existing.to_csv(filename, index=False)
        last_timestamp = pd.Timestamp("2024-11-11", tz='UTC')

    # Step 2: Retrieve new data from Binance
    new_data = []
    start_time = int(last_timestamp.timestamp() * 1000) + 1 if not pd.isna(last_timestamp) else None

    while True:
        klines = client.get_klines(symbol=symbol, interval=interval, startTime=start_time, limit=1000)

        if not klines:
            break  # Exit if no more data is available

        # Convert the API response to a DataFrame
        temp_df = pd.DataFrame(klines, columns=[
            'timestamp', 'open', 'high', 'low', 'close', 'volume', 'close_time',
            'quote_asset_volume', 'number_of_trades', 'taker_buy_base_asset_volume',
            'taker_buy_quote_asset_volume', 'ignore'
        ])
        
        # Parse and clean data
        temp_df['timestamp'] = pd.to_datetime(temp_df['timestamp'], unit='ms', utc=True)
        temp_df.set_index('timestamp', inplace=True)
        temp_df['open'] = temp_df['open'].astype(float)
        temp_df['high'] = temp_df['high'].astype(float)
        temp_df['low'] = temp_df['low'].astype(float)
        temp_df['close'] = temp_df['close'].astype(float)
        temp_df['volume'] = temp_df['volume'].astype(float)

        new_data.append(temp_df)

        # Update start_time for the next batch
        start_time = int(temp_df.index[-1].timestamp() * 1000) + 1

        # Pause to respect API rate limits
        time.sleep(1)

    # Step 3: Merge existing and new data, and save to CSV
    if new_data:
        df_new = pd.concat(new_data)
        df_combined = pd.concat([df_existing, df_new]).drop_duplicates()
        df_combined.to_csv(filename)
        print(f"Archivo {filename} actualizado con {len(df_new)} registros nuevos.")
    else:
        print("No hay datos nuevos para añadir.")

update_historical_data('BTCUSDT', interval='1m', filename='historical_data.csv')

# Load the file to confirm it contains the filtered data in the correct timezone
df = pd.read_csv('historical_data.csv', index_col='timestamp', parse_dates=True)

# Check if already timezone-aware, if not, convert it
if df.index.tz is None:
    df.index = df.index.tz_localize('UTC').tz_convert('America/New_York')
else:
    df.index = df.index.tz_convert('America/New_York')

# Sort the DataFrame by the timestamp index in descending order
df = df.sort_index(ascending=False)

print(df)