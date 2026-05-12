-- ============================================================
-- EMAIL QUEUE TABLE
-- Run this once in phpMyAdmin or via mysql CLI:
--   mysql -u root websitebinan < email_queue_table.sql
-- ============================================================
CREATE TABLE IF NOT EXISTS `email_queue` (
    `id`          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `to_email`    VARCHAR(255)  NOT NULL,
    `from_email`  VARCHAR(255)  NOT NULL DEFAULT 'websiteBinan@gmail.com',
    `from_name`   VARCHAR(255)  NOT NULL DEFAULT 'Biñan Tech Support',
    `reply_to`    VARCHAR(255)  NULL,
    `subject`     VARCHAR(500)  NOT NULL,
    `body`        LONGTEXT      NOT NULL,
    `status`      ENUM('PENDING','SENT','FAILED') NOT NULL DEFAULT 'PENDING',
    `attempts`    TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `error_msg`   TEXT          NULL,
    `created_at`  DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `sent_at`     DATETIME      NULL,
    INDEX `idx_status` (`status`),
    INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
