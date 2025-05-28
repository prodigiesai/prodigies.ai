# C# Cheat Sheet

# -----------------------------
# 1. Basic Syntax
# -----------------------------

using System;

class Program {
    static void Main(string[] args) {
        # Print to console
        Console.WriteLine("Hello, World!");
    }
}

# -----------------------------
# 2. Variables and Data Types
# -----------------------------

int age = 30;  # Integer
double price = 19.99;  # Double (floating-point number)
char grade = 'A';  # Character
string name = "John Doe";  # String
bool isStudent = true;  # Boolean

# Constants
const double PI = 3.14159;

# Type Inference
var count = 10;  # Automatically infers type as int

# -----------------------------
# 3. Operators
# -----------------------------

int a = 10, b = 20;
int sum = a + b;  # Addition
int diff = b - a;  # Subtraction
int product = a * b;  # Multiplication
int quotient = b / a;  # Division
int remainder = b % a;  # Modulus

# Comparison operators
bool isEqual = (a == b);  # Equal to
bool isGreater = (a > b);  # Greater than

# -----------------------------
# 4. Conditionals (if, else, switch)
# -----------------------------

if (a > b) {
    Console.WriteLine("a is greater than b");
} else if (a == b) {
    Console.WriteLine("a is equal to b");
} else {
    Console.WriteLine("a is less than b");
}

# Switch statement
int day = 2;
switch (day) {
    case 1:
        Console.WriteLine("Monday");
        break;
    case 2:
        Console.WriteLine("Tuesday");
        break;
    default:
        Console.WriteLine("Invalid day");
        break;
}

# Ternary operator
string result = (a > b) ? "a is greater" : "b is greater";

# -----------------------------
# 5. Loops (for, while, do-while)
# -----------------------------

# For loop
for (int i = 0; i < 5; i++) {
    Console.WriteLine(i);  # Output: 0 1 2 3 4
}

# While loop
int i = 0;
while (i < 5) {
    Console.WriteLine(i);
    i++;
}

# Do-While loop
i = 0;
do {
    Console.WriteLine(i);
    i++;
} while (i < 5);

# -----------------------------
# 6. Arrays and Lists
# -----------------------------

# Array
int[] numbers = {1, 2, 3, 4, 5};

# Accessing elements
Console.WriteLine(numbers[0]);  # Output: 1

# List (Dynamic Array)
using System.Collections.Generic;

List<int> list = new List<int> {1, 2, 3, 4};

# Add element to list
list.Add(5);

# Remove element from list
list.Remove(2);

# Loop through list
foreach (int num in list) {
    Console.WriteLine(num);  # Output: 1 3 4 5
}

# -----------------------------
# 7. Strings
# -----------------------------

string str1 = "Hello";
string str2 = "World";

# String concatenation
string combined = str1 + " " + str2;

# String length
Console.WriteLine(str1.Length);  # Output: 5

# Substring
Console.WriteLine(str1.Substring(0, 3));  # Output: "Hel"

# String interpolation
string interpolated = $"Hello, {name}!";

# -----------------------------
# 8. Methods (Functions)
# -----------------------------

# Method definition
int Add(int a, int b) {
    return a + b;
}

# Calling a method
int result = Add(5, 10);
Console.WriteLine("Result: " + result);  # Output: Result: 15

# Method with default arguments
void Greet(string name = "Guest") {
    Console.WriteLine("Hello, " + name);
}

Greet();  # Output: Hello, Guest

# -----------------------------
# 9. Classes and Objects (OOP)
# -----------------------------

class Car {
    public string Make { get; set; }
    public string Model { get; set; }

    # Constructor
    public Car(string make, string model) {
        Make = make;
        Model = model;
    }

    # Method
    public void DisplayInfo() {
        Console.WriteLine($"Car: {Make} {Model}");
    }
}

# Creating an object (instance of a class)
Car myCar = new Car("Toyota", "Corolla");
myCar.DisplayInfo();  # Output: Car: Toyota Corolla

# -----------------------------
# 10. Inheritance
# -----------------------------

# Base class
class Animal {
    public virtual void MakeSound() {
        Console.WriteLine("Animal sound");
    }
}

# Derived class
class Dog : Animal {
    public override void MakeSound() {
        Console.WriteLine("Bark");
    }
}

# Using inheritance
Dog dog = new Dog();
dog.MakeSound();  # Output: Bark

# -----------------------------
# 11. Properties (Getters/Setters)
# -----------------------------

class Person {
    private string name;

