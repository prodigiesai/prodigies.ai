-- =============================================
-- DISCHARGE INFORMATION
-- =============================================
CREATE TABLE PatientDischarges (
    DischargeID INT PRIMARY KEY IDENTITY(1,1), -- Unique discharge record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    AppointmentID INT NULL, -- Associated appointment (FK to Appointments)
    DischargeDate DATETIME DEFAULT GETDATE(), -- Date/time of discharge (ISO format, shown as MM/DD/YYYY in US UI)
    Summary NVARCHAR(MAX), -- Discharge summary
    Instructions NVARCHAR(MAX), -- Discharge instructions
    ProviderID INT, -- Provider responsible for discharge (FK to Providers)
    CONSTRAINT FK_PatientDischarges_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientDischarges_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PatientDischarges_AppointmentID FOREIGN KEY (AppointmentID) REFERENCES Appointments(AppointmentID),
    CONSTRAINT FK_PatientDischarges_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID)
);