CREATE TABLE PatientVitals (
    VitalsID INT PRIMARY KEY IDENTITY(1,1), -- Unique vitals record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    VisitDate DATETIME DEFAULT GETDATE(), -- Date/time of vitals measurement (ISO format, shown as MM/DD/YYYY in US UI)
    Height DECIMAL(5,2), -- Height
    Weight DECIMAL(5,2), -- Weight
    TemperatureC DECIMAL(4,2), -- Body temperature in Celsius (°C)
    HeartRateBPM INT, -- Heart rate in beats per minute (BPM)
    BloodPressure NVARCHAR(20), -- Blood pressure (e.g., '120/80')
    RespiratoryRate INT, -- Respiratory rate (breaths per minute)
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_PatientVitals_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientVitals_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_PatientVitals_PatientID ON PatientVitals(PatientID);
CREATE INDEX IDX_PatientVitals_OrgID ON PatientVitals(OrgID);