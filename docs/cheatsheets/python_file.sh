# Python Cheat Sheet for File Manipulation, Data Analysis, and Machine Learning

# -----------------------------
# 1. File Manipulation (CSV, Excel, PDF)
# -----------------------------

# 1.1. Working with CSV Files
import csv

# Read from a CSV file
with open('data.csv', mode='r') as file:
    csv_reader = csv.reader(file)
    for row in csv_reader:
        print(row)

# Write to a CSV file
with open('output.csv', mode='w', newline='') as file:
    csv_writer = csv.writer(file)
    csv_writer.writerow(['Name', 'Age', 'Occupation'])
    csv_writer.writerow(['John Doe', 30, 'Engineer'])

# 1.2. Working with Excel Files using pandas
import pandas as pd

# Read an Excel file
df = pd.read_excel('data.xlsx')
print(df.head())

# Write to an Excel file
df.to_excel('output.xlsx', index=False)

# 1.3. Working with PDFs using PyPDF2
from PyPDF2 import PdfReader, PdfWriter

# Read from a PDF file
reader = PdfReader('document.pdf')
for page in reader.pages:
    print(page.extract_text())

# Merge PDF files
writer = PdfWriter()
for pdf in ['file1.pdf', 'file2.pdf']:
    reader = PdfReader(pdf)
    for page in reader.pages:
        writer.add_page(page)

with open('merged.pdf', 'wb') as output_pdf:
    writer.write(output_pdf)

# -----------------------------
# 2. Data Transformation and Cleaning (Using pandas)
# -----------------------------

# 2.1. Load Data with pandas
import pandas as pd

# Load a CSV into a DataFrame
df = pd.read_csv('data.csv')

# 2.2. Basic DataFrame Operations
print(df.head())  # Show first few rows
print(df.info())  # Information about DataFrame
print(df.describe())  # Summary statistics

# 2.3. Data Cleaning
# Handle missing values
df.fillna(0, inplace=True)  # Replace NaN with 0
df.dropna(inplace=True)  # Remove rows with missing values

# Renaming columns
df.rename(columns={'OldName': 'NewName'}, inplace=True)

# Converting data types
df['Column'] = df['Column'].astype(float)

# 2.4. Data Transformation
# Apply a function to a column
df['NewColumn'] = df['Column'].apply(lambda x: x * 2)

# Filter rows based on a condition
filtered_df = df[df['Age'] > 30]

# -----------------------------
# 3. Data Analysis (Using pandas, numpy, matplotlib)
# -----------------------------

import pandas as pd
import numpy as np
import matplotlib.pyplot as plt

# 3.1. Basic Statistical Analysis
mean_value = df['Column'].mean()  # Calculate mean
median_value = df['Column'].median()  # Calculate median
std_dev = df['Column'].std()  # Calculate standard deviation

# 3.2. Grouping and Aggregation
grouped = df.groupby('Category').agg({'Sales': 'sum', 'Profit': 'mean'})
print(grouped)

# 3.3. Plotting Data with matplotlib
plt.figure(figsize=(10, 6))
plt.plot(df['Date'], df['Sales'])
plt.title('Sales Over Time')
plt.xlabel('Date')
plt.ylabel('Sales')
plt.show()

# -----------------------------
# 4. Machine Learning (Using sklearn)
# -----------------------------

from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error

# 4.1. Splitting Data
X = df[['Feature1', 'Feature2']]  # Features
y = df['Target']  # Target variable

# Split the data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 4.2. Building a Simple Linear Regression Model
model = LinearRegression()
model.fit(X_train, y_train)

# 4.3. Predicting and Evaluating the Model
y_pred = model.predict(X_test)

# Evaluate the model with Mean Squared Error (MSE)
mse = mean_squared_error(y_test, y_pred)
print(f'Mean Squared Error: {mse}')

# 4.4. Model Coefficients
print('Intercept:', model.intercept_)
print('Coefficients:', model.coef_)

# -----------------------------
# 5. Useful Snippets for File Manipulation, Data Analysis, and ML
# -----------------------------

# 5.1. Save a DataFrame to CSV or Excel
df.to_csv('output.csv', index=False)
df.to_excel('output.xlsx', index=False)

# 5.2. Load and Manipulate Data in a Single Step
df = pd.read_csv('data.csv').dropna().rename(columns={'OldName': 'NewName'})

