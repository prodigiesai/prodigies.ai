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
client = Client(api_key, api_secret, tld='us')
# Obtener los precios actuales
prices = client.get_all_tickers()

# Convertir a un DataFrame de pandas para análisis
df_prices = pd.DataFrame(prices)
df_prices.columns = ['symbol', 'price']
df_prices['price'] = df_prices['price'].astype(float)

# Mostrar las 10 principales criptomonedas
print(df_prices.head(10))