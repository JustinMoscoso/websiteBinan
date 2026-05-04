-- Add email column to jobs table
ALTER TABLE `jobs` ADD COLUMN `email` VARCHAR(255) NOT NULL AFTER `publication_date`;

-- Update existing records with a default email (you can change this to your preferred email)
UPDATE `jobs` SET `email` = 'hr@binan.gov.ph' WHERE `email` = '' OR `email` IS NULL; 