# 5.3. Create Dummy Variables (One-Hot Encoding)
df = pd.get_dummies(df, columns=['Category'])

# 5.4. Convert a String Column to DateTime
df['Date'] = pd.to_datetime(df['Date'])

# 5.5. Export DataFrame Summary Stats to a CSV
summary = df.describe()
summary.to_csv('summary_stats.csv')

# 5.6. Plotting a Histogram
plt.hist(df['Column'], bins=10)
plt.title('Histogram of Column')
plt.xlabel('Value')
plt.ylabel('Frequency')
plt.show()

# 5.7. Train-Test Split and Model Training for Linear Regression
X_train, X_test, y_train, y_test = train_test_split(df[['Feature1', 'Feature2']], df['Target'], test_size=0.2)
model = LinearRegression()
model.fit(X_train, y_train)
y_pred = model.predict(X_test)
print('MSE:', mean_squared_error(y_test, y_pred))



# Python Cheat Sheet for File Manipulation, Data Formats, and Common Tasks

# -----------------------------
# 1. JSON Manipulation (Reading and Writing)
# -----------------------------

import json

# Read JSON from a file
with open('data.json', 'r') as file:
    data = json.load(file)  # data is now a Python dictionary
    print(data)

# Write JSON to a file
with open('output.json', 'w') as file:
    json.dump(data, file, indent=4)  # Writing with pretty-printing (indentation)

# Convert Python object to JSON string
json_string = json.dumps(data, indent=4)
print(json_string)

# Convert JSON string to Python object
data_from_string = json.loads(json_string)
print(data_from_string)

# -----------------------------
# 2. XML Manipulation (Reading and Writing)
# -----------------------------

import xml.etree.ElementTree as ET

# Read and parse an XML file
tree = ET.parse('data.xml')
root = tree.getroot()

# Print the root element and its attributes
print(root.tag, root.attrib)

# Iterate through child elements
for child in root:
    print(child.tag, child.attrib, child.text)

# Find a specific element
element = root.find('TagName')
print(element.text)

# Create and write XML to a file
root = ET.Element('root')
child = ET.SubElement(root, 'child')
child.text = 'This is child element text'
tree = ET.ElementTree(root)

with open('output.xml', 'wb') as file:
    tree.write(file)

# -----------------------------
# 3. SQLite Database Manipulation
# -----------------------------

import sqlite3

# Connect to an SQLite database (or create one)
conn = sqlite3.connect('example.db')
cursor = conn.cursor()

# Create a table
cursor.execute('''CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY, 
    name TEXT, 
    age INTEGER
)''')

# Insert a row of data
cursor.execute("INSERT INTO users (name, age) VALUES (?, ?)", ('John Doe', 30))

# Commit the transaction
conn.commit()

# Query data
cursor.execute("SELECT * FROM users")
rows = cursor.fetchall()
for row in rows:
    print(row)

# Close the connection
conn.close()

# -----------------------------
# 4. ZIP File Handling
# -----------------------------

import zipfile

# Create a zip file and add files to it
with zipfile.ZipFile('archive.zip', 'w') as zipf:
    zipf.write('file1.txt')
    zipf.write('file2.txt')

# Extract files from a zip archive
with zipfile.ZipFile('archive.zip', 'r') as zipf:
    zipf.extractall('extracted_files')

# List contents of a zip file
with zipfile.ZipFile('archive.zip', 'r') as zipf:
    print(zipf.namelist())

# -----------------------------
# 5. URL Handling with urllib
# -----------------------------

import urllib.request

# Read the content of a URL
response = urllib.request.urlopen('http://example.com')
html = response.read().decode('utf-8')
print(html)

# Download a file from a URL
urllib.request.urlretrieve('http://example.com/file.zip', 'downloaded_file.zip')

# -----------------------------
# 6. YAML Manipulation (Reading and Writing)
# -----------------------------

import yaml

# Read YAML from a file
with open('data.yaml', 'r') as file:
    yaml_data = yaml.safe_load(file)
    print(yaml_data)

# Write Python data to a YAML file
data_to_write = {'name': 'John', 'age': 30, 'children': ['Anna', 'Bob']}
with open('output.yaml', 'w') as file:
    yaml.dump(data_to_write, file, default_flow_style=False)

