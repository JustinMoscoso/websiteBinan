-- Create jobs table for Biñan City website
-- Run this in phpMyAdmin

-- First, check if the table exists and drop it if it does
DROP TABLE IF EXISTS `jobs`;

-- Create the jobs table
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
  KEY `office` (`office`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample job data
INSERT INTO `jobs` (`title`, `description`, `office`, `publication_date`, `status`) VALUES
('Administrative Assistant', 'We are looking for a detail-oriented Administrative Assistant to join our team. Responsibilities include managing office operations, coordinating meetings, and providing administrative support to department staff. The ideal candidate should have excellent organizational skills and strong communication abilities.', 1, CURDATE(), 'ACTIVE'),
('IT Support Specialist', 'Join our IT team as a Support Specialist. You will be responsible for providing technical support to staff, maintaining computer systems, and ensuring network security. Experience with Windows and Linux systems preferred.', 2, CURDATE(), 'ACTIVE'),
('Public Relations Officer', 'We are seeking a Public Relations Officer to manage our city\'s public image and communications. This role involves creating press releases, managing social media accounts, and coordinating with local media outlets.', 3, CURDATE(), 'ACTIVE');

-- Note: Make sure the department_content table exists and has departments with IDs 1, 2, 3
-- If not, you may need to adjust the office values to match existing department IDs 