    # Property
    public string Name {
        get { return name; }
        set { name = value; }
    }
}

Person person = new Person();
person.Name = "John";  # Set property
Console.WriteLine(person.Name);  # Get property

# -----------------------------
# 12. Exception Handling
# -----------------------------

try {
    int result = 10 / 0;  # This will throw a DivideByZeroException
} catch (DivideByZeroException e) {
    Console.WriteLine("Error: " + e.Message);
} finally {
    Console.WriteLine("This block always executes");
}

# -----------------------------
# 13. File Handling
# -----------------------------

using System.IO;

# Writing to a file
File.WriteAllText("example.txt", "Hello, C#!");

# Reading from a file
string content = File.ReadAllText("example.txt");
Console.WriteLine(content);  # Output: Hello, C#!

# -----------------------------
# 14. Memory Management (Garbage Collection)
# -----------------------------

# In C#, memory is managed by the garbage collector, so manual memory management is not needed like in C/C++.
# Objects that are no longer in use are automatically freed by the garbage collector.

# -----------------------------
# 15. LINQ (Language Integrated Query)
# -----------------------------

using System.Linq;

int[] numbers = {1, 2, 3, 4, 5, 6};

# LINQ query to find even numbers
var evenNumbers = numbers.Where(n => n % 2 == 0);

# Loop through result
foreach (var num in evenNumbers) {
    Console.WriteLine(num);  # Output: 2 4 6
}

# -----------------------------
# 16. Async and Await (Asynchronous Programming)
# -----------------------------

using System.Threading.Tasks;

class Program {
    static async Task Main(string[] args) {
        await DoSomethingAsync();
        Console.WriteLine("Done");
    }

    static async Task DoSomethingAsync() {
        await Task.Delay(2000);  # Simulate asynchronous work (2 seconds)
        Console.WriteLine("Task Completed");
    }
}

# -----------------------------
# 17. Popular Algorithms in C#
# -----------------------------

# 1. Bubble Sort
void BubbleSort(int[] arr) {
    int n = arr.Length;
    for (int i = 0; i < n - 1; i++) {
        for (int j = 0; j < n - i - 1; j++) {
            if (arr[j] > arr[j + 1]) {
                # Swap elements
                int temp = arr[j];
                arr[j] = arr[j + 1];
                arr[j + 1] = temp;
            }
        }
    }
}

# 2. Linear Search
int LinearSearch(int[] arr, int target) {
    for (int i = 0; i < arr.Length; i++) {
        if (arr[i] == target) {
            return i;  # Return index of target
        }
    }
    return -1;  # Return -1 if not found
}

# 3. Binary Search (on sorted array)
int BinarySearch(int[] arr, int target) {
    int left = 0, right = arr.Length - 1;
    while (left <= right) {
        int mid = left + (right - left) / 2;
        if (arr[mid] == target) {
            return mid;  # Return index of target
        } else if (arr[mid] < target) {
            left = mid + 1;
        } else {
            right = mid - 1;
        }
    }
    return -1;  # Return -1 if not found
}

# 4. Factorial (Recursive)
int Factorial(int n) {
    if (n == 0) return 1;
    return n * Factorial(n - 1);  # Recursive call
}

# 5. Fibonacci (Recursive)
int Fibonacci(int n) {
    if (n <= 1) return n;
    return Fibonacci(n - 1) + Fibonacci(n - 2);  # Recursive call
}

# 6. Merge Sort
void Merge(int[] arr, int left, int mid, int right) {
    int n1 = mid - left + 1;
    int n2 = right - mid;
    int[] L = new int[n1], R = new int[n2];
    
    for (int i = 0; i < n1; i++)
        L[i] = arr[left + i];
    for (int j = 0; j < n2; j++)
        R[j] = arr[mid + 1 + j];

    int i1 = 0, j1 = 0, k = left;
    while (i1 < n1 && j1 < n2) {
        if (L[i1] <= R[j1]) {
            arr[k] = L[i1];
            i1++;
        } else {
            arr[k] = R[j1];
            j1++;
        }
        k++;
    }
    while (i1 < n1) {
        arr[k] = L[i1];
        i1++;
        k++;
    }
    while (j1 < n2) {
        arr[k] = R[j1];
        j1++;
        k++;
    }
}

void MergeSort(int[] arr, int left, int right) {
    if (left < right) {
        int mid = left + (right - left) / 2;
        MergeSort(arr, left, mid);
        MergeSort(arr, mid + 1, right);
        Merge(arr, left, mid, right);
    }
}
