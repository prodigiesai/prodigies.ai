import pandas as pd
from datetime import datetime
import backoff

@backoff.on_exception(backoff.expo, Exception, max_tries=5)
def fetch_binance_data(client, symbol, interval, start_time):
    """
    Fetch historical candlestick data from Binance.
    
    :param client: Binance API client
    :param symbol: Trading pair (e.g., 'BTCUSDT')
    :param interval: Time interval for candlesticks (e.g., '1m', '15m')
    :param start_time: Start time for fetching data (in milliseconds)
    :return: DataFrame with historical data
    """

    # Convert to DataFrame
    columns = [
        'timestamp', 'open', 'high', 'low', 'close', 'volume',
        'close_time', 'quote_asset_volume', 'number_of_trades',
        'taker_buy_base_asset_volume', 'taker_buy_quote_asset_volume', 'ignore'
    ]
    klines = client.get_klines(symbol=symbol, interval=interval, startTime=start_time, limit=1000)

    df = pd.DataFrame(klines, columns=columns)
    df.count()

    return df



# def update_historical_data(client, symbol, interval, filename, start_time):
#     """Updates historical data from Binance and saves it to a file."""
#     try:
#         # Load existing file if it exists
#         try:
#             df_existing = pd.read_csv(filename)
#         except FileNotFoundError:
#             df_existing = pd.DataFrame(columns=['timestamp', 'open', 'high', 'low', 'close', 'volume'])

#         # Ensure timestamp column is treated as integers
#         df_existing['timestamp'] = pd.to_numeric(df_existing['timestamp'], errors='coerce').dropna().astype(int)
        
#         # Fetch new data
#         new_data = []
#         while True:
#             klines = fetch_binance_data(client, symbol, interval, start_time)
#             if not klines:
#                 break
#             temp_df = pd.DataFrame(klines, columns=[
#                 'timestamp', 'open', 'high', 'low', 'close', 'volume', '_', '_', '_', '_', '_', '_'
#             ])[['timestamp', 'open', 'high', 'low', 'close', 'volume']]
#             temp_df['timestamp'] = temp_df['timestamp'].astype(int)  # Ensure timestamps are integers
#             new_data.append(temp_df)
#             start_time = temp_df['timestamp'].max() + 1
        
#         # Combine and save data
#         if new_data:
#             df_new = pd.concat(new_data)
#             valid_dfs = [df for df in [df_existing, df_new] if not df.empty]
#             df_combined = pd.concat(valid_dfs).drop_duplicates(subset=['timestamp']).sort_values(by='timestamp')
#             df_combined.to_csv(filename, index=False)
#             print(f"Updated {filename} with {len(df_new)} new rows.")
#         else:
#             print("No new data to add.")
#     except Exception as e:
#         print(f"Error updating historical data: {e}")