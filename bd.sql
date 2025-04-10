CREATE DATABASE IF NOT EXISTS sistema_usuarios;

USE sistema_usuarios;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL, /*Colocar UNIQUE para evitar possiveis erros no codigo da aplicação que possam ser explorados. */
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL
    
);

-- Inserir um admin inicial (senha: admin123)
INSERT INTO usuarios (username, password, is_admin, created_at) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1, NOW()),
('Joao', '$2y$10$4AE0k7enDZbUIiz/RZPal.56.tZt26zcMU8xaPhLtbayaf.szjuse', 0, NOW()),
('Pedro', '$2y$10$4AE0k7enDZbUIiz/RZPal.56.tZt26zcMU8xaPhLtbayaf.szjuse', 0, NOW()),
('Tiago', '12345678', 0, NOW()),
('Maria', '$2y$10$4AE0k7enDZbUIiz/RZPal.56.tZt26zcMU8xaPhLtbayaf.szjuse', 0, NOW()),
('Maria', '12345678',0, NOW()),
('Leonardo', '$2y$10$4AE0k7enDZbUIiz/RZPal.56.tZt26zcMU8xaPhLtbayaf.szjuse',0, NOW());
