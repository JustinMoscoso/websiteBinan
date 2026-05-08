-- Add job type column to jobs table
ALTER TABLE jobs ADD COLUMN type VARCHAR(20) NOT NULL DEFAULT 'Full Time' AFTER office;

-- Update existing jobs to have a default job type
UPDATE jobs SET type = 'Full Time' WHERE type IS NULL OR type = '';

-- Add comment to the column
ALTER TABLE jobs MODIFY COLUMN type VARCHAR(20) NOT NULL DEFAULT 'Full Time' COMMENT 'Job type: Full Time or Part Time'; 