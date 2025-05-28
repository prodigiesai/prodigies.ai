# # C++ Cheat Sheet

# # -----------------------------
# # 1. Basic Syntax
# # -----------------------------

#include <iostream>  # For input/output

# int main() {
#     # Print to console
#     std::cout << "Hello, World!" << std::endl;
#     return 0;
# }

# -----------------------------
# 2. Variables and Data Types
# -----------------------------

int age = 30;  # Integer
float price = 19.99f;  # Floating-point number
char grade = 'A';  # Character
std::string name = "John Doe";  # String (C++ standard library)

# Constants
const float PI = 3.14159;

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

# # -----------------------------
# # 4. Conditionals (if, else, switch)
# # -----------------------------

# if (a > b) {
#     std::cout << "a is greater than b" << std::endl;
# } else if (a == b) {
#     std::cout << "a is equal to b" << std::endl;
# } else {
#     std::cout << "a is less than b" << std::endl;
# }

# # Switch statement
# int day = 2;
# switch (day) {
#     case 1:
#         std::cout << "Monday" << std::endl;
#         break;
#     case 2:
#         std::cout << "Tuesday" << std::endl;
#         break;
#     default:
#         std::cout << "Invalid day" << std::endl;
# }

# # -----------------------------
# # 5. Loops (for, while, do-while)
# # -----------------------------

# # For loop
# for (int i = 0; i < 5; i++) {
#     std::cout << i << std::endl;  # Output: 0 1 2 3 4
# }

# # While loop
# int i = 0;
# while (i < 5) {
#     std::cout << i << std::endl;
#     i++;
# }

# # Do-While loop
# i = 0;
# do {
#     std::cout << i << std::endl;
#     i++;
# } while (i < 5);

# # -----------------------------
# # 6. Arrays
# # -----------------------------

# int numbers[] = {1, 2, 3, 4, 5};  # Array of integers

# # Accessing elements
# std::cout << numbers[0] << std::endl;  # Output: 1

# # Modifying an element
# numbers[0] = 10;

# # -----------------------------
# # 7. Vectors (Dynamic Arrays)
# # -----------------------------

# #include <vector>

# std::vector<int> vec = {1, 2, 3, 4};

# # Add an element
# vec.push_back(5);

# # Remove last element
# vec.pop_back();

# # Access elements
# std::cout << vec[0] << std::endl;  # Output: 1

# # Loop through vector
# for (int x : vec) {
#     std::cout << x << std::endl;  # Output: 1 2 3 4
# }

# # -----------------------------
# # 8. Strings
# # -----------------------------

# #include <string>

# std::string str1 = "Hello";
# std::string str2 = "World";

# # Concatenating strings
# std::string combined = str1 + " " + str2;

# # String length
# std::cout << str1.length() << std::endl;  # Output: 5

# # Substring
# std::string sub = str1.substr(0, 3);  # Output: "Hel"

# # -----------------------------
# # 9. Functions
# # -----------------------------

# # Function definition
# int add(int a, int b) {
#     return a + b;
# }

# # Calling a function
# int result = add(5, 10);
# std::cout << "Result: " << result << std::endl;  # Output: Result: 15

# # -----------------------------
# # 10. Pointers and References
# # -----------------------------

# int x = 10;
# int* p = &x;  # Pointer to x

# # Access the value using pointer
# std::cout << *p << std::endl;  # Dereference pointer (Output: 10)

# # Modify value through pointer
# *p = 20;
# std::cout << x << std::endl;  # Output: 20

# # References
# int& ref = x;  # Reference to x
# ref = 30;
# std::cout << x << std::endl;  # Output: 30

# # -----------------------------
# # 11. Classes and Objects (OOP)
# # -----------------------------

# class Car {
# public:
#     std::string make;
#     std::string model;

#     # Constructor
#     Car(std::string m, std::string mdl) : make(m), model(mdl) {}

#     # Method
#     void displayInfo() {
#         std::cout << "Car: " << make << " " << model << std::endl;
#     }
# };

# # Creating an object (instance of a class)
# Car myCar("Toyota", "Corolla");
# myCar.displayInfo();  # Output: Car: Toyota Corolla

# # -----------------------------
# # 12. Inheritance
# # -----------------------------

# # Base class
# class Animal {
# public:
#     void makeSound() {
#         std::cout << "Animal sound" << std::endl;
#     }
# };

