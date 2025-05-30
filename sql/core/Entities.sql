-- =============================================
-- TABLE: Entities
-- Centralized entity details for all entities (patients, providers, vendors, etc.)
-- Contact information (address, phone, fax, email, cell) is now stored in EntityContacts table.
-- =============================================
CREATE TABLE Entities (
    EntityID INT PRIMARY KEY IDENTITY(1,1), -- Unique entity identifier
    EntityType NVARCHAR(50), -- [Patient, Vendor, Employee, Organization, Pharmacy, EmergencyContact, Other]
    OrgID INT NOT NULL, -- Organization the entity belongs to (FK to Organizations)
    Prefix NVARCHAR(20), -- Prefix [Mr., Mrs., Ms., Miss, Dr., Prof., Rev., Hon., Sir, Lady, Fr., Rabbi, Imam, Capt., Col., Gen., Lt., Maj., Sgt., Judge, Officer, Dean]
    FirstName NVARCHAR(100), -- First name (if individual)
    MiddleName NVARCHAR(100), -- Optional middle name
    LastName NVARCHAR(100), -- Last name (if individual)
    Suffix NVARCHAR(20), -- Suffix [Jr., Sr., II, III, IV, V, MD, DO, DDS, DMD, PhD, JD, Esq., MBA, RN, PA-C, NP, DNP, LPN, RPh, PsyD, EdD, DVM]
    OrganizationName NVARCHAR(255), -- Name of company/business (optional)
    -- Address, Phone, Fax, Email fields removed; see EntityContacts for contact info
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_Entities_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- References owning organization
);

-- Indices for Entities
CREATE INDEX IDX_Entities_OrgID ON Entities(OrgID);
CREATE INDEX IDX_Entities_EntityType ON Entities(EntityType);
