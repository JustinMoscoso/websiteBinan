-- ============================================================
-- Patch #23/#24/#25: Department & Barangay Account Types
-- Adds account_type and entity_ref_id to useradmin table
-- ============================================================

-- Add account_type: STANDARD (default), DEPARTMENT, BARANGAY
ALTER TABLE `useradmin`
    ADD COLUMN `account_type` ENUM('STANDARD','DEPARTMENT','BARANGAY') NOT NULL DEFAULT 'STANDARD' AFTER `user_lvl`,
    ADD COLUMN `entity_ref_id` INT UNSIGNED NULL DEFAULT NULL AFTER `account_type`,
    ADD INDEX `idx_account_type` (`account_type`),
    ADD INDEX `idx_entity_ref_id` (`entity_ref_id`);

-- Existing accounts are all STANDARD
UPDATE `useradmin` SET `account_type` = 'STANDARD' WHERE `account_type` IS NULL OR `account_type` = '';
