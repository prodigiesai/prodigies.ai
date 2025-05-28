# El mejor en general para visualización de datos en Python, considerando recomendaciones, popularidad, uso activo, comunidad, y demandas laborales, sería:
# Matplotlib con Seaborn como complemento

import matplotlib.pyplot as plt
import seaborn as sns
import pandas as pd

# Cargar datos de ejemplo
df = pd.DataFrame({
    'A': [1, 2, 3, 4, 5],
    'B': [5, 4, 3, 2, 1],
    'C': [2, 3, 4, 5, 6]
})

# Gráfico básico con Matplotlib
plt.plot(df['A'], df['B'], label='A vs B')
plt.title('Gráfico de líneas con Matplotlib')
plt.xlabel('Eje X')
plt.ylabel('Eje Y')
plt.legend()
plt.show()

# Gráfico avanzado con Seaborn
sns.scatterplot(x='A', y='C', data=df)
plt.title('Gráfico de dispersión con Seaborn')
plt.show()
