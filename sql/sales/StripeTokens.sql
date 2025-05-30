-- =============================================
-- TABLE: StripeTokens
-- Stores tokenized payment methods for Stripe
-- =============================================
CREATE TABLE StripeTokens (
    TokenID INT PRIMARY KEY IDENTITY(1,1), -- Unique token identifier
    OrgID INT NOT NULL, -- Organization (tenant)
    PatientID INT, -- Associated patient
    StripeCustomerID NVARCHAR(255), -- Stripe customer reference
    StripePaymentMethodID NVARCHAR(255), -- Stripe payment method reference
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- Created by user (audit)
    ModifiedAt DATETIME NULL, -- Last update timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Modified by user (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_StripeTokens_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_StripeTokens_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID)
);