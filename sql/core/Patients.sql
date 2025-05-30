-- TABLE: Patients
-- Stores patient demographic and registration data
-- =============================================
CREATE TABLE Patients (
    PatientID INT PRIMARY KEY IDENTITY(1,1), -- Unique identifier for the patient
    OrgID INT NOT NULL, -- Organization the patient belongs to (FK to Organizations)
    EntityID INT NOT NULL, -- Reference to Entities(EntityID) for patient entity info
    DOB DATE, -- Date of birth
    Gender NVARCHAR(10), -- Gender of the patient [Male, Female] (per US HHS 2025 policy)
    SSNEncrypted VARBINARY(MAX), -- Encrypted Social Security Number (HIPAA)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_Patients_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Org reference
    CONSTRAINT FK_Patients_EntityID FOREIGN KEY (EntityID) REFERENCES Entities(EntityID) -- Patient entity info
);

-- Indices for Patients
CREATE INDEX IDX_Patients_OrgID ON Patients(OrgID);
CREATE INDEX IDX_Patients_EntityID ON Patients(EntityID);