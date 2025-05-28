import pandas as pd
from datetime import datetime
import backoff

coinbase_api_key = config['coinbase']['api_key']
coinbase_api_secret = config['coinbase']['api_secret']

# usd_account = "2f70928e-3697-5ea8-ad26-00ac7d92f569"  # Cuenta en USD

# Inicializar clientes Binance y Coinbase
coinbase_client = CoinbaseAPI(coinbase_api_key, coinbase_api_secret)

@backoff.on_exception(backoff.expo, Exception, max_tries=5)
def fetch_coinbase_data(client, product_id, granularity, start_time, end_time):
    """
    Fetch historical candlestick data from Coinbase.

    :param client: Coinbase API client
    :param product_id: Trading pair (e.g., 'BTC-USD')
    :param granularity: Time interval for candlesticks in seconds (e.g., 60 for 1m, 300 for 5m)
    :param start_time: Start time for fetching data (ISO 8601 format)
    :param end_time: End time for fetching data (ISO 8601 format)
    :return: DataFrame with historical data
    """
    try:
        # Fetch candles
        candles = client.get_candles(product_id=product_id, granularity=granularity, start=start_time, end=end_time, limit=350)

        # Convert to DataFrame
        columns = ['time', 'low', 'high', 'open', 'close', 'volume']
        df = pd.DataFrame(candles, columns=columns)

        # Convert time from timestamp to datetime
        df['time'] = pd.to_datetime(df['time'], unit='s')
        df.set_index('time', inplace=True)

        return df
    except Exception as e:
        print(f"Error fetching data from Coinbase: {e}")
        raise


# Main
def main():
    df = fetch_coinbase_data()
    df
if __name__ == "__main__":
    main()