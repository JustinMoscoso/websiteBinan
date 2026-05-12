-- Add force_pass_reset column to useradmin table
ALTER TABLE useradmin ADD COLUMN force_pass_reset TINYINT(1) DEFAULT 0;
