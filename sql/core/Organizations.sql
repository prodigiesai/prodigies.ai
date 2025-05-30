-- =============================================
-- TABLE: Organizations
-- Stores tenant/organization master records
-- =============================================
CREATE TABLE Organizations (
    OrgID INT PRIMARY KEY IDENTITY(1,1), -- Unique organization identifier (tenant)
    OrgName NVARCHAR(255) NOT NULL, -- Name of the organization or clinic
    Address NVARCHAR(500), -- Mailing address of the organization
    Phone NVARCHAR(50), -- Main entity phone number
    Email NVARCHAR(255), -- Organization's email address
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0 -- Logical deletion flag
);
-- Indices for Organizations
-- (No common query fields specified for Organizations)