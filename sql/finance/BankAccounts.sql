-- =============================================
-- BANK ACCOUNTS (FOR TREASURY AND PAYMENT ROUTING)
-- =============================================
CREATE TABLE BankAccounts (
    BankAccountID INT PRIMARY KEY IDENTITY(1,1), -- Unique bank account identifier
    OrgID INT NOT NULL, -- Organization owning the bank account (FK to Organizations)
    EntityID INT NOT NULL, -- Linked to Vendor, Employee, or Org
    EntityType NVARCHAR(50) NOT NULL, -- [Vendor, Employee, Organization]    
    BankName NVARCHAR(255), -- Name of the bank
    RoutingNumber NVARCHAR(50), -- Bank routing number (ABA)
    AccountNumberMasked NVARCHAR(50), -- Masked bank account number (last 4 digits, etc.)
    AccountType NVARCHAR(50), -- Account type [Checking, Savings, Corporate Card, Zelle, Stripe]
    Currency NVARCHAR(10) DEFAULT 'USD', -- Account currency (default USD)
    IsPrimary BIT DEFAULT 0,  -- Indicates if this is the primary bank account for the entity (1 = Yes, 0 = No)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_BankAccounts_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_BankAccounts_EntityID ON BankAccounts(EntityID);