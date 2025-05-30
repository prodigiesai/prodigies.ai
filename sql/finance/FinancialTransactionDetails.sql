-- =============================================
-- TRANSACTION LINE ITEMS (IF APPLICABLE)
-- For expenses, details are stored here as well.
CREATE TABLE FinancialTransactionDetails (
    DetailID INT PRIMARY KEY IDENTITY(1,1), -- Unique transaction detail/line item identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    FinancialTransactionID INT, -- Parent transaction (FK to FinancialTransactions)
    ServiceItemID INT NULL, -- Service item billed (FK to ServiceItems)
    Description NVARCHAR(255), -- Description or memo for the line item
    Quantity INT, -- Quantity of service/item
    UnitPrice DECIMAL(10,2), -- Unit price in USD
    TotalPrice AS (Quantity * UnitPrice) PERSISTED, -- Computed total price in USD
    ProviderID INT NULL, -- Provider associated with line item (FK to Providers)
    AuthorizationStatus NVARCHAR(50), -- Authorization status [Pending, Approved, Denied, Not Required]
    CONSTRAINT FK_FinancialTransactionDetails_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_FinancialTransactionDetails_FinancialTransactionID FOREIGN KEY (FinancialTransactionID) REFERENCES FinancialTransactions(FinancialTransactionID),
    CONSTRAINT FK_FinancialTransactionDetails_ServiceItemID FOREIGN KEY (ServiceItemID) REFERENCES ServiceItems(ServiceItemID),
    CONSTRAINT FK_FinancialTransactionDetails_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID)
);