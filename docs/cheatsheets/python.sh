# Python Cheat Sheet

# -----------------------------
# 1. Basic Syntax
# -----------------------------

# Print output to the console
print("Hello, World!")

# Comments
# This is a single-line comment

'''
This is a 
multi-line comment
'''

# Variables
name = "John"  # String variable
age = 30  # Integer variable
is_student = True  # Boolean variable

# Constants (conventionally use uppercase)
PI = 3.14159

# -----------------------------
# 2. Data Types and Type Casting
# -----------------------------

# Basic data types
integer = 10  # Integer
floating_point = 3.14  # Float
boolean = True  # Boolean
string = "Python"  # String

# Type Casting
age = "30"
age_as_int = int(age)  # Convert string to integer
pi_as_int = int(PI)  # Convert float to integer

# Get the type of a variable
print(type(age_as_int))  # Output: <class 'int'>

# -----------------------------
# 3. Strings and String Methods
# -----------------------------

name = "John Doe"

# String concatenation
greeting = "Hello, " + name

# String formatting (f-strings)
greeting = f"Hello, {name}"

# Common string methods
print(name.lower())  # Convert to lowercase
print(name.upper())  # Convert to uppercase
print(name.strip())  # Remove leading/trailing spaces
print(len(name))  # Get string length
print(name.replace("Doe", "Smith"))  # Replace part of the string
print(name.startswith("John"))  # Check if string starts with a substring
print(name.endswith("Doe"))  # Check if string ends with a substring

# Substring (slicing)
print(name[0:4])  # Output: "John"

# -----------------------------
# 4. Lists (Arrays)
# -----------------------------

# List creation
fruits = ["Apple", "Banana", "Orange"]

# Accessing elements
print(fruits[0])  # Output: "Apple"

# Adding elements
fruits.append("Grapes")

# Removing elements
fruits.remove("Banana")

# Sorting the list
fruits.sort()

# Slicing a list
print(fruits[0:2])  # Output: ['Apple', 'Grapes']

# Looping through a list
for fruit in fruits:
    print(fruit)

# -----------------------------
# 5. Tuples
# -----------------------------

# Tuple creation
coordinates = (10, 20)

# Accessing elements
print(coordinates[0])  # Output: 10

# Tuples are immutable (cannot be changed)
# coordinates[0] = 15  # This would raise an error

# -----------------------------
# 6. Dictionaries (Hash Maps)
# -----------------------------

# Dictionary creation
person = {
    "name": "John",
    "age": 30,
    "email": "john@example.com"
}

# Accessing values
print(person["name"])  # Output: "John"

# Adding/Updating values
person["address"] = "123 Main St"
person["age"] = 31

# Removing key-value pairs
del person["email"]

# Looping through a dictionary
for key, value in person.items():
    print(f"{key}: {value}")

# -----------------------------
# 7. Conditionals (if-elif-else)
# -----------------------------

age = 18

if age < 18:
    print("Minor")
elif age == 18:
    print("Just turned adult")
else:
    print("Adult")

# Ternary operator
status = "Adult" if age >= 18 else "Minor"

# -----------------------------
# 8. Loops (for, while)
# -----------------------------

# For loop
for i in range(5):
    print(i)  # Output: 0 1 2 3 4

# While loop
i = 0
while i < 5:
    print(i)
    i += 1

# -----------------------------
# 9. Functions
# -----------------------------

# Function definition
def greet(name):
    return f"Hello, {name}"

# Calling a function
greeting = greet("John")  # Output: "Hello, John"

# Function with default argument
def greet(name="Guest"):
    return f"Hello, {name}"

greeting = greet()  # Output: "Hello, Guest"

# -----------------------------
# 10. Classes and Objects (OOP)
# -----------------------------

# Defining a class
class Car:
    # Constructor
    def __init__(self, make, model):
        self.make = make
        self.model = model

    # Method
    def display_info(self):
        return f"Car: {self.make} {self.model}"

# Creating an object (instance of a class)
my_car = Car("Toyota", "Corolla")
print(my_car.display_info())  # Output: Car: Toyota Corolla

# -----------------------------
# 11. Exception Handling
# -----------------------------

try:
    result = 10 / 0  # This will raise a ZeroDivisionError
except ZeroDivisionError as e:
    print(f"Error: {e}")
finally:
    print("This block always executes")

# -----------------------------
# 12. File Handling
# -----------------------------

# Reading from a file
with open("example.txt", "r") as file:
    content = file.read()
    print(content)

# Writing to a file
with open("output.txt", "w") as file:
    file.write("Hello, Python!")

# -----------------------------
# 13. List Comprehensions
# -----------------------------

# Simple list comprehension
squares = [x**2 for x in range(5)]  # Output: [0, 1, 4, 9, 16]

# List comprehension with condition
even_numbers = [x for x in range(10) if x % 2 == 0]  # Output: [0, 2, 4, 6, 8]

# -----------------------------
# 14. Lambda Functions
# -----------------------------

# Defining a lambda function
add = lambda a, b: a + b

# Calling a lambda function
result = add(3, 5)  # Output: 8

# -----------------------------
# 15. Modules and Packages
# -----------------------------

# Importing a module
import math

# Using a function from a module
print(math.sqrt(16))  # Output: 4.0

# Importing specific functions
from math import pi, sqrt
print(pi)  # Output: 3.141592653589793

# -----------------------------
# 16. JSON Handling
# -----------------------------

import json

# Converting Python dictionary to JSON
person = {"name": "John", "age": 30}
person_json = json.dumps(person)
print(person_json)  # Output: '{"name": "John", "age": 30}'

# Converting JSON to Python dictionary
person_dict = json.loads(person_json)
print(person_dict["name"])  # Output: John

# -----------------------------
# 17. Date and Time
# -----------------------------

from datetime import datetime

# Get the current date and time
now = datetime.now()
print(now)  # Output: Current date and time

# Formatting date and time
formatted = now.strftime("%Y-%m-%d %H:%M:%S")
print(formatted)  # Output: 2024-09-30 14:45:20

# -----------------------------
# 18. Regular Expressions
# -----------------------------

import re

# Searching for a pattern
pattern = r"\d+"  # Matches one or more digits
text = "The price is 100 dollars"
match = re.search(pattern, text)
if match:
    print(match.group())  # Output: 100

# -----------------------------
# 19. Virtual Environments
# -----------------------------

# Create a virtual environment
python -m venv myenv

# Activate virtual environment (Windows)
myenv\Scripts\activate

# Activate virtual environment (macOS/Linux)
source myenv/bin/activate

# Deactivate the virtual environment
deactivate

# -----------------------------
# 20. Pip (Python Package Manager)
# -----------------------------

# Install a package
pip install requests

# Uninstall a package
pip uninstall requests

# List installed packages
pip list

# Upgrade a package
pip install --upgrade requests