# -----------------------------
# 7. Environment Variables Handling with os
# -----------------------------

import os

# Get an environment variable
database_url = os.getenv('DATABASE_URL', 'default_value')
print(database_url)

# Set an environment variable (this change is temporary)
os.environ['MY_ENV_VAR'] = '12345'
print(os.getenv('MY_ENV_VAR'))

# -----------------------------
# 8. Regular Expressions with re
# -----------------------------

import re

# Search for a pattern in a string
pattern = r'\d{3}-\d{2}-\d{4}'  # Pattern for matching a Social Security Number
text = "John's SSN is 123-45-6789."
match = re.search(pattern, text)

if match:
    print(f"Found match: {match.group()}")

# Replace patterns in a string
modified_text = re.sub(r'\d{3}-\d{2}-\d{4}', 'XXX-XX-XXXX', text)
print(modified_text)

# -----------------------------
# 9. Common Python File Operations
# -----------------------------

# 9.1. Reading a file line by line
with open('example.txt', 'r') as file:
    for line in file:
        print(line.strip())

# 9.2. Writing to a file
with open('output.txt', 'w') as file:
    file.write("Hello, World!\n")

# 9.3. Appending to a file
with open('output.txt', 'a') as file:
    file.write("This line is appended.\n")

# 9.4. Reading an entire file as a string
with open('example.txt', 'r') as file:
    content = file.read()
    print(content)

# -----------------------------
# 10. Snippet: Function for Reading, Writing, and Manipulating JSON
# -----------------------------

def read_json(filename):
    """Reads JSON data from a file and returns it as a dictionary."""
    with open(filename, 'r') as file:
        return json.load(file)

def write_json(data, filename):
    """Writes a dictionary to a JSON file."""
    with open(filename, 'w') as file:
        json.dump(data, file, indent=4)

# Example usage:
data = read_json('input.json')
print(data)
write_json(data, 'output.json')

# -----------------------------
# 11. Snippet: XML Parsing and Writing Example
# -----------------------------

def parse_xml(filename):
    """Parses an XML file and prints the tag names and text content."""
    tree = ET.parse(filename)
    root = tree.getroot()
    for child in root:
        print(f"{child.tag}: {child.text}")

def create_xml(filename):
    """Creates an XML file with sample data."""
    root = ET.Element('root')
    item = ET.SubElement(root, 'item')
    item.text = 'Sample Item'
    tree = ET.ElementTree(root)
    with open(filename, 'wb') as file:
        tree.write(file)

# Example usage:
parse_xml('data.xml')
create_xml('output.xml')

# -----------------------------
# 12. Snippet: Connect to SQLite, Insert Data, and Query
# -----------------------------

def insert_user(conn, name, age):
    """Inserts a user into the users table."""
    cursor = conn.cursor()
    cursor.execute("INSERT INTO users (name, age) VALUES (?, ?)", (name, age))
    conn.commit()

def query_users(conn):
    """Queries all users from the users table."""
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM users")
    return cursor.fetchall()

# Example usage:
conn = sqlite3.connect('example.db')
insert_user(conn, 'Alice', 25)
users = query_users(conn)
for user in users:
    print(user)
conn.close()


# 1. Basic ML Workflow (Scikit-Learn)

from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score

# Data Preparation
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)

# Data Scaling
scaler = StandardScaler()
X_train = scaler.fit_transform(X_train)
X_test = scaler.transform(X_test)

# Model Training
model = LogisticRegression()
model.fit(X_train, y_train)

# Prediction
y_pred = model.predict(X_test)

# Accuracy
accuracy = accuracy_score(y_test, y_pred)
print(f"Accuracy: {accuracy}")


# 2. Neural Networks (Using TensorFlow and Keras)
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense

# Model Definition
model = Sequential([
    Dense(64, activation='relu', input_shape=(X_train.shape[1],)),
    Dense(32, activation='relu'),
    Dense(1, activation='sigmoid')  # for binary classification
])

# Compile the model
model.compile(optimizer='adam', loss='binary_crossentropy', metrics=['accuracy'])

# Train the model
model.fit(X_train, y_train, epochs=10, batch_size=32, validation_split=0.2)

# Evaluate the model
loss, accuracy = model.evaluate(X_test, y_test)
print(f"Test Accuracy: {accuracy}")


