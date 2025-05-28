-- T-SQL Cheat Sheet

-- -----------------------------
-- 1. Basic SQL Syntax
-- -----------------------------

SELECT * FROM <table_name>;  -- Retrieve all rows from a table
SELECT name, age FROM <table_name>;  -- Retrieve specific columns
SELECT * FROM <table_name> WHERE age > 30;  -- Filter rows based on a condition
SELECT * FROM <table_name> ORDER BY age DESC;  -- Retrieve and sort rows by a column

INSERT INTO <table_name> (name, age) VALUES ('John Doe', 30);  -- Insert a single row

UPDATE <table_name>  -- Update a row in a table
SET age = 32
WHERE name = 'John Doe';

DELETE FROM <table_name> WHERE name = 'John Doe';  -- Delete a single row

-- -----------------------------
-- 2. Data Types
-- -----------------------------

INT  -- Integer data type
BIGINT  -- Large integer data type
DECIMAL(10, 2)  -- Decimal with 10 total digits and 2 decimal places
VARCHAR(255)  -- Variable-length string
NVARCHAR(100)  -- Unicode variable-length string
DATE  -- Date data type (YYYY-MM-DD)
DATETIME  -- Date and time data type (YYYY-MM-DD HH:MM:SS)

-- -----------------------------
-- 3. Creating Tables
-- -----------------------------

CREATE TABLE <table_name> (  -- Create a new table with columns
    id INT PRIMARY KEY,
    name NVARCHAR(50),
    age INT,
    created_at DATETIME DEFAULT GETDATE()
);

ALTER TABLE <table_name> ADD email NVARCHAR(100);  -- Add a new column to an existing table
DROP TABLE <table_name>;  -- Drop a table

-- -----------------------------
-- 4. Constraints
-- -----------------------------

PRIMARY KEY  -- Uniquely identifies each row in a table
FOREIGN KEY  -- Creates a relationship between two tables
NOT NULL  -- Ensures a column cannot have NULL values
UNIQUE  -- Ensures all values in a column are unique
DEFAULT  -- Provides a default value for a column

-- Example with constraints:
CREATE TABLE Orders (
    order_id INT PRIMARY KEY,
    order_date DATETIME DEFAULT GETDATE(),
    customer_id INT,
    CONSTRAINT fk_customer FOREIGN KEY (customer_id) REFERENCES Customers(id)
);

-- -----------------------------
-- 5. Joins
-- -----------------------------

-- Inner Join: Retrieve records that have matching values in both tables
SELECT customers.name, orders.order_date
FROM customers
INNER JOIN orders ON customers.id = orders.customer_id;

-- Left Join: Retrieve all records from the left table, and the matched records from the right table
SELECT customers.name, orders.order_date
FROM customers
LEFT JOIN orders ON customers.id = orders.customer_id;

-- Right Join: Retrieve all records from the right table, and the matched records from the left table
SELECT customers.name, orders.order_date
FROM customers
RIGHT JOIN orders ON customers.id = orders.customer_id;

-- Full Outer Join: Retrieve all records when there is a match in either left or right table
SELECT customers.name, orders.order_date
FROM customers
FULL OUTER JOIN orders ON customers.id = orders.customer_id;

-- -----------------------------
-- 6. Aggregations and Grouping
-- -----------------------------

SELECT COUNT(*) FROM <table_name>;  -- Count the number of rows
SELECT AVG(age) FROM <table_name>;  -- Calculate the average age
SELECT SUM(salary) FROM <table_name>;  -- Sum values in a column
SELECT MAX(age), MIN(age) FROM <table_name>;  -- Get the max and min values

-- Group by and aggregate
SELECT department, COUNT(*) FROM employees
GROUP BY department;

-- Having clause (used after Group By)
SELECT department, AVG(salary)
FROM employees
GROUP BY department
HAVING AVG(salary) > 50000;  -- Filter groups based on aggregate results

-- -----------------------------
-- 7. Case Statement
-- -----------------------------

SELECT name,
CASE
    WHEN age < 18 THEN 'Minor'
    WHEN age >= 18 AND age < 65 THEN 'Adult'
    ELSE 'Senior'
