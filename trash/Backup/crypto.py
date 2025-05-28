# 1. Obtener Precios de Criptomonedas con la Binance API
# Primero, creamos un script que obtenga los precios de las criptomonedas en tiempo real usando la Binance API.

# Instalar Librerías Necesarias:
# pip3 install requests pandas -binance
# pip3 install ta yfinance scikit-learn numpy pandas

# Código para Obtener Precios de Criptomonedas:

from binance.client import Client
import pandas as pd

# Configura la API de Binance
api_key = 'j3W5PfILpjwkl18wX9XQPTnzsZLBJlRR8VeYQ7wBl392kvVk7l93XBDXYUIiko1Q'
api_secret = 'sxIitxiRGXbV9YqUv3dBPK8G4qteGAH0XdXvPa5ekgORlP5KGSkQ8gmsLzdQgYlc'
client = Client(api_key, api_secret)

# Obtener los precios actuales
prices = client.get_all_tickers()

# Convertir a un DataFrame de pandas para análisis
df_prices = pd.DataFrame(prices)
df_prices.columns = ['symbol', 'price']
df_prices['price'] = df_prices['price'].astype(float)

# Mostrar las 10 principales criptomonedas
print(df_prices.head(10))


# 2. Análisis Predictivo con IA y Fórmulas Estadísticas
    # Usamos fórmulas estadísticas y machine learning para analizar los precios y predecir las tendencias.

    # Instalar Librerías para Análisis Predictivo:
    # pip3 install numpy scikit-learn ta yfinance pandas

    # Análisis con Indicadores Técnicos (Bandas de Bollinger, RSI, etc.):
    # Vamos a usar la librería ta-lib para aplicar indicadores técnicos.

    import ta
    import pandas as pd
    import numpy as np
    import matplotlib.pyplot as plt
    from sklearn.linear_model import LinearRegression
    from ta.volatility import BollingerBands
    from ta.momentum import RSIIndicator, StochasticOscillator
    from ta.trend import MACD, SMAIndicator, EMAIndicator, ADXIndicator
    from ta.volume import OnBalanceVolumeIndicator

    # Supongamos que ya tenemos datos históricos de Binance o YFinance
    # Aquí simulamos la obtención de datos
    def get_historical_data(symbol, interval='1h', limit=100):
        # Este sería un código de ejemplo para obtener datos históricos de Binance
        # Los datos deben incluir precios de cierre, volumen, etc.
        # df es un DataFrame de pandas con datos históricos
        df = pd.DataFrame({
            'timestamp': pd.date_range(start='2023-01-01', periods=100, freq='H'),
            'close': np.random.random(100) * 50000,  # Simulando precios
            'volume': np.random.random(100) * 1000
        })
        df.set_index('timestamp', inplace=True)
        return df

    # Obtener datos históricos
    df = get_historical_data('BTCUSDT')

    # Aplicar Bandas de Bollinger
    bb = BollingerBands(close=df['close'], window=20, window_dev=2)
    df['bb_high'] = bb.bollinger_hband()
    df['bb_low'] = bb.bollinger_lband()

    # Aplicar RSI
    rsi = RSIIndicator(close=df['close'], window=14)
    df['rsi'] = rsi.rsi()

    # Aplicar Media Móvil Simple (SMA)
    sma = SMAIndicator(close=df['close'], window=14)
    df['sma'] = sma.sma_indicator()

    # Aplicar Media Móvil Exponencial (EMA)
    ema = EMAIndicator(close=df['close'], window=14)
    df['ema'] = ema.ema_indicator()

    # Aplicar MACD
    macd = MACD(close=df['close'])
    df['macd'] = macd.macd()
    df['macd_signal'] = macd.macd_signal()

    # Aplicar Oscilador Estocástico
    stoch = StochasticOscillator(close=df['close'], high=df['close'], low=df['close'])
    df['stoch'] = stoch.stoch()

    # Aplicar Promedio de Rango Verdadero (ATR)
    atr = ta.volatility.AverageTrueRange(high=df['close'], low=df['close'], close=df['close'])
    df['atr'] = atr.average_true_range()

    # Aplicar ADX (Índice de Movimiento Direccional)
    adx = ADXIndicator(high=df['close'], low=df['close'], close=df['close'])
    df['adx'] = adx.adx()

    # Aplicar OBV (On-Balance Volume)
    obv = OnBalanceVolumeIndicator(close=df['close'], volume=df['volume'])
    df['obv'] = obv.on_balance_volume()

    # Aplicar Parabolic SAR
    df['psar'] = ta.trend.PSARIndicator(high=df['close'], low=df['close'], close=df['close']).psar()

    # Visualización de las Bandas de Bollinger y el Precio de Cierre
    plt.figure(figsize=(12,6))
    plt.plot(df.index, df['close'], label='Precio de Cierre')
    plt.plot(df.index, df['bb_high'], label='Banda Bollinger Alta', linestyle='--')
    plt.plot(df.index, df['bb_low'], label='Banda Bollinger Baja', linestyle='--')
    plt.title('Bandas de Bollinger y Precio de Cierre')
    plt.legend()
    plt.show()



