-- TABLE: PatientMedicalRecords
-- Patient visit medical records, diagnoses, and treatment
-- =============================================
CREATE TABLE PatientMedicalRecords (
    RecordID INT PRIMARY KEY IDENTITY(1,1), -- Unique medical record identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    PatientID INT, -- Patient (FK to Patients)
    VisitDate DATETIME, -- Date/time of medical visit
    Summary NVARCHAR(MAX), -- Visit summary
    Diagnosis NVARCHAR(255), -- Diagnosis code/description [ICD-10]
    TreatmentPlan NVARCHAR(MAX), -- Plan for treatment (medications, procedures, referrals)
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_PatientMedicalRecords_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_PatientMedicalRecords_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID) -- Patient reference
);

-- Indices for PatientMedicalRecords
CREATE INDEX IDX_PatientMedicalRecords_OrgID ON PatientMedicalRecords(OrgID);
CREATE INDEX IDX_PatientMedicalRecords_PatientID ON PatientMedicalRecords(PatientID);
CREATE INDEX IDX_PatientMedicalRecords_VisitDate ON PatientMedicalRecords(VisitDate);