-- =============================================
-- TABLE: Permissions
-- List of system permissions for role-based access control
-- =============================================
CREATE TABLE Permissions (
    PermissionID INT PRIMARY KEY IDENTITY(1,1), -- Unique permission identifier
    OrgID INT NOT NULL, -- Organization the user is assigned to (FK to Organizations)
    Name NVARCHAR(100), -- Permission code (e.g., ViewPatients, EditPatients, etc.)
    Description NVARCHAR(255) -- Description of what the permission allows
    CONSTRAINT FK_Permissions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Org reference
);

CREATE INDEX IDX_Permissions_OrgID ON Permissions(OrgID);