# Análisis Predictivo con Machine Learning (Regresión Lineal):

    # Crear variables predictoras (X) y objetivo (y)
    X = df[['rsi', 'macd', 'macd_signal']].dropna()
    y = df['close'].shift(-1).dropna()

    # Ajustar el modelo de regresión lineal
    model = LinearRegression()
    model.fit(X[:-1], y)

    # Predecir el próximo precio
    predicted_price = model.predict([X.iloc[-1].values])
    print(f"Predicción de precio para el siguiente minuto: {predicted_price[0]}")



# Paso 3: Realizar el Análisis Predictivo Combinando Múltiples Indicadores
    from sklearn.ensemble import RandomForestRegressor
    from sklearn.model_selection import train_test_split

    # Definir variables predictoras (indicadores técnicos)
    X = df[['rsi', 'sma', 'ema', 'macd', 'macd_signal', 'stoch', 'atr', 'adx', 'obv', 'psar']].dropna()

    # Definir variable objetivo (el precio de cierre futuro)
    df['future_close'] = df['close'].shift(-1)  # Predecir el siguiente precio de cierre
    y = df['future_close'].dropna()

    # Ajustar el modelo de Random Forest
    X_train, X_test, y_train, y_test = train_test_split(X[:-1], y, test_size=0.3, random_state=42)
    rf_model = RandomForestRegressor(n_estimators=100)
    rf_model.fit(X_train, y_train)

    # Hacer predicciones
    y_pred = rf_model.predict(X_test)

    # Evaluar el rendimiento
    from sklearn.metrics import mean_squared_error
    mse = mean_squared_error(y_test, y_pred)
    print(f"Mean Squared Error: {mse}")

    # Visualizar predicciones vs valores reales
    plt.figure(figsize=(12,6))
    plt.plot(y_test.values, label='Valores Reales')
    plt.plot(y_pred, label='Predicciones')
    plt.title('Predicción vs Valor Real')
    plt.legend()
    plt.show()

# Paso 4: Implementar Estrategia de Trading Basada en el Análisis


# Instalar la API de Coinbase:
pip install coinbase



# Código para Automatizar Compras/Ventas en Coinbase:
from coinbase.wallet.client import Client

# Configurar la API de Coinbase
client_cb = Client('COINBASE_API_KEY', 'COINBASE_API_SECRET')

# Obtener el balance de la cuenta
accounts = client_cb.get_accounts()
for account in accounts['data']:
    print(f"{account['balance']['amount']} {account['balance']['currency']}")

# Ejemplo de compra automática de Bitcoin en Coinbase
def comprar_bitcoin(cantidad_usd):
    buy = client_cb.buy('BTC', total= cantidad_usd, currency="USD")
    print(f"Compra exitosa: {buy['status']}")

# Ejemplo de venta automática de Bitcoin en Coinbase
def vender_bitcoin(cantidad_btc):
    sell = client_cb.sell('BTC', amount=cantidad_btc, currency="BTC")
    print(f"Venta exitosa: {sell['status']}")

# Comprar o vender según el análisis predictivo
if predicted_price > df['close'].iloc[-1]:  # Si se espera un incremento de precio
    comprar_bitcoin(100)  # Comprar $100 de Bitcoin
else:
    vender_bitcoin(0.01)  # Vender 0.01 Bitcoin



# 4. Automatizar el Proceso (Integrar todo en un bucle)
# Para hacer que el sistema trabaje automáticamente, puedes configurar un bucle infinito que ejecute las predicciones cada minuto y realice las compras/ventas según el pronóstico.

import time

while True:
    # Obtener los precios actuales
    df = get_historical_data('BTCUSDT')

    # Actualizar indicadores
    df['rsi'] = RSIIndicator(close=df['close']).rsi()
    df['macd'] = MACD(close=df['close']).macd()
    df['macd_signal'] = MACD(close=df['close']).macd_signal()

    # Predecir precios futuros
    X = df[['rsi', 'macd', 'macd_signal']].dropna()
    y = df['close'].shift(-1).dropna()
    model.fit(X[:-1], y)
    predicted_price = model.predict([X.iloc[-1].values])

    # Ejecutar transacciones automáticas
    if predicted_price > df['close'].iloc[-1]:
        comprar_bitcoin(100)  # Comprar $100 de Bitcoin
    else:
        vender_bitcoin(0.01)  # Vender 0.01 Bitcoin

    # Esperar 60 segundos antes de la siguiente ejecución
    time.sleep(60)