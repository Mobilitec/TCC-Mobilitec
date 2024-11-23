<?php
$host = 'junction.proxy.rlwy.net'; // Host fornecido pelo Railway
$port = '50991';                  // Porta fornecida
$dbname = 'nome-do-seu-banco';     // Nome do banco de dados
$user = 'seu-usuario';             // Usuário fornecido
$password = 'sua-senha';           // Senha fornecida

try {
    // Conexão para MySQL
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password);
    
    // Caso seja PostgreSQL, use:
    // $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexão bem-sucedida!";
} catch (PDOException $e) {
    die("Erro ao conectar: " . $e->getMessage());
}
?>
