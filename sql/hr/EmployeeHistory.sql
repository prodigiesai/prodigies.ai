-- =============================================
-- TABLE: EmployeeHistory
-- Tracks HR history/events for employees
-- =============================================
CREATE TABLE EmployeeHistory (
    HistoryID INT PRIMARY KEY IDENTITY(1,1), -- Unique employee history/event identifier
    EmployeeID INT, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EventType NVARCHAR(50), -- Event type [Promotion, Raise, Warning, Absence, Vacation, Sick Leave]
    Description NVARCHAR(MAX), -- Event description/details
    EffectiveDate DATE, -- Date event became effective (ISO format, shown as MM/DD/YYYY in US UI)
    RecordedBy NVARCHAR(100), -- User who recorded the event
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeHistory_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeHistory_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeHistory_OrgID ON EmployeeHistory(OrgID);
CREATE INDEX IDX_EmployeeHistory_EmployeeID ON EmployeeHistory(EmployeeID);