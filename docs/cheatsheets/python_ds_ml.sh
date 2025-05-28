# -- Python for Data Science, Predictive Analysis, and ML Cheat Sheet

# -- -----------------------------
# -- 1. Importing Essential Libraries
# -- -----------------------------

import numpy as np  # Numerical computing
import pandas as pd  # Data manipulation and analysis
import matplotlib.pyplot as plt  # Data visualization
import seaborn as sns  # Advanced data visualization
from sklearn.model_selection import train_test_split  # Train-test splitting
from sklearn.preprocessing import StandardScaler  # Data normalization
from sklearn.linear_model import LinearRegression  # Linear regression
from sklearn.metrics import mean_squared_error, accuracy_score  # Evaluation metrics
from sklearn.ensemble import RandomForestClassifier  # Random Forest Classifier
from sklearn.cluster import KMeans  # Clustering
import statsmodels.api as sm  # Statistical modeling and testing
import scipy.stats as stats  # Statistical functions

# -- -----------------------------
# -- 2. Data Loading and Basic Data Operations
# -- -----------------------------

# Load dataset (CSV file) into a DataFrame
df = pd.read_csv('your_data.csv')

# Show the first few rows of the dataset
print(df.head())

# Show dataset summary (data types, missing values, etc.)
print(df.info())

# Check for missing data
print(df.isnull().sum())

# Drop missing values
df = df.dropna()

# -- -----------------------------
# -- 3. Exploratory Data Analysis (EDA)
# -- -----------------------------

# Summary statistics for numerical columns
print(df.describe())

# Correlation matrix
correlation_matrix = df.corr()
print(correlation_matrix)

# Correlation heatmap (visualizing the correlation matrix)
sns.heatmap(correlation_matrix, annot=True, cmap='coolwarm')
plt.show()

# Pair plot for continuous variables
sns.pairplot(df)
plt.show()

# Distribution of a single feature
sns.histplot(df['feature_column'], bins=30, kde=True)
plt.show()

# Boxplot for detecting outliers
sns.boxplot(x=df['feature_column'])
plt.show()

# Scatter plot between two variables
sns.scatterplot(x=df['feature_1'], y=df['feature_2'])
plt.show()

# -- -----------------------------
# -- 4. Data Preprocessing
# -- -----------------------------

# Handling categorical variables with one-hot encoding
df = pd.get_dummies(df, columns=['categorical_column'], drop_first=True)

# Split data into train and test sets
X = df.drop('target_column', axis=1)
y = df['target_column']
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)

# Scaling features (Normalization)
scaler = StandardScaler()
X_train_scaled = scaler.fit_transform(X_train)
X_test_scaled = scaler.transform(X_test)

# -- -----------------------------
# -- 5. Statistical Analysis
# -- -----------------------------

# T-test: Comparing means of two groups
t_stat, p_val = stats.ttest_ind(df['group_1'], df['group_2'])
print(f'T-statistic: {t_stat}, P-value: {p_val}')

# Chi-square test: Test for independence between categorical variables
contingency_table = pd.crosstab(df['category_1'], df['category_2'])
chi2_stat, p_val, dof, expected = stats.chi2_contingency(contingency_table)
print(f'Chi-square Statistic: {chi2_stat}, P-value: {p_val}')

# Linear Regression using statsmodels
X = sm.add_constant(X)  # Adding a constant for the intercept
model = sm.OLS(y, X).fit()
print(model.summary())

# -- -----------------------------
# -- 6. Predictive Analysis: Linear Regression
# -- -----------------------------

# Create a Linear Regression model using sklearn
linear_model = LinearRegression()
linear_model.fit(X_train_scaled, y_train)

# Make predictions on test data
y_pred = linear_model.predict(X_test_scaled)

# Evaluate model performance (Mean Squared Error)
mse = mean_squared_error(y_test, y_pred)
print(f'Mean Squared Error: {mse}')

# Plot the predictions vs actual
plt.scatter(y_test, y_pred)
plt.xlabel('Actual Values')
plt.ylabel('Predicted Values')
plt.title('Actual vs Predicted')
plt.show()

# -- -----------------------------
# -- 7. Classification: Random Forest
# -- -----------------------------

# Random Forest Classifier
rf_classifier = RandomForestClassifier(n_estimators=100, random_state=42)
rf_classifier.fit(X_train, y_train)

