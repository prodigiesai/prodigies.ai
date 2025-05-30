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