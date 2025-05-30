-- CRM COMMUNICATIONS LOG (INCLUDES AI BOT, EMAIL, SMS, WHATSAPP, ETC.)
CREATE TABLE Communications (
    CommunicationID INT PRIMARY KEY IDENTITY(1,1), -- Unique communication log identifier
    OrgID INT NOT NULL, -- Organization owning the communication (FK to Organizations)
    RelatedEntityType NVARCHAR(50), -- Entity type referenced [Patient, Insurance, Pharmacy, Lab, Provider, Other]
    RelatedEntityID INT, -- ID of referenced entity (FK to appropriate table depending on RelatedEntityType)
    Channel NVARCHAR(50), -- Communication channel [Phone, Email, SMS, WhatsApp, Portal, Fax, Chat, In-Person, E-Prescribe, Other]
    Direction NVARCHAR(20), -- Direction of communication [Inbound, Outbound]
    Subject NVARCHAR(255), -- Subject or topic of communication
    MessageBody NVARCHAR(MAX), -- Full conversation text or summary
    Status NVARCHAR(50), -- Communication status [Pending, Completed, Failed, Archived, Referred]
    Timestamp DATETIME DEFAULT GETDATE(), -- Communication timestamp (ISO format, shown as MM/DD/YYYY in US UI)
    HandledBy NVARCHAR(100), -- User ID or AI Bot identifier who handled the communication
    ReferredTo NVARCHAR(100), -- Optional: role or person to whom the case was referred
    FollowUpRequired BIT DEFAULT 0, -- Indicates if follow-up is required [0 = No, 1 = Yes]
    FollowUpDate DATETIME NULL, -- Scheduled follow-up date (ISO format)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Communications_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

-- Indices for Communications
CREATE INDEX IDX_Communications_OrgID ON Communications(OrgID);
CREATE INDEX IDX_Communications_RelatedEntity ON Communications(RelatedEntityType, RelatedEntityID);
CREATE INDEX IDX_Communications_Channel ON Communications(Channel);
CREATE INDEX IDX_Communications_Timestamp ON Communications(Timestamp);