# # Derived class
# class Dog : public Animal {
# public:
#     void makeSound() {
#         std::cout << "Bark" << std::endl;
#     }
# };

# # Using inheritance
# Dog dog;
# dog.makeSound();  # Output: Bark

# # -----------------------------
# # 13. File Handling
# # -----------------------------

# #include <fstream>

# # Writing to a file
# std::ofstream file("example.txt");
# if (file.is_open()) {
#     file << "Hello, C++!";
#     file.close();
# }

# # Reading from a file
# std::ifstream infile("example.txt");
# if (infile.is_open()) {
#     std::string line;
#     while (getline(infile, line)) {
#         std::cout << line << std::endl;  # Output: Hello, C++!
#     }
#     infile.close();
# }

# # -----------------------------
# # 14. Exception Handling
# # -----------------------------

# try {
#     int result = 10 / 0;  # This will throw an exception
# } catch (const std::exception& e) {
#     std::cerr << "Error: " << e.what() << std::endl;
# }

# # -----------------------------
# # 15. Memory Management (Dynamic Allocation)
# # -----------------------------

# int* ptr = new int;  # Dynamically allocate an integer
# *ptr = 100;  # Assign a value
# std::cout << *ptr << std::endl;  # Output: 100

# delete ptr;  # Deallocate memory

# # Array allocation
# int* arr = new int[5];
# for (int i = 0; i < 5; i++) {
#     arr[i] = i + 1;
# }
# delete[] arr;  # Deallocate memory

# # -----------------------------
# # 16. Popular Algorithms in C++
# # -----------------------------

# # 1. Bubble Sort
# void bubbleSort(std::vector<int>& arr) {
#     int n = arr.size();
#     for (int i = 0; i < n - 1; i++) {
#         for (int j = 0; j < n - i - 1; j++) {
#             if (arr[j] > arr[j + 1]) {
#                 std::swap(arr[j], arr[j + 1]);  # Swap elements
#             }
#         }
#     }
# }

# # 2. Linear Search
# int linearSearch(const std::vector<int>& arr, int target) {
#     for (int i = 0; i < arr.size(); i++) {
#         if (arr[i] == target) {
#             return i;  # Return index of target
#         }
#     }
#     return -1;  # Return -1 if not found
# }

# # 3. Binary Search (on sorted array)
# int binarySearch(const std::vector<int>& arr, int left, int right, int target) {
#     while (left <= right) {
#         int mid = left + (right - left) / 2;
#         if (arr[mid] == target) {
#             return mid;  # Return index of target
#         } else if (arr[mid] < target) {
#             left = mid + 1;
#         } else {
#             right = mid - 1;
#         }
#     }
#     return -1;  # Return -1 if not found
# }

# # 4. Factorial (Recursive)
# int factorial(int n) {
#     if (n == 0) return 1;
#     return n * factorial(n - 1);  # Recursive call
# }

# # 5. Fibonacci (Recursive)
# int fibonacci(int n) {
#     if (n <= 1) return n;
#     return fibonacci(n - 1) + fibonacci(n - 2);  # Recursive call
# }

# # 6. Merge Sort
# void merge(std::vector<int>& arr, int l, int m, int r) {
#     int n1 = m - l + 1;
#     int n2 = r - m;
#     std::vector<int> L(n1), R(n2);
#     for (int i = 0; i < n1; i++)
#         L[i] = arr[l + i];
#     for (int j = 0; j < n2; j++)
#         R[j] = arr[m + 1 + j];

#     int i = 0, j = 0, k = l;
#     while (i < n1 && j < n2) {
#         if (L[i] <= R[j]) {
#             arr[k] = L[i];
#             i++;
#         } else {
#             arr[k] = R[j];
#             j++;
#         }
#         k++;
#     }
#     while (i < n1) {
#         arr[k] = L[i];
#         i++;
#         k++;
#     }
#     while (j < n2) {
#         arr[k] = R[j];
#         j++;
#         k++;
#     }
# }

# void mergeSort(std::vector<int>& arr, int l, int r) {
#     if (l < r) {
#         int m = l + (r - l) / 2;
#         mergeSort(arr, l, m);
#         mergeSort(arr, m + 1, r);
#         merge(arr, l, m, r);
#     }
# }
