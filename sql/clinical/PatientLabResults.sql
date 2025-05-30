-- TABLE: PatientLabResults
-- Stores lab/imaging results for patients
-- =============================================
CREATE TABLE PatientLabResults (
    LabResultID INT PRIMARY KEY IDENTITY(1,1), -- Unique lab result identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    PatientID INT, -- Patient (FK to Patients)
    LabType NVARCHAR(255), -- Type of lab test [Blood Test, X-Ray, etc.]
    Result NVARCHAR(MAX), -- Test result value(s)
    ResultDate DATE, -- Date test result was obtained
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_PatientLabResults_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_PatientLabResults_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID) -- Patient reference
);

-- Indices for PatientLabResults
CREATE INDEX IDX_PatientLabResults_OrgID ON PatientLabResults(OrgID);
CREATE INDEX IDX_PatientLabResults_PatientID ON PatientLabResults(PatientID);
CREATE INDEX IDX_PatientLabResults_ResultDate ON PatientLabResults(ResultDate);