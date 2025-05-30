-- =============================================
-- PATIENT PHYSICAL EXAMS (SCHOOL FORMS, MEDICAL CHECKUPS)
-- =============================================

CREATE TABLE PatientPhysicalExams (
    ExamID INT PRIMARY KEY IDENTITY(1,1), -- Unique physical exam record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    ExamDate DATE, -- Date of physical exam (ISO format, shown as MM/DD/YYYY in US UI)
    FormType NVARCHAR(100), -- Exam form type [School Entry, Sports Clearance, Annual Physical, Pre-employment, DOT Physical, Well-Child Visit, Pre-operative Clearance, Camp Entry, College Entry, Military Enlistment, Other]
    Height DECIMAL(5,2), -- Patient height in inches (US Imperial)
    Weight DECIMAL(5,2), -- Patient weight in pounds (US Imperial)
    Vision NVARCHAR(50), -- Vision assessment/result
    Hearing NVARCHAR(50), -- Hearing assessment/result
    BloodPressure NVARCHAR(20), -- Blood pressure (e.g., '120/80')
    HeartRateBPM INT, -- Heart rate in beats per minute (BPM)
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_PatientPhysicalExams_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientPhysicalExams_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_PatientPhysicalExams_PatientID ON Supplies(PatientID);
CREATE INDEX IDX_PatientPhysicalExams_OrgID ON Supplies(OrgID);