-- =============================================
-- DATABASE SCHEMA FOR MULTI-TENANT EHR SYSTEM
-- WITH FULL ADMINISTRATIVE, CLINICAL, AND BILLING SUPPORT
-- =============================================

-- CREATE DATABASE STATEMENT
CREATE DATABASE EHR_MultiTenant;
GO

USE EHR_MultiTenant;
GO

-- =============================================
-- TABLE: Organizations
-- Stores tenant/organization master records
-- =============================================
CREATE TABLE Organizations (
    OrgID INT PRIMARY KEY IDENTITY(1,1), -- Unique organization identifier (tenant)
    OrgName NVARCHAR(255) NOT NULL, -- Name of the organization or clinic
    Address NVARCHAR(500), -- Mailing address of the organization
    Phone NVARCHAR(50), -- Main entity phone number
    Email NVARCHAR(255), -- Organization's email address
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0 -- Logical deletion flag
);
-- Indices for Organizations
-- (No common query fields specified for Organizations)

-- =============================================
-- TABLE: Entities
-- Centralized entity details for all entities (patients, providers, vendors, etc.)
-- Contact information (address, phone, fax, email, cell) is now stored in EntityContacts table.
-- =============================================
CREATE TABLE Entities (
    EntityID INT PRIMARY KEY IDENTITY(1,1), -- Unique entity identifier
    EntityType NVARCHAR(50), -- [Patient, Vendor, Employee, Organization, Pharmacy, EmergencyContact, Other]
    OrgID INT NOT NULL, -- Organization the entity belongs to (FK to Organizations)
    Prefix NVARCHAR(20), -- Prefix [Mr., Mrs., Ms., Miss, Dr., Prof., Rev., Hon., Sir, Lady, Fr., Rabbi, Imam, Capt., Col., Gen., Lt., Maj., Sgt., Judge, Officer, Dean]
    FirstName NVARCHAR(100), -- First name (if individual)
    MiddleName NVARCHAR(100), -- Optional middle name
    LastName NVARCHAR(100), -- Last name (if individual)
    Suffix NVARCHAR(20), -- Suffix [Jr., Sr., II, III, IV, V, MD, DO, DDS, DMD, PhD, JD, Esq., MBA, RN, PA-C, NP, DNP, LPN, RPh, PsyD, EdD, DVM]
    OrganizationName NVARCHAR(255), -- Name of company/business (optional)
    -- Address, Phone, Fax, Email fields removed; see EntityContacts for contact info
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_Entities_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- References owning organization
);

-- Indices for Entities
CREATE INDEX IDX_Entities_OrgID ON Entities(OrgID);
CREATE INDEX IDX_Entities_EntityType ON Entities(EntityType);


