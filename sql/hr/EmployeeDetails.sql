-- TABLE: EmployeeDetails
-- Detailed employment, payroll, and HR data per employee
-- =============================================
CREATE TABLE EmployeeDetails (
    EmployeeID INT PRIMARY KEY, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    HireDate DATE, -- Hire date (ISO format, shown as MM/DD/YYYY in US UI)
    TerminationDate DATE, -- Termination date (ISO format, shown as MM/DD/YYYY in US UI)
    JobTitle NVARCHAR(100), -- Employee's official job title
    Department NVARCHAR(100), -- Department name
    ManagerID INT, -- Manager (FK to Employees)
    Salary DECIMAL(12,2), -- Annual salary in USD
    PayType NVARCHAR(50), -- Pay type [Hourly, Salary]
    Benefits NVARCHAR(MAX), -- Benefits description/details
    InsuranceProvider NVARCHAR(255), -- Health insurance provider name
    InsurancePlan NVARCHAR(100), -- Insurance plan name
    PayrollDeductions NVARCHAR(MAX), -- Payroll deductions/withholdings
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeDetails_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeDetails_ManagerID_2 FOREIGN KEY (ManagerID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeDetails_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeDetails_OrgID ON EmployeeDetails(OrgID);
CREATE INDEX IDX_EmployeeDetails_EmployeeID ON EmployeeDetails(EmployeeID);
CREATE INDEX IDX_EmployeeDetails_ManagerID ON EmployeeDetails(ManagerID);