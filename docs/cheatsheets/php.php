<?php
// PHP Cheat Sheet

// -----------------------------
// 1. Basic Syntax and Variables
// -----------------------------

// Outputting text
echo "Hello, World!";  // Print to the screen
print "Hello, World!";  // Print with a return value

// Variables
$name = "John";  // String variable
$age = 30;  // Integer variable
$is_admin = true;  // Boolean variable

// Constants
define("SITE_NAME", "My Website");  // Define a constant
echo SITE_NAME;  // Output the constant value

// -----------------------------
// 2. Data Types and Type Casting
// -----------------------------

// String, Integer, Float, Boolean, Array, Object, NULL
$number = (int)"123";  // Type casting string to integer
$is_bool = (bool)1;  // Type casting integer to boolean

// Get type of a variable
echo gettype($name);  // Output the type of variable

// Check if a variable is a specific type
is_int($age);  // Check if $age is an integer
is_string($name);  // Check if $name is a string

// -----------------------------
// 3. Arrays
// -----------------------------

// Indexed Array
$fruits = array("Apple", "Banana", "Orange");
echo $fruits[0];  // Output "Apple"

// Associative Array
$person = array("name" => "John", "age" => 30);
echo $person["name"];  // Output "John"

// Multidimensional Array
$people = array(
    array("John", 30),
    array("Alice", 25),
    array("Bob", 35)
);
echo $people[1][0];  // Output "Alice"

// Array Functions
count($fruits);  // Get the number of elements in the array
array_push($fruits, "Grapes");  // Add an element to the end of the array
array_pop($fruits);  // Remove the last element
sort($fruits);  // Sort the array

// -----------------------------
// 4. String Functions
// -----------------------------

$str = "Hello, World!";
strlen($str);  // Get the length of the string
strtolower($str);  // Convert string to lowercase
strtoupper($str);  // Convert string to uppercase
str_replace("World", "PHP", $str);  // Replace part of the string
substr($str, 0, 5);  // Get a substring ("Hello")
strpos($str, "World");  // Find the position of a substring

// -----------------------------
// 5. Conditionals and Loops
// -----------------------------

// If, Else, Elseif
if ($age > 18) {
    echo "Adult";
} elseif ($age == 18) {
    echo "Just turned adult";
} else {
    echo "Minor";
}

// Switch
$fruit = "Apple";
switch ($fruit) {
    case "Apple":
        echo "You selected Apple";
        break;
    case "Banana":
        echo "You selected Banana";
        break;
    default:
        echo "Unknown fruit";
}

// Loops: For, While, Do-While, Foreach
for ($i = 0; $i < 5; $i++) {
    echo $i;  // Output: 0 1 2 3 4
}

$i = 0;
while ($i < 5) {
    echo $i;
    $i++;
}

$i = 0;
do {
    echo $i;
    $i++;
} while ($i < 5);

$fruits = array("Apple", "Banana", "Orange");
foreach ($fruits as $fruit) {
    echo $fruit;  // Output: Apple Banana Orange
}

// -----------------------------
// 6. Functions
// -----------------------------

// Define a function
function greet($name) {
    return "Hello, " . $name;
}
echo greet("John");  // Output: Hello, John

// Function with default argument
function greet_default($name = "Guest") {
    return "Hello, " . $name;
}
echo greet_default();  // Output: Hello, Guest

// -----------------------------
// 7. Superglobals
// -----------------------------

// $_GET: Retrieve query parameters from the URL
echo $_GET['name'];  // Get the "name" parameter from the URL

// $_POST: Retrieve form data
echo $_POST['email'];  // Get the "email" field from a form

// $_SERVER: Get information about the server
echo $_SERVER['REQUEST_METHOD'];  // Get the request method (GET or POST)

// -----------------------------
// 8. File Handling
// -----------------------------

// Reading from a file
$contents = file_get_contents('example.txt');  // Read the entire file into a string
echo $contents;

// Writing to a file
file_put_contents('output.txt', 'Hello, PHP!');  // Write to a file

// Checking if a file exists
if (file_exists('example.txt')) {
    echo "File exists!";
}

// Opening and closing files
$file = fopen('example.txt', 'r');  // Open file for reading
fclose($file);  // Close the file

// -----------------------------
// 9. Sessions and Cookies
// -----------------------------

// Starting a session
session_start();
$_SESSION['username'] = "JohnDoe";  // Set session variable

// Accessing session data
echo $_SESSION['username'];

// Destroying a session
session_destroy();

// Setting a cookie
setcookie("username", "JohnDoe", time() + 3600);  // Set a cookie for 1 hour

// Accessing a cookie
if (isset($_COOKIE['username'])) {
    echo $_COOKIE['username'];
}

// -----------------------------
// 10. Object-Oriented Programming
// -----------------------------

// Defining a class
class Car {
    public $make;
    public $model;

    // Constructor
    public function __construct($make, $model) {
        $this->make = $make;
        $this->model = $model;
    }

    // Method
    public function getCarDetails() {
        return $this->make . " " . $this->model;
    }
}

// Instantiating an object
$myCar = new Car("Toyota", "Corolla");
echo $myCar->getCarDetails();  // Output: Toyota Corolla

// -----------------------------
// 11. Exception Handling
// -----------------------------

try {
    if (!file_exists("file.txt")) {
        throw new Exception("File not found.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// -----------------------------
// 12. MySQLi (Database Connection)
// -----------------------------

// Create a connection
$mysqli = new mysqli("localhost", "username", "password", "database");

// Check connection
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Query the database
$result = $mysqli->query("SELECT * FROM users");

// Fetch rows
while ($row = $result->fetch_assoc()) {
    echo $row['name'];
}

// Close the connection
$mysqli->close();

// -----------------------------
// 13. JSON Encoding and Decoding
// -----------------------------

// Encode an array into JSON
$data = array("name" => "John", "age" => 30);
$json_data = json_encode($data);
echo $json_data;  // Output: {"name":"John","age":30}

// Decode JSON into an associative array
$json_data = '{"name":"John","age":30}';
$array = json_decode($json_data, true);
echo $array['name'];  // Output: John

// -----------------------------
// 14. Date and Time
// -----------------------------

echo date("Y-m-d H:i:s");  // Output the current date and time
$timestamp = strtotime("2024-09-30");  // Convert a date string to a timestamp
echo date("Y-m-d", $timestamp);  // Format a timestamp into a readable date

// -----------------------------
// 15. PHP Filters
// -----------------------------

// Sanitize an email address
$email = filter_var("john.doe@example.com", FILTER_SANITIZE_EMAIL);
echo $email;

// Validate an email address
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Valid email!";
} else {
    echo "Invalid email!";
}
?>
