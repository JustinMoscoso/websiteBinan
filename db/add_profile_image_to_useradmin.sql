-- Adds profile picture support for admin users.
ALTER TABLE `useradmin`
ADD COLUMN `profile_image` VARCHAR(255) NULL AFTER `email`;
