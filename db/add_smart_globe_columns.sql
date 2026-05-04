-- Migration to add Smart and Globe columns to hotlines table
-- This migration adds two new columns for Smart and Globe contact numbers

-- Add Smart column
ALTER TABLE `hotlines` 
ADD COLUMN `smart` varchar(45) NOT NULL DEFAULT '-' AFTER `telco`;

-- Add Globe column  
ALTER TABLE `hotlines` 
ADD COLUMN `globe` varchar(45) NOT NULL DEFAULT '-' AFTER `smart`;

-- Update existing records to have default values
UPDATE `hotlines` SET `smart` = '-' WHERE `smart` = '';
UPDATE `hotlines` SET `globe` = '-' WHERE `globe` = '';

-- Add comments to document the new columns
ALTER TABLE `hotlines` 
MODIFY COLUMN `smart` varchar(45) NOT NULL DEFAULT '-' COMMENT 'Smart mobile number in format xxxx-xxx-xxxx or -';

ALTER TABLE `hotlines` 
MODIFY COLUMN `globe` varchar(45) NOT NULL DEFAULT '-' COMMENT 'Globe mobile number in format xxxx-xxx-xxxx or -'; 