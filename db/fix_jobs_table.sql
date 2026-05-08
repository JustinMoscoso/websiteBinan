-- Fix jobs table structure to match the Job model
-- Drop the existing table and recreate it with the correct structure

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `office` int(11) NOT NULL,
  `publication_date` date NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `fk_jobs_office` (`office`),
  CONSTRAINT `fk_jobs_office` FOREIGN KEY (`office`) REFERENCES `department_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Get the first few department IDs that exist in department_content table
-- and use those for sample data
SET @dept1 = (SELECT ID FROM department_content WHERE status = 'ACTIVE' LIMIT 1);
SET @dept2 = (SELECT ID FROM department_content WHERE status = 'ACTIVE' AND ID != @dept1 LIMIT 1);
SET @dept3 = (SELECT ID FROM department_content WHERE status = 'ACTIVE' AND ID != @dept1 AND ID != @dept2 LIMIT 1);
SET @dept4 = (SELECT ID FROM department_content WHERE status = 'ACTIVE' AND ID != @dept1 AND ID != @dept2 AND ID != @dept3 LIMIT 1);
SET @dept5 = (SELECT ID FROM department_content WHERE status = 'ACTIVE' AND ID != @dept1 AND ID != @dept2 AND ID != @dept3 AND ID != @dept4 LIMIT 1);

-- Insert sample data using existing department IDs
INSERT INTO `jobs` (`title`, `description`, `office`, `publication_date`, `status`, `created_date`) VALUES
('Administrative Assistant', 'We are looking for a detail-oriented Administrative Assistant to join our team. Responsibilities include managing office operations, coordinating meetings, and providing administrative support to department heads.', IFNULL(@dept1, 1), '2024-01-15', 'ACTIVE', NOW()),
('IT Support Specialist', 'Join our IT department as a Support Specialist. You will be responsible for providing technical support to staff, maintaining computer systems, and ensuring network security.', IFNULL(@dept2, 1), '2024-01-20', 'ACTIVE', NOW()),
('Public Relations Officer', 'We are seeking a skilled Public Relations Officer to manage our city\'s public image and communications. Experience in government communications is preferred.', IFNULL(@dept3, 1), '2024-01-25', 'ACTIVE', NOW()),
('Environmental Officer', 'Help us maintain and improve our city\'s environmental standards. This role involves monitoring environmental compliance, conducting inspections, and developing sustainability programs.', IFNULL(@dept4, 1), '2024-02-01', 'ACTIVE', NOW()),
('Finance Analyst', 'Join our finance team to help manage the city\'s budget and financial planning. Strong analytical skills and experience in government finance are required.', IFNULL(@dept5, 1), '2024-02-05', 'ACTIVE', NOW()); 