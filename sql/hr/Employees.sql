-- TABLE: Employees
-- Employee master records
-- =============================================
CREATE TABLE Employees (
    EmployeeID INT PRIMARY KEY IDENTITY(1,1), -- Unique employee identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    FirstName NVARCHAR(100), -- Employee first name
    LastName NVARCHAR(100), -- Employee last name
    Role NVARCHAR(100), -- Role in the organization [Admin, Nurse, Physician, etc.]
    Department NVARCHAR(100), -- Department [HR, Finance, Clinical, etc.]
    EntityID INT, -- FK to Entities table
    HireDate DATE, -- Date of hire
    Status NVARCHAR(50), -- [Active, On Leave, Terminated]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_Employees_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- Organization reference
);
