-- =============================================
-- TABLE: EntityContacts
-- Stores addresses, phone numbers, emails, faxes, and cell numbers for entities in a unified structure
-- =============================================
CREATE TABLE EntityContacts (
    ContactID INT PRIMARY KEY IDENTITY(1,1), -- Unique contact record
    EntityID INT NOT NULL, -- FK to Entities
    OrgID INT NOT NULL, -- Organization for scoping
    Type NVARCHAR(50), -- [Phone, Address, Email, Fax, Cell]
    StreetAddress NVARCHAR(255), -- Street number and name (if address)
    AptSuite NVARCHAR(100), -- Apartment or suite (if address)
    City NVARCHAR(100), -- City (if address)
    State NVARCHAR(100), -- State or province (if address)
    Zip NVARCHAR(20), -- Postal/ZIP code (if address)
    County NVARCHAR(100), -- County (if address)
    Country NVARCHAR(100), -- Country (if address)
    Value NVARCHAR(255), -- Contact value (phone number, email address, etc.)
    IsPrimary BIT DEFAULT 0, -- Indicates primary contact
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_EntityContacts_EntityID FOREIGN KEY (EntityID) REFERENCES Entities(EntityID),
    CONSTRAINT FK_EntityContacts_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_EntityContacts_EntityID ON EntityContacts(EntityID);
CREATE INDEX IDX_EntityContacts_OrgID ON EntityContacts(OrgID);