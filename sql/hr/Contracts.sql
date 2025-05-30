CREATE TABLE Contracts (
    ContractID INT PRIMARY KEY IDENTITY(1,1), -- Unique contract identifier
    OrgID INT NOT NULL, -- Organization associated with the contract (FK to Organizations)
    ContractName NVARCHAR(255), -- Name/title of the contract
    Vendor NVARCHAR(255), -- Vendor or counterparty name
    StartDate DATE, -- Contract start date (ISO format, shown as MM/DD/YYYY in US UI)
    EndDate DATE, -- Contract end/expiration date (ISO format, shown as MM/DD/YYYY in US UI)
    Status NVARCHAR(50), -- Contract status [Active, Pending, Terminated]
    Details NVARCHAR(MAX), -- Contract details/terms
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit, ISO format)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Contracts_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_Contracts_OrgID ON Contracts(OrgID);
CREATE INDEX IDX_Contracts_Status ON Contracts(Status);