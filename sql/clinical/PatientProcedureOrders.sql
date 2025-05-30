-- =============================================
-- PROCEDURE ORDERS (ORDERED PROCEDURES PER PATIENT)
-- =============================================
CREATE TABLE PatientProcedureOrders (
    OrderID INT PRIMARY KEY IDENTITY(1,1), -- Unique procedure order identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    ProviderID INT, -- Ordering provider (FK to Providers)
    ServiceItemID INT, -- Service/procedure ordered (FK to ServiceItems)
    OrderDate DATE DEFAULT GETDATE(), -- Date order was placed (ISO format, shown as MM/DD/YYYY in US UI)
    Notes NVARCHAR(MAX), -- Additional notes or instructions
    CONSTRAINT FK_PatientProcedureOrders_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientProcedureOrders_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PatientProcedureOrders_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID),
    CONSTRAINT FK_PatientProcedureOrders_ServiceItemID FOREIGN KEY (ServiceItemID) REFERENCES ServiceItems(ServiceItemID)
);