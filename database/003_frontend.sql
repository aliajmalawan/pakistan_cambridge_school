-- ============================================================
-- Kohsar — Frontend expansion: fee structure + downloadable resources
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 003_frontend.sql
-- ============================================================

USE kohsarschool;

CREATE TABLE IF NOT EXISTS fee_structure (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    class_group VARCHAR(80) NOT NULL,          -- e.g. "Playgroup – KG"
    admission_fee INT UNSIGNED NOT NULL DEFAULT 0,
    monthly_fee INT UNSIGNED NOT NULL DEFAULT 0,
    exam_fee INT UNSIGNED NOT NULL DEFAULT 0,  -- per term
    notes VARCHAR(200) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS downloads (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(180) NOT NULL,
    description VARCHAR(300) NOT NULL DEFAULT '',
    category ENUM('admissions','academics','forms','results','general') NOT NULL DEFAULT 'general',
    file_path VARCHAR(255) NULL,               -- stored under /uploads/downloads
    external_url VARCHAR(300) NOT NULL DEFAULT '',
    file_size INT UNSIGNED NOT NULL DEFAULT 0, -- bytes
    file_ext VARCHAR(10) NOT NULL DEFAULT '',
    download_count INT UNSIGNED NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Fee structure — figures the office publishes to parents
-- ------------------------------------------------------------
INSERT INTO fee_structure (class_group, admission_fee, monthly_fee, exam_fee, notes, sort_order) VALUES
('Playgroup – KG',            3000,  1800,  500, 'Includes activity materials', 1),
('Grades I – V',              4000,  2200,  600, 'Sibling concession applies from the second child', 2),
('Grades VI – VIII',          5000,  2800,  700, 'Computer lab charges included', 3),
('Grades IX – X (Science)',   6500,  3500,  900, 'Laboratory and practical charges included', 4),
('1st & 2nd Year (F.Sc)',     8000,  4500, 1200, 'Board registration billed separately at cost', 5);

-- ------------------------------------------------------------
-- Downloadable resources (files are attached by the office via the CMS)
-- ------------------------------------------------------------
INSERT INTO downloads (title, description, category, sort_order) VALUES
('Admission Form — Session 2026–27', 'Printable application form for Playgroup through 1st Year. Submit at the campus office with the required documents.', 'admissions', 1),
('Prospectus 2026–27', 'Full prospectus: programs, faculty, facilities, fee structure and school calendar.', 'admissions', 2),
('Fee Structure 2026–27', 'Official fee schedule for all classes, including concession rules.', 'admissions', 3),
('Academic Calendar 2026–27', 'Term dates, examination weeks, holidays and result announcement dates.', 'academics', 4),
('Leaving Certificate Request Form', 'For guardians requesting a school leaving certificate or character certificate.', 'forms', 5),
('Uniform & Stationery List', 'Approved uniform specification and class-wise stationery requirements.', 'general', 6);
