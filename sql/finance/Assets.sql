-- =============================================
-- FIXED ASSETS TABLE
-- =============================================
CREATE TABLE Assets (
    AssetID INT PRIMARY KEY IDENTITY(1,1), -- Unique asset identifier
    OrgID INT NOT NULL, -- Organization that owns the asset (FK to Organizations)
    AssetName NVARCHAR(255), -- Asset name/description
    AssetType NVARCHAR(100), -- Asset type [Computer, Printer, Vehicle, Medical Equipment, Other]
    SerialNumber NVARCHAR(100), -- Manufacturer serial number
    Vendor NVARCHAR(255), -- Vendor or supplier name
    PurchaseDate DATE, -- Date asset was purchased (ISO format, shown as MM/DD/YYYY in US UI)
    WarrantyExpiry DATE, -- Warranty expiration date (ISO format, shown as MM/DD/YYYY in US UI)
    Location NVARCHAR(255), -- Physical location of asset
    EntityPerson NVARCHAR(100), -- Person responsible for asset
    Status NVARCHAR(50), -- Asset status [Active, In Repair, Retired]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Assets_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_Assets_OrgID ON Assets(OrgID);