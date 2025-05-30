-- TABLE: Providers
-- Healthcare provider master data (uses centralized entities)
-- =============================================
CREATE TABLE Providers (
    ProviderID INT PRIMARY KEY IDENTITY(1,1), -- Unique provider identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EntityID INT NOT NULL, -- Reference to Entities(EntityID) for provider entity info
    Specialty NVARCHAR(100), -- Medical specialty [General Practice, Pediatrics, etc.]
    NPI NVARCHAR(50), -- National Provider Identifier
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_Providers_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_Providers_EntityID FOREIGN KEY (EntityID) REFERENCES Entities(EntityID) -- Provider entity info
);

-- Indices for Providers
CREATE INDEX IDX_Providers_OrgID ON Providers(OrgID);
CREATE INDEX IDX_Providers_Specialty ON Providers(Specialty);
CREATE INDEX IDX_Providers_EntityID ON Providers(EntityID);