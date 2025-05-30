CREATE TABLE EntityRoles (
    EntityID INT NOT NULL, -- FK to Entities.EntityID
    OrgID INT NOT NULL, -- FK to Organizations.OrgID
    Role NVARCHAR(50) NOT NULL, -- Assigned role [Patient, Vendor, Employee, Pharmacy, EmergencyContact, Org, Other]
    IsPrimary BIT DEFAULT 0, -- Optional: indicates if this is the primary role
    CreatedAt DATETIME DEFAULT GETDATE(), -- Timestamp of role assignment
    CreatedBy NVARCHAR(100), -- Assigned by
    ModifiedAt DATETIME NULL, -- Last updated timestamp
    ModifiedBy NVARCHAR(100) NULL, -- Last modified by
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT PK_EntityRoles PRIMARY KEY (EntityID, Role),
    CONSTRAINT FK_EntityRoles_Entities FOREIGN KEY (EntityID) REFERENCES Entities(EntityID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FK_EntityRoles_Organizations FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

-- Recommended indices for query optimization
CREATE INDEX IDX_EntityRoles_OrgID ON EntityRoles(OrgID);
CREATE INDEX IDX_EntityRoles_Role ON EntityRoles(Role);