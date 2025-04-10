<?php
// config.php
// Configurações do banco de dados e da aplicação

// Configuração do banco de dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'sistema_usuarios');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações de segurança
define('MIN_PASSWORD_LENGTH', 8);

// Inicia a sessão
session_start();

// Função para conectar ao banco de dados
function getDBConnection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, 
            DB_USER, 
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
}

// Função para verificar se o usuário é admin
function isAdmin() {
    return isset($_SESSION['user']) && $_SESSION['user']['is_admin'] == 1;
}

// Função para redirecionar se não for admin
function redirectIfNotAdmin() {
    if (!isAdmin()) {
        header('Location: index.php');
        exit();
    }
}
?>