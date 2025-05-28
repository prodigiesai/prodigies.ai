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