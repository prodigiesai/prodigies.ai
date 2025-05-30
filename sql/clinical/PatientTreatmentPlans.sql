-- =============================================
-- TREATMENT PLANS (AGREED PLAN PER APPOINTMENT OR PATIENT REQUEST)
-- =============================================
CREATE TABLE PatientTreatmentPlans (
    PlanID INT PRIMARY KEY IDENTITY(1,1), -- Unique treatment plan identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    AppointmentID INT NULL, -- Associated appointment (FK to Appointments)
    Description NVARCHAR(MAX), -- Description of the treatment plan
    EstimatedCost DECIMAL(10,2), -- Estimated cost in USD
    Status NVARCHAR(50), -- Plan status [Planned, Approved, In Progress, Completed, Canceled]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_PatientTreatmentPlans_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientTreatmentPlans_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PatientTreatmentPlans_AppointmentID FOREIGN KEY (AppointmentID) REFERENCES Appointments(AppointmentID)
);
CREATE INDEX IDX_PatientTreatmentPlans_PatientID ON PatientTreatmentPlans(PatientID);
CREATE INDEX IDX_PatientTreatmentPlans_OrgID ON PatientTreatmentPlans(OrgID);
CREATE INDEX IDX_PatientTreatmentPlans_AppointmentID ON PatientTreatmentPlans(AppointmentID);