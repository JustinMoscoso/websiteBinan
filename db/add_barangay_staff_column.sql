-- Add barangay_staff column to barangay_content table
-- This column will store information about barangay staff members

ALTER TABLE `barangay_content` 
ADD COLUMN `barangay_staff` TEXT NULL AFTER `contact`;

-- Update existing records to have empty staff information
UPDATE `barangay_content` SET `barangay_staff` = '' WHERE `barangay_staff` IS NULL;

-- Add comment to document the column purpose
ALTER TABLE `barangay_content` 
MODIFY COLUMN `barangay_staff` TEXT NULL COMMENT 'Information about barangay staff members including names, positions, and contact details'; 