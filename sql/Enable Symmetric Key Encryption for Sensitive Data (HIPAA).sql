-- Enable Symmetric Key Encryption for Sensitive Data (HIPAA)
-- Step 1: Create Master Key (only once per database)
CREATE MASTER KEY ENCRYPTION BY PASSWORD = 'N1k!mat!em1!';

-- Step 2: Create Certificate
CREATE CERTIFICATE EHR_Cert
WITH SUBJECT = 'EHR Data Protection Certificate';

-- Step 3: Create Symmetric Key
CREATE SYMMETRIC KEY EHR_Key
WITH ALGORITHM = AES_256
ENCRYPTION BY CERTIFICATE EHR_Cert;

SELECT * FROM sys.symmetric_keys; 

-- Example: Patients table definition (snippet)
-- SSNEncrypted VARBINARY(MAX), -- Encrypted SSN using EHR_Key (AES_256 + Certificate)

-- Example: Insert encrypted SSN
OPEN SYMMETRIC KEY EHR_Key DECRYPTION BY CERTIFICATE EHR_Cert;
INSERT INTO Patients (OrgID, FirstName, LastName, DOB, Gender, SSNEncrypted, Phone, Email, Address, InsuranceProvider, InsuranceNumber, CreatedBy)
VALUES (1, 'John', 'Doe', '1980-01-01', 'Male', ENCRYPTBYKEY(KEY_GUID('EHR_Key'), '123-45-6789'), '555-1234', 'john@example.com', '123 Main St', 'Aetna', 'INS123456', 'admin');
CLOSE SYMMETRIC KEY EHR_Key;

-- Example: Read decrypted SSN
OPEN SYMMETRIC KEY EHR_Key DECRYPTION BY CERTIFICATE EHR_Cert;
SELECT CONVERT(NVARCHAR, DECRYPTBYKEY(SSNEncrypted)) AS SSN
FROM Patients;
CLOSE SYMMETRIC KEY EHR_Key;