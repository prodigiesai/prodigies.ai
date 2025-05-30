-- TABLE: EmployeeReferences
-- References for employee background checks (uses centralized entities)
-- =============================================
CREATE TABLE EmployeeReferences (
    ReferenceID INT PRIMARY KEY IDENTITY(1,1), -- Unique employee reference identifier
    EmployeeID INT, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    Name NVARCHAR(255), -- Reference's name
    Entity NVARCHAR(255), -- Reference's entity details (phone/email)
    Relationship NVARCHAR(100), -- Relationship to employee (e.g., Supervisor, Colleague)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeReferences_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeReferences_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeReferences_OrgID ON EmployeeReferences(OrgID);
CREATE INDEX IDX_EmployeeReferences_EmployeeID ON EmployeeReferences(EmployeeID);