-- ============================================================
-- Kohsar — Academics page
--
-- The Programs page answers "what can my child study here".
-- This page answers "how is it taught, examined and graded", which is a
-- different question and a different set of tables:
--
--   academic_framework  the standing rules of teaching (curriculum, medium, day)
--   academic_calendar   the shape of the school year
--   grading_scale       how a mark becomes a grade
--
-- Subject offerings are NOT duplicated here — the page reads the existing
-- `programs` table so the two pages can never disagree.
--
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 010_academics.sql
-- ============================================================

USE kohsarschool;

-- ------------------------------------------------------------
-- 1. Framework blocks
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS academic_framework (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(120) NOT NULL,
    body VARCHAR(600) NOT NULL DEFAULT '',
    icon VARCHAR(40) NOT NULL DEFAULT 'book',
    accent VARCHAR(24) NOT NULL DEFAULT 'accent-blue',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO academic_framework (title, body, icon, accent, sort_order) VALUES
('Curriculum and affiliation',
 'The school wing follows the Khyber Pakhtunkhwa scheme of studies. Matriculation and F.Sc candidates are registered with the Board of Intermediate and Secondary Education, Kohat, and are prepared strictly against its syllabus and paper pattern rather than a private one.',
 'book', 'accent-blue', 1),

('Medium of instruction',
 'English is the medium of instruction from Playgroup onwards, including mathematics and the sciences. Urdu and Islamiat are taught in Urdu. Arabic is introduced from Grade III. Teachers are expected to explain in whichever language a child actually understands before returning to English.',
 'message', 'accent-teal', 2),

('The teaching day',
 'Classes run 8:00 AM to 1:30 PM, Monday to Saturday, in eight periods of forty minutes with two breaks. Saturday is a half day reserved for tests, remedial work and laboratory sessions. Nothing academic is scheduled outside these hours.',
 'clock', 'accent-violet', 3),

('Class size',
 'Sections are capped at thirty students in the school wing and forty in the college wing. When a section fills, the school opens another section rather than seating more children in the same room — a cap that is only meaningful if it is enforced.',
 'users', 'accent-amber', 4),

('How marks are earned',
 'Each term mark is built from three parts: class tests (20%), term work including notebooks, assignments and practicals (20%), and the terminal examination (60%). The weighting is published so that no student is surprised by a result and no mark rests on a single paper.',
 'award', 'accent-rose', 5),

('Promotion and remedial support',
 'A student needs 40% overall and at least 33% in each subject to be promoted. A shortfall triggers remedial classes and a written plan shared with the guardian before the next term begins — a child is not held back without a documented attempt to fix the problem first.',
 'shield', 'accent-orange', 6);

-- ------------------------------------------------------------
-- 2. The academic year
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS academic_calendar (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    term VARCHAR(120) NOT NULL,
    period VARCHAR(80) NOT NULL DEFAULT '',      -- e.g. "March - June"
    detail VARCHAR(400) NOT NULL DEFAULT '',
    milestone TINYINT(1) NOT NULL DEFAULT 0,     -- examinations and other fixed points
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO academic_calendar (term, period, detail, milestone, sort_order) VALUES
('Session begins', 'First week of March',
 'New admissions are confirmed, books and diaries are issued, and the term scheme of studies goes home with every child.', 0, 1),

('First term', 'March to June',
 'Two class tests per subject, the first parent meeting in April, and science practicals beginning in the second week.', 0, 2),

('First term examinations', 'Third week of June',
 'Written papers for all classes. Results and a signed progress report are issued to guardians within ten working days.', 1, 3),

('Summer break', 'Mid-July to mid-August',
 'Holiday work is set for Grades III and above and is marked in the first week back.', 0, 4),

('Second term', 'Mid-August to November',
 'Board registration for Grades IX and XI is completed in this term. Second parent meeting in October.', 0, 5),

('Annual examinations', 'First half of December',
 'Annual papers for Playgroup through Grade VIII. Board candidates sit the school''s send-up examinations in the same window.', 1, 6),

('Winter break', 'Late December to February',
 'Kurram winters close the campus. Board candidates continue with a published revision timetable and on-call subject teachers.', 0, 7);

-- ------------------------------------------------------------
-- 3. Grading scale
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS grading_scale (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    grade VARCHAR(8) NOT NULL,
    band VARCHAR(40) NOT NULL,                   -- e.g. "80% and above"
    remark VARCHAR(120) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO grading_scale (grade, band, remark, sort_order) VALUES
('A+', '80% and above', 'Outstanding', 1),
('A',  '70% to 79%',    'Excellent', 2),
('B',  '60% to 69%',    'Very good', 3),
('C',  '50% to 59%',    'Good', 4),
('D',  '40% to 49%',    'Pass', 5),
('F',  'Below 40%',     'Remedial classes and a written plan for the guardian', 6);

-- ------------------------------------------------------------
-- The page itself joins Admin -> Pages like every other route
-- ------------------------------------------------------------
INSERT INTO pages (page_type, slug, route, title, lede, content, meta_description, status) VALUES
('system', 'sys-academics', '/academics',
 'Academics',
 'How teaching, examination and grading actually work here - written down, so it can be held to.',
 '',
 'The academic framework at Kohsar School & College Parachinar: curriculum and board affiliation, the teaching day, assessment weightings, the academic calendar and the grading scale.',
 'published')
ON DUPLICATE KEY UPDATE title = VALUES(title);
