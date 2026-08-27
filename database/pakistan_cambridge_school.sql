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
