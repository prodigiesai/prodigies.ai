-- RETENTION POLICIES CONFIGURATION
CREATE TABLE RetentionPolicies (
    RetentionPolicyID INT PRIMARY KEY IDENTITY(1,1), -- Unique retention policy identifier
    OrgID INT NOT NULL, -- Organization the policy applies to (FK to Organizations)
    RecordType NVARCHAR(100), -- Record type [MedicalRecord, Prescription, LabResult, Communication, Appointment, etc.]
    RetainForYears INT DEFAULT 7, -- Number of years to retain records
    Notes NVARCHAR(255), -- Additional notes or explanation
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_RetentionPolicies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

-- Index to facilitate retention policy lookups
CREATE INDEX IDX_RetentionPolicies_OrgID ON RetentionPolicies(OrgID);
CREATE INDEX IDX_RetentionPolicies_RecordType ON RetentionPolicies(RecordType);