import pandas as pd
from sqlalchemy import create_engine
import backoff

@backoff.on_exception(backoff.expo, Exception, max_tries=5)
def fetch_historical_data(client, symbol, interval, start_time):
    return client.get_klines(symbol=symbol, interval=interval, startTime=start_time, limit=1000)

def save_to_database(data, db_path, table_name):
    engine = create_engine(f'sqlite:///{db_path}')
    data.to_sql(table_name, engine, if_exists='append', index=True)

def load_from_database(db_path, table_name):
    engine = create_engine(f'sqlite:///{db_path}')
    return pd.read_sql(table_name, con=engine, index_col='timestamp', parse_dates=True)
