<?php
// login_vulneravel.php
// Página com vulnerabilidade de SQL Injection

// Conexão com o banco de dados
require_once 'config.php';


// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];  // Recebe diretamente do POST sem filtro
    $password = $_POST['password'];  // Recebe diretamente do POST sem filtro

$pdo = getDBConnection();

// Consulta EXTREMAMENTE vulnerável a SQL Injection
$sql = "SELECT * FROM usuarios WHERE username = '$username' AND password = '$password'";
$stmt = $pdo->query($sql);  // Usando query() direto em vez de prepare()
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    echo "Login bem sucedido!";
    $_SESSION['user'] = $user;
    header('Location: ' . ($user['is_admin'] ? 'admin.php' : 'index.php'));
    exit();
} else {
    echo "Usuário ou senha incorretos!";
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login (Vulnerável)</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
        .error { color: red; margin-bottom: 15px; }
        input { width: 100%; padding: 8px; margin: 8px 0; box-sizing: border-box; }
        button { background-color: #4CAF50; color: white; padding: 10px; border: none; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <label for="username">Usuário:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Senha:</label>
        <input type="password" id="password" name="password" required>
        
        <button type="submit">Entrar</button>
    </form>
</body>
</html>