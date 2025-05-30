-- =============================================
-- PROCEDURES PERFORMED (ACTUAL EXECUTION OF SERVICE)
-- =============================================
CREATE TABLE PatientProcedurePerformed (
    PerformedID INT PRIMARY KEY IDENTITY(1,1), -- Unique procedure performed record identifier
    OrgID INT NOT NULL, -- Organization placing the order (FK to Organizations)
    OrderID INT, -- Procedure order (FK to ProcedureOrders)
    PatientID INT, -- Patient (FK to Patients)
    ProviderID INT, -- Provider who performed (FK to Providers)
    PerformedDate DATE DEFAULT GETDATE(), -- Date procedure was performed (ISO format, shown as MM/DD/YYYY in US UI)
    Outcome NVARCHAR(MAX), -- Outcome/results of procedure
    CONSTRAINT FK_PatientProcedurePerformed_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),    
    CONSTRAINT FK_PatientProcedurePerformed_OrderID FOREIGN KEY (OrderID) REFERENCES ProcedureOrders(OrderID),
    CONSTRAINT FK_PatientProcedurePerformed_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientProcedurePerformed_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID)
);

CREATE INDEX IDX_PatientProcedurePerformed_PatientID ON PatientProcedurePerformed(PatientID);
CREATE INDEX IDX_PatientProcedurePerformed_OrgID ON PatientProcedurePerformed(OrderID);
CREATE INDEX IDX_PatientProcedurePerformed_AppointmentID ON PatientProcedurePerformed(AppointmentID);
CREATE INDEX IDX_PatientProcedurePerformed_ProviderID ON PatientProcedurePerformed(ProviderID);