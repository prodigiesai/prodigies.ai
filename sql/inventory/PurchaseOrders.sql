-- =============================================
-- PURCHASE ORDERS
-- =============================================
CREATE TABLE PurchaseOrders (
    PurchaseOrderID INT PRIMARY KEY IDENTITY(1,1), -- Unique purchase order identifier
    OrgID INT NOT NULL, -- Organization placing the order (FK to Organizations)
    VendorEntityID INT, -- Vendor entity (FK to Entities)
    OrderDate DATETIME DEFAULT GETDATE(), -- Date order was placed (ISO format, shown as MM/DD/YYYY in US UI)
    Status NVARCHAR(50), -- Purchase order status [Requested, Approved, Received, Canceled]
    Notes NVARCHAR(MAX), -- Additional notes/details
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_PurchaseOrders_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PurchaseOrders_VendorEntityID FOREIGN KEY (VendorEntityID) REFERENCES Entities(EntityID)
);

CREATE INDEX IDX_ProcedurePerformed_PatientID ON ProcedurePerformed(PatientID);
CREATE INDEX IDX_ProcedurePerformed_OrgID ON ProcedurePerformed(OrderID);