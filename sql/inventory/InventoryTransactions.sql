-- =============================================
-- INVENTORY TRANSACTIONS (PURCHASE, ADJUSTMENT, RETURN, CONSUMPTION)
-- =============================================
CREATE TABLE InventoryTransactions (
    InventoryTransactionID INT PRIMARY KEY IDENTITY(1,1), -- Unique inventory transaction identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    SupplyID INT, -- Supply item (FK to Supplies)
    TransactionType NVARCHAR(50), -- Transaction type [Purchase, Adjustment, Return, Consumption]
    Unit NVARCHAR(50), -- Unit of measure [Box, Pack, Bottle, Gallon, Ounce, Pound, Piece] (US Imperial Units)
    Quantity INT, -- Quantity involved in transaction
    UnitCost DECIMAL(10,2), -- Unit cost in USD
    TotalCost AS (Quantity * UnitCost) PERSISTED, -- Computed total cost in USD
    SourceReference NVARCHAR(100), -- Reference to source document or entity (e.g., PO number)
    TransactionDate DATETIME DEFAULT GETDATE(), -- Date/time of transaction (ISO format, shown as MM/DD/YYYY in US UI)
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_InventoryTransactions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_InventoryTransactions_SupplyID FOREIGN KEY (SupplyID) REFERENCES Supplies(SupplyID)
);

CREATE INDEX IDX_InventoryTransactions_OrgID ON InventoryTransactions(OrgID);
CREATE INDEX IDX_InventoryTransactions_SupplyID ON InventoryTransactions(SupplyID);