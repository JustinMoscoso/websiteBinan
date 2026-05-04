-- Add org_chart_img column to department_content table
-- This column will store the organizational chart image for departments

ALTER TABLE `department_content` 
ADD COLUMN `org_chart_img` VARCHAR(255) NULL AFTER `img_logo`;

-- Update existing records to have NULL org chart images
UPDATE `department_content` SET `org_chart_img` = NULL WHERE `org_chart_img` IS NULL;

-- Add comment to document the column purpose
ALTER TABLE `department_content` 
MODIFY COLUMN `org_chart_img` VARCHAR(255) NULL COMMENT 'Organizational chart image filename for the department'; 