END AS age_group
FROM employees;  -- Classify age into categories

-- -----------------------------
-- 8. Views
-- -----------------------------

CREATE VIEW employee_view AS  -- Create a view
SELECT name, age, department
FROM employees;

SELECT * FROM employee_view;  -- Query a view
DROP VIEW employee_view;  -- Drop a view

-- -----------------------------
-- 9. Transactions
-- -----------------------------

BEGIN TRANSACTION;  -- Start a transaction
    UPDATE accounts SET balance = balance - 100 WHERE id = 1;
    UPDATE accounts SET balance = balance + 100 WHERE id = 2;
COMMIT;  -- Commit the transaction (make changes permanent)

ROLLBACK;  -- Roll back the transaction (undo changes)

-- -----------------------------
-- 10. Stored Procedures
-- -----------------------------

-- Create a stored procedure
CREATE PROCEDURE GetEmployeesByDepartment
    @department NVARCHAR(50)
AS
BEGIN
    SELECT * FROM employees WHERE department = @department;
END;

-- Execute a stored procedure
EXEC GetEmployeesByDepartment @department = 'Sales';

-- Drop a stored procedure
DROP PROCEDURE GetEmployeesByDepartment;

-- -----------------------------
-- 11. Functions
-- -----------------------------

-- Create a scalar function
CREATE FUNCTION GetAgeCategory (@age INT)
RETURNS NVARCHAR(50)
AS
BEGIN
    DECLARE @category NVARCHAR(50);
    IF @age < 18
        SET @category = 'Minor';
    ELSE IF @age >= 18 AND @age < 65
        SET @category = 'Adult';
    ELSE
        SET @category = 'Senior';
    RETURN @category;
END;

-- Execute a scalar function
SELECT name, dbo.GetAgeCategory(age) AS age_category
FROM employees;

-- Drop a scalar function
DROP FUNCTION GetAgeCategory;

-- -----------------------------
-- 12. Common Table Expressions (CTEs)
-- -----------------------------

WITH EmployeeCTE AS (  -- Define a CTE
    SELECT id, name, department
    FROM employees
    WHERE department = 'Sales'
)
SELECT * FROM EmployeeCTE;  -- Query the CTE

-- -----------------------------
-- 13. Window Functions
-- -----------------------------

-- Calculate a running total using window function
SELECT name, salary,
SUM(salary) OVER (ORDER BY salary) AS running_total
FROM employees;

-- Rank rows within a result set
SELECT name, salary,
RANK() OVER (ORDER BY salary DESC) AS salary_rank
FROM employees;

-- -----------------------------
-- 14. String Functions
-- -----------------------------

SELECT UPPER(name) FROM employees;  -- Convert string to uppercase
SELECT LOWER(name) FROM employees;  -- Convert string to lowercase
SELECT LEN(name) FROM employees;  -- Get the length of a string
SELECT SUBSTRING(name, 1, 4) FROM employees;  -- Extract a substring from a string

-- -----------------------------
-- 15. Date Functions
-- -----------------------------

SELECT GETDATE();  -- Get the current date and time
SELECT DATEADD(year, 1, GETDATE());  -- Add 1 year to the current date
SELECT DATEDIFF(day, '2023-01-01', GETDATE());  -- Calculate the difference between two dates
SELECT FORMAT(GETDATE(), 'yyyy-MM-dd');  -- Format the current date

-- -----------------------------
-- 16. Error Handling
-- -----------------------------

BEGIN TRY
    -- Try to execute this block
    INSERT INTO employees (name, age) VALUES ('John Doe', 'InvalidAge');
END TRY
BEGIN CATCH
    -- If an error occurs, handle it here
    SELECT ERROR_MESSAGE() AS ErrorMessage;
END CATCH;

-- -----------------------------
-- 17. Miscellaneous Commands
-- -----------------------------

SELECT @@VERSION;  -- Show SQL Server version
EXEC sp_who;  -- Show active connections and users
EXEC sp_helpdb;  -- Show information about all databases
DBCC CHECKDB;  -- Check the integrity of the database
DBCC SHOWCONTIG;  -- Show fragmentation information for the database
