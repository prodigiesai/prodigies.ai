-- TABLE: EmployeeEducation
-- Employee education and credentials
-- =============================================
CREATE TABLE EmployeeEducation (
    EducationID INT PRIMARY KEY IDENTITY(1,1), -- Unique employee education record identifier
    EmployeeID INT, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    Institution NVARCHAR(255), -- Name of educational institution
    Degree NVARCHAR(255), -- Degree or certification earned (e.g., MD, BSN)
    Field NVARCHAR(255), -- Field of study/major
    StartYear INT, -- Year education started (YYYY)
    EndYear INT, -- Year education ended (YYYY)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeEducation_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeEducation_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeEducation_OrgID ON EmployeeEducation(OrgID);
CREATE INDEX IDX_EmployeeEducation_EmployeeID ON EmployeeEducation(EmployeeID);