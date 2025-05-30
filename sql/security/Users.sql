-- USERS
-- =============================================
-- TABLE: Users
-- Application user accounts (login, role, org membership)
-- =============================================
CREATE TABLE Users (
    UserID INT PRIMARY KEY IDENTITY(1,1), -- Unique user identifier
    OrgID INT NOT NULL, -- Organization the user is assigned to (FK to Organizations)
    Username NVARCHAR(100) NOT NULL UNIQUE, -- Login username (unique)
    PasswordHash NVARCHAR(255), -- Hashed password
    Role NVARCHAR(50), -- User role [Admin, Staff, Provider, Patient]
    Type NVARCHAR(50), -- User type [Internal, External]
    Status NVARCHAR(50), -- User status [Active, Inactive, Locked]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Account creation timestamp
    CONSTRAINT FK_Users_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- Organization reference
);
CREATE INDEX IDX_Users_OrgID ON Users(OrgID);