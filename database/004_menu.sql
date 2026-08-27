-- ============================================================
-- Kohsar — Editable navigation menu
-- Replaces the hardcoded navbar links with database-driven items.
-- Import: mysql -u root --default-character-set=utf8mb4 kohsarschool < 004_menu.sql
-- ============================================================

USE kohsarschool;

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
