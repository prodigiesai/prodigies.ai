from sklearn.ensemble import RandomForestRegressor
from sklearn.model_selection import train_test_split, GridSearchCV
from sklearn.preprocessing import StandardScaler

def train_predictive_model(df, features, target):
    # Verifica que las características existan en el DataFrame
    missing_features = [feature for feature in features if feature not in df.columns]
    if missing_features:
        raise ValueError(f"Missing features in DataFrame: {missing_features}")

    # Elimina filas con valores NaN en las columnas de interés
    df = df.dropna(subset=features + [target])
    
    # Verifica si el DataFrame sigue teniendo datos
    if df.empty:
        raise ValueError("DataFrame is empty after dropping NaN values.")

    # Define las características (X) y la variable objetivo (y)
    X = df[features]
    y = df[target]

    # Escalado de datos
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X)

    # División de datos en entrenamiento y prueba
    X_train, X_test, y_train, y_test = train_test_split(X_scaled, y, test_size=0.3, random_state=42)

    # Configuración de hiperparámetros para Random Forest
    param_grid = {
        'n_estimators': [50, 100],  # En lugar de [50, 100, 200, 300]
        'max_depth': [3, 5],       # En lugar de [3, 5, 10, None]
        'min_samples_split': [2, 5]
    }

    # Grid Search para encontrar los mejores parámetros
    rf = RandomForestRegressor(random_state=42)
    grid_search = GridSearchCV(rf, param_grid, cv=3, n_jobs=-1)  # Menos combinaciones y folds
    grid_search.fit(X_train, y_train)

    # Retorna el mejor modelo y el escalador
    return grid_search.best_estimator_, scaler