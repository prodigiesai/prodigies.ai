# -----------------------------
# 1. Basic Syntax
# -----------------------------

public class HelloWorld {
    public static void main(String[] args) {
        System.out.println("Hello, World!");  # Print output to the console
    }
}

# -----------------------------
# 2. Variables and Data Types
# -----------------------------

# Variable declaration and initialization
int age = 30;  # Integer
double price = 19.99;  # Double (floating-point)
char grade = 'A';  # Character
boolean isJavaFun = true;  # Boolean
String name = "John Doe";  # String

# Constants
final int MAX_AGE = 100;  # Constant variable

# Type casting
int num = (int) 19.99;  # Explicit casting from double to int

# -----------------------------
# 3. Operators
# -----------------------------

int a = 10;
int b = 20;

int sum = a + b;  # Addition
int difference = b - a;  # Subtraction
int product = a * b;  # Multiplication
int quotient = b / a;  # Division
int remainder = b % a;  # Modulus (remainder)

a++;  # Increment (a = a + 1)
b--;  # Decrement (b = b - 1)

boolean isEqual = (a == b);  # Equal to
boolean isGreater = (a > b);  # Greater than

# -----------------------------
# 4. Conditionals
# -----------------------------

if (a > b) {
    System.out.println("a is greater than b");
} else if (a == b) {
    System.out.println("a is equal to b");
} else {
    System.out.println("a is less than b");
}

# Switch statement
int day = 2;
switch (day) {
    case 1:
        System.out.println("Monday");
        break;
    case 2:
        System.out.println("Tuesday");
        break;
    default:
        System.out.println("Invalid day");
}

# -----------------------------
# 5. Loops
# -----------------------------

# For loop
for (int i = 0; i < 5; i++) {
    System.out.println(i);  # Output: 0 1 2 3 4
}

# While loop
int i = 0;
while (i < 5) {
    System.out.println(i);
    i++;
}

# Do-While loop
i = 0;
do {
    System.out.println(i);
    i++;
} while (i < 5);

# -----------------------------
# 6. Arrays
# -----------------------------

# Array declaration and initialization
int[] numbers = {1, 2, 3, 4, 5};
String[] fruits = {"Apple", "Banana", "Cherry"};

# Accessing elements
System.out.println(numbers[0]);  # Output: 1

# Looping through an array
for (int num : numbers) {
    System.out.println(num);  # Output each number in the array
}

# -----------------------------
# 7. Methods (Functions)
# -----------------------------

# Method definition
public static int add(int a, int b) {
    return a + b;  # Return the sum of a and b
}

# Calling a method
int result = add(5, 10);  # result = 15

# Method with default argument
public static void greet(String name) {
    System.out.println("Hello, " + name);
}
greet("John");  # Output: Hello, John

# -----------------------------
# 8. Object-Oriented Programming (OOP)
# -----------------------------

# Defining a class
class Car {
    # Attributes (fields)
    String make;
    String model;

    # Constructor
    public Car(String make, String model) {
        this.make = make;
        this.model = model;
    }

    # Method (behavior)
    public void displayInfo() {
        System.out.println("Car: " + make + " " + model);
    }
}

# Instantiating an object
Car myCar = new Car("Toyota", "Corolla");
myCar.displayInfo();  # Output: Car: Toyota Corolla

# -----------------------------
# 9. Inheritance
# -----------------------------

# Parent class
class Animal {
    public void makeSound() {
        System.out.println("Animal makes sound");
    }
}

# Child class (inherits from Animal)
class Dog extends Animal {
    @Override
    public void makeSound() {
        System.out.println("Dog barks");
    }
}

# Using inheritance
Dog dog = new Dog();
dog.makeSound();  # Output: Dog barks

# -----------------------------
# 10. Interfaces and Abstract Classes
# -----------------------------

# Defining an interface
interface AnimalInterface {
    void sound();
}

# Implementing the interface
class Cat implements AnimalInterface {
    public void sound() {
        System.out.println("Cat meows");
    }
}

# Abstract class
abstract class Vehicle {
    public abstract void drive();  # Abstract method (no body)
    public void fuel() {  # Concrete method
        System.out.println("Vehicle refuels");
    }
}

# Concrete class extending abstract class
class Bike extends Vehicle {
    public void drive() {
        System.out.println("Bike drives");
    }
}

Cat cat = new Cat();
cat.sound();  # Output: Cat meows

Bike bike = new Bike();
bike.drive();  # Output: Bike drives
bike.fuel();  # Output: Vehicle refuels

# -----------------------------
# 11. Exception Handling
# -----------------------------

try {
    int result = 10 / 0;  # This will cause an ArithmeticException
} catch (ArithmeticException e) {
    System.out.println("Error: " + e.getMessage());
} finally {
    System.out.println("This block always executes");
}

# -----------------------------
# 12. File Handling
# -----------------------------

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.Scanner;

# Writing to a file
try {
    FileWriter writer = new FileWriter("output.txt");
    writer.write("Hello, Java!");
    writer.close();
    System.out.println("Successfully wrote to the file.");
} catch (IOException e) {
    System.out.println("An error occurred.");
}

# Reading from a file
try {
    File file = new File("output.txt");
    Scanner reader = new Scanner(file);
    while (reader.hasNextLine()) {
        String data = reader.nextLine();
        System.out.println(data);  # Output: Hello, Java!
    }
    reader.close();
} catch (IOException e) {
    System.out.println("An error occurred.");
}

# -----------------------------
# 13. Collections (ArrayList, HashMap, etc.)
# -----------------------------

import java.util.ArrayList;
import java.util.HashMap;

# ArrayList
ArrayList<String> colors = new ArrayList<>();
colors.add("Red");
colors.add("Green");
colors.add("Blue");

for (String color : colors) {
    System.out.println(color);  # Output: Red, Green, Blue
}

# HashMap
HashMap<String, Integer> ages = new HashMap<>();
ages.put("John", 30);
ages.put("Alice", 25);
ages.put("Bob", 35);

System.out.println(ages.get("John"));  # Output: 30

# -----------------------------
# 14. Date and Time
# -----------------------------

import java.time.LocalDate;
import java.time.LocalTime;
import java.time.LocalDateTime;

# Get current date
LocalDate date = LocalDate.now();
System.out.println(date);  # Output: current date (e.g., 2024-09-30)

# Get current time
LocalTime time = LocalTime.now();
System.out.println(time);  # Output: current time

# Get current date and time
LocalDateTime dateTime = LocalDateTime.now();
System.out.println(dateTime);  # Output: current date and time

# -----------------------------
# 15. JSON Parsing (Using org.json library)
# -----------------------------

# Assuming org.json library is available
import org.json.JSONObject;

String jsonString = "{\"name\":\"John\",\"age\":30}";
JSONObject obj = new JSONObject(jsonString);

String name = obj.getString("name");
int age = obj.getInt("age");

System.out.println(name + " is " + age + " years old.");  # Output: John is 30 years old.

# -----------------------------
# 16. JDBC (Connecting to MySQL Database)
# -----------------------------

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.Statement;

# Database connection setup
String url = "jdbc:mysql:#localhost:3306/mydatabase";
String username = "root";
String password = "password";

try {
    Connection connection = DriverManager.getConnection(url, username, password);
    Statement statement = connection.createStatement();
    
    # Execute a query
    ResultSet resultSet = statement.executeQuery("SELECT * FROM users");

    # Process the result set
    while (resultSet.next()) {
        System.out.println(resultSet.getString("name") + " - " + resultSet.getInt("age"));
    }

    # Close connection
    connection.close();
} catch (Exception e) {
    e.printStackTrace();
}