# Predict on test data
y_pred_rf = rf_classifier.predict(X_test)

# Evaluate model performance (Accuracy)
accuracy = accuracy_score(y_test, y_pred_rf)
print(f'Accuracy: {accuracy * 100:.2f}%')

# Feature Importance (which features are most important)
importances = rf_classifier.feature_importances_
feature_importance = pd.Series(importances, index=X.columns)
feature_importance.sort_values().plot(kind='barh')
plt.title('Feature Importance in Random Forest')
plt.show()

# -- -----------------------------
# -- 8. Clustering: KMeans
# -- -----------------------------

# Apply KMeans clustering
kmeans = KMeans(n_clusters=3, random_state=42)
df['cluster'] = kmeans.fit_predict(df[['feature_1', 'feature_2']])

# Visualize clusters
sns.scatterplot(x='feature_1', y='feature_2', hue='cluster', data=df, palette='Set1')
plt.title('KMeans Clustering')
plt.show()

# Elbow method to find optimal number of clusters
sse = []
for k in range(1, 10):
    kmeans = KMeans(n_clusters=k, random_state=42)
    kmeans.fit(df[['feature_1', 'feature_2']])
    sse.append(kmeans.inertia_)

plt.plot(range(1, 10), sse)
plt.xlabel('Number of clusters')
plt.ylabel('SSE')
plt.title('Elbow Method for Optimal K')
plt.show()

# -- -----------------------------
# -- 9. Model Evaluation Metrics
# -- -----------------------------

# Confusion matrix for classification
from sklearn.metrics import confusion_matrix
conf_matrix = confusion_matrix(y_test, y_pred_rf)
print(conf_matrix)

# ROC Curve for binary classification
from sklearn.metrics import roc_curve, roc_auc_score

# Assuming a binary classifier
y_prob = rf_classifier.predict_proba(X_test)[:, 1]
fpr, tpr, thresholds = roc_curve(y_test, y_prob)
plt.plot(fpr, tpr, marker='.')
plt.xlabel('False Positive Rate')
plt.ylabel('True Positive Rate')
plt.title('ROC Curve')
plt.show()

# AUC Score (Area Under Curve)
auc_score = roc_auc_score(y_test, y_prob)
print(f'AUC Score: {auc_score:.2f}')

# -- -----------------------------
# -- 10. Cross-Validation and Hyperparameter Tuning
# -- -----------------------------

# Perform cross-validation
from sklearn.model_selection import cross_val_score
cv_scores = cross_val_score(rf_classifier, X, y, cv=5)
print(f'Cross-Validation Accuracy: {np.mean(cv_scores):.2f}')

# Hyperparameter tuning using GridSearchCV
from sklearn.model_selection import GridSearchCV

param_grid = {
    'n_estimators': [50, 100, 200],
    'max_depth': [None, 10, 20, 30],
    'min_samples_split': [2, 5, 10],
    'min_samples_leaf': [1, 2, 4]
}

grid_search = GridSearchCV(estimator=rf_classifier, param_grid=param_grid, cv=5, verbose=2)
grid_search.fit(X_train, y_train)

# Best hyperparameters
print(grid_search.best_params_)

# -- -----------------------------
# -- 11. Saving and Loading Models (for deployment)
# -- -----------------------------

# Save the trained model using joblib
import joblib

joblib.dump(rf_classifier, 'random_forest_model.pkl')

# Load the model
loaded_model = joblib.load('random_forest_model.pkl')

# Make predictions with the loaded model
loaded_model.predict(X_test)

# -- -----------------------------
# -- 12. Time Series Analysis (Basic Example)
# -- -----------------------------

# Load a time series dataset
df_ts = pd.read_csv('time_series_data.csv', index_col='date', parse_dates=True)

# Visualize the time series
df_ts['value'].plot()
plt.title('Time Series Plot')
plt.show()

# Decompose the time series into trend, seasonality, and residuals
from statsmodels.tsa.seasonal import seasonal_decompose

decomposition = seasonal_decompose(df_ts['value'], model='additive', period=12)
decomposition.plot()
plt.show()

# Autocorrelation plot (ACF) and Partial Autocorrelation plot (PACF)
from statsmodels.graphics.tsaplots import plot_acf, plot_pacf

plot_acf(df_ts['value'])
plot_pacf(df_ts['value'])
plt.show()

