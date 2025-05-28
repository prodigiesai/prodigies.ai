# -- PL/SQL (Oracle) Cheat Sheet

# -- -----------------------------
# -- 1. Basic SQL Syntax
# -- -----------------------------

# SELECT * FROM employees;  -- Retrieve all rows from the employees table
# SELECT first_name, last_name FROM employees;  -- Select specific columns
# SELECT * FROM employees WHERE department_id = 10;  -- Filter rows based on a condition
# SELECT * FROM employees ORDER BY first_name ASC;  -- Order rows by a column (ascending)

# INSERT INTO employees (first_name, last_name, email) VALUES ('John', 'Doe', 'john@example.com');  -- Insert a row
# UPDATE employees SET salary = 5000 WHERE employee_id = 101;  -- Update a row
# DELETE FROM employees WHERE employee_id = 102;  -- Delete a row

# -- -----------------------------
# -- 2. Declaring Variables
# -- -----------------------------

# DECLARE
#     v_employee_id employees.employee_id%TYPE;  -- Declare a variable of the same type as a table column
#     v_name VARCHAR2(50);  -- Declare a VARCHAR2 variable
#     v_salary NUMBER(10, 2);  -- Declare a numeric variable
# BEGIN
#     v_employee_id := 101;  -- Assign value to variable
#     v_name := 'John Doe';
#     v_salary := 3000.50;
# END;

# -- -----------------------------
# -- 3. Conditionals (IF-THEN-ELSE)
# -- -----------------------------

# DECLARE
#     v_salary NUMBER(10, 2);
# BEGIN
#     v_salary := 4000;
#     IF v_salary < 3000 THEN
#         DBMS_OUTPUT.PUT_LINE('Salary is below average');
#     ELSIF v_salary BETWEEN 3000 AND 5000 THEN
#         DBMS_OUTPUT.PUT_LINE('Salary is average');
#     ELSE
#         DBMS_OUTPUT.PUT_LINE('Salary is above average');
#     END IF;
# END;

# -- -----------------------------
# -- 4. Loops (FOR, WHILE, LOOP)
# -- -----------------------------

# -- FOR loop example
# BEGIN
#     FOR i IN 1..5 LOOP
#         DBMS_OUTPUT.PUT_LINE('Iteration: ' || i);
#     END LOOP;
# END;

# -- WHILE loop example
# DECLARE
#     v_counter NUMBER := 1;
# BEGIN
#     WHILE v_counter <= 5 LOOP
#         DBMS_OUTPUT.PUT_LINE('Counter: ' || v_counter);
#         v_counter := v_counter + 1;
#     END LOOP;
# END;

# -- Basic LOOP example with EXIT
# DECLARE
#     v_counter NUMBER := 1;
# BEGIN
#     LOOP
#         DBMS_OUTPUT.PUT_LINE('Counter: ' || v_counter);
#         v_counter := v_counter + 1;
#         EXIT WHEN v_counter > 5;  -- Exit loop after 5 iterations
#     END LOOP;
# END;

# -- -----------------------------
# -- 5. Cursors
# -- -----------------------------

# -- Implicit Cursor Example
# BEGIN
#     FOR employee_record IN (SELECT first_name, salary FROM employees WHERE department_id = 10) LOOP
#         DBMS_OUTPUT.PUT_LINE(employee_record.first_name || ' earns ' || employee_record.salary);
#     END LOOP;
# END;

# -- Explicit Cursor Example
# DECLARE
#     CURSOR emp_cursor IS SELECT first_name, salary FROM employees WHERE department_id = 10;
#     emp_record emp_cursor%ROWTYPE;
# BEGIN
#     OPEN emp_cursor;
#     LOOP
#         FETCH emp_cursor INTO emp_record;
#         EXIT WHEN emp_cursor%NOTFOUND;
#         DBMS_OUTPUT.PUT_LINE(emp_record.first_name || ' earns ' || emp_record.salary);
#     END LOOP;
#     CLOSE emp_cursor;
# END;

# -- -----------------------------
# -- 6. Procedures and Functions
# -- -----------------------------

# -- Stored Procedure Example
# CREATE OR REPLACE PROCEDURE update_salary(p_employee_id IN employees.employee_id%TYPE, p_new_salary IN NUMBER) IS
# BEGIN
#     UPDATE employees SET salary = p_new_salary WHERE employee_id = p_employee_id;
#     COMMIT;
# END update_salary;

# -- Call the procedure
# BEGIN
#     update_salary(101, 6000);  -- Update employee's salary
# END;

