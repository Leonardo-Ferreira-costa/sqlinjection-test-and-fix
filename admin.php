<?php
require_once 'config.php';
redirectIfNotAdmin();

// Processar criação de usuário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;
    
    // Validações
    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        $error = "A senha deve ter pelo menos " . MIN_PASSWORD_LENGTH . " caracteres.";
    } else {
        try {
            $pdo = getDBConnection();
            
            // Verifica se o usuário já existe
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->fetch()) {
                $error = "Nome de usuário já está em uso.";
            } else {
                // Cria hash da senha
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                // Insere o novo usuário
                $stmt = $pdo->prepare(
                    "INSERT INTO usuarios (username, password, is_admin, created_at) 
                     VALUES (?, ?, ?, NOW())"
                );
                $stmt->execute([$username, $password_hash, $is_admin]);
                
                $success = "Usuário criado com sucesso!";
            }
        } catch (PDOException $e) {
            $error = "Erro ao criar usuário. Tente novamente.";
            error_log("Create user error: " . $e->getMessage());
        }
    }
}

// Listar usuários existentes
try {
    $pdo = getDBConnection();
    $users = $pdo->query("SELECT id, username, is_admin, created_at FROM usuarios ORDER BY created_at DESC")->fetchAll();
} catch (PDOException $e) {
    $error = "Erro ao carregar usuários.";
    error_log("List users error: " . $e->getMessage());
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .error { color: red; }
        .success { color: green; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        form { margin-bottom: 20px; background: #f9f9f9; padding: 20px; border-radius: 5px; }
        input, select { padding: 8px; margin: 5px 0; width: 100%; box-sizing: border-box; }
        button { background-color: #4CAF50; color: white; padding: 10px; border: none; cursor: pointer; }
        .logout { float: right; }
    </style>
</head>
<body>
    <h1>Painel Administrativo</h1>
    <a href="logout.php" class="logout">Sair</a>
    
    <?php if (isset($error)): ?>
        <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($success)): ?>
        <div class="success"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>
    
    <h2>Criar Novo Usuário</h2>
    <form method="POST">
        <input type="hidden" name="create_user" value="1">
        
        <label for="username">Nome de Usuário:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Senha (mínimo <?php echo MIN_PASSWORD_LENGTH; ?> caracteres):</label>
        <input type="password" id="password" name="password" required>
        
        <label>
            <input type="checkbox" name="is_admin" value="1"> Administrador
        </label>
        
        <button type="submit">Criar Usuário</button>
    </form>
    
    <h2>Usuários Existentes</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome de Usuário</th>
                <th>Tipo</th>
                <th>Data de Criação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo $user['is_admin'] ? 'Administrador' : 'Usuário'; ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>