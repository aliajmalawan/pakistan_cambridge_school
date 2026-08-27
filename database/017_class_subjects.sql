-- ============================================================
-- Pakistan Cambridge School — Subjects Allocated per Class
-- Shown as a table on the public Programs page. Managed under
-- Admin → Academics → Class Subjects.
-- Import: mysql -u root --default-character-set=utf8mb4 pakistan_cambridge_school < 017_class_subjects.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS class_subjects (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    class_name VARCHAR(80) NOT NULL,
    subjects VARCHAR(400) NOT NULL DEFAULT '',   -- comma-separated
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