-- =============================================
-- TABLE: EntityContacts
-- Stores addresses, phone numbers, emails, faxes, and cell numbers for entities in a unified structure
-- =============================================
CREATE TABLE EntityContacts (
    ContactID INT PRIMARY KEY IDENTITY(1,1), -- Unique contact record
    EntityID INT NOT NULL, -- FK to Entities
    OrgID INT NOT NULL, -- Organization for scoping
    Type NVARCHAR(50), -- [Phone, Address, Email, Fax, Cell]
    StreetAddress NVARCHAR(255), -- Street number and name (if address)
    AptSuite NVARCHAR(100), -- Apartment or suite (if address)
    City NVARCHAR(100), -- City (if address)
    State NVARCHAR(100), -- State or province (if address)
    Zip NVARCHAR(20), -- Postal/ZIP code (if address)
    County NVARCHAR(100), -- County (if address)
    Country NVARCHAR(100), -- Country (if address)
    Value NVARCHAR(255), -- Contact value (phone number, email address, etc.)
    IsPrimary BIT DEFAULT 0, -- Indicates primary contact
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_EntityContacts_EntityID FOREIGN KEY (EntityID) REFERENCES Entities(EntityID),
    CONSTRAINT FK_EntityContacts_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_EntityContacts_EntityID ON EntityContacts(EntityID);
CREATE INDEX IDX_EntityContacts_OrgID ON EntityContacts(OrgID);

CREATE TABLE EntityRoles (
    EntityID INT NOT NULL, -- FK to Entities.EntityID
    OrgID INT NOT NULL, -- FK to Organizations.OrgID
    Role NVARCHAR(50) NOT NULL, -- Assigned role [Patient, Vendor, Employee, Pharmacy, EmergencyContact, Org, Other]
    IsPrimary BIT DEFAULT 0, -- Optional: indicates if this is the primary role
    CreatedAt DATETIME DEFAULT GETDATE(), -- Timestamp of role assignment
    CreatedBy NVARCHAR(100), -- Assigned by
    ModifiedAt DATETIME NULL, -- Last updated timestamp
    ModifiedBy NVARCHAR(100) NULL, -- Last modified by
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT PK_EntityRoles PRIMARY KEY (EntityID, Role),
    CONSTRAINT FK_EntityRoles_Entities FOREIGN KEY (EntityID) REFERENCES Entities(EntityID)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT FK_EntityRoles_Organizations FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

-- Recommended indices for query optimization
CREATE INDEX IDX_EntityRoles_OrgID ON EntityRoles(OrgID);
CREATE INDEX IDX_EntityRoles_Role ON EntityRoles(Role);

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

-- =============================================
-- ROLE-BASED PERMISSION MANAGEMENT FOR HIPAA'S MINIMUM NECESSARY RULE
-- =============================================

-- PERMISSIONS
-- =============================================
-- TABLE: Permissions
-- List of system permissions for role-based access control
-- =============================================
CREATE TABLE Permissions (
    PermissionID INT PRIMARY KEY IDENTITY(1,1), -- Unique permission identifier
    OrgID INT NOT NULL, -- Organization the user is assigned to (FK to Organizations)
    Name NVARCHAR(100), -- Permission code (e.g., ViewPatients, EditPatients, etc.)
    Description NVARCHAR(255) -- Description of what the permission allows
    CONSTRAINT FK_Permissions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Org reference
);

CREATE INDEX IDX_Permissions_OrgID ON Permissions(OrgID);

-- ROLE TO PERMISSION MAPPING
-- =============================================
-- TABLE: RolePermissions
-- Maps system roles to permissions (many-to-many)
-- =============================================
CREATE TABLE RolePermissions (
    Role NVARCHAR(50), -- Role name (must match Users.Role)
    OrgID INT NOT NULL, -- Organization the user is assigned to (FK to Organizations)
    PermissionID INT, -- Permission assigned to the role (FK to Permissions)
    PRIMARY KEY (Role, PermissionID),
    CONSTRAINT FK_RolePermissions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Org reference
    CONSTRAINT FK_RolePermissions_PermissionID FOREIGN KEY (PermissionID) REFERENCES Permissions(PermissionID)
);

-- Indexes for permission checks
CREATE INDEX IDX_RolePermissions_Role ON RolePermissions(Role);
CREATE INDEX IDX_RolePermissions_PermissionID ON RolePermissions(PermissionID);

-- =============================================
-- PATIENTS TABLE (USES CENTRALIZED ENTITIES)
-- =============================================
-- =============================================
-- TABLE: Patients
-- Stores patient demographic and registration data
-- =============================================
CREATE TABLE Patients (
    PatientID INT PRIMARY KEY IDENTITY(1,1), -- Unique identifier for the patient
    OrgID INT NOT NULL, -- Organization the patient belongs to (FK to Organizations)
    EntityID INT NOT NULL, -- Reference to Entities(EntityID) for patient entity info
    DOB DATE, -- Date of birth
    Gender NVARCHAR(10), -- Gender of the patient [Male, Female] (per US HHS 2025 policy)
    SSNEncrypted VARBINARY(MAX), -- Encrypted Social Security Number (HIPAA)
    InsuranceProvider NVARCHAR(255), -- Insurance company name [Aetna, Blue Cross Blue Shield, etc.]
    InsurancePhone NVARCHAR(50), -- Insurance company entity phone
    InsuranceFax NVARCHAR(50), -- Insurance company fax number
    InsuranceNumber NVARCHAR(100), -- Member/policy number
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_Patients_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Org reference
    CONSTRAINT FK_Patients_EntityID FOREIGN KEY (EntityID) REFERENCES Entities(EntityID) -- Patient entity info
);

-- Indices for Patients
CREATE INDEX IDX_Patients_OrgID ON Patients(OrgID);
CREATE INDEX IDX_Patients_EntityID ON Patients(EntityID);

-- =============================================
-- PATIENT INTAKE FORMS (COMPREHENSIVE INTAKE DATA)
-- =============================================
-- =============================================
-- TABLE: PatientIntakeForms
-- Comprehensive intake data for new patients
-- =============================================
CREATE TABLE PatientIntakeForms (
    IntakeFormID INT PRIMARY KEY IDENTITY(1,1), -- Unique intake form identifier
    PatientID INT NOT NULL, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EmergencyEntityName NVARCHAR(100), -- Name of emergency entity
    EmergencyEntityRelation NVARCHAR(100), -- Relationship to patient
    EmergencyEntityPhone NVARCHAR(50), -- Emergency entity phone number
    Allergies NVARCHAR(MAX), -- List of allergies
    CurrentMedications NVARCHAR(MAX), -- List of current medications
    PastSurgeries NVARCHAR(MAX), -- Past surgeries/procedures
    ChronicConditions NVARCHAR(MAX), -- List of chronic conditions
    FamilyMedicalHistory NVARCHAR(MAX), -- Hereditary diseases
    SubstanceUse NVARCHAR(255), -- Tobacco, alcohol, drugs
    ExerciseFrequency NVARCHAR(100), -- Frequency of exercise
    DietaryRestrictions NVARCHAR(255), -- Food/dietary restrictions
    ImmunizationStatus NVARCHAR(MAX), -- Immunizations received
    -- Demographic information
    Race NVARCHAR(100), -- [White, Black or African American, Asian, etc.]
    Ethnicity NVARCHAR(100), -- [Hispanic or Latino, Not Hispanic or Latino, etc.]
    PreferredLanguage NVARCHAR(100), -- [English, Spanish, Haitian Creole, etc.]
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_PatientIntakeForms_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID), -- Patient reference
    CONSTRAINT FK_PatientIntakeForms_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- Organization reference
);

CREATE INDEX IDX_PatientIntakeForms_OrgID ON PatientIntakeForms(OrgID);
CREATE INDEX IDX_PatientIntakeForms_PatientID ON PatientIntakeForms(PatientID);

-- PHARMACIES AND PATIENT PHARMACIES
-- =============================================
-- PHARMACIES TABLE (USES CENTRALIZED ENTITIES)
-- =============================================
-- =============================================
-- TABLE: Pharmacies
-- Pharmacy master data (uses centralized entities)
-- =============================================
CREATE TABLE Pharmacies (
    PharmacyID INT PRIMARY KEY IDENTITY(1,1), -- Unique pharmacy identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EntityID INT NOT NULL, -- Reference to Entities(EntityID) for pharmacy entity info
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CONSTRAINT FK_Pharmacies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_Pharmacies_EntityID FOREIGN KEY (EntityID) REFERENCES Entities(EntityID) -- Entity reference
);

CREATE INDEX IDX_Pharmacies_OrgID ON Pharmacies(OrgID);
CREATE INDEX IDX_Pharmacies_PatientID ON Pharmacies(PatientID);

-- =============================================
-- TABLE: PatientPharmacies
-- Maps patients to their pharmacies (many-to-many)
-- =============================================
CREATE TABLE PatientPharmacies (
    PatientID INT, -- Patient (FK to Patients)
    PharmacyID INT, -- Pharmacy (FK to Pharmacies)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    IsPrimary BIT DEFAULT 0, -- Indicates if this is the primary pharmacy for the patient
    CONSTRAINT FK_PatientPharmacies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID), -- Organization reference
    CONSTRAINT FK_PatientPharmacies_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientPharmacies_PharmacyID FOREIGN KEY (PharmacyID) REFERENCES Pharmacies(PharmacyID),
    PRIMARY KEY (PatientID, PharmacyID)
);

CREATE INDEX IDX_PatientPharmacies_OrgID ON PatientPharmacies(OrgID);
CREATE INDEX IDX_PatientPharmacies_PatientID ON PatientPharmacies(PatientID);
CREATE INDEX IDX_PatientPharmacies_PharmacyID ON PatientPharmacies(PharmacyID);

-- =============================================
-- PROVIDERS TABLE (USES CENTRALIZED ENTITIES)
-- =============================================
-- =============================================




-- =============================================


-- =============================================


-- =============================================




-- =============================================


-- =============================================


-- =============================================


-- =============================================
-- EMPLOYEE REFERENCES TABLE (USES CENTRALIZED ENTITIES)
-- =============================================
-- =============================================


