-- =============================================
-- TABLE: Documents
-- Stores uploaded files like consent forms, school letters, referrals, etc.
-- =============================================
CREATE TABLE Documents (
    DocumentID INT PRIMARY KEY IDENTITY(1,1), -- Unique document identifier
    OrgID INT NOT NULL, -- Organization that owns the document (FK)
    EntityType NVARCHAR(50), -- [Patient, Employee, Vendor, Pharmacy, Other]
    EntityID INT, -- Referenced entity
    FileName NVARCHAR(255), -- Original file name
    FileType NVARCHAR(50), -- [Consent, Referral, SchoolLetter, InsuranceCard, ID, Other]
    FileUrl NVARCHAR(MAX), -- Path or Blob Storage URL
    UploadedAt DATETIME DEFAULT GETDATE(), -- Upload timestamp (audit)
    UploadedBy NVARCHAR(100), -- User who uploaded the document (audit)
    Notes NVARCHAR(MAX), -- Additional context or notes
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last update timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Last modifying user (audit)
    CONSTRAINT FK_Documents_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);