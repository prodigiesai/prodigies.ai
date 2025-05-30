-- =============================================
-- TABLE: InvoiceMeta
-- Stores invoice-specific metadata, referencing FinancialTransactions
-- =============================================
CREATE TABLE InvoiceMeta (
    FinancialTransactionID INT PRIMARY KEY, -- FK to FinancialTransactions(FinancialTransactionID); must have TransactionType = 'PatientInvoice'
    OrgID INT NOT NULL, -- Organization that owns the invoice (tenant). FK to Organizations(OrgID)
    DiscountAmount DECIMAL(10,2), -- Amount discounted from subtotal before tax. Supports coupons, promotions, etc.
    TaxAmount DECIMAL(10,2), -- Tax amount applied to the subtotal after discount
    DueDate DATETIME, -- Final date by which invoice should be paid. Used in AR aging and dunning reports
    FinalAmount AS ( -- Computed amount due after applying discount and tax
        (SELECT Amount FROM FinancialTransactions WHERE FinancialTransactionID = InvoiceMeta.FinancialTransactionID)
        - DiscountAmount + TaxAmount
    ) PERSISTED,
    Status NVARCHAR(50), -- Invoice status: [Pending = unpaid, Paid = fully paid, Partially Paid = partially covered, Canceled = voided]
    Notes NVARCHAR(MAX), -- Free-form field for extra context, such as billing comments, approvals, notes from provider
    CreatedAt DATETIME DEFAULT GETDATE(), -- Timestamp when the invoice record was created (audit)
    CreatedBy NVARCHAR(100), -- Username or system that created the record (audit)
    ModifiedAt DATETIME NULL, -- Timestamp when invoice was last modified (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Username or system that last modified the record (audit)
    CONSTRAINT FK_InvoiceMeta_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_InvoiceMeta_FinancialTransactionID FOREIGN KEY (FinancialTransactionID) REFERENCES FinancialTransactions(FinancialTransactionID)
);