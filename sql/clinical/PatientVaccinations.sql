
-- =============================================
-- PATIENT VACCINATIONS (IMMUNIZATION RECORDS)
-- =============================================

CREATE TABLE PatientVaccinations (
    VaccinationID INT PRIMARY KEY IDENTITY(1,1), -- Unique vaccination record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    VaccineName NVARCHAR(255), -- Name of vaccine administered
    Manufacturer NVARCHAR(255), -- Vaccine manufacturer
    DoseNumber INT, -- Dose number in series (e.g., 1, 2, 3)
    TotalDoses INT, -- Total number of doses required for full series
    AdministrationDate DATE, -- Date vaccine was administered (ISO format, shown as MM/DD/YYYY in US UI)
    LotNumber NVARCHAR(100), -- Vaccine lot/batch number
    AdministeredBy NVARCHAR(100), -- Name or ID of provider who administered
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_PatientVaccinations_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientVaccinations_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_PatientVaccinations_PatientID ON PatientVaccinations(PatientID);
CREATE INDEX IDX_PatientVaccinations_OrgID ON PatientVaccinations(OrgID);