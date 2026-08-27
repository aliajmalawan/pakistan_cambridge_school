
-- ============================================================
-- SOURCE: pakistan_cambridge_school.sql
-- ============================================================
-- ============================================================
-- Pakistan Cambridge School — ERP/CMS Database
-- MySQL 8 / MariaDB (XAMPP) · utf8mb4
-- Import: mysql -u root < pakistan_cambridge_school.sql   (or via phpMyAdmin)
-- ============================================================

CREATE DATABASE IF NOT EXISTS pakistan_cambridge_school CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pakistan_cambridge_school;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS settings, users, pages, sliders, programs, faculty, testimonials,
    news, gallery_albums, gallery_images, admissions, contact_messages,
    activity_log, dashboard_prefs, menu_items, menus, fee_structure, downloads, page_views;
SET FOREIGN_KEY_CHECKS = 1;

-- ------------------------------------------------------------
-- Settings (key/value site configuration)
-- ------------------------------------------------------------
CREATE TABLE settings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    skey VARCHAR(80) NOT NULL UNIQUE,
    svalue TEXT NOT NULL
) ENGINE=InnoDB;

INSERT INTO settings (skey, svalue) VALUES
('site_name', 'Pakistan Cambridge School'),
('site_name_ur', 'پاکستان کیمبرج سکول، حافظ آباد'),
('tagline', 'Learning Today. Leading Tomorrow.'),
('phone', '+92 926 310555'),
('email', ''),
('address', 'Main College Road, Hafizabad, Punjab, Pakistan'),
('facebook', ''),
('youtube', ''),
('whatsapp', '+92 300 0000000'),
('map_embed', ''),
('stat_students', '1745'),
('stat_faculty', '92'),
('stat_pass_rate', '98%'),
('stat_years', '25+'),
('admissions_open', '1'),
('admissions_note', 'Admissions for Session 2026–27 are open for Playgroup to 1st Year. Seats in Grade VI and 1st Year are limited.'),
('mission', 'To deliver an uncompromising standard of education — built on knowledge, character and discipline — that equips the youth of Hafizabad and the wider Kurram region to lead with integrity at the national and global level.'),
('vision', 'To stand as the leading centre of academic excellence in the mountains of Kurram: an institution where every student, regardless of background, is given the tools, mentorship and standards to reach the summit of their potential.'),
('footer_about', 'Pakistan Cambridge School has served the families of Hafizabad for over two decades — from playgroup to F.Sc — holding a single campus to a national standard of teaching, discipline and care.');

-- ------------------------------------------------------------
-- Users (admin panel)
-- ------------------------------------------------------------
CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('superadmin','admin','editor') NOT NULL DEFAULT 'editor',
    status ENUM('active','disabled') NOT NULL DEFAULT 'active',
    last_login_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Default login: admin@pcs.edu.pk  (seeded password is in the handover
-- notes, not in this repository — change it after the first login)
INSERT INTO users (name, email, password, role) VALUES
('Site Administrator', 'admin@pcs.edu.pk', '$2y$12$amKiPzRRsIcBESawDwkLxeGrfQGDpw.eBBfNfcklIny/km1hoYEpu', 'superadmin');

-- ------------------------------------------------------------
-- CMS Pages
-- ------------------------------------------------------------
CREATE TABLE pages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(160) NOT NULL UNIQUE,
    title VARCHAR(200) NOT NULL,
    content MEDIUMTEXT NOT NULL,
    meta_description VARCHAR(255) NOT NULL DEFAULT '',
    image VARCHAR(255) NULL,
    show_in_menu TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO pages (slug, title, content, meta_description, show_in_menu, sort_order) VALUES
('about', 'About Pakistan Cambridge School', '<p><strong>Pakistan Cambridge School — “the land of mountains” —</strong> takes its name from the terrain that surrounds Hafizabad: steep, weathered and unshaken. The school was built on a simple belief: a world-class education does not require a world-class city. It requires world-class standards, applied without compromise, in the place its students call home.</p><p>From playgroup to F.Sc, our single campus serves more than 1,700 students under one standard of teaching, discipline and care. Our faculty of over ninety educators — many of them alumni who returned to teach — hold their classes to the results that matter: board examinations, university placements, and the character our graduates carry into both.</p><h3>Why families choose Pakistan Cambridge School</h3><ul><li>A consistent 98% board pass rate across Matric and F.Sc streams.</li><li>Small sections with attendance and progress reported to guardians by SMS.</li><li>Science and computer laboratories, a stocked library, and structured sports houses.</li><li>Scholarships and sibling concessions applied transparently on the fee ledger.</li></ul><p>The crest we wear — a shield bearing the three peaks of Kurram and the ribbon of honour — is a promise to every family that entrusts us with their children: their climb is our charge.</p>', 'About Pakistan Cambridge School — our history, standards and campus.', 1, 1),
('facilities', 'Campus & Facilities', '<p>The Pakistan Cambridge School campus is built for serious study in a safe, ordered environment.</p><h3>Academic facilities</h3><ul><li><strong>Science laboratories</strong> — separate physics, chemistry and biology labs for Matric and F.Sc practical work, equipped to board syllabus requirements.</li><li><strong>Computer laboratory</strong> — a networked lab where every student from Grade VI takes weekly IT classes.</li><li><strong>Library</strong> — reference sections in English and Urdu, daily newspapers, and a supervised reading hour for middle grades.</li></ul><h3>Student life</h3><ul><li><strong>Sports grounds</strong> — cricket and volleyball grounds with inter-house competitions each term.</li><li><strong>Assembly courtyard</strong> — morning assembly beneath the Kurram range, where the whole school gathers as one.</li><li><strong>Transport</strong> — van routes covering Hafizabad city and surrounding villages.</li></ul><h3>Boarding</h3><p>Hostel accommodation is available for boys of Grade VIII and above from surrounding tehsils, with warden supervision, evening prep and weekend family visits.</p>', 'Laboratories, library, sports grounds, transport and boarding at Pakistan Cambridge School.', 1, 2),
('rules', 'Rules & Discipline', '<p>Discipline at Pakistan Cambridge School is firm, fair and consistent — discipline without fear, standards without excuses.</p><h3>Attendance</h3><ul><li>Minimum 85% attendance is required to sit term examinations.</li><li>Guardians are notified of absence by SMS the same morning.</li><li>Three consecutive unexplained absences trigger a guardian meeting.</li></ul><h3>Conduct</h3><ul><li>Full uniform, checked at the gate daily.</li><li>Mobile phones are not permitted for students on campus.</li><li>Respect for staff and fellow students is non-negotiable; bullying leads to immediate disciplinary review before the head of campus.</li></ul><h3>Fees</h3><ul><li>Monthly challans are issued on the 1st and payable by the 10th.</li><li>Concessions (sibling, merit, hardship) are applied on the ledger and reviewed each session.</li></ul>', 'Attendance, conduct and fee rules at Pakistan Cambridge School.', 0, 3);

