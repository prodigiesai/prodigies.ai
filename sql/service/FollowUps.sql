-- =============================================
-- FOLLOW-UP SCHEDULE
-- =============================================
CREATE TABLE FollowUps (
    FollowUpID INT PRIMARY KEY IDENTITY(1,1), -- Unique follow-up record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    RelatedVisitID INT NULL, -- Related visit/appointment (FK to Appointments)
    FollowUpDate DATETIME, -- Scheduled follow-up date (ISO format, shown as MM/DD/YYYY in US UI)
    Purpose NVARCHAR(255), -- Purpose of follow-up
    Status NVARCHAR(50), -- Follow-up status [Pending, Completed, Canceled]
    Notes NVARCHAR(MAX), -- Additional notes
    CONSTRAINT FK_FollowUps_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_FollowUps_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_FollowUps_RelatedVisitID FOREIGN KEY (RelatedVisitID) REFERENCES Appointments(AppointmentID)
);