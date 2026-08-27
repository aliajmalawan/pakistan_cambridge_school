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

USE kohsarschool;

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