-- ------------------------------------------------------------
-- Homepage sliders
-- ------------------------------------------------------------
CREATE TABLE sliders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    eyebrow VARCHAR(120) NOT NULL DEFAULT '',
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(300) NOT NULL DEFAULT '',
    image VARCHAR(255) NULL,
    cta_text VARCHAR(60) NOT NULL DEFAULT '',
    cta_link VARCHAR(255) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO sliders (eyebrow, title, subtitle, cta_text, cta_link, sort_order) VALUES
('Admissions Open — Session 2026–27', 'Learning Today. Leading Tomorrow.', 'From playgroup to F.Sc — a single campus in Hafizabad holding itself to a national standard of teaching, discipline and care.', 'Apply Now', '/admissions', 1),
('Results — Board of Intermediate & Secondary Education', 'A 98% pass rate is not luck. It is a habit.', 'Our Matric and F.Sc candidates deliver year after year — with top-ten district positions in three of the last five sessions.', 'See Our Programs', '/programs', 2),
('Beyond the Classroom', 'Strong minds need strong grounds.', 'Inter-house cricket and volleyball, science exhibitions, qirat and debate competitions — every student competes, every house counts.', 'Life at Pakistan Cambridge School', '/page/facilities', 3);

-- ------------------------------------------------------------
-- Academic programs
-- ------------------------------------------------------------
CREATE TABLE programs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(160) NOT NULL UNIQUE,
    name VARCHAR(160) NOT NULL,
    level VARCHAR(80) NOT NULL,
    description TEXT NOT NULL,
    subjects VARCHAR(500) NOT NULL DEFAULT '',
    icon VARCHAR(40) NOT NULL DEFAULT 'book',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO programs (slug, name, level, description, subjects, icon, sort_order) VALUES
('early-years', 'Early Years', 'Playgroup – KG', 'A gentle, structured start: phonics in English and Urdu, number sense, motor skills and classroom habits — taught by staff trained for early childhood, in classrooms designed for it.', 'English Phonics, Urdu, Numbers, Rhymes & Stories, Activity Work', 'sun', 1),
('primary', 'Primary Section', 'Grades I – V', 'The foundation years. Reading fluency in English and Urdu, arithmetic mastery, Nazra Quran, and the discipline of daily homework — with progress reported to guardians every term.', 'English, Urdu, Mathematics, General Science, Islamiyat, Nazra, Social Studies', 'pencil', 2),
('middle', 'Middle Section', 'Grades VI – VIII', 'Where study becomes scholarship. Subject-specialist teachers take over, computer classes begin, and students join house competitions in sports, debate and qirat.', 'English, Urdu, Mathematics, Science, Computer, Islamiyat, History & Geography', 'book', 3),
('matric-science', 'Matriculation — Science', 'Grades IX – X', 'Board preparation done properly: syllabus completion a term early, weekly tests on the board pattern, laboratory practicals, and past-paper drills through the final months.', 'Physics, Chemistry, Biology / Computer Science, Mathematics, English, Urdu, Pak Studies, Islamiyat', 'flask', 4),
('fsc-pre-medical', 'F.Sc — Pre-Medical', '1st & 2nd Year', 'For future doctors: full board syllabus with MDCAT-aligned depth, dissection and titration practicals, and evening test sessions in the final year.', 'Biology, Chemistry, Physics, English, Urdu, Islamiyat, Pak Studies', 'heart', 5),
('fsc-pre-engineering', 'F.Sc — Pre-Engineering', '1st & 2nd Year', 'For future engineers: mathematics taught to ECAT standard, physics practicals on board pattern, and structured problem-solving hours every week.', 'Mathematics, Physics, Chemistry, English, Urdu, Islamiyat, Pak Studies', 'cog', 6);

