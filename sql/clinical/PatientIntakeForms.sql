-- =============================================
-- TABLE: PatientIntakeForms
-- Comprehensive intake data for new patients
-- =============================================
CREATE TABLE PatientIntakeForms (
    IntakeFormID INT PRIMARY KEY IDENTITY(1,1), -- Unique intake form identifier
    PatientID INT NOT NULL, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EmergencyEntityName NVARCHAR(100), -- Name of emergency entity
    EmergencyEntityRelation NVARCHAR(100), -- Relationship to patient
    EmergencyEntityPhone NVARCHAR(50), -- Emergency entity phone number
    Allergies NVARCHAR(MAX), -- List of allergies
    CurrentMedications NVARCHAR(MAX), -- List of current medications
    PastSurgeries NVARCHAR(MAX), -- Past surgeries/procedures
    ChronicConditions NVARCHAR(MAX), -- List of chronic conditions
    FamilyMedicalHistory NVARCHAR(MAX), -- Hereditary diseases
    SubstanceUse NVARCHAR(255), -- Tobacco, alcohol, drugs
    ExerciseFrequency NVARCHAR(100), -- Frequency of exercise
    DietaryRestrictions NVARCHAR(255), -- Food/dietary restrictions
    ImmunizationStatus NVARCHAR(MAX), -- Immunizations received
    -- Demographic information
    Race NVARCHAR(100), -- [White, Black or African American, Asian, etc.]
    Ethnicity NVARCHAR(100), -- [Hispanic or Latino, Not Hispanic or Latino, etc.]
    PreferredLanguage NVARCHAR(100), -- [English, Spanish, Haitian Creole, etc.]
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_PatientIntakeForms_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID), -- Patient reference
    CONSTRAINT FK_PatientIntakeForms_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- Organization reference
);

CREATE INDEX IDX_PatientIntakeForms_OrgID ON PatientIntakeForms(OrgID);
CREATE INDEX IDX_PatientIntakeForms_PatientID ON PatientIntakeForms(PatientID);