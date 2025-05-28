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