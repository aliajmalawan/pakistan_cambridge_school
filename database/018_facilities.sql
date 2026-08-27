-- ============================================================
-- Pakistan Cambridge School — Campus Facilities
-- Shown as a card grid on the public Facilities page. Managed
-- under Admin → Facilities.
-- Import: mysql -u root --default-character-set=utf8mb4 pakistan_cambridge_school < 018_facilities.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS facilities (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    description VARCHAR(400) NOT NULL DEFAULT '',
    icon VARCHAR(30) NOT NULL DEFAULT 'shield',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
