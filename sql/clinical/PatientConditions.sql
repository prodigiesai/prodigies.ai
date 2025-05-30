
CREATE TABLE PatientConditions (
    ConditionID INT PRIMARY KEY IDENTITY(1,1), -- Unique patient condition identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    ConditionName NVARCHAR(255), -- Condition name [Diabetes, Hypertension, Asthma, COPD, Depression, Anxiety, Cancer, Obesity, Arthritis, Heart Disease, Stroke, Alzheimer's, Chronic Kidney Disease, HIV/AIDS, Epilepsy, Osteoporosis, GERD, Multiple Sclerosis, Lupus, Other]
    Type NVARCHAR(50), -- Condition type [Chronic, Allergy, Genetic, Infectious, Autoimmune, Neurological, Mental Health, Developmental, Other]
    Status NVARCHAR(50), -- Condition status [Active, Resolved]
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_PatientConditions_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientConditions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_PatientConditions_PatientID ON PatientConditions(PatientID);
CREATE INDEX IDX_PatientConditions_OrgID ON PatientConditions(OrgID);