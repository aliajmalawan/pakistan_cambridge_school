-- ============================================================
-- Kohsar — About page journey timeline
--
-- The one part of the new About page with nowhere to live yet: the school's
-- story told as dated milestones. Everything else on that page already has a
-- home (mission and vision in settings, values in core_values, the Principal's
-- message in leadership, the narrative in the page's own content field).
--
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 012_milestones.sql
-- ============================================================

USE kohsarschool;

CREATE TABLE IF NOT EXISTS milestones (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    year VARCHAR(16) NOT NULL,                   -- text, not an int: "2001", "2014-15"
    title VARCHAR(140) NOT NULL,
    detail VARCHAR(400) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO milestones (year, title, detail, sort_order) VALUES
('2001', 'The school opens',
 'Kohsar begins with four rooms on Main College Road and 63 students from Playgroup to Grade III.', 1),

('2006', 'First Matriculation batch',
 'The school extends to Grade X and registers with the Board of Intermediate and Secondary Education, Kohat.', 2),

('2011', 'Science laboratories built',
 'Dedicated physics, chemistry and biology laboratories open, and practical work becomes part of every science class from Grade VI.', 3),

('2014', 'College wing established',
 'F.Sc Pre-Medical and Pre-Engineering are introduced, allowing students to complete their schooling on one campus.', 4),

('2019', 'Computer laboratory and library',
 'A computer laboratory and a stocked library open, with library periods timetabled rather than optional.', 5),

('2023', 'Campus extension',
 'Additional classrooms bring every section within the thirty-student cap, and the sports ground is levelled and marked.', 6);
