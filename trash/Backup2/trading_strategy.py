import time
import uuid  
from datetime import datetime, timedelta
from coinbase.wallet.client import Client as CoinbaseAPI
from binance.client import Client as BinanceAPI
import json

def generate_client_order_id():
    return str(uuid.uuid4())

def execute_trade(client, product, amount, current_price, target_price, stop_loss, start_time, end_time):

    with open('/Users/obarrientos/Documents/Prodigies/apps/crypto/config.json') as config_file:
        config = json.load(config_file)


    # Ajustar precisión del tamaño de la orden
    quote_size_usd = round(amount, 2)  # Limitar a 2 decimales

    try:
        client_order_id = generate_client_order_id()
        # response = ''
        response = client.market_order_buy(
            client_order_id=client_order_id,
            product_id="BTC-USD",  # Par de mercado
            quote_size=str(quote_size_usd)  # Monto en USD
        )
        print("Orden de compra completada:", response)
    except Exception as e:
        print("Error al realizar la compra:", e)

    while start_time < end_time:
        # Fetch current market price

        try:
            product_details = client.get_product(product_id=product)
            current_price = float(product_details['price'])
            # print(f"Current price of {product}: ${current_price:.2f}")                
        except KeyError as e:
            print(f"Error: Missing key in ticker response: {e}")
            return
        except Exception as e:
            print(f"Unexpected error while fetching ticker: {e}")
            return
        
        # print(f"Current Price: ${current_price:.2f}")
        
        # Check if profit target or stop loss is hit
        if current_price >= target_price:
            print("Profit target reached. Placing market sell order...")  
            # Define the allowed precision for base size
            BASE_SIZE_PRECISION = 8  # Typically 8 for BTC

            # Round base size to allowed precision
            base_size_btc = round(quote_size_usd / current_price, BASE_SIZE_PRECISION)   
            client_order_id = generate_client_order_id()         
            sell_order = client.market_order_sell(
                client_order_id = client_order_id,
                product_id=product,
                base_size=str(base_size_btc)  # Monto en BTC
            )
            print("Sell Order Executed:", sell_order)
            break
        elif current_price <= stop_loss:
            print("Stop loss triggered. Placing market sell order...")
            client_order_id = generate_client_order_id()
            base_size_btc = quote_size_usd / current_price 
            sell_order = client.market_order_sell(
                client_order_id = client_order_id,
                product_id=product,
                base_size=str(base_size_btc)  # Monto en BTC
            )
            print("Sell Order Executed:", sell_order)
            break
        else:
            # Wait for a short time before checking again
            time.sleep(30)

    else:
        # Step 4: After 15 Minutes, Sell No Matter What
        print("15 minutes passed. Placing market sell order...")
        client_order_id = generate_client_order_id()
        base_size_btc = quote_size_usd / current_price             
        sell_order = client.market_order_sell(
            client_order_id = client_order_id,
            product_id=product,
            base_size=str(base_size_btc)  # Monto en USD
        )
        print("Sell Order Executed:", sell_order)
