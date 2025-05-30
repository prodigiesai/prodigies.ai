-- =============================================
-- INSURANCE CLAIMS (STATUS AND AMOUNTS PER INVOICE)
-- =============================================
CREATE TABLE InsuranceClaims (
    ClaimID INT PRIMARY KEY IDENTITY(1,1), -- Unique insurance claim identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    InvoiceID INT, -- Invoice referenced by the claim (FK to Invoices)
    InsuranceProvider NVARCHAR(255), -- Name of insurance provider
    ClaimAmount DECIMAL(10,2), -- Total claim amount in USD
    CoveredAmount DECIMAL(10,2), -- Amount covered by insurance in USD
    PatientResponsibility DECIMAL(10,2), -- Amount owed by patient in USD
    Status NVARCHAR(50), -- Claim status [Submitted, Approved, Denied, Partially Approved]
    SubmittedDate DATETIME, -- Date claim was submitted (ISO format, shown as MM/DD/YYYY in US UI)
    ResolvedDate DATETIME NULL, -- Date claim was resolved (ISO format, shown as MM/DD/YYYY in US UI)
    CONSTRAINT FK_InsuranceClaims_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_InsuranceClaims_InvoiceID FOREIGN KEY (InvoiceID) REFERENCES Invoices(InvoiceID)
);

CREATE INDEX IDX_InsuranceClaims_OrgID ON InsuranceClaims(OrgID);
CREATE INDEX IDX_InsuranceClaims_InvoiceID ON InsuranceClaims(InvoiceID);