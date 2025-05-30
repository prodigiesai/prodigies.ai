-- =============================================
-- TABLE: Pharmacies
-- Pharmacy master data (uses centralized entities)
-- =============================================
CREATE TABLE Pharmacies (
    PharmacyID INT PRIMARY KEY IDENTITY(1,1), -- Unique pharmacy identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EntityID INT NOT NULL, -- Reference to Entities(EntityID) for pharmacy entity info
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Pharmacies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_Pharmacies_EntityID FOREIGN KEY (EntityID) REFERENCES Entities(EntityID) -- Entity reference
);

CREATE INDEX IDX_Pharmacies_OrgID ON Pharmacies(OrgID);
CREATE INDEX IDX_Pharmacies_PatientID ON Pharmacies(PatientID);