-- =============================================
-- TABLE: Appointments
-- Patient appointment scheduling and tracking
-- =============================================
CREATE TABLE Appointments (
    AppointmentID INT PRIMARY KEY IDENTITY(1,1), -- Unique appointment identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    PatientID INT, -- Patient (FK to Patients)
    ProviderID INT, -- Provider (FK to Providers)
    ScheduledAt DATETIME, -- Date and time of appointment
    Reason NVARCHAR(500), -- Reason for visit/appointment
    Status NVARCHAR(50), -- Appointment status [Scheduled, Completed, Canceled, No-Show]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_Appointments_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_Appointments_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID), -- Patient reference
    CONSTRAINT FK_Appointments_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID) -- Provider reference
);

-- Indices for Appointments
CREATE INDEX IDX_Appointments_OrgID ON Appointments(OrgID);
CREATE INDEX IDX_Appointments_PatientID ON Appointments(PatientID);
CREATE INDEX IDX_Appointments_ProviderID ON Appointments(ProviderID);
CREATE INDEX IDX_Appointments_ScheduledAt ON Appointments(ScheduledAt);