-- ------------------------------------------------------------
-- Faculty
-- ------------------------------------------------------------
CREATE TABLE faculty (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    designation VARCHAR(120) NOT NULL,
    department VARCHAR(80) NOT NULL DEFAULT '',
    qualification VARCHAR(200) NOT NULL DEFAULT '',
    photo VARCHAR(255) NULL,
    bio TEXT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO faculty (name, designation, department, qualification, sort_order) VALUES
('Prof. Syed Ali Raza', 'Principal', 'Administration', 'M.Phil Physics — University of Peshawar', 1),
('Mrs. Shazia Batool', 'Vice Principal (School Wing)', 'Administration', 'M.A English, B.Ed', 2),
('Mr. Kamran Hussain', 'Head of Sciences', 'Physics', 'M.Sc Physics — Kohat University', 3),
('Dr. Nadia Kazmi', 'Senior Lecturer', 'Biology', 'Ph.D Botany — University of Peshawar', 4),
('Mr. Zeeshan Abbas', 'Lecturer', 'Chemistry', 'M.Sc Chemistry — Gomal University', 5),
('Mr. Riaz Hussain', 'Senior Teacher', 'Mathematics', 'M.Sc Mathematics — AIOU', 6),
('Mrs. Kausar Fatima', 'Senior Teacher', 'English', 'M.A English — University of Peshawar', 7),
('Qari Ghulam Hussain', 'Instructor', 'Islamiyat & Nazra', 'Shahadat-ul-Aalmiya, Wifaq-ul-Madaris', 8);

-- ------------------------------------------------------------
-- Testimonials
-- ------------------------------------------------------------
CREATE TABLE testimonials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    role VARCHAR(120) NOT NULL DEFAULT '',
    content TEXT NOT NULL,
    photo VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO testimonials (name, role, content, sort_order) VALUES
('Syed Hassan', 'Parent — Grades VI & IX', 'My daughters walk to a school we trust completely — and their results speak beside any big-city institution.', 1),
('Fatima Zahra', 'Alumna — Class of 2023', 'Pakistan Cambridge School''s teachers stayed after hours for our entire F.Sc batch before the board exams. I am in MBBS today because of it.', 2),
('Muhammad Jawad', 'Parent — Grade IV', 'Discipline without fear, and standards without excuses. That balance is rare anywhere in the country.', 3);

-- ------------------------------------------------------------
-- News / Events / Notices
-- ------------------------------------------------------------
CREATE TABLE news (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(200) NOT NULL UNIQUE,
    title VARCHAR(220) NOT NULL,
    category ENUM('news','event','notice') NOT NULL DEFAULT 'news',
    excerpt VARCHAR(300) NOT NULL DEFAULT '',
    content MEDIUMTEXT NOT NULL,
    image VARCHAR(255) NULL,
    published_at DATETIME NOT NULL,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO news (slug, title, category, excerpt, content, published_at) VALUES
('admissions-open-2026-27', 'Admissions Open — Session 2026–27', 'notice', 'Applications are now open for Playgroup to 1st Year. Grade VI and 1st Year seats are limited this session.', '<p>Admissions for <strong>Session 2026–27</strong> are now open for all classes from Playgroup to 1st Year (F.Sc Pre-Medical and Pre-Engineering).</p><p><strong>How to apply:</strong></p><ol><li>Submit the online application form on this website, or collect a form from the school office (8:00 AM – 1:30 PM).</li><li>Bring the student''s B-Form, guardian CNIC copy, previous school leaving certificate and two photographs.</li><li>Entrance assessments for Grade VI and above are held on Saturdays; results are shared by SMS within three working days.</li></ol><p>Seats in Grade VI and 1st Year are limited. Early applications are strongly advised.</p>', '2026-07-15 09:00:00'),
('matric-results-2026', 'Matric Results 2026 — 98.4% Pass Rate, Two District Positions', 'news', 'Our Grade X candidates have again delivered: 98.4% passed, with Sara Jafri placing 3rd in the district.', '<p>The Board of Intermediate & Secondary Education has announced the Matriculation results for 2026, and Pakistan Cambridge School''s candidates have upheld the school''s standard once again.</p><ul><li><strong>98.4%</strong> of our 126 candidates passed, 74 of them with A or A+ grades.</li><li><strong>Sara Jafri</strong> secured <strong>3rd position in the district</strong> with 1067 marks.</li><li><strong>Bilal Hussain</strong> placed 9th in the district in the Science group.</li></ul><p>The results were celebrated at a school assembly where the Principal reminded students: <em>"A pass rate is a habit, not an event. The habit is daily attendance, weekly tests and honest marking — and it belongs to every one of you."</em></p>', '2026-07-02 10:00:00'),
('eid-milad-celebration', 'Eid Milad-un-Nabi ﷺ Celebration on Campus', 'event', 'The campus will mark Eid Milad-un-Nabi with a naat and qirat program in the assembly courtyard.', '<p>Pakistan Cambridge School will mark <strong>Eid Milad-un-Nabi ﷺ</strong> with a program of naat, qirat and speeches in the assembly courtyard, beginning at 9:30 AM.</p><p>Winners of the inter-house naat and qirat competitions will perform, and prizes for the term''s competitions will be distributed. Parents and guardians are warmly invited; seating for guests will be available from 9:00 AM.</p>', '2026-08-20 09:30:00'),
('science-exhibition-2026', 'Annual Science Exhibition — Grades VIII to X', 'event', 'Working models, chemistry demonstrations and the annual inter-house science quiz — open to parents.', '<p>The annual <strong>Pakistan Cambridge School Science Exhibition</strong> returns with working models and demonstrations prepared by students of Grades VIII to X across physics, chemistry, biology and computer science.</p><p>This year''s centrepiece is a student-built weather station that has been logging Hafizabad''s temperature and rainfall since March. The inter-house science quiz final will close the day.</p><p>The exhibition is open to parents and guardians from 10:00 AM to 1:00 PM in the science block.</p>', '2026-08-05 10:00:00'),
('summer-timing-notice', 'Revised School Timings — Summer Schedule', 'notice', 'From 1 August, school timings are 7:45 AM to 1:15 PM for all classes. Friday closing 12:00 noon.', '<p>From <strong>Friday 1 August 2026</strong>, the school will observe summer timings:</p><ul><li><strong>Monday – Thursday, Saturday:</strong> 7:45 AM – 1:15 PM</li><li><strong>Friday:</strong> 7:45 AM – 12:00 noon</li></ul><p>Van routes will run 30 minutes earlier accordingly. Guardians who collect students personally are requested to arrive by closing time, as staff meetings follow dismissal.</p>', '2026-07-25 08:00:00');

-- ------------------------------------------------------------
-- Gallery
-- ------------------------------------------------------------
CREATE TABLE gallery_albums (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(160) NOT NULL UNIQUE,
    title VARCHAR(160) NOT NULL,
    description VARCHAR(300) NOT NULL DEFAULT '',
    cover VARCHAR(255) NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE gallery_images (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    album_id INT UNSIGNED NOT NULL,
    image VARCHAR(255) NOT NULL,
    caption VARCHAR(200) NOT NULL DEFAULT '',
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_gallery_album FOREIGN KEY (album_id) REFERENCES gallery_albums(id) ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT INTO gallery_albums (slug, title, description, sort_order) VALUES
('campus-life', 'Campus Life', 'Morning assembly, classrooms and the everyday rhythm of the school beneath the Kurram range.', 1),
('sports-day-2026', 'Sports Day 2026', 'Inter-house cricket and volleyball finals, march past and the prize distribution ceremony.', 2),
('science-exhibition', 'Science Exhibition', 'Working models and demonstrations by Grades VIII to X in the science block.', 3);

-- ------------------------------------------------------------
-- Admission applications
-- ------------------------------------------------------------
CREATE TABLE admissions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    app_no VARCHAR(20) NOT NULL UNIQUE,
    student_name VARCHAR(120) NOT NULL,
    father_name VARCHAR(120) NOT NULL,
    bform VARCHAR(20) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('male','female') NOT NULL,
    class_applied VARCHAR(60) NOT NULL,
    prev_school VARCHAR(200) NOT NULL DEFAULT '',
    guardian_phone VARCHAR(20) NOT NULL,
    address VARCHAR(300) NOT NULL,
    notes VARCHAR(500) NOT NULL DEFAULT '',
    status ENUM('pending','under_review','accepted','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Contact messages
-- ------------------------------------------------------------
CREATE TABLE contact_messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(160) NOT NULL DEFAULT '',
    phone VARCHAR(20) NOT NULL DEFAULT '',
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Dashboard: activity log + per-user widget preferences
-- ------------------------------------------------------------
CREATE TABLE activity_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,               -- NULL = public visitor action
    actor VARCHAR(120) NOT NULL,             -- display name at time of action
    action VARCHAR(40) NOT NULL,             -- login, created, updated, deleted, submitted, status_changed, uploaded
    subject_type VARCHAR(40) NOT NULL,       -- news, page, admission, message, settings, slider, gallery, user, auth, system
    subject_label VARCHAR(220) NOT NULL,     -- human-readable target
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_created (created_at),
    INDEX idx_activity_subject (subject_type)
) ENGINE=InnoDB;

INSERT INTO activity_log (user_id, actor, action, subject_type, subject_label) VALUES
(1, 'Site Administrator', 'created', 'system', 'Pakistan Cambridge School CMS installed and database seeded');

CREATE TABLE dashboard_prefs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    widgets VARCHAR(500) NOT NULL,           -- comma-separated visible widget keys, in order
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_prefs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Navigation menu (editable in Admin -> Navigation Menu)
-- ------------------------------------------------------------

CREATE TABLE menu_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,                       -- NULL = top level; set = dropdown child
    label VARCHAR(80) NOT NULL,
    link_type ENUM('route','page','custom') NOT NULL DEFAULT 'route',
    route VARCHAR(120) NOT NULL DEFAULT '',            -- built-in path, e.g. /programs
    page_id INT UNSIGNED NULL,                         -- when link_type = page
    custom_url VARCHAR(300) NOT NULL DEFAULT '',       -- when link_type = custom
    new_tab TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_menu_parent (parent_id),
    INDEX idx_menu_sort (sort_order),
    CONSTRAINT fk_menu_parent FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    CONSTRAINT fk_menu_page   FOREIGN KEY (page_id)   REFERENCES pages(id)      ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Seed with the menu the site is already showing, so nothing
-- changes visually until an admin edits it.
-- ------------------------------------------------------------
INSERT INTO menu_items (label, link_type, route, sort_order) VALUES
('Home',     'route', '/',          10),
('Programs', 'route', '/programs',  30),
('Fees',     'route', '/fees',      40),
('Faculty',  'route', '/faculty',   50),
('News',     'route', '/news',      60),
('Gallery',  'route', '/gallery',   70),
('Contact',  'route', '/contact',   80);

-- CMS pages that were flagged show_in_menu become real menu items
INSERT INTO menu_items (label, link_type, page_id, sort_order)
SELECT title, 'page', id, 20 + (sort_order * 2)
FROM pages
WHERE show_in_menu = 1 AND status = 'published';

-- show_in_menu is superseded by this table; the Pages screen now links here.

-- ------------------------------------------------------------
-- Traffic analytics (admin dashboard)
-- ------------------------------------------------------------

CREATE TABLE page_views (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR(255) NOT NULL,
    -- Visitors are counted by a salted hash, never a stored IP address:
    -- enough to count unique people, not enough to identify one.
    visitor_hash CHAR(64) NOT NULL,
    referrer VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_views_created (created_at),
    INDEX idx_views_path (path),
    INDEX idx_views_visitor (visitor_hash)
) ENGINE=InnoDB;


-- ============================================================
-- SOURCE: 002_dashboard.sql
-- ============================================================
-- ============================================================
-- Kohsar — Admin Dashboard upgrade
-- Activity log + per-user dashboard widget preferences
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 002_dashboard.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS activity_log (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,               -- NULL = public visitor action
    actor VARCHAR(120) NOT NULL,             -- display name at time of action
    action VARCHAR(40) NOT NULL,             -- e.g. login, created, updated, deleted, submitted, status_changed
    subject_type VARCHAR(40) NOT NULL,       -- e.g. news, page, admission, message, settings, slider
    subject_label VARCHAR(220) NOT NULL,     -- human-readable target
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_activity_created (created_at),
    INDEX idx_activity_subject (subject_type)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS dashboard_prefs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL UNIQUE,
    widgets VARCHAR(500) NOT NULL,           -- comma-separated visible widget keys, in order
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_prefs_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Seed the log with the system's own birth so the feed is never empty
INSERT INTO activity_log (user_id, actor, action, subject_type, subject_label) VALUES
(1, 'Site Administrator', 'created', 'system', 'Kohsar CMS installed and database seeded');


-- ============================================================
-- SOURCE: 003_frontend.sql
-- ============================================================
-- ============================================================
-- Kohsar — Frontend expansion: fee structure + downloadable resources
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 003_frontend.sql
-- ============================================================

USE pakistan_cambridge_school;

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


-- ============================================================
-- SOURCE: 004_menu.sql
-- ============================================================
-- ============================================================
-- Kohsar — Editable navigation menu
-- Replaces the hardcoded navbar links with database-driven items.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 004_menu.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS menu_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL,                       -- NULL = top level; set = dropdown child
    label VARCHAR(80) NOT NULL,
    link_type ENUM('route','page','custom') NOT NULL DEFAULT 'route',
    route VARCHAR(120) NOT NULL DEFAULT '',            -- built-in path, e.g. /programs
    page_id INT UNSIGNED NULL,                         -- when link_type = page
    custom_url VARCHAR(300) NOT NULL DEFAULT '',       -- when link_type = custom
    new_tab TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_menu_parent (parent_id),
    INDEX idx_menu_sort (sort_order),
    CONSTRAINT fk_menu_parent FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    CONSTRAINT fk_menu_page   FOREIGN KEY (page_id)   REFERENCES pages(id)      ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Seed with the menu the site is already showing, so nothing
-- changes visually until an admin edits it.
-- ------------------------------------------------------------
INSERT INTO menu_items (label, link_type, route, sort_order) VALUES
('Home',     'route', '/',          10),
('Programs', 'route', '/programs',  30),
('Fees',     'route', '/fees',      40),
('Faculty',  'route', '/faculty',   50),
('News',     'route', '/news',      60),
('Gallery',  'route', '/gallery',   70),
('Contact',  'route', '/contact',   80);

-- CMS pages that were flagged show_in_menu become real menu items
INSERT INTO menu_items (label, link_type, page_id, sort_order)
SELECT title, 'page', id, 20 + (sort_order * 2)
FROM pages
WHERE show_in_menu = 1 AND status = 'published';

-- show_in_menu is superseded by this table; the Pages screen now links here.


-- ============================================================
-- SOURCE: 005_menu_builder.sql
-- ============================================================
-- ============================================================
-- Kohsar — Menu Builder
-- Upgrades the single navigation list into named menus (header +
-- footer columns) with drag-and-drop ordering and nesting.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 005_menu_builder.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS menus (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    slug VARCHAR(80) NOT NULL UNIQUE,      -- 'main' drives the header and cannot be deleted
    description VARCHAR(200) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO menus (name, slug, description) VALUES
('Header Menu',      'main',            'The main navigation bar at the top of every page.'),
('Footer — Quick Links', 'footer-quick', 'First link column in the website footer.'),
('Footer — Admissions',  'footer-admissions', 'Second link column in the website footer.')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- Attach existing items to the header menu
SET @main := (SELECT id FROM menus WHERE slug = 'main');

ALTER TABLE menu_items
    ADD COLUMN IF NOT EXISTS menu_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id;

UPDATE menu_items SET menu_id = @main WHERE menu_id IS NULL OR menu_id = 0;

-- 'none' lets an item act purely as a dropdown heading
ALTER TABLE menu_items
    MODIFY COLUMN link_type ENUM('route','page','custom','none') NOT NULL DEFAULT 'route';

-- Index + FK for the new column. The cascade means deleting a menu removes
-- its items rather than leaving them orphaned.
ALTER TABLE menu_items ADD INDEX IF NOT EXISTS idx_menu_items_menu (menu_id);

DELETE FROM menu_items WHERE menu_id NOT IN (SELECT id FROM menus);

ALTER TABLE menu_items
    ADD CONSTRAINT fk_menu_items_menu FOREIGN KEY (menu_id) REFERENCES menus(id) ON DELETE CASCADE;

-- ------------------------------------------------------------
-- Seed the footer columns with the links the footer currently hardcodes
-- ------------------------------------------------------------
SET @quick := (SELECT id FROM menus WHERE slug = 'footer-quick');
SET @adm   := (SELECT id FROM menus WHERE slug = 'footer-admissions');

INSERT INTO menu_items (menu_id, label, link_type, route, sort_order) VALUES
(@quick, 'Academic Programs', 'route', '/programs', 20),
(@quick, 'Our Faculty',       'route', '/faculty',  30),
(@quick, 'News & Notices',    'route', '/news',     40),
(@quick, 'Gallery',           'route', '/gallery',  50),
(@adm,   'Apply Online',      'route', '/admissions', 10),
(@adm,   'Fee Structure',     'route', '/fees',       20),
(@adm,   'Downloads & Forms', 'route', '/downloads',  30),
(@adm,   'Contact the Office','route', '/contact',    40);

-- "About Us" in Quick Links points at the CMS page when it exists
INSERT INTO menu_items (menu_id, label, link_type, page_id, sort_order)
SELECT @quick, 'About Us', 'page', id, 10 FROM pages WHERE slug = 'about' LIMIT 1;

-- Footer "Rules & Discipline" / "Campus & Facilities" links
INSERT INTO menu_items (menu_id, label, link_type, page_id, sort_order)
SELECT @adm, 'Rules & Discipline', 'page', id, 50 FROM pages WHERE slug = 'rules' LIMIT 1;
INSERT INTO menu_items (menu_id, label, link_type, page_id, sort_order)
SELECT @adm, 'Campus & Facilities', 'page', id, 60 FROM pages WHERE slug = 'facilities' LIMIT 1;


-- ============================================================
-- SOURCE: 006_analytics.sql
-- ============================================================
-- ============================================================
-- Kohsar — Traffic analytics for the admin dashboard
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 006_analytics.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS page_views (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    path VARCHAR(255) NOT NULL,
    -- Visitors are counted by a salted hash, never a stored IP address:
    -- enough to count unique people, not enough to identify one.
    visitor_hash CHAR(64) NOT NULL,
    referrer VARCHAR(255) NOT NULL DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_views_created (created_at),
    INDEX idx_views_path (path),
    INDEX idx_views_visitor (visitor_hash)
) ENGINE=InnoDB;


-- ============================================================
-- SOURCE: 007_core_values.sql
-- ============================================================
-- ============================================================
-- Kohsar — Core values for the Vision & Mission page
-- Mission and vision themselves live in `settings` so they stay a single
-- source of truth for the homepage and this page alike.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 007_core_values.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS core_values (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(80) NOT NULL,
    description VARCHAR(400) NOT NULL,
    icon VARCHAR(30) NOT NULL DEFAULT 'shield',   -- key from App\Core\Icon
    accent VARCHAR(20) NOT NULL DEFAULT 'accent-blue',
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- The six values from docs/brand/BRAND_GUIDELINES.md §1.3
INSERT INTO core_values (title, description, icon, accent, sort_order) VALUES
('Excellence',      'We measure ourselves against world standards, not local convenience. A result is only good if it would be good anywhere.', 'award',    'accent-amber',  1),
('Integrity',       'Every mark, every result, every claim is earned and honest. We would rather report a hard truth than a comfortable number.', 'shield',   'accent-blue',   2),
('Resilience',      'Kohsar means “the land of mountains.” Our students are built to endure and rise, like the terrain around them.',            'mountain', 'accent-teal',   3),
('Community',       'The institution exists in service of Parachinar''s families, not apart from them. Their trust is the whole mandate.',       'users',    'accent-violet', 4),
('Curiosity',       'Knowledge is pursued for its own sake, not only for examinations. A question asked freely is worth more than a fact recited.', 'book',  'accent-rose',   5),
('Unity & Respect', 'One campus, one standard, one community — open to every student who commits to it, whatever their background.',            'ribbon',   'accent-orange', 6);


-- ============================================================
-- SOURCE: 008_system_pages.sql
-- ============================================================
-- ============================================================
-- Kohsar — Bring every public page under Admin → Pages
--
-- Two kinds of page now live in the same table:
--   content — free HTML pages an admin writes and can delete (About, Rules…)
--   system  — pages a route renders from live data (Programs, News, Fees…).
--             Their heading, intro and SEO text become editable; the page
--             itself cannot be deleted because a route depends on it.
--
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 008_system_pages.sql
-- ============================================================

USE pakistan_cambridge_school;

ALTER TABLE pages
    ADD COLUMN IF NOT EXISTS page_type ENUM('content','system') NOT NULL DEFAULT 'content' AFTER id,
    ADD COLUMN IF NOT EXISTS route VARCHAR(120) NULL AFTER slug,
    ADD COLUMN IF NOT EXISTS lede VARCHAR(400) NOT NULL DEFAULT '' AFTER title;

-- A route identifies a system page the way a slug identifies a content page
ALTER TABLE pages ADD UNIQUE INDEX IF NOT EXISTS idx_pages_route (route);

-- Existing hand-written pages keep their leading sentence as the hero lede
UPDATE pages SET lede = meta_description WHERE page_type = 'content' AND lede = '';

-- ------------------------------------------------------------
-- One row per public route. slug mirrors the route so the unique
-- slug index stays satisfied; only `route` is used for lookups.
-- ------------------------------------------------------------
INSERT INTO pages (page_type, slug, route, title, lede, content, meta_description, status) VALUES
('system', 'sys-home', '/',
 'Kohsar School & College Parachinar',
 'Excellence, earned in the mountains.',
 '',
 'Kohsar School & College Parachinar — from playgroup to F.Sc, held to a national standard of teaching, discipline and care.', 'published'),

('system', 'sys-vision-mission', '/vision-mission',
 'Vision & Mission',
 'What this school is for, where it intends to go, and the standards it holds itself to along the way.',
 '',
 'The mission, vision and core values of Kohsar School & College Parachinar.', 'published'),

('system', 'sys-programs', '/programs',
 'Academic Programs',
 'One campus, one standard — six programs carrying a student from their first day of playgroup to their board examinations.',
 '<p>Every program below is taught to the syllabus of the Board of Intermediate &amp; Secondary Education, with weekly testing and progress reported to guardians each term.</p>',
 'From Playgroup to F.Sc Pre-Medical and Pre-Engineering — the academic programs, subjects and levels taught at Kohsar School & College Parachinar.', 'published'),

('system', 'sys-fees', '/fees',
 'Fee Structure',
 'Published rates for every class — billed monthly, with nothing undisclosed.',
 '<p>Fees are billed monthly by challan, issued on the 1st and payable by the 10th. Every figure below is the official published rate.</p>',
 'Official fee schedule for Kohsar School & College Parachinar — admission, monthly and examination fees for every class, with concession rules.', 'published'),

('system', 'sys-faculty', '/faculty',
 'Our Faculty',
 'Subject specialists, board examiners and alumni who returned to teach — the people your children learn from every day.',
 '',
 'Meet the teachers of Kohsar School & College Parachinar — subject specialists, board examiners and alumni who returned to teach.', 'published'),

('system', 'sys-news', '/news',
 'News & Notices',
 'Results, admissions, holidays and campus events — announced here first.',
 '',
 'Latest news, events and official notices from Kohsar School & College Parachinar.', 'published'),

('system', 'sys-gallery', '/gallery',
 'Gallery',
 'Assemblies beneath the Kurram range, sports days, science exhibitions and prize distributions.',
 '',
 'Photographs of campus life at Kohsar School & College Parachinar.', 'published'),

('system', 'sys-admissions', '/admissions',
 'Admissions',
 'Apply online for Session 2026–27 — Playgroup through 1st Year.',
 '',
 'Apply online to Kohsar School & College Parachinar. Entrance assessment, interview and document requirements explained.', 'published'),

('system', 'sys-downloads', '/downloads',
 'Downloads & Forms',
 'Official documents published by the campus office.',
 '<p>Printed copies of every form are also available at the office during working hours.</p>',
 'Download the admission form, prospectus, fee structure and academic calendar from Kohsar School & College Parachinar.', 'published'),

('system', 'sys-contact', '/contact',
 'Contact Us',
 'Call, write, or visit the campus during office hours.',
 '',
 'Contact Kohsar School & College Parachinar — campus address, phone, email and office hours.', 'published'),

('system', 'sys-search', '/search',
 'Search',
 'Search news, notices, pages and academic programs across the website.',
 '',
 'Search the Kohsar School & College Parachinar website.', 'published')
ON DUPLICATE KEY UPDATE title = VALUES(title);


-- ============================================================
-- SOURCE: 009_leadership.sql
-- ============================================================
-- ============================================================
-- Kohsar — Leadership page
-- Kept separate from `faculty`: leadership profiles carry a message and are
-- presented differently, and the faculty list should not repeat them.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 009_leadership.sql
-- ============================================================

USE pakistan_cambridge_school;

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


-- ============================================================
-- SOURCE: 010_academics.sql
-- ============================================================
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

USE pakistan_cambridge_school;

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


-- ============================================================
-- SOURCE: 011_academic_calendar.sql
-- ============================================================
-- ============================================================
-- Kohsar — Academic Calendar page
--
-- The Academics page shows the SHAPE of the year (terms and breaks, already in
-- `academic_calendar`). This adds the DATED calendar: every holiday, examination
-- window, meeting and deadline of a session, with real dates.
--
-- Both tables now belong to the Academic Calendar page and are edited together
-- under Admin -> Academic Calendar.
--
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 011_academic_calendar.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS calendar_events (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    session VARCHAR(16) NOT NULL DEFAULT '',            -- e.g. "2026-27"
    title VARCHAR(160) NOT NULL,
    starts_on DATE NOT NULL,
    ends_on DATE NULL,                                  -- blank for a single day
    type ENUM('holiday','examination','event','meeting','deadline') NOT NULL DEFAULT 'event',
    detail VARCHAR(400) NOT NULL DEFAULT '',
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_session_date (session, starts_on)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Session 2026-27 (March 2026 to February 2027)
-- Islamic dates are the announced expectation and move with the moon sighting;
-- the detail line says so rather than pretending otherwise.
-- ------------------------------------------------------------
INSERT INTO calendar_events (session, title, starts_on, ends_on, type, detail) VALUES
('2026-27', 'Session begins', '2026-03-02', NULL, 'event',
 'Classes resume for all sections. Books, diaries and the term scheme of studies are issued on the first day.'),

('2026-27', 'Eid ul-Fitr holidays', '2026-03-20', '2026-03-22', 'holiday',
 'Subject to the moon sighting. Any change is announced by SMS to the guardian''s registered number.'),

('2026-27', 'Pakistan Day', '2026-03-23', NULL, 'holiday',
 'Assembly and a short commemoration on the last working day before the holiday.'),

('2026-27', 'First parent-teacher meeting', '2026-04-18', NULL, 'meeting',
 'Grades I to X, 9:00 AM to 12:30 PM. Subject teachers are available section by section; no appointment needed.'),

('2026-27', 'Labour Day', '2026-05-01', NULL, 'holiday', ''),

('2026-27', 'Eid ul-Adha holidays', '2026-05-27', '2026-05-29', 'holiday',
 'Subject to the moon sighting.'),

('2026-27', 'First term examinations', '2026-06-15', '2026-06-20', 'examination',
 'Written papers for all classes. The date sheet is issued two weeks in advance and pinned at the office.'),

('2026-27', 'Ashura', '2026-06-25', '2026-06-26', 'holiday',
 '9th and 10th Muharram. The campus is closed; dates follow the moon sighting.'),

('2026-27', 'First term results issued', '2026-06-30', NULL, 'deadline',
 'Progress reports are handed to guardians in person. Reports are not released to students.'),

('2026-27', 'Summer break', '2026-07-15', '2026-08-14', 'holiday',
 'Holiday work is set for Grades III and above and is marked in the first week back.'),

('2026-27', 'Second term begins', '2026-08-17', NULL, 'event', ''),

('2026-27', 'Eid Milad un-Nabi', '2026-08-25', NULL, 'holiday',
 'Subject to the moon sighting.'),

('2026-27', 'Board registration closes — Grades IX and XI', '2026-09-05', NULL, 'deadline',
 'Forms and fees for BISE Kohat must reach the office by this date. Late registration is not accepted by the board.'),

('2026-27', 'Second parent-teacher meeting', '2026-10-10', NULL, 'meeting',
 'Grades I to X, 9:00 AM to 12:30 PM.'),

('2026-27', 'Inter-house sports week', '2026-10-19', '2026-10-21', 'event',
 'Athletics, cricket and the house shield. Guardians are welcome on the final afternoon.'),

('2026-27', 'Iqbal Day', '2026-11-09', NULL, 'holiday', ''),

('2026-27', 'Annual examinations', '2026-12-01', '2026-12-10', 'examination',
 'Annual papers for Playgroup through Grade VIII. Board candidates sit send-up papers in the same window.'),

('2026-27', 'Annual results and prize distribution', '2026-12-14', NULL, 'event',
 'Results are read out and prizes given at the campus. Guardians of every prize-winner are invited by letter.'),

('2026-27', 'Winter break', '2026-12-21', '2027-02-28', 'holiday',
 'Kurram winters close the campus. Board candidates continue on a published revision timetable with on-call subject teachers.'),

('2026-27', 'Quaid-e-Azam Day', '2026-12-25', NULL, 'holiday', ''),

('2026-27', 'Kashmir Solidarity Day', '2027-02-05', NULL, 'holiday', ''),

('2026-27', 'Send-up examinations — board candidates', '2027-02-15', '2027-02-20', 'examination',
 'Grades X and XII only. A pass in the send-up is required before the school forwards a board admission form.');

-- ------------------------------------------------------------
-- The page itself joins Admin -> Pages like every other route
-- ------------------------------------------------------------
INSERT INTO pages (page_type, slug, route, title, lede, content, meta_description, status) VALUES
('system', 'sys-academic-calendar', '/academic-calendar',
 'Academic Calendar',
 'Every holiday, examination window, meeting and deadline of the session, with dates.',
 '',
 'The academic calendar of Kohsar School & College Parachinar: term dates, examination windows, holidays, parent-teacher meetings and board deadlines.',
 'published')
ON DUPLICATE KEY UPDATE title = VALUES(title);


-- ============================================================
-- SOURCE: 012_milestones.sql
-- ============================================================
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

USE pakistan_cambridge_school;

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


-- ============================================================
-- SOURCE: 013_fee_notes.sql
-- ============================================================
-- ============================================================
-- Kohsar — Fee page notes
--
-- The fee table itself already lives in `fee_structure`, but the two lists
-- beside it (Concessions, Payment) were written into the view, so the office
-- could not change them without a developer. They move into the database here.
--
-- `panel` decides which of the two boxes a line appears in, so a new note can
-- be added to either without a schema change.
--
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 013_fee_notes.sql
-- ============================================================

USE pakistan_cambridge_school;

CREATE TABLE IF NOT EXISTS fee_notes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    panel ENUM('concession','payment') NOT NULL DEFAULT 'concession',
    body VARCHAR(300) NOT NULL,
    sort_order INT NOT NULL DEFAULT 0,
    status ENUM('published','draft') NOT NULL DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_panel_sort (panel, sort_order)
) ENGINE=InnoDB;

INSERT INTO fee_notes (panel, body, sort_order) VALUES
('concession', 'Sibling concession from the second enrolled child.', 1),
('concession', 'Merit concession for top three positions in each class.', 2),
('concession', 'Hardship review for families in documented need — apply in writing to the Principal.', 3),
('payment', 'Challans issued on the 1st, payable by the 10th of each month.', 1),
('payment', 'Payment at the campus office or by bank transfer against the challan number.', 2),
('payment', 'A receipt SMS is sent to the guardian''s registered number on every payment.', 3);

-- fee_structure predates the admin modules and never got an updated_at column
ALTER TABLE fee_structure
    ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


-- ============================================================
-- SOURCE: 014_pcs_branding.sql
-- ============================================================
USE pakistan_cambridge_school;
UPDATE settings SET svalue='Pakistan Cambridge School' WHERE skey='site_name';
UPDATE settings SET svalue='پاکستان کیمبرج سکول، حافظ آباد' WHERE skey='site_name_ur';
UPDATE settings SET svalue='Learning Today. Leading Tomorrow.' WHERE skey='tagline';
UPDATE settings SET svalue='Hafizabad, Punjab, Pakistan' WHERE skey='address';
UPDATE settings SET svalue='' WHERE skey IN ('facebook','youtube','phone','email','whatsapp','map_embed');
UPDATE settings SET svalue='Building confident learners, responsible citizens and future leaders through purposeful education.' WHERE skey='mission';
UPDATE settings SET svalue='To create a learning community where academic excellence, character, confidence and leadership grow together.' WHERE skey='vision';
UPDATE settings SET svalue='Pakistan Cambridge School Hafizabad is committed to a balanced education that develops strong academic foundations, character and future-ready skills.' WHERE skey='footer_about';
DELETE FROM sliders;
INSERT INTO sliders (eyebrow,title,subtitle,cta_text,cta_link,sort_order,status) VALUES
('Pakistan Cambridge School • Hafizabad','Where ambition meets education.','A modern school community where academic excellence, character, confidence and leadership grow together.','Explore Our School','#about',1,'published'),
('Academic Pathways','Learning with purpose.','Structured pathways help students build knowledge step by step while developing the confidence to apply it.','Explore Academics','#academics',2,'published'),
('Student Life','Strong minds. Stronger character.','Sports, clubs, competitions and leadership experiences help students discover their strengths beyond the classroom.','Discover Student Life','#life',3,'published');


-- ============================================================
-- SOURCE: 015_pcs_pages_navigation.sql
-- ============================================================
-- Pakistan Cambridge School — Public Pages + Navigation
USE pakistan_cambridge_school;

UPDATE pages
SET title = REPLACE(REPLACE(REPLACE(title, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar School & College', 'Pakistan Cambridge School'), 'About Kohsar', 'About Pakistan Cambridge School'),
    lede = REPLACE(REPLACE(lede, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar', 'Pakistan Cambridge School'),
    content = REPLACE(REPLACE(REPLACE(content, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar School & College', 'Pakistan Cambridge School'), 'Kohsar', 'Pakistan Cambridge School'),
    meta_description = REPLACE(REPLACE(meta_description, 'Kohsar School & College Parachinar', 'Pakistan Cambridge School Hafizabad'), 'Kohsar School & College', 'Pakistan Cambridge School');

UPDATE pages SET show_in_menu = 1 WHERE status = 'published';

SET @main := (SELECT id FROM menus WHERE slug = 'main' LIMIT 1);
DELETE FROM menu_items WHERE menu_id = @main;

INSERT INTO menu_items (menu_id,label,link_type,route,sort_order,status) VALUES
(@main,'Home','route','/',10,'published'),
(@main,'About','none','',20,'published'),
(@main,'Academics','none','',30,'published'),
(@main,'Campus & Facilities','none','',40,'published'),
(@main,'News & Notices','route','/news',50,'published'),
(@main,'Admissions','route','/admissions',60,'published'),
(@main,'Downloads','route','/downloads',70,'published'),
(@main,'Contact','route','/contact',80,'published');

SET @about := (SELECT id FROM menu_items WHERE menu_id=@main AND label='About' ORDER BY id DESC LIMIT 1);
SET @academics := (SELECT id FROM menu_items WHERE menu_id=@main AND label='Academics' ORDER BY id DESC LIMIT 1);
SET @campus := (SELECT id FROM menu_items WHERE menu_id=@main AND label='Campus & Facilities' ORDER BY id DESC LIMIT 1);

INSERT INTO menu_items (menu_id,label,link_type,page_id,parent_id,sort_order,status)
SELECT @main,'About Us','page',id,@about,10,'published' FROM pages WHERE slug='about' LIMIT 1;
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Vision & Mission','route','/vision-mission',@about,20,'published');
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Leadership','route','/leadership',@about,30,'published');
INSERT INTO menu_items (menu_id,label,link_type,page_id,parent_id,sort_order,status)
SELECT @main,'Rules & Discipline','page',id,@about,40,'published' FROM pages WHERE slug='rules' LIMIT 1;

INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status) VALUES
(@main,'Academic Overview','route','/academics',@academics,10,'published'),
(@main,'Academic Programs','route','/programs',@academics,20,'published'),
(@main,'Academic Calendar','route','/academic-calendar',@academics,30,'published'),
(@main,'Fee Structure','route','/fees',@academics,40,'published');

INSERT INTO menu_items (menu_id,label,link_type,page_id,parent_id,sort_order,status)
SELECT @main,'Campus & Facilities','page',id,@campus,10,'published' FROM pages WHERE slug='facilities' LIMIT 1;
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Our Faculty','route','/faculty',@campus,20,'published');
INSERT INTO menu_items (menu_id,label,link_type,route,parent_id,sort_order,status)
VALUES (@main,'Gallery','route','/gallery',@campus,30,'published');
