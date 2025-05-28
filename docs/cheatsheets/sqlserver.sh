# SQL Server Express Cheat Sheet

# -----------------------------
# 1. SQL Server Basics
# -----------------------------

sqlcmd -S <server_name> -U <username> -P <password>  # Connect to SQL Server via command line
sqlcmd -S localhost -U sa -P 'P@ssw0rd123!'  # Example: Connect to local server with 'sa' user
exit  # Exit the SQL Server command-line tool

# -----------------------------
# 2. Database Operations
# -----------------------------

CREATE DATABASE <database_name>;  # Create a new database
DROP DATABASE <database_name>;  # Drop a database
USE <database_name>;  # Switch to a specific database
SELECT name FROM sys.databases;  # List all databases

# -----------------------------
# 3. Table Operations
# -----------------------------

CREATE TABLE <table_name> (  # Create a new table with columns
    id INT PRIMARY KEY,
    name NVARCHAR(50),
    age INT
);

DROP TABLE <table_name>;  # Drop a table
EXEC sp_help <table_name>;  # Show table structure (columns, data types, etc.)
SELECT * FROM INFORMATION_SCHEMA.TABLES;  # List all tables in the current database

# -----------------------------
# 4. Inserting Data
# -----------------------------

INSERT INTO <table_name> (name, age) VALUES ('John Doe', 30);  # Insert a single row
INSERT INTO <table_name> (name, age) VALUES  # Insert multiple rows
('Alice', 25),
('Bob', 35);

# -----------------------------
# 5. Querying Data
# -----------------------------

SELECT * FROM <table_name>;  # Retrieve all rows from a table
SELECT name, age FROM <table_name>;  # Retrieve specific columns
SELECT * FROM <table_name> WHERE age > 30;  # Filter rows based on a condition
SELECT * FROM <table_name> ORDER BY age DESC;  # Retrieve and sort rows by a column

# -----------------------------
# 6. Updating Data
# -----------------------------

UPDATE <table_name>  # Update data in a table
SET age = 32
WHERE name = 'John Doe';

# Update multiple rows with a condition
UPDATE <table_name>
SET age = age + 1
WHERE age > 30;

# -----------------------------
# 7. Deleting Data
# -----------------------------

DELETE FROM <table_name> WHERE name = 'John Doe';  # Delete a single row
DELETE FROM <table_name> WHERE age < 30;  # Delete rows based on a condition
DELETE FROM <table_name>;  # Delete all rows in a table

# -----------------------------
# 8. Indexes
# -----------------------------

CREATE INDEX idx_name ON <table_name>(name);  # Create an index on a column
SELECT * FROM sys.indexes WHERE object_id = OBJECT_ID('<table_name>');  # Show all indexes on a table
DROP INDEX idx_name ON <table_name>;  # Drop an index from a table

# -----------------------------
# 9. Joins
# -----------------------------

# Inner Join: Get data from multiple tables where there is a match
SELECT users.name, orders.order_date
FROM users
INNER JOIN orders ON users.id = orders.user_id;

# Left Join: Get all rows from the left table, even if there’s no match in the right table
SELECT users.name, orders.order_date
FROM users
LEFT JOIN orders ON users.id = orders.user_id;

# Right Join: Get all rows from the right table, even if there’s no match in the left table
SELECT users.name, orders.order_date
FROM users
RIGHT JOIN orders ON users.id = orders.user_id;

# -----------------------------
# 10. Aggregation
# -----------------------------

SELECT COUNT(*) FROM <table_name>;  # Count total number of rows
SELECT AVG(age) FROM <table_name>;  # Calculate the average age
SELECT SUM(salary) FROM <table_name>;  # Sum values in a column
SELECT MAX(age), MIN(age) FROM <table_name>;  # Get the max and min values

# Group by and aggregate
SELECT age, COUNT(*) FROM <table_name>
GROUP BY age;

# -----------------------------
# 11. Stored Procedures
# -----------------------------

# Create a stored procedure
CREATE PROCEDURE <procedure_name>
AS
BEGIN
    SELECT * FROM <table_name>;
END;

# Execute a stored procedure
EXEC <procedure_name>;

# Drop a stored procedure
DROP PROCEDURE <procedure_name>;

# -----------------------------
# 12. Users and Permissions
# -----------------------------

# Create a new user with login
CREATE LOGIN <login_name> WITH PASSWORD = 'password';
CREATE USER <username> FOR LOGIN <login_name>;

# Grant permission to the user
GRANT SELECT, INSERT, UPDATE, DELETE ON <table_name> TO <username>;

# Show user privileges
sp_helprotect @username = '<username>';

# Drop a user and login
DROP USER <username>;
DROP LOGIN <login_name>;

# -----------------------------
# 13. Backup and Restore
# -----------------------------

# Backup a database
BACKUP DATABASE <database_name>
TO DISK = 'C:\backup\database_backup.bak';

# Restore a database from a backup
RESTORE DATABASE <database_name>
FROM DISK = 'C:\backup\database_backup.bak';

# -----------------------------
# 14. Export and Import Data
# -----------------------------

# Export a table to a CSV file
sqlcmd -S <server_name> -U <username> -P <password> -Q "SELECT * FROM <table_name>" -o output.csv -s"," -W -w 1024

# Import data from a CSV file into a table (via BULK INSERT)
BULK INSERT <table_name>
FROM 'C:\path\to\file.csv'
WITH (
    FIELDTERMINATOR = ',',
    ROWTERMINATOR = '\n',
    FIRSTROW = 2  # Skip header row
);

# -----------------------------
# 15. Miscellaneous Commands
# -----------------------------

SELECT @@VERSION;  # Show the SQL Server version
EXEC sp_who;  # Show active sessions and connections
EXEC sp_helpdb;  # Show information about all databases
EXEC sp_help <table_name>;  # Show table information
DBCC CHECKDB;  # Check the integrity of the database
DBCC SHOWCONTIG;  # Show fragmentation information for the database

