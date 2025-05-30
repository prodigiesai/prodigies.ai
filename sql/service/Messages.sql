
CREATE TABLE Messages (
    MessageID INT PRIMARY KEY IDENTITY(1,1), -- Unique message identifier
    OrgID INT NOT NULL, -- Organization owning the message (FK to Organizations)
    SenderID INT, -- User ID of message sender (FK to Users)
    ReceiverID INT, -- User ID of message recipient (FK to Users)
    SentAt DATETIME DEFAULT GETDATE(), -- Message sent timestamp (ISO format, shown as MM/DD/YYYY in US UI)
    Subject NVARCHAR(255), -- Message subject/title
    Body NVARCHAR(MAX), -- Message body/content
    Status NVARCHAR(50), -- Message status [Unread, Read, Archived]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Messages_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_Messages_OrgID ON Messages(OrgID);
CREATE INDEX IDX_Messages_ReceiverID ON Messages(ReceiverID);