-- =============================================
-- TABLE: EmployeeHistory
-- Tracks HR history/events for employees
-- =============================================
CREATE TABLE EmployeeHistory (
    HistoryID INT PRIMARY KEY IDENTITY(1,1), -- Unique history record
    EmployeeID INT, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EventType NVARCHAR(50), -- [Promotion, Raise, Warning, Absence, Vacation, Sick Leave]
    Description NVARCHAR(MAX), -- Event description/details
    EffectiveDate DATE, -- Date event became effective
    RecordedBy NVARCHAR(100), -- User who recorded the event
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp
    CreatedBy NVARCHAR(100), -- User who created the record
    ModifiedAt DATETIME NULL, -- Last modification timestamp
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag
    CONSTRAINT FK_EmployeeHistory_EmployeeID FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID), -- Employee reference
    CONSTRAINT FK_EmployeeHistory_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- Organization reference
);
CREATE INDEX IDX_EmployeeHistory_EmployeeID ON EmployeeHistory(EmployeeID);
CREATE INDEX IDX_EmployeeHistory_OrgID ON EmployeeHistory(OrgID);


CREATE TABLE Contracts (
    ContractID INT PRIMARY KEY IDENTITY(1,1), -- Unique contract identifier
    OrgID INT NOT NULL, -- Organization associated with the contract (FK to Organizations)
    ContractName NVARCHAR(255), -- Name/title of the contract
    Vendor NVARCHAR(255), -- Vendor or counterparty name
    StartDate DATE, -- Contract start date (ISO format, shown as MM/DD/YYYY in US UI)
    EndDate DATE, -- Contract end/expiration date (ISO format, shown as MM/DD/YYYY in US UI)
    Status NVARCHAR(50), -- Contract status [Active, Pending, Terminated]
    Details NVARCHAR(MAX), -- Contract details/terms
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit, ISO format)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Contracts_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_Contracts_OrgID ON Contracts(OrgID);
CREATE INDEX IDX_Contracts_Status ON Contracts(Status);

CREATE TABLE InventoryItems (
    ItemID INT PRIMARY KEY IDENTITY(1,1), -- Unique inventory item identifier
    OrgID INT NOT NULL, -- Organization that owns the item (FK to Organizations)
    ItemName NVARCHAR(255), -- Name/description of inventory item
    Category NVARCHAR(100), -- Inventory category [Medical, Office, Cleaning, PPE, Furniture, Other]
    Quantity INT, -- Quantity on hand (unitless, see Unit)
    Unit NVARCHAR(50), -- Unit of measure [Box, Unit, Piece, Pack, Liter, Other]
    ReorderLevel INT, -- Quantity threshold to trigger reorder
    LastUpdated DATETIME DEFAULT GETDATE(), -- Last inventory update timestamp (ISO format)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit, ISO format)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_InventoryItems_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_InventoryItems_OrgID ON InventoryItems(OrgID);
CREATE INDEX IDX_InventoryItems_ItemName ON InventoryItems(ItemName);





