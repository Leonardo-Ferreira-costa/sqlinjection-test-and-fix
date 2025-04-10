<?php
$senha = 'admin123';
$hash = password_hash($senha, PASSWORD_DEFAULT);
echo "Hash gerado: " . $hash;

// Teste de verificação
echo "\nVerificação: " . (password_verify($senha, $hash) ? 'OK' : 'Falha');

echo "\n O hash gerado é único e não se repte."

?>




