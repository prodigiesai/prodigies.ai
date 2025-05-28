# MariaDB Cheat Sheet

# -----------------------------
# 1. MariaDB Shell Basics
# -----------------------------

mysql -u root -p  # Connect to MariaDB shell (prompt for password)
exit  # Exit the MariaDB shell
SHOW DATABASES;  # List all databases
USE <database_name>;  # Switch to a specific database
SHOW TABLES;  # List all tables in the current database

# -----------------------------
# 2. Database Operations
# -----------------------------

CREATE DATABASE <database_name>;  # Create a new database
DROP DATABASE <database_name>;  # Drop a database
SHOW DATABASES;  # Show all databases

# -----------------------------
# 3. Table Operations
# -----------------------------

CREATE TABLE <table_name> (  # Create a table with columns
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    age INT
);

DROP TABLE <table_name>;  # Drop a table
DESCRIBE <table_name>;  # Show table structure (columns, data types, etc.)
SHOW TABLES;  # List all tables in the current database

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
SHOW INDEX FROM <table_name>;  # Show all indexes on a table
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
# 11. Users and Privileges
# -----------------------------

# Create a new user with a password
CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';

# Grant privileges to a user
GRANT ALL PRIVILEGES ON <database_name>.* TO 'newuser'@'localhost';

# Show user privileges
SHOW GRANTS FOR 'newuser'@'localhost';

# Revoke privileges
REVOKE ALL PRIVILEGES ON <database_name>.* FROM 'newuser'@'localhost';

# Delete a user
DROP USER 'newuser'@'localhost';

# -----------------------------
# 12. Export and Import Data
# -----------------------------

# Export a database to a SQL file
mysqldump -u root -p <database_name> > <backup.sql>

# Import a SQL file into a database
mysql -u root -p <database_name> < <backup.sql>

# Export a table to a CSV file
SELECT * INTO OUTFILE '/tmp/output.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n'
FROM <table_name>;

# Import data from a CSV file into a table
LOAD DATA INFILE '/tmp/input.csv'
INTO TABLE <table_name>
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
LINES TERMINATED BY '\n';

# -----------------------------
# 13. Backup and Restore
# -----------------------------

# Backup an entire MariaDB database
mysqldump -u root -p <database_name> > backup.sql

# Restore a database from a backup
mysql -u root -p <database_name> < backup.sql

# -----------------------------
# 14. Miscellaneous Commands
# -----------------------------

SHOW VARIABLES LIKE 'version';  # Show the version of MariaDB
FLUSH PRIVILEGES;  # Reload the privileges after making changes
SHOW PROCESSLIST;  # Show active connections and queries
EXPLAIN SELECT * FROM <table_name>;  # Show the execution plan of a query

