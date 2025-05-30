-- =============================================
-- SUPPLIES TABLE (CONSUMABLES)
-- =============================================
CREATE TABLE Supplies (
    SupplyID INT PRIMARY KEY IDENTITY(1,1), -- Unique supply identifier
    OrgID INT NOT NULL, -- Organization owning the supply (FK to Organizations)
    SupplyName NVARCHAR(255), -- Name/description of supply item
    Category NVARCHAR(100), -- Supply category [Anesthesia, PPE, Cleaning, Sanitary, Other]
    Unit NVARCHAR(50), -- Unit of measure [Box, Pack, Bottle, Gallon, Ounce, Pound, Piece] (US Imperial Units)
    Quantity INT, -- Quantity on hand (unitless, see Unit)
    Vendor NVARCHAR(255), -- Vendor or supplier name
    LastRestocked DATE, -- Date last restocked (ISO format, shown as MM/DD/YYYY in US UI)
    ReorderLevel INT, -- Minimum quantity before reorder is triggered
    StorageLocation NVARCHAR(255), -- Physical storage location
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Supplies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_Supplies_OrgID ON Supplies(OrgID);