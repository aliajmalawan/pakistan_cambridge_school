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

USE kohsarschool;

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
