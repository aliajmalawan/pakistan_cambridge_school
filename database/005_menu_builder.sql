-- ============================================================
-- Kohsar — Menu Builder
-- Upgrades the single navigation list into named menus (header +
-- footer columns) with drag-and-drop ordering and nesting.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 005_menu_builder.sql
-- ============================================================

USE kohsarschool;

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
