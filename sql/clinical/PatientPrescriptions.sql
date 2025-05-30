-- TABLE: PatientPrescriptions
-- Medication PatientPrescriptions for patients
-- =============================================
CREATE TABLE PatientPrescriptions (
    PrescriptionID INT PRIMARY KEY IDENTITY(1,1), -- Unique prescription identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    PatientID INT, -- Patient (FK to Patients)
    ProviderID INT, -- Provider (FK to Providers)
    Medication NVARCHAR(255), -- Medication name
    Dosage NVARCHAR(100), -- Dosage amount (e.g., 10mg)
    Frequency NVARCHAR(100), -- Frequency (e.g., Twice daily)
    StartDate DATE, -- Start date of prescription
    EndDate DATE, -- End date or expiration
    Notes NVARCHAR(1000), -- Additional instructions
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_PatientPrescriptions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_PatientPrescriptions_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID), -- Patient reference
    CONSTRAINT FK_PatientPrescriptions_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID) -- Provider reference
);

-- Indices for PatientPrescriptions
CREATE INDEX IDX_PatientPrescriptions_OrgID ON PatientPrescriptions(OrgID);
CREATE INDEX IDX_PatientPrescriptions_PatientID ON PatientPrescriptions(PatientID);
CREATE INDEX IDX_PatientPrescriptions_ProviderID ON PatientPrescriptions(ProviderID);