# C Cheat Sheet

# -----------------------------
# 1. Basic Syntax
# -----------------------------

#include <stdio.h>

int main() {
    # Print to console
    printf("Hello, World!\n");
    return 0;
}

# -----------------------------
# 2. Variables and Data Types
# -----------------------------

int age = 30;  # Integer
float price = 19.99;  # Floating-point number
char grade = 'A';  # Character
char name[] = "John Doe";  # String (array of characters)

# Constants
#define PI 3.14159

# -----------------------------
# 3. Operators
# -----------------------------

int a = 10, b = 20;
int sum = a + b;  # Addition
int diff = b - a;  # Subtraction
int product = a * b;  # Multiplication
int quotient = b / a;  # Division
int remainder = b % a;  # Modulus

# -----------------------------
# 4. Conditionals (if, else, switch)
# -----------------------------

if (a > b) {
    printf("a is greater than b\n");
} else if (a == b) {
    printf("a is equal to b\n");
} else {
    printf("a is less than b\n");
}

# Switch statement
int day = 2;
switch (day) {
    case 1:
        printf("Monday\n");
        break;
    case 2:
        printf("Tuesday\n");
        break;
    default:
        printf("Invalid day\n");
}

# -----------------------------
# 5. Loops (for, while, do-while)
# -----------------------------

# For loop
for (int i = 0; i < 5; i++) {
    printf("%d\n", i);  # Output: 0 1 2 3 4
}

# While loop
int i = 0;
while (i < 5) {
    printf("%d\n", i);
    i++;
}

# Do-While loop
i = 0;
do {
    printf("%d\n", i);
    i++;
} while (i < 5);

# -----------------------------
# 6. Arrays
# -----------------------------

int numbers[] = {1, 2, 3, 4, 5};  # Array of integers

# Accessing elements
printf("%d\n", numbers[0]);  # Output: 1

# Modifying an element
numbers[0] = 10;

# -----------------------------
# 7. Strings
# -----------------------------

#include <string.h>

char str1[] = "Hello";
char str2[] = "World";

# Concatenating strings
strcat(str1, str2);

# Copying strings
strcpy(str1, str2);

# String length
int len = strlen(str1);

# -----------------------------
# 8. Functions
# -----------------------------

# Function definition
int add(int a, int b) {
    return a + b;
}

# Calling a function
int result = add(5, 10);
printf("Result: %d\n", result);  # Output: Result: 15

# -----------------------------
# 9. Pointers
# -----------------------------

int x = 10;
int *p = &x;  # Pointer to the variable x

printf("Address of x: %p\n", &x);  # Print address of x
printf("Value at p: %d\n", *p);  # Dereference the pointer (output: 10)

# Modifying the value via pointer
*p = 20;
printf("New value of x: %d\n", x);  # Output: 20

# -----------------------------
# 10. Structures
# -----------------------------

struct Person {
    char name[50];
    int age;
    float height;
};

struct Person person1;

strcpy(person1.name, "John Doe");
person1.age = 30;
person1.height = 5.9;

printf("Name: %s, Age: %d, Height: %.1f\n", person1.name, person1.age, person1.height);

# -----------------------------
# 11. Memory Management
# -----------------------------

#include <stdlib.h>

# Allocating memory
int *arr = (int *)malloc(5 * sizeof(int));

# Using the allocated memory
for (int i = 0; i < 5; i++) {
    arr[i] = i + 1;
}

# Freeing memory
free(arr);

# -----------------------------
# 12. File Handling
# -----------------------------

#include <stdio.h>

FILE *file;

# Writing to a file
file = fopen("example.txt", "w");
if (file != NULL) {
    fprintf(file, "Hello, C!\n");
    fclose(file);
}

# Reading from a file
file = fopen("example.txt", "r");
if (file != NULL) {
    char buffer[100];
    while (fgets(buffer, 100, file)) {
        printf("%s", buffer);
    }
    fclose(file);
}

# -----------------------------
# 13. Error Handling (using errno)
# -----------------------------

#include <errno.h>
#include <string.h>

file = fopen("nonexistent.txt", "r");
if (file == NULL) {
    printf("Error: %s\n", strerror(errno));  # Output error message
}

# -----------------------------
# 14. Common Algorithms in C
# -----------------------------

# 1. Bubble Sort
void bubbleSort(int arr[], int n) {
    for (int i = 0; i < n-1; i++) {
        for (int j = 0; j < n-i-1; j++) {
            if (arr[j] > arr[j+1]) {
                # Swap arr[j] and arr[j+1]
                int temp = arr[j];
                arr[j] = arr[j+1];
                arr[j+1] = temp;
            }
        }
    }
}

# 2. Linear Search
int linearSearch(int arr[], int n, int target) {
    for (int i = 0; i < n; i++) {
        if (arr[i] == target) {
            return i;  # Return the index of the target
        }
    }
    return -1;  # Return -1 if the target is not found
}

# 3. Binary Search (requires a sorted array)
int binarySearch(int arr[], int left, int right, int target) {
    while (left <= right) {
        int mid = left + (right - left) / 2;
        if (arr[mid] == target) {
            return mid;  # Return the index of the target
        }
        if (arr[mid] < target) {
            left = mid + 1;
        } else {
            right = mid - 1;
        }
    }
    return -1;  # Return -1 if the target is not found
}

# 4. Factorial (using recursion)
int factorial(int n) {
    if (n == 0) {
        return 1;  # Base case: 0! = 1
    }
    return n * factorial(n - 1);  # Recursive case
}

# 5. Fibonacci (using recursion)
int fibonacci(int n) {
    if (n <= 1) {
        return n;
    }
    return fibonacci(n - 1) + fibonacci(n - 2);
}

# 6. Reverse a String (in-place)
void reverseString(char str[]) {
    int n = strlen(str);
    for (int i = 0; i < n / 2; i++) {
        char temp = str[i];
        str[i] = str[n - i - 1];
        str[n - i - 1] = temp;
    }
}

# 7. Palindrome Check
int isPalindrome(char str[]) {
    int n = strlen(str);
    for (int i = 0; i < n / 2; i++) {
        if (str[i] != str[n - i - 1]) {
            return 0;  # Not a palindrome
        }
    }
    return 1;  # Is a palindrome
}

# 8. Merge Sort
void merge(int arr[], int l, int m, int r) {
    int n1 = m - l + 1;
    int n2 = r - m;
    int L[n1], R[n2];
    for (int i = 0; i < n1; i++)
        L[i] = arr[l + i];
    for (int j = 0; j < n2; j++)
        R[j] = arr[m + 1 + j];
    
    int i = 0, j = 0, k = l;
    while (i < n1 && j < n2) {
        if (L[i] <= R[j]) {
            arr[k] = L[i];
            i++;
        } else {
            arr[k] = R[j];
            j++;
        }
        k++;
    }
    while (i < n1) {
        arr[k] = L[i];
        i++;
        k++;
    }
    while (j < n2) {
        arr[k] = R[j];
        j++;
        k++;
    }
}

void mergeSort(int arr[], int l, int r) {
    if (l < r) {
        int m = l + (r - l) / 2;
        mergeSort(arr, l, m);
        mergeSort(arr, m + 1, r);
        merge(arr, l, m, r);
    }
}
