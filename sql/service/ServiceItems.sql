-- =============================================
-- SERVICE CATALOG (STANDARD SERVICES WITH CODES AND PRICES)
-- =============================================
CREATE TABLE ServiceItems (
    ServiceItemID INT PRIMARY KEY IDENTITY(1,1), -- Unique service item identifier
    OrgID INT NOT NULL, -- Organization that owns the service (FK to Organizations)
    Code NVARCHAR(50), -- Service code (internal or billing)
    Name NVARCHAR(255), -- Service name/description
    Description NVARCHAR(MAX), -- Service details/notes
    Price DECIMAL(10,2), -- Standard price for service in USD
    IsPreventive BIT DEFAULT 0, -- Indicates if service is preventive [0 = No, 1 = Yes]
    CPTCode NVARCHAR(20), -- Optional CPT/ICD/HCPCS code
    AuthorizationRequired BIT DEFAULT 0, -- Indicates if pre-authorization is required [0 = No, 1 = Yes]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CONSTRAINT FK_ServiceItems_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_ServiceItems_OrgID ON ServiceItems(OrgID);