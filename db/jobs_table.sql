-- Create jobs table if it doesn't exist
CREATE TABLE IF NOT EXISTS `jobs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `office` int(11) NOT NULL,
  `publication_date` date NOT NULL,
  `status` enum('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
  `created_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_date` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`),
  KEY `office` (`office`),
  CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`office`) REFERENCES `department_content` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample data if table is empty
INSERT INTO `jobs` (`title`, `description`, `office`, `publication_date`, `status`) 
SELECT 'Administrative Assistant', 'We are looking for a detail-oriented Administrative Assistant to join our team. Responsibilities include managing office operations, coordinating meetings, and providing administrative support to department staff.', 1, CURDATE(), 'ACTIVE'
WHERE NOT EXISTS (SELECT 1 FROM `jobs` LIMIT 1);

INSERT INTO `jobs` (`title`, `description`, `office`, `publication_date`, `status`) 
SELECT 'IT Support Specialist', 'Join our IT team as a Support Specialist. You will be responsible for providing technical support to staff, maintaining computer systems, and ensuring network security.', 2, CURDATE(), 'ACTIVE'
WHERE (SELECT COUNT(*) FROM `jobs`) = 1; 