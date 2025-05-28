import http.client
import json

conn = http.client.HTTPSConnection("api.coinbase.com")
payload = ''
headers = {
  'Content-Type': 'application/json'
}
conn.request("GET", "/api/v3/brokerage/products/BTC-USD/candles?start=1733088712&end=1733188712&granularity=FIVE_MINUTE&limit=350", payload, headers)
res = conn.getresponse()
data = res.read()
print(data.decode("utf-8"))