-- =============================================
-- PATIENT FINANCIAL SUMMARY (DEDUCTIBLES & LIMITS)
-- =============================================
CREATE TABLE PatientFinancials (
    PatientID INT PRIMARY KEY, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    InsuranceProvider NVARCHAR(255), -- Insurance company name [Aetna, Blue Cross Blue Shield, etc.]
    InsurancePhone NVARCHAR(50), -- Insurance company entity phone
    InsuranceFax NVARCHAR(50), -- Insurance company fax number
    InsuranceNumber NVARCHAR(100), -- Member/policy number    
    Year INT, -- Calendar year for financial summary (YYYY)
    AnnualDeductible DECIMAL(10,2), -- Annual deductible in USD
    DeductibleUsed DECIMAL(10,2), -- Deductible used in USD
    MaxOutOfPocket DECIMAL(10,2), -- Maximum out-of-pocket in USD
    OutOfPocketUsed DECIMAL(10,2), -- Out-of-pocket used in USD
    CONSTRAINT FK_PatientFinancials_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientFinancials_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);