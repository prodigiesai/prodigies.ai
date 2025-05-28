import pandas as pd
import numpy as np
from concurrent.futures import ThreadPoolExecutor

def preprocess_data(df):
    numeric_columns = ['close', 'open', 'high', 'low', 'volume']
    for col in numeric_columns:
        df[col] = pd.to_numeric(df[col], errors='coerce')
    df = df.dropna()
    return df

def calculate_indicators_parallel(chunk):
    numeric_columns = ['close']
    for col in numeric_columns:
        chunk[col] = pd.to_numeric(chunk[col], errors='coerce')

    chunk = chunk.dropna(subset=numeric_columns)
    if chunk.empty:
        return pd.DataFrame()

    # print(f"Processing chunk with {len(chunk)} rows.")

    chunk['rsi'] = 100 - (100 / (1 + (chunk['close'].diff(1).clip(lower=0).rolling(window=14).mean() /
                                      abs(chunk['close'].diff(1)).rolling(window=14).mean())))
    chunk['sma'] = chunk['close'].rolling(window=14).mean()
    chunk['ema'] = chunk['close'].ewm(span=14, adjust=False).mean()
    chunk['macd'] = chunk['close'].ewm(span=12).mean() - chunk['close'].ewm(span=26).mean()
    chunk['macd_signal'] = chunk['macd'].ewm(span=9).mean()

    # chunk = chunk.dropna()
    # print(f"Finished chunk with {len(chunk)} rows.")
    return chunk

def process_data_in_parallel(df, n_jobs):
    chunks = np.array_split(df, n_jobs)
    with ThreadPoolExecutor(max_workers=n_jobs) as executor:
        results = list(executor.map(calculate_indicators_parallel, chunks))

    valid_results = [result for result in results if result is not None and not result.empty]

    if not valid_results:
        raise ValueError("All processed chunks are empty or None.")

    return pd.concat(valid_results)