# -- Function Example
# CREATE OR REPLACE FUNCTION get_salary(p_employee_id IN employees.employee_id%TYPE) RETURN NUMBER IS
#     v_salary employees.salary%TYPE;
# BEGIN
#     SELECT salary INTO v_salary FROM employees WHERE employee_id = p_employee_id;
#     RETURN v_salary;
# END get_salary;

# -- Call the function
# DECLARE
#     v_employee_salary NUMBER;
# BEGIN
#     v_employee_salary := get_salary(101);
#     DBMS_OUTPUT.PUT_LINE('Salary: ' || v_employee_salary);
# END;

# -- -----------------------------
# -- 7. Exception Handling
# -- -----------------------------

# DECLARE
#     v_salary employees.salary%TYPE;
# BEGIN
#     SELECT salary INTO v_salary FROM employees WHERE employee_id = 999;  -- Non-existent employee
#     DBMS_OUTPUT.PUT_LINE('Salary: ' || v_salary);
# EXCEPTION
#     WHEN NO_DATA_FOUND THEN
#         DBMS_OUTPUT.PUT_LINE('Employee not found.');
#     WHEN OTHERS THEN
#         DBMS_OUTPUT.PUT_LINE('An error occurred: ' || SQLERRM);
# END;

# -- -----------------------------
# -- 8. Triggers
# -- -----------------------------

# -- Creating a trigger that fires before insert
# CREATE OR REPLACE TRIGGER before_insert_employee
# BEFORE INSERT ON employees
# FOR EACH ROW
# BEGIN
#     IF :NEW.salary < 2000 THEN
#         :NEW.salary := 2000;  -- Ensure salary is at least 2000
#     END IF;
# END;

# -- -----------------------------
# -- 9. Packages
# -- -----------------------------

# -- Creating a package specification
# CREATE OR REPLACE PACKAGE employee_pkg AS
#     PROCEDURE update_employee_name(p_employee_id IN employees.employee_id%TYPE, p_new_name IN VARCHAR2);
#     FUNCTION get_employee_salary(p_employee_id IN employees.employee_id%TYPE) RETURN NUMBER;
# END employee_pkg;

# -- Creating a package body
# CREATE OR REPLACE PACKAGE BODY employee_pkg AS
#     PROCEDURE update_employee_name(p_employee_id IN employees.employee_id%TYPE, p_new_name IN VARCHAR2) IS
#     BEGIN
#         UPDATE employees SET first_name = p_new_name WHERE employee_id = p_employee_id;
#         COMMIT;
#     END update_employee_name;

#     FUNCTION get_employee_salary(p_employee_id IN employees.employee_id%TYPE) RETURN NUMBER IS
#         v_salary employees.salary%TYPE;
#     BEGIN
#         SELECT salary INTO v_salary FROM employees WHERE employee_id = p_employee_id;
#         RETURN v_salary;
#     END get_employee_salary;
# END employee_pkg;

# -- Calling package procedures and functions
# BEGIN
#     employee_pkg.update_employee_name(101, 'NewName');
#     DBMS_OUTPUT.PUT_LINE('Salary: ' || employee_pkg.get_employee_salary(101));
# END;

# -- -----------------------------
# -- 10. Transactions
# -- -----------------------------

# BEGIN
#     UPDATE employees SET salary = salary + 500 WHERE employee_id = 101;
#     SAVEPOINT salary_update;
#     UPDATE employees SET salary = salary + 1000 WHERE employee_id = 102;
#     ROLLBACK TO salary_update;  -- Undo second update but keep the first one
#     COMMIT;
# END;

# -- -----------------------------
# -- 11. Dynamic SQL
# -- -----------------------------

# DECLARE
#     v_table_name VARCHAR2(30) := 'employees';
#     v_sql VARCHAR2(1000);
#     v_count NUMBER;
# BEGIN
#     v_sql := 'SELECT COUNT(*) FROM ' || v_table_name;
#     EXECUTE IMMEDIATE v_sql INTO v_count;
#     DBMS_OUTPUT.PUT_LINE('Total employees: ' || v_count);
# END;

# -- -----------------------------
# -- 12. Bulk Collect and FORALL
# -- -----------------------------

# -- Bulk Collect Example
# DECLARE
#     TYPE employee_table IS TABLE OF employees%ROWTYPE;
#     v_employees employee_table;
# BEGIN
#     SELECT * BULK COLLECT INTO v_employees FROM employees WHERE department_id = 10;
#     FOR i IN v_employees.FIRST..v_employees.LAST LOOP
#         DBMS_OUTPUT.PUT_LINE(v_employees(i).first_name || ' earns ' || v_employees(i).salary);
#     END LOOP;
# END;

# -- FORALL Example (for batch updates)
# BEGIN
#     FORALL i IN 1..5
#         UPDATE employees SET salary = salary + 500 WHERE employee_id = i;
# END;
