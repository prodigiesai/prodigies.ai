# Paso 4: Implementar Estrategia de Trading Basada en el Análisis


# Instalar la API de Coinbase:
# pip3 install coinbase



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