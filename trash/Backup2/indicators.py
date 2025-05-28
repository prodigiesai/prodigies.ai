import pandas as pd
import numpy as np

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