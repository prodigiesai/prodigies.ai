# Docker - Start SQL Server Express Container Session
docker exec -it sqlserver-express /bin/bash  # Enter the container's bash shell

# Inside the SQL Server Container - Set Up Microsoft SQL Server Tools

# Update package list and install necessary dependencies
apt-get update
apt-get install -y curl gnupg2

# Add the Microsoft GPG key and repository for SQL Server tools
curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
curl https://packages.microsoft.com/config/ubuntu/20.04/prod.list > /etc/apt/sources.list.d/mssql-release.list

# Update package list again with the new Microsoft repository
apt-get update

# Install SQL Server tools (sqlcmd) and other required packages
ACCEPT_EULA=Y apt-get install -y msodbcsql17 unixodbc-dev mssql-tools

# Add sqlcmd to the PATH for easy use in the terminal
echo 'export PATH="$PATH:/opt/mssql-tools/bin"' >> ~/.bashrc
source ~/.bashrc

# Running SQL Commands via `sqlcmd`

# Example: Connect to SQL Server and run commands
sqlcmd -S localhost -U sa -P 'P@ssw0rd123!'  # Connect to SQL Server using 'sa' user

# List all databases in the SQL Server instance
SELECT name FROM sys.databases;
GO

# Example: Create and use a new database
CREATE DATABASE MyDatabase;
GO

USE MyDatabase;
GO

# Create a new table inside MyDatabase
CREATE TABLE Users (
    ID INT PRIMARY KEY,
    Name NVARCHAR(50),
    Email NVARCHAR(50)
);
GO

# Insert a row of data into the Users table
INSERT INTO Users (ID, Name, Email) VALUES (1, 'John Doe', 'john@example.com');
GO

# Query all rows from the Users table
SELECT * FROM Users;
GO

# Other Useful SQL Queries

# Get the current database name
SELECT DB_NAME();
GO

# List all tables in the current database
SELECT * FROM sys.tables;
GO

# Run a SQL query from the command line without entering `sqlcmd`
sqlcmd -S localhost,1433 -U sa -P 'P@ssw0rd123!' -Q "SELECT name FROM sys.databases"

# Full Workflow Example - Creating and Using a Database and Table

# Connect to SQL Server
sqlcmd -S localhost,1433 -U sa -P 'P@ssw0rd123!'

# Create a new database
CREATE DATABASE MyNewDatabase;
GO

# Switch to the new database
USE MyNewDatabase;
GO

# Create a table called Products
CREATE TABLE Products (
    ProductID INT PRIMARY KEY,
    ProductName NVARCHAR(50),
    Price DECIMAL(10, 2)
);
GO

# Insert data into the Products table
INSERT INTO Products (ProductID, ProductName, Price) VALUES (1, 'Laptop', 999.99);
GO

# Query the data from the Products table
SELECT * FROM Products;
GO

# Exit sqlcmd session
QUIT
