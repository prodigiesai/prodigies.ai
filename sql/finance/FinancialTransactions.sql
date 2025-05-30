
-- =============================================
-- TABLE: FinancialTransactions
-- Stores all unified financial transactions (invoices, payments, payroll, expenses, etc.)
-- TransactionType may be:
--   [PatientInvoice, PatientPayment, VendorPayment, PurchasePayment, Refund, CreditNote, DebitNote, Payroll, TaxPayment, Transfer, Expense]
-- For expenses:
--   - TransactionType = 'Expense'
--   - EntityType = 'Vendor', EntityID = Entities.EntityID of vendor
--   - Category = Expense category [Office Supplies, Utilities, Maintenance, Marketing, Salaries, Insurance, Travel, Software, Rent, Other]
--   - Notes = Expense memo/description
--   - Amount = Total expense amount
--   - TransactionDate = Expense date
--   - Status = [Pending, Completed, Failed, Applied]
CREATE TABLE FinancialTransactions (
    FinancialTransactionID INT PRIMARY KEY IDENTITY(1,1), -- Unique financial transaction identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    TransactionType NVARCHAR(50) NOT NULL, -- Transaction type [PatientInvoice, PatientPayment, VendorPayment, PurchasePayment, Refund, CreditNote, DebitNote, Payroll, TaxPayment, Transfer, Expense]
    EntityType NVARCHAR(50) NOT NULL, -- Entity type [Patient, Vendor, Employee, Bank, Insurance]
    EntityID INT NOT NULL, -- ID of the referenced entity (FK to Patients, Vendors, Employees, etc.)
    RelatedModule NVARCHAR(50), -- Related module name [Appointment, PurchaseOrder, PayrollRun, Claim, Asset]
    ReferenceID INT, -- Reference to related module record (e.g., AppointmentID, PurchaseOrderID)
    Amount DECIMAL(10,2) NOT NULL, -- Monetary amount in USD
    Method NVARCHAR(50), -- Payment method [Cash, Zelle, ACH, Stripe, PayPal, Check, Card]
    Status NVARCHAR(50) NOT NULL, -- Transaction status [Pending, Completed, Failed, Applied]
    TransactionDate DATETIME DEFAULT GETDATE() NOT NULL, -- Date/time of transaction (ISO format, shown as MM/DD/YYYY in US UI)
    Category NVARCHAR(100), -- Expense category [Office Supplies, Utilities, Maintenance, Marketing, Salaries, Insurance, Travel, Software, Rent, Other] (used only for TransactionType = 'Expense')
    Notes NVARCHAR(MAX), -- Additional details or comments (memo, expense description, etc.)
    CreatedAt DATETIME DEFAULT GETDATE() NOT NULL, -- Record creation timestamp (audit, ISO format)
    CreatedBy NVARCHAR(100) NOT NULL, -- User or system who created the transaction (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Last modifying user (audit)
    IsDeleted BIT DEFAULT 0 NOT NULL, -- Soft delete flag (audit)
    CONSTRAINT FK_FinancialTransactions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

-- Indices for FinancialTransactions
CREATE INDEX IDX_FinancialTransactions_OrgID ON FinancialTransactions(OrgID);
CREATE INDEX IDX_FinancialTransactions_TransactionType ON FinancialTransactions(TransactionType);
CREATE INDEX IDX_FinancialTransactions_Entity ON FinancialTransactions(EntityType, EntityID);
CREATE INDEX IDX_FinancialTransactions_Module ON FinancialTransactions(RelatedModule, ReferenceID);
CREATE INDEX IDX_FinancialTransactions_TransactionDate ON FinancialTransactions(TransactionDate);