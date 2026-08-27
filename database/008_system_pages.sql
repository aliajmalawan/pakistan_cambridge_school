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

USE kohsarschool;

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