# 3. Natural Language Processing (NLP) with spaCy
import spacy

# Load pre-trained NLP model
nlp = spacy.load('en_core_web_sm')

# Process text
doc = nlp("This is a sentence to analyze using spaCy.")

# Named Entity Recognition (NER)
for ent in doc.ents:
    print(ent.text, ent.label_)

# Tokenization
tokens = [token.text for token in doc]
print(tokens)

# Part-of-Speech (POS) tagging
for token in doc:
    print(f"{token.text}: {token.pos_}")


# 4. Text Classification (TF-IDF & Naive Bayes)
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.naive_bayes import MultinomialNB
from sklearn.pipeline import make_pipeline

# Vectorization and Model in a pipeline
model = make_pipeline(TfidfVectorizer(), MultinomialNB())

# Train the model
model.fit(X_train_texts, y_train)

# Predict
predicted_categories = model.predict(X_test_texts)


# 5. Handling Imbalanced Datasets (SMOTE)
from imblearn.over_sampling import SMOTE
from sklearn.model_selection import train_test_split

# Resampling the dataset
sm = SMOTE(random_state=42)
X_res, y_res = sm.fit_resample(X, y)

# Splitting into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X_res, y_res, test_size=0.3)


# 6. Deep Learning (Convolutional Neural Networks - CNN)
import tensorflow as tf
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Conv2D, MaxPooling2D, Flatten, Dense

# Building a CNN
model = Sequential([
    Conv2D(32, (3,3), activation='relu', input_shape=(64, 64, 3)),
    MaxPooling2D(pool_size=(2,2)),
    Conv2D(64, (3,3), activation='relu'),
    MaxPooling2D(pool_size=(2,2)),
    Flatten(),
    Dense(128, activation='relu'),
    Dense(10, activation='softmax')  # Multi-class classification
])

# Compile the model
model.compile(optimizer='adam', loss='sparse_categorical_crossentropy', metrics=['accuracy'])

# Train the model
model.fit(X_train, y_train, epochs=10, validation_split=0.2)




# 7. Text Embeddings (Word2Vec)
from gensim.models import Word2Vec

# Prepare data
sentences = [["this", "is", "a", "sentence"], ["another", "sentence", "for", "example"]]

# Train Word2Vec model
model = Word2Vec(sentences, vector_size=100, window=5, min_count=1, workers=4)

# Access word vectors
vector = model.wv['sentence']

# Find similar words
similar_words = model.wv.most_similar('sentence')
print(similar_words)




# 8. Time Series Forecasting (LSTM - Long Short Term Memory)
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import LSTM, Dense

# LSTM model
model = Sequential([
    LSTM(50, activation='relu', input_shape=(X_train.shape[1], X_train.shape[2])),
    Dense(1)
])

# Compile
model.compile(optimizer='adam', loss='mse')

# Fit the model
model.fit(X_train, y_train, epochs=50, batch_size=32, validation_split=0.2)




# 9. Gradient Boosting (XGBoost)
import xgboost as xgb
from sklearn.metrics import accuracy_score

# Create XGBoost model
model = xgb.XGBClassifier()

# Train model
model.fit(X_train, y_train)

# Make predictions
y_pred = model.predict(X_test)

# Evaluate accuracy
accuracy = accuracy_score(y_test, y_pred)
print(f"XGBoost Accuracy: {accuracy}")




# 10. Hyperparameter Tuning (GridSearchCV)
from sklearn.model_selection import GridSearchCV
from sklearn.ensemble import RandomForestClassifier

# Define model and parameters for grid search
model = RandomForestClassifier()
param_grid = {
    'n_estimators': [100, 200],
    'max_depth': [10, 20, None],
    'min_samples_split': [2, 5]
}

# Perform grid search
grid_search = GridSearchCV(estimator=model, param_grid=param_grid, cv=3)
grid_search.fit(X_train, y_train)

# Best parameters and score
print("Best Params:", grid_search.best_params_)
print("Best Score:", grid_search.best_score_)




# 11. Explainable AI (SHAP)
import shap

# Train model (using XGBoost as example)
model = xgb.XGBClassifier()
model.fit(X_train, y_train)

# SHAP Explainer
explainer = shap.TreeExplainer(model)
shap_values = explainer.shap_values(X_test)

# Plot SHAP values
shap.summary_plot(shap_values, X_test)
