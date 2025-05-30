-- TABLE: RolePermissions
-- Maps system roles to permissions (many-to-many)
-- =============================================
CREATE TABLE RolePermissions (
    Role NVARCHAR(50), -- Role name (must match Users.Role)
    OrgID INT NOT NULL, -- Organization the user is assigned to (FK to Organizations)
    PermissionID INT, -- Permission assigned to the role (FK to Permissions)
    PRIMARY KEY (Role, PermissionID),
    CONSTRAINT FK_RolePermissions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Org reference
    CONSTRAINT FK_RolePermissions_PermissionID FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID)
);

-- Indexes for permission checks
CREATE INDEX IDX_RolePermissions_Role ON RolePermissions(Role);
CREATE INDEX IDX_RolePermissions_PermissionID ON RolePermissions(PermissionID);