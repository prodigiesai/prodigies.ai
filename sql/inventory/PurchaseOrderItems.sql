CREATE TABLE PurchaseOrderItems (
    ItemID INT PRIMARY KEY IDENTITY(1,1), -- Unique purchase order line item identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    PurchaseOrderID INT, -- Purchase order (FK to PurchaseOrders)
    SupplyID INT, -- Supply item ordered (FK to Supplies)
    Quantity INT, -- Quantity ordered
    UnitCost DECIMAL(10,2), -- Unit cost in USD
    CONSTRAINT FK_PurchaseOrderItems_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PurchaseOrderItems_PurchaseOrderID FOREIGN KEY (PurchaseOrderID) REFERENCES PurchaseOrders(PurchaseOrderID),
    CONSTRAINT FK_PurchaseOrderItems_SupplyID FOREIGN KEY (SupplyID) REFERENCES Supplies(SupplyID)
);

CREATE INDEX IDX_PurchaseOrderItems_PurchaseOrderID ON PurchaseOrderItems(PurchaseOrderID);
CREATE INDEX IDX_PurchaseOrderItems_OrgID ON PurchaseOrderItems(OrgID);
CREATE INDEX IDX_PurchaseOrderItems_SupplyID ON PurchaseOrderItems(SupplyID);