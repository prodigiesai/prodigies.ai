CREATE TABLE AccessLogs (
    LogID INT PRIMARY KEY IDENTITY(1,1), -- Unique access log identifier
    OrgID INT NOT NULL, -- Organization associated with the log (FK to Organizations)
    UserID NVARCHAR(100), -- User ID (or username) performing the action (FK to Users)
    Action NVARCHAR(255), -- Action performed [Login, ViewRecord, ModifyRecord, DeleteRecord, Export, etc.]
    Entity NVARCHAR(100), -- Entity type affected [Patient, Appointment, LabResult, etc.]
    EntityID INT, -- ID of the affected entity
    Timestamp DATETIME DEFAULT GETDATE(), -- Timestamp of the action (ISO format, shown as MM/DD/YYYY in US UI)
    IPAddress NVARCHAR(50), -- IP address where the action originated
    CONSTRAINT FK_AccessLogs_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_AccessLogs_OrgID ON AccessLogs(OrgID);