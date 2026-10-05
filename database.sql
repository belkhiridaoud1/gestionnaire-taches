CREATE DATABASE IF NOT EXISTS gestionnaire_taches
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE gestionnaire_taches;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    status ENUM('À faire', 'En cours', 'Terminée') DEFAULT 'À faire',
    priority ENUM('Basse', 'Moyenne', 'Haute') DEFAULT 'Moyenne',
    due_date DATE NULL,
    category_id INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_tasks_category
        FOREIGN KEY (category_id)
        REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
) ENGINE=InnoDB;

INSERT INTO categories (name) VALUES
('Études'),
('Travail'),
('Personnel'),
('Projet')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO tasks
(title, description, status, priority, due_date, category_id)
VALUES
(
    'Préparer le projet GitHub',
    'Finaliser le projet et préparer le repository GitHub.',
    'En cours',
    'Haute',
    DATE_ADD(CURDATE(), INTERVAL 3 DAY),
    (SELECT id FROM categories WHERE name = 'Projet')
),
(
    'Réviser PHP',
    'Réviser PHP et PDO.',
    'À faire',
    'Moyenne',
    DATE_ADD(CURDATE(), INTERVAL 5 DAY),
    (SELECT id FROM categories WHERE name = 'Études')
),
(
    'Mettre à jour le CV',
    'Ajouter les nouveaux projets au CV.',
    'Terminée',
    'Haute',
    CURDATE(),
    (SELECT id FROM categories WHERE name = 'Travail')
);