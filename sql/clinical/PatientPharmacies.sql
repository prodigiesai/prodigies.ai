CREATE TABLE PatientPharmacies (
    PatientID INT, -- Patient (FK to Patients)
    PharmacyID INT, -- Pharmacy (FK to Pharmacies)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    IsPrimary BIT DEFAULT 0, -- Indicates if this is the patient's primary pharmacy [0 = No, 1 = Yes]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)    
    CONSTRAINT FK_PatientPharmacies_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PatientPharmacies_PatientID_2 FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientPharmacies_PharmacyID_2 FOREIGN KEY (PharmacyID) REFERENCES Pharmacies(PharmacyID),
    PRIMARY KEY (PatientID, PharmacyID)
);
CREATE INDEX IDX_PatientPharmacies_OrgID ON PatientPharmacies(OrgID);
CREATE INDEX IDX_PatientPharmacies_PatientID ON PatientPharmacies(PatientID);
CREATE INDEX IDX_PatientPharmacies_PharmacyID ON PharmacyID(OrgID);