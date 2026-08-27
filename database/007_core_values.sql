-- ============================================================
-- Kohsar — Core values for the Vision & Mission page
-- Mission and vision themselves live in `settings` so they stay a single
-- source of truth for the homepage and this page alike.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 007_core_values.sql
-- ============================================================

USE kohsarschool;

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
