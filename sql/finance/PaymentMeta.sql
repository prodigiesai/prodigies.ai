-- =============================================
-- TABLE: PaymentMeta
-- Stores payment-specific metadata, referencing FinancialTransactions
-- =============================================
CREATE TABLE PaymentMeta (
    FinancialTransactionID INT PRIMARY KEY, -- FK to FinancialTransactions(FinancialTransactionID); must have TransactionType = 'PatientPayment'
    OrgID INT NOT NULL, -- Organization that received the payment (tenant). FK to Organizations(OrgID)
    PaymentDate DATETIME, -- Date/time when payment was received. Useful for cash flow tracking and reconciliation
    Method NVARCHAR(50), -- Payment method used: [Cash, Credit Card, Stripe, ACH, Check, Zelle, Other]
    Reference NVARCHAR(255), -- External transaction ID (e.g., Stripe paymentIntent, Zelle ref number). Used for reconciliation
    CreatedAt DATETIME DEFAULT GETDATE(), -- Timestamp when the payment record was created (audit)
    CreatedBy NVARCHAR(100), -- Username or system that created the record (audit)
    ModifiedAt DATETIME NULL, -- Timestamp when payment was last modified (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Username or system that last modified the record (audit)
    CONSTRAINT FK_PaymentMeta_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PaymentMeta_FinancialTransactionID FOREIGN KEY (FinancialTransactionID) REFERENCES FinancialTransactions(FinancialTransactionID)
);