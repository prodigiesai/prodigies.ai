CREATE TABLE InventoryItems (
    ItemID INT PRIMARY KEY IDENTITY(1,1), -- Unique inventory item identifier
    OrgID INT NOT NULL, -- Organization that owns the item (FK to Organizations)
    ItemName NVARCHAR(255), -- Name/description of inventory item
    Category NVARCHAR(100), -- Inventory category [Medical, Office, Cleaning, PPE, Furniture, Other]
    Quantity INT, -- Quantity on hand (unitless, see Unit)
    Unit NVARCHAR(50), -- Unit of measure [Box, Unit, Piece, Pack, Liter, Other]
    ReorderLevel INT, -- Quantity threshold to trigger reorder
    LastUpdated DATETIME DEFAULT GETDATE(), -- Last inventory update timestamp (ISO format)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit, ISO format)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_InventoryItems_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_InventoryItems_OrgID ON InventoryItems(OrgID);
CREATE INDEX IDX_InventoryItems_ItemName ON InventoryItems(ItemName);