-- USERS
CREATE TABLE Users (
    UserID INT PRIMARY KEY IDENTITY(1,1), -- Unique user account identifier
    OrgID INT NOT NULL, -- Organization the user belongs to (FK to Organizations)
    Username NVARCHAR(100) NOT NULL UNIQUE, -- Login username (must be unique)
    PasswordHash NVARCHAR(255), -- Hashed password
    Role NVARCHAR(100), -- User role [Admin, Staff, Provider, Receptionist, Nurse, Technician, Specialist, Case Manager, IT Support, Biller, HR]
    Type NVARCHAR(50), -- User type [Internal, External]
    Status NVARCHAR(50), -- User account status [Active, Inactive, Locked]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Account creation timestamp (audit, ISO format)
    CONSTRAINT FK_Users_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_Users_OrgID ON Users(OrgID);


CREATE TABLE AccessLogs (
    LogID INT PRIMARY KEY IDENTITY(1,1), -- Unique access log identifier
    OrgID INT NOT NULL, -- Organization associated with the log (FK to Organizations)
    UserID NVARCHAR(100), -- User ID (or username) performing the action (FK to Users)
    Action NVARCHAR(255), -- Action performed [Login, ViewRecord, ModifyRecord, DeleteRecord, Export, etc.]
    Entity NVARCHAR(100), -- Entity type affected [Patient, Appointment, LabResult, etc.]
    EntityID INT, -- ID of the affected entity
    Timestamp DATETIME DEFAULT GETDATE(), -- Timestamp of the action (ISO format, shown as MM/DD/YYYY in US UI)
    IPAddress NVARCHAR(50), -- IP address where the action originated
    CONSTRAINT FK_AccessLogs_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

CREATE INDEX IDX_AccessLogs_OrgID ON AccessLogs(OrgID);

-- RETENTION POLICIES CONFIGURATION
CREATE TABLE RetentionPolicies (
    RetentionPolicyID INT PRIMARY KEY IDENTITY(1,1), -- Unique retention policy identifier
    OrgID INT NOT NULL, -- Organization the policy applies to (FK to Organizations)
    RecordType NVARCHAR(100), -- Record type [MedicalRecord, Prescription, LabResult, Communication, Appointment, etc.]
    RetainForYears INT DEFAULT 7, -- Number of years to retain records
    Notes NVARCHAR(255), -- Additional notes or explanation
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_RetentionPolicies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID) -- FK to Organizations
);

-- Index to facilitate retention policy lookups
CREATE INDEX IDX_RetentionPolicies_OrgID ON RetentionPolicies(OrgID);
CREATE INDEX IDX_RetentionPolicies_RecordType ON RetentionPolicies(RecordType);

-- =============================================
-- PATIENT MEDICAL VITALS & DEMOGRAPHICS
-- =============================================

CREATE TABLE PatientVitals (
    VitalsID INT PRIMARY KEY IDENTITY(1,1), -- Unique vitals record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    VisitDate DATETIME DEFAULT GETDATE(), -- Date/time of vitals measurement (ISO format, shown as MM/DD/YYYY in US UI)
    Height DECIMAL(5,2), -- Height
    Weight DECIMAL(5,2), -- Weight
    TemperatureC DECIMAL(4,2), -- Body temperature in Celsius (°C)
    HeartRateBPM INT, -- Heart rate in beats per minute (BPM)
    BloodPressure NVARCHAR(20), -- Blood pressure (e.g., '120/80')
    RespiratoryRate INT, -- Respiratory rate (breaths per minute)
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_PatientVitals_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientVitals_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_PatientVitals_PatientID ON PatientVitals(PatientID);
CREATE INDEX IDX_PatientVitals_OrgID ON PatientVitals(OrgID);

CREATE TABLE PatientConditions (
    ConditionID INT PRIMARY KEY IDENTITY(1,1), -- Unique patient condition identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    ConditionName NVARCHAR(255), -- Condition name [Diabetes, Hypertension, Asthma, COPD, Depression, Anxiety, Cancer, Obesity, Arthritis, Heart Disease, Stroke, Alzheimer's, Chronic Kidney Disease, HIV/AIDS, Epilepsy, Osteoporosis, GERD, Multiple Sclerosis, Lupus, Other]
    Type NVARCHAR(50), -- Condition type [Chronic, Allergy, Genetic, Infectious, Autoimmune, Neurological, Mental Health, Developmental, Other]
    Status NVARCHAR(50), -- Condition status [Active, Resolved]
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_PatientConditions_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientConditions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_PatientConditions_PatientID ON PatientConditions(PatientID);
CREATE INDEX IDX_PatientConditions_OrgID ON PatientConditions(OrgID);

-- =============================================
-- PHARMACIES AND PATIENT PHARMACIES
-- =============================================

CREATE TABLE Pharmacies (
    PharmacyID INT PRIMARY KEY IDENTITY(1,1), -- Unique pharmacy identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    Name NVARCHAR(255), -- Pharmacy name
    Address NVARCHAR(500), -- Pharmacy address
    Phone NVARCHAR(50), -- Pharmacy phone number
    Fax NVARCHAR(50), -- Pharmacy fax number
    Email NVARCHAR(255), -- Pharmacy email address
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Pharmacies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_Pharmacies_OrgID ON Pharmacies(OrgID);

CREATE TABLE PatientPharmacies (
    PatientID INT, -- Patient (FK to Patients)
    PharmacyID INT, -- Pharmacy (FK to Pharmacies)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    IsPrimary BIT DEFAULT 0, -- Indicates if this is the patient's primary pharmacy [0 = No, 1 = Yes]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)    
    CONSTRAINT FK_PatientPharmacies_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PatientPharmacies_PatientID_2 FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientPharmacies_PharmacyID_2 FOREIGN KEY (PharmacyID) REFERENCES Pharmacies(PharmacyID),
    PRIMARY KEY (PatientID, PharmacyID)
);
CREATE INDEX IDX_PatientPharmacies_OrgID ON PatientPharmacies(OrgID);
CREATE INDEX IDX_PatientPharmacies_PatientID ON PatientPharmacies(PatientID);
CREATE INDEX IDX_PatientPharmacies_PharmacyID ON PharmacyID(OrgID);

-- =============================================
-- EMPLOYEE DETAILS, PAYROLL, EDUCATION, REFERENCES, HR HISTORY
-- =============================================

CREATE TABLE EmployeeDetails (
    EmployeeID INT PRIMARY KEY, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    HireDate DATE, -- Hire date (ISO format, shown as MM/DD/YYYY in US UI)
    TerminationDate DATE, -- Termination date (ISO format, shown as MM/DD/YYYY in US UI)
    JobTitle NVARCHAR(100), -- Employee's official job title
    Department NVARCHAR(100), -- Department name
    ManagerID INT, -- Manager (FK to Employees)
    Salary DECIMAL(12,2), -- Annual salary in USD
    PayType NVARCHAR(50), -- Pay type [Hourly, Salary]
    Benefits NVARCHAR(MAX), -- Benefits description/details
    InsuranceProvider NVARCHAR(255), -- Health insurance provider name
    InsurancePlan NVARCHAR(100), -- Insurance plan name
    PayrollDeductions NVARCHAR(MAX), -- Payroll deductions/withholdings
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeDetails_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeDetails_ManagerID_2 FOREIGN KEY (ManagerID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeDetails_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeDetails_OrgID ON EmployeeDetails(OrgID);
CREATE INDEX IDX_EmployeeDetails_EmployeeID ON EmployeeDetails(EmployeeID);
CREATE INDEX IDX_EmployeeDetails_ManagerID ON EmployeeDetails(ManagerID);

CREATE TABLE EmployeeHistory (
    HistoryID INT PRIMARY KEY IDENTITY(1,1), -- Unique employee history/event identifier
    EmployeeID INT, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    EventType NVARCHAR(50), -- Event type [Promotion, Raise, Warning, Absence, Vacation, Sick Leave]
    Description NVARCHAR(MAX), -- Event description/details
    EffectiveDate DATE, -- Date event became effective (ISO format, shown as MM/DD/YYYY in US UI)
    RecordedBy NVARCHAR(100), -- User who recorded the event
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeHistory_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeHistory_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeHistory_OrgID ON EmployeeHistory(OrgID);
CREATE INDEX IDX_EmployeeHistory_EmployeeID ON EmployeeHistory(EmployeeID);

CREATE TABLE EmployeeEducation (
    EducationID INT PRIMARY KEY IDENTITY(1,1), -- Unique employee education record identifier
    EmployeeID INT, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    Institution NVARCHAR(255), -- Name of educational institution
    Degree NVARCHAR(255), -- Degree or certification earned (e.g., MD, BSN)
    Field NVARCHAR(255), -- Field of study/major
    StartYear INT, -- Year education started (YYYY)
    EndYear INT, -- Year education ended (YYYY)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeEducation_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeEducation_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeEducation_OrgID ON EmployeeEducation(OrgID);
CREATE INDEX IDX_EmployeeEducation_EmployeeID ON EmployeeEducation(EmployeeID);

CREATE TABLE EmployeeReferences (
    ReferenceID INT PRIMARY KEY IDENTITY(1,1), -- Unique employee reference identifier
    EmployeeID INT, -- Employee (FK to Employees)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    Name NVARCHAR(255), -- Reference's name
    Entity NVARCHAR(255), -- Reference's entity details (phone/email)
    Relationship NVARCHAR(100), -- Relationship to employee (e.g., Supervisor, Colleague)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_EmployeeReferences_EmployeeID_2 FOREIGN KEY (EmployeeID) REFERENCES Employees(EmployeeID),
    CONSTRAINT FK_EmployeeReferences_OrgID_2 FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_EmployeeReferences_OrgID ON EmployeeReferences(OrgID);
CREATE INDEX IDX_EmployeeReferences_EmployeeID ON EmployeeReferences(EmployeeID);
-- =============================================
-- FIXED ASSETS TABLE
-- =============================================

CREATE TABLE Assets (
    AssetID INT PRIMARY KEY IDENTITY(1,1), -- Unique asset identifier
    OrgID INT NOT NULL, -- Organization that owns the asset (FK to Organizations)
    AssetName NVARCHAR(255), -- Asset name/description
    AssetType NVARCHAR(100), -- Asset type [Computer, Printer, Vehicle, Medical Equipment, Other]
    SerialNumber NVARCHAR(100), -- Manufacturer serial number
    Vendor NVARCHAR(255), -- Vendor or supplier name
    PurchaseDate DATE, -- Date asset was purchased (ISO format, shown as MM/DD/YYYY in US UI)
    WarrantyExpiry DATE, -- Warranty expiration date (ISO format, shown as MM/DD/YYYY in US UI)
    Location NVARCHAR(255), -- Physical location of asset
    EntityPerson NVARCHAR(100), -- Person responsible for asset
    Status NVARCHAR(50), -- Asset status [Active, In Repair, Retired]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Assets_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_Assets_OrgID ON Assets(OrgID);
-- =============================================
-- SUPPLIES TABLE (CONSUMABLES)
-- =============================================

CREATE TABLE Supplies (
    SupplyID INT PRIMARY KEY IDENTITY(1,1), -- Unique supply identifier
    OrgID INT NOT NULL, -- Organization owning the supply (FK to Organizations)
    SupplyName NVARCHAR(255), -- Name/description of supply item
    Category NVARCHAR(100), -- Supply category [Anesthesia, PPE, Cleaning, Sanitary, Other]
    Unit NVARCHAR(50), -- Unit of measure [Box, Pack, Bottle, Gallon, Ounce, Pound, Piece] (US Imperial Units)
    Quantity INT, -- Quantity on hand (unitless, see Unit)
    Vendor NVARCHAR(255), -- Vendor or supplier name
    LastRestocked DATE, -- Date last restocked (ISO format, shown as MM/DD/YYYY in US UI)
    ReorderLevel INT, -- Minimum quantity before reorder is triggered
    StorageLocation NVARCHAR(255), -- Physical storage location
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Supplies_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_Supplies_OrgID ON Supplies(OrgID);
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
-- =============================================
-- PATIENT VACCINATIONS (IMMUNIZATION RECORDS)
-- =============================================

CREATE TABLE PatientVaccinations (
    VaccinationID INT PRIMARY KEY IDENTITY(1,1), -- Unique vaccination record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    VaccineName NVARCHAR(255), -- Name of vaccine administered
    Manufacturer NVARCHAR(255), -- Vaccine manufacturer
    DoseNumber INT, -- Dose number in series (e.g., 1, 2, 3)
    TotalDoses INT, -- Total number of doses required for full series
    AdministrationDate DATE, -- Date vaccine was administered (ISO format, shown as MM/DD/YYYY in US UI)
    LotNumber NVARCHAR(100), -- Vaccine lot/batch number
    AdministeredBy NVARCHAR(100), -- Name or ID of provider who administered
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- User who last modified the record (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_PatientVaccinations_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientVaccinations_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

CREATE INDEX IDX_PatientVaccinations_PatientID ON PatientVaccinations(PatientID);
CREATE INDEX IDX_PatientVaccinations_OrgID ON PatientVaccinations(OrgID);

-- =============================================
-- TREATMENT PLANS (AGREED PLAN PER APPOINTMENT OR PATIENT REQUEST)
-- =============================================
CREATE TABLE TreatmentPlans (
    PlanID INT PRIMARY KEY IDENTITY(1,1), -- Unique treatment plan identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    AppointmentID INT NULL, -- Associated appointment (FK to Appointments)
    Description NVARCHAR(MAX), -- Description of the treatment plan
    EstimatedCost DECIMAL(10,2), -- Estimated cost in USD
    Status NVARCHAR(50), -- Plan status [Planned, Approved, In Progress, Completed, Canceled]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_TreatmentPlans_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_TreatmentPlans_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_TreatmentPlans_AppointmentID FOREIGN KEY (AppointmentID) REFERENCES Appointments(AppointmentID)
);
CREATE INDEX IDX_TreatmentPlans_PatientID ON TreatmentPlans(PatientID);
CREATE INDEX IDX_TreatmentPlans_OrgID ON TreatmentPlans(OrgID);
CREATE INDEX IDX_TreatmentPlans_AppointmentID ON TreatmentPlans(AppointmentID);

-- =============================================
-- SERVICE CATALOG (STANDARD SERVICES WITH CODES AND PRICES)
-- =============================================
CREATE TABLE ServiceItems (
    ServiceItemID INT PRIMARY KEY IDENTITY(1,1), -- Unique service item identifier
    OrgID INT NOT NULL, -- Organization that owns the service (FK to Organizations)
    Code NVARCHAR(50), -- Service code (internal or billing)
    Name NVARCHAR(255), -- Service name/description
    Description NVARCHAR(MAX), -- Service details/notes
    Price DECIMAL(10,2), -- Standard price for service in USD
    IsPreventive BIT DEFAULT 0, -- Indicates if service is preventive [0 = No, 1 = Yes]
    CPTCode NVARCHAR(20), -- Optional CPT/ICD/HCPCS code
    AuthorizationRequired BIT DEFAULT 0, -- Indicates if pre-authorization is required [0 = No, 1 = Yes]
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CONSTRAINT FK_ServiceItems_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_ServiceItems_OrgID ON ServiceItems(OrgID);
-- =============================================
-- PROCEDURE ORDERS (ORDERED PROCEDURES PER PATIENT)
-- =============================================
CREATE TABLE ProcedureOrders (
    OrderID INT PRIMARY KEY IDENTITY(1,1), -- Unique procedure order identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    ProviderID INT, -- Ordering provider (FK to Providers)
    ServiceItemID INT, -- Service/procedure ordered (FK to ServiceItems)
    OrderDate DATE DEFAULT GETDATE(), -- Date order was placed (ISO format, shown as MM/DD/YYYY in US UI)
    Notes NVARCHAR(MAX), -- Additional notes or instructions
    CONSTRAINT FK_ProcedureOrders_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_ProcedureOrders_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_ProcedureOrders_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID),
    CONSTRAINT FK_ProcedureOrders_ServiceItemID FOREIGN KEY (ServiceItemID) REFERENCES ServiceItems(ServiceItemID)
);

-- =============================================
-- PROCEDURES PERFORMED (ACTUAL EXECUTION OF SERVICE)
-- =============================================
CREATE TABLE ProcedurePerformed (
    PerformedID INT PRIMARY KEY IDENTITY(1,1), -- Unique procedure performed record identifier
    OrgID INT NOT NULL, -- Organization placing the order (FK to Organizations)
    OrderID INT, -- Procedure order (FK to ProcedureOrders)
    PatientID INT, -- Patient (FK to Patients)
    ProviderID INT, -- Provider who performed (FK to Providers)
    PerformedDate DATE DEFAULT GETDATE(), -- Date procedure was performed (ISO format, shown as MM/DD/YYYY in US UI)
    Outcome NVARCHAR(MAX), -- Outcome/results of procedure
    CONSTRAINT FK_ProcedurePerformed_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),    
    CONSTRAINT FK_ProcedurePerformed_OrderID FOREIGN KEY (OrderID) REFERENCES ProcedureOrders(OrderID),
    CONSTRAINT FK_ProcedurePerformed_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_ProcedurePerformed_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID)
);

CREATE INDEX IDX_ProcedurePerformed_PatientID ON ProcedurePerformed(PatientID);
CREATE INDEX IDX_ProcedurePerformed_OrgID ON ProcedurePerformed(OrderID);
CREATE INDEX IDX_ProcedurePerformed_AppointmentID ON ProcedurePerformed(AppointmentID);
CREATE INDEX IDX_ProcedurePerformed_ProviderID ON ProcedurePerformed(ProviderID);

-- =============================================
-- PURCHASE ORDERS
-- =============================================
CREATE TABLE PurchaseOrders (
    PurchaseOrderID INT PRIMARY KEY IDENTITY(1,1), -- Unique purchase order identifier
    OrgID INT NOT NULL, -- Organization placing the order (FK to Organizations)
    VendorEntityID INT, -- Vendor entity (FK to Entities)
    OrderDate DATETIME DEFAULT GETDATE(), -- Date order was placed (ISO format, shown as MM/DD/YYYY in US UI)
    Status NVARCHAR(50), -- Purchase order status [Requested, Approved, Received, Canceled]
    Notes NVARCHAR(MAX), -- Additional notes/details
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_PurchaseOrders_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PurchaseOrders_VendorEntityID FOREIGN KEY (VendorEntityID) REFERENCES Entities(EntityID)
);

CREATE INDEX IDX_ProcedurePerformed_PatientID ON ProcedurePerformed(PatientID);
CREATE INDEX IDX_ProcedurePerformed_OrgID ON ProcedurePerformed(OrderID);

CREATE TABLE PurchaseOrderItems (
    ItemID INT PRIMARY KEY IDENTITY(1,1), -- Unique purchase order line item identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    PurchaseOrderID INT, -- Purchase order (FK to PurchaseOrders)
    SupplyID INT, -- Supply item ordered (FK to Supplies)
    Quantity INT, -- Quantity ordered
    UnitCost DECIMAL(10,2), -- Unit cost in USD
    CONSTRAINT FK_PurchaseOrderItems_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PurchaseOrderItems_PurchaseOrderID FOREIGN KEY (PurchaseOrderID) REFERENCES PurchaseOrders(PurchaseOrderID),
    CONSTRAINT FK_PurchaseOrderItems_SupplyID FOREIGN KEY (SupplyID) REFERENCES Supplies(SupplyID)
);

CREATE INDEX IDX_PurchaseOrderItems_PurchaseOrderID ON PurchaseOrderItems(PurchaseOrderID);
CREATE INDEX IDX_PurchaseOrderItems_OrgID ON PurchaseOrderItems(OrgID);
CREATE INDEX IDX_PurchaseOrderItems_SupplyID ON PurchaseOrderItems(SupplyID);


-- =============================================
-- INSURANCE CLAIMS (STATUS AND AMOUNTS PER INVOICE)
-- =============================================
CREATE TABLE InsuranceClaims (
    ClaimID INT PRIMARY KEY IDENTITY(1,1), -- Unique insurance claim identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    InvoiceID INT, -- Invoice referenced by the claim (FK to Invoices)
    InsuranceProvider NVARCHAR(255), -- Name of insurance provider
    ClaimAmount DECIMAL(10,2), -- Total claim amount in USD
    CoveredAmount DECIMAL(10,2), -- Amount covered by insurance in USD
    PatientResponsibility DECIMAL(10,2), -- Amount owed by patient in USD
    Status NVARCHAR(50), -- Claim status [Submitted, Approved, Denied, Partially Approved]
    SubmittedDate DATETIME, -- Date claim was submitted (ISO format, shown as MM/DD/YYYY in US UI)
    ResolvedDate DATETIME NULL, -- Date claim was resolved (ISO format, shown as MM/DD/YYYY in US UI)
    CONSTRAINT FK_InsuranceClaims_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_InsuranceClaims_InvoiceID FOREIGN KEY (InvoiceID) REFERENCES Invoices(InvoiceID)
);

CREATE INDEX IDX_InsuranceClaims_OrgID ON InsuranceClaims(OrgID);
CREATE INDEX IDX_InsuranceClaims_InvoiceID ON InsuranceClaims(InvoiceID);

-- =============================================
-- PATIENT FINANCIAL SUMMARY (DEDUCTIBLES & LIMITS)
-- =============================================
CREATE TABLE PatientFinancials (
    PatientID INT PRIMARY KEY, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    Year INT, -- Calendar year for financial summary (YYYY)
    AnnualDeductible DECIMAL(10,2), -- Annual deductible in USD
    DeductibleUsed DECIMAL(10,2), -- Deductible used in USD
    MaxOutOfPocket DECIMAL(10,2), -- Maximum out-of-pocket in USD
    OutOfPocketUsed DECIMAL(10,2), -- Out-of-pocket used in USD
    CONSTRAINT FK_PatientFinancials_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_PatientFinancials_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

-- =============================================
-- DISCHARGE INFORMATION
-- =============================================
CREATE TABLE Discharges (
    DischargeID INT PRIMARY KEY IDENTITY(1,1), -- Unique discharge record identifier
    PatientID INT, -- Patient (FK to Patients)
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    AppointmentID INT NULL, -- Associated appointment (FK to Appointments)
    DischargeDate DATETIME DEFAULT GETDATE(), -- Date/time of discharge (ISO format, shown as MM/DD/YYYY in US UI)
    Summary NVARCHAR(MAX), -- Discharge summary
    Instructions NVARCHAR(MAX), -- Discharge instructions
    ProviderID INT, -- Provider responsible for discharge (FK to Providers)
    CONSTRAINT FK_Discharges_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID),
    CONSTRAINT FK_Discharges_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_Discharges_AppointmentID FOREIGN KEY (AppointmentID) REFERENCES Appointments(AppointmentID),
    CONSTRAINT FK_Discharges_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID)
);

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
-- =============================================
-- INVENTORY TRANSACTIONS (PURCHASE, ADJUSTMENT, RETURN, CONSUMPTION)
-- =============================================
CREATE TABLE InventoryTransactions (
    InventoryTransactionID INT PRIMARY KEY IDENTITY(1,1), -- Unique inventory transaction identifier
    OrgID INT NOT NULL, -- Organization (FK to Organizations)
    SupplyID INT, -- Supply item (FK to Supplies)
    TransactionType NVARCHAR(50), -- Transaction type [Purchase, Adjustment, Return, Consumption]
    Unit NVARCHAR(50), -- Unit of measure [Box, Pack, Bottle, Gallon, Ounce, Pound, Piece] (US Imperial Units)
    Quantity INT, -- Quantity involved in transaction
    UnitCost DECIMAL(10,2), -- Unit cost in USD
    TotalCost AS (Quantity * UnitCost) PERSISTED, -- Computed total cost in USD
    SourceReference NVARCHAR(100), -- Reference to source document or entity (e.g., PO number)
    TransactionDate DATETIME DEFAULT GETDATE(), -- Date/time of transaction (ISO format, shown as MM/DD/YYYY in US UI)
    Notes NVARCHAR(MAX), -- Additional notes
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_InventoryTransactions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_InventoryTransactions_SupplyID FOREIGN KEY (SupplyID) REFERENCES Supplies(SupplyID)
);

CREATE INDEX IDX_InventoryTransactions_OrgID ON InventoryTransactions(OrgID);
CREATE INDEX IDX_InventoryTransactions_SupplyID ON InventoryTransactions(SupplyID);


-- =============================================
-- BANK ACCOUNTS (FOR TREASURY AND PAYMENT ROUTING)
-- =============================================
CREATE TABLE BankAccounts (
    BankAccountID INT PRIMARY KEY IDENTITY(1,1), -- Unique bank account identifier
    OrgID INT NOT NULL, -- Organization owning the bank account (FK to Organizations)
    EntityID INT NOT NULL, -- Linked to Vendor, Employee, or Org
    EntityType NVARCHAR(50) NOT NULL, -- [Vendor, Employee, Organization]    
    BankName NVARCHAR(255), -- Name of the bank
    RoutingNumber NVARCHAR(50), -- Bank routing number (ABA)
    AccountNumberMasked NVARCHAR(50), -- Masked bank account number (last 4 digits, etc.)
    AccountType NVARCHAR(50), -- Account type [Checking, Savings, Corporate Card, Zelle, Stripe]
    Currency NVARCHAR(10) DEFAULT 'USD', -- Account currency (default USD)
    IsPrimary BIT DEFAULT 0,  -- Indicates if this is the primary bank account for the entity (1 = Yes, 0 = No)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    CONSTRAINT FK_BankAccounts_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);
CREATE INDEX IDX_BankAccounts_EntityID ON BankAccounts(EntityID);



-- =============================================
-- TABLE: FinancialTransactions
-- Stores all unified financial transactions (invoices, payments, payroll, expenses, etc.)
-- TransactionType may be:
--   [PatientInvoice, PatientPayment, VendorPayment, PurchasePayment, Refund, CreditNote, DebitNote, Payroll, TaxPayment, Transfer, Expense]
-- For expenses:
--   - TransactionType = 'Expense'
--   - EntityType = 'Vendor', EntityID = Entities.EntityID of vendor
--   - Category = Expense category [Office Supplies, Utilities, Maintenance, Marketing, Salaries, Insurance, Travel, Software, Rent, Other]
--   - Notes = Expense memo/description
--   - Amount = Total expense amount
--   - TransactionDate = Expense date
--   - Status = [Pending, Completed, Failed, Applied]
CREATE TABLE FinancialTransactions (
    FinancialTransactionID INT PRIMARY KEY IDENTITY(1,1), -- Unique financial transaction identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    TransactionType NVARCHAR(50) NOT NULL, -- Transaction type [PatientInvoice, PatientPayment, VendorPayment, PurchasePayment, Refund, CreditNote, DebitNote, Payroll, TaxPayment, Transfer, Expense]
    EntityType NVARCHAR(50) NOT NULL, -- Entity type [Patient, Vendor, Employee, Bank, Insurance]
    EntityID INT NOT NULL, -- ID of the referenced entity (FK to Patients, Vendors, Employees, etc.)
    RelatedModule NVARCHAR(50), -- Related module name [Appointment, PurchaseOrder, PayrollRun, Claim, Asset]
    ReferenceID INT, -- Reference to related module record (e.g., AppointmentID, PurchaseOrderID)
    Amount DECIMAL(10,2) NOT NULL, -- Monetary amount in USD
    Method NVARCHAR(50), -- Payment method [Cash, Zelle, ACH, Stripe, PayPal, Check, Card]
    Status NVARCHAR(50) NOT NULL, -- Transaction status [Pending, Completed, Failed, Applied]
    TransactionDate DATETIME DEFAULT GETDATE() NOT NULL, -- Date/time of transaction (ISO format, shown as MM/DD/YYYY in US UI)
    Category NVARCHAR(100), -- Expense category [Office Supplies, Utilities, Maintenance, Marketing, Salaries, Insurance, Travel, Software, Rent, Other] (used only for TransactionType = 'Expense')
    Notes NVARCHAR(MAX), -- Additional details or comments (memo, expense description, etc.)
    CreatedAt DATETIME DEFAULT GETDATE() NOT NULL, -- Record creation timestamp (audit, ISO format)
    CreatedBy NVARCHAR(100) NOT NULL, -- User or system who created the transaction (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Last modifying user (audit)
    IsDeleted BIT DEFAULT 0 NOT NULL, -- Soft delete flag (audit)
    CONSTRAINT FK_FinancialTransactions_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

-- Indices for FinancialTransactions
CREATE INDEX IDX_FinancialTransactions_OrgID ON FinancialTransactions(OrgID);
CREATE INDEX IDX_FinancialTransactions_TransactionType ON FinancialTransactions(TransactionType);
CREATE INDEX IDX_FinancialTransactions_Entity ON FinancialTransactions(EntityType, EntityID);
CREATE INDEX IDX_FinancialTransactions_Module ON FinancialTransactions(RelatedModule, ReferenceID);
CREATE INDEX IDX_FinancialTransactions_TransactionDate ON FinancialTransactions(TransactionDate);

-- =============================================
-- TRANSACTION LINE ITEMS (IF APPLICABLE)
-- For expenses, details are stored here as well.
CREATE TABLE FinancialTransactionDetails (
    DetailID INT PRIMARY KEY IDENTITY(1,1), -- Unique transaction detail/line item identifier
    OrgID INT NOT NULL, -- Organization identifier (tenant, FK to Organizations)
    FinancialTransactionID INT, -- Parent transaction (FK to FinancialTransactions)
    ServiceItemID INT NULL, -- Service item billed (FK to ServiceItems)
    Description NVARCHAR(255), -- Description or memo for the line item
    Quantity INT, -- Quantity of service/item
    UnitPrice DECIMAL(10,2), -- Unit price in USD
    TotalPrice AS (Quantity * UnitPrice) PERSISTED, -- Computed total price in USD
    ProviderID INT NULL, -- Provider associated with line item (FK to Providers)
    AuthorizationStatus NVARCHAR(50), -- Authorization status [Pending, Approved, Denied, Not Required]
    CONSTRAINT FK_FinancialTransactionDetails_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_FinancialTransactionDetails_FinancialTransactionID FOREIGN KEY (FinancialTransactionID) REFERENCES FinancialTransactions(FinancialTransactionID),
    CONSTRAINT FK_FinancialTransactionDetails_ServiceItemID FOREIGN KEY (ServiceItemID) REFERENCES ServiceItems(ServiceItemID),
    CONSTRAINT FK_FinancialTransactionDetails_ProviderID FOREIGN KEY (ProviderID) REFERENCES Providers(ProviderID)
);


-- =============================================
-- TABLE: Documents
-- Stores uploaded files like consent forms, school letters, referrals, etc.
-- =============================================
CREATE TABLE Documents (
    DocumentID INT PRIMARY KEY IDENTITY(1,1), -- Unique document identifier
    OrgID INT NOT NULL, -- Organization that owns the document (FK)
    EntityType NVARCHAR(50), -- [Patient, Employee, Vendor, Pharmacy, Other]
    EntityID INT, -- Referenced entity
    FileName NVARCHAR(255), -- Original file name
    FileType NVARCHAR(50), -- [Consent, Referral, SchoolLetter, InsuranceCard, ID, Other]
    FileUrl NVARCHAR(MAX), -- Path or Blob Storage URL
    UploadedAt DATETIME DEFAULT GETDATE(), -- Upload timestamp (audit)
    UploadedBy NVARCHAR(100), -- User who uploaded the document (audit)
    Notes NVARCHAR(MAX), -- Additional context or notes
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- User who created the record (audit)
    ModifiedAt DATETIME NULL, -- Last update timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Last modifying user (audit)
    CONSTRAINT FK_Documents_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);


-- =============================================
-- TABLE: Coupons
-- Supports discount codes and promotional offers
-- =============================================
CREATE TABLE Coupons (
    CouponID INT PRIMARY KEY IDENTITY(1,1), -- Unique coupon identifier
    OrgID INT NOT NULL, -- Organization offering the coupon
    Code NVARCHAR(50) UNIQUE, -- Discount code
    Description NVARCHAR(255), -- Description of the offer
    DiscountAmount DECIMAL(10,2), -- Value of the discount
    ExpiryDate DATE, -- Valid until date
    IsActive BIT DEFAULT 1, -- Active flag
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- Created by user (audit)
    ModifiedAt DATETIME NULL, -- Last modification timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Modified by user (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_Coupons_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID)
);

-- =============================================
-- TABLE: StripeTokens
-- Stores tokenized payment methods for Stripe
-- =============================================
CREATE TABLE StripeTokens (
    TokenID INT PRIMARY KEY IDENTITY(1,1), -- Unique token identifier
    OrgID INT NOT NULL, -- Organization (tenant)
    PatientID INT, -- Associated patient
    StripeCustomerID NVARCHAR(255), -- Stripe customer reference
    StripePaymentMethodID NVARCHAR(255), -- Stripe payment method reference
    CreatedAt DATETIME DEFAULT GETDATE(), -- Record creation timestamp (audit)
    CreatedBy NVARCHAR(100), -- Created by user (audit)
    ModifiedAt DATETIME NULL, -- Last update timestamp (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Modified by user (audit)
    IsDeleted BIT DEFAULT 0, -- Logical deletion flag (audit)
    CONSTRAINT FK_StripeTokens_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_StripeTokens_PatientID FOREIGN KEY (PatientID) REFERENCES Patients(PatientID)
);
-- =============================================
-- TABLE: InvoiceMeta
-- Stores invoice-specific metadata, referencing FinancialTransactions
-- =============================================
CREATE TABLE InvoiceMeta (
    FinancialTransactionID INT PRIMARY KEY, -- FK to FinancialTransactions(FinancialTransactionID); must have TransactionType = 'PatientInvoice'
    OrgID INT NOT NULL, -- Organization that owns the invoice (tenant). FK to Organizations(OrgID)
    DiscountAmount DECIMAL(10,2), -- Amount discounted from subtotal before tax. Supports coupons, promotions, etc.
    TaxAmount DECIMAL(10,2), -- Tax amount applied to the subtotal after discount
    DueDate DATETIME, -- Final date by which invoice should be paid. Used in AR aging and dunning reports
    FinalAmount AS ( -- Computed amount due after applying discount and tax
        (SELECT Amount FROM FinancialTransactions WHERE FinancialTransactionID = InvoiceMeta.FinancialTransactionID)
        - DiscountAmount + TaxAmount
    ) PERSISTED,
    Status NVARCHAR(50), -- Invoice status: [Pending = unpaid, Paid = fully paid, Partially Paid = partially covered, Canceled = voided]
    Notes NVARCHAR(MAX), -- Free-form field for extra context, such as billing comments, approvals, notes from provider
    CreatedAt DATETIME DEFAULT GETDATE(), -- Timestamp when the invoice record was created (audit)
    CreatedBy NVARCHAR(100), -- Username or system that created the record (audit)
    ModifiedAt DATETIME NULL, -- Timestamp when invoice was last modified (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Username or system that last modified the record (audit)
    CONSTRAINT FK_InvoiceMeta_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_InvoiceMeta_FinancialTransactionID FOREIGN KEY (FinancialTransactionID) REFERENCES FinancialTransactions(FinancialTransactionID)
);

-- =============================================
-- TABLE: PaymentMeta
-- Stores payment-specific metadata, referencing FinancialTransactions
-- =============================================
CREATE TABLE PaymentMeta (
    FinancialTransactionID INT PRIMARY KEY, -- FK to FinancialTransactions(FinancialTransactionID); must have TransactionType = 'PatientPayment'
    OrgID INT NOT NULL, -- Organization that received the payment (tenant). FK to Organizations(OrgID)
    PaymentDate DATETIME, -- Date/time when payment was received. Useful for cash flow tracking and reconciliation
    Method NVARCHAR(50), -- Payment method used: [Cash, Credit Card, Stripe, ACH, Check, Zelle, Other]
    Reference NVARCHAR(255), -- External transaction ID (e.g., Stripe paymentIntent, Zelle ref number). Used for reconciliation
    CreatedAt DATETIME DEFAULT GETDATE(), -- Timestamp when the payment record was created (audit)
    CreatedBy NVARCHAR(100), -- Username or system that created the record (audit)
    ModifiedAt DATETIME NULL, -- Timestamp when payment was last modified (audit)
    ModifiedBy NVARCHAR(100) NULL, -- Username or system that last modified the record (audit)
    CONSTRAINT FK_PaymentMeta_OrgID FOREIGN KEY (OrgID) REFERENCES Organizations(OrgID),
    CONSTRAINT FK_PaymentMeta_FinancialTransactionID FOREIGN KEY (FinancialTransactionID) REFERENCES FinancialTransactions(FinancialTransactionID)
);