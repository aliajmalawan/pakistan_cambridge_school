-- ============================================================
-- Kohsar — Leadership page
-- Kept separate from `faculty`: leadership profiles carry a message and are
-- presented differently, and the faculty list should not repeat them.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 009_leadership.sql
-- ============================================================

USE kohsarschool;

CREATE TABLE IF NOT EXISTS leadership (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    designation VARCHAR(120) NOT NULL,
    qualification VARCHAR(200) NOT NULL DEFAULT '',
    tenure VARCHAR(80) NOT NULL DEFAULT '',      -- e.g. "Principal since 2014"
    photo VARCHAR(255) NULL,
    bio VARCHAR(500) NOT NULL DEFAULT '',        -- short line under the name
    message MEDIUMTEXT NOT NULL DEFAULT '',      -- long statement, HTML allowed
    featured TINYINT(1) NOT NULL DEFAULT 0,      -- shown as the opening message block
    email VARCHAR(160) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO leadership (name, designation, qualification, tenure, bio, message, featured, sort_order) VALUES
('Prof. Syed Ali Raza', 'Principal', 'M.Phil Physics — University of Peshawar', 'Principal since 2014',
 'Leads the campus and chairs the academic council.',
 '<p>When families send their children to Kohsar, they are not buying a service. They are lending us their trust for the years that decide a child''s direction — and that is a heavier thing than any fee.</p><p>My commitment to every guardian is simple. We will tell you the truth about your child''s progress, even when it is uncomfortable. We will not inflate a mark to keep you happy. And we will not let a capable student drift because nobody was paying attention.</p><p>Come and see the campus during working hours. Ask the teachers what they are covering this week. Ask to see the results. An institution that cannot answer those questions in person has no business making promises on a website.</p>',
 1, 1),

('Mrs. Shazia Batool', 'Vice Principal — School Wing', 'M.A English, B.Ed', 'Vice Principal since 2017',
 'Oversees Playgroup through Grade VIII: timetabling, discipline and guardian communication.',
 '', 0, 2),

('Mr. Kamran Hussain', 'Head of Sciences', 'M.Sc Physics — Kohat University', 'Heading Sciences since 2019',
 'Runs the physics, chemistry and biology departments and the laboratory programme.',
 '', 0, 3),

('Mrs. Kausar Fatima', 'Head of Examinations', 'M.A English — University of Peshawar', 'Examinations since 2016',
 'Responsible for term assessments, board registrations and the publication of results.',
 '', 0, 4);

-- ------------------------------------------------------------
-- The page itself joins Admin → Pages like every other route
-- ------------------------------------------------------------
INSERT INTO pages (page_type, slug, route, title, lede, content, meta_description, status) VALUES
('system', 'sys-leadership', '/leadership',
 'Leadership',
 'The people accountable for what happens on this campus — and how to reach them.',
 '',
 'Meet the leadership of Kohsar School & College Parachinar — the Principal, Vice Principal and heads of department.',
 'published')
ON DUPLICATE KEY UPDATE title = VALUES(title);
