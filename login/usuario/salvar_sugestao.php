<?php
// Conectar ao banco de dados (substitua com suas credenciais)
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "projeto_login";
$conn = new mysqli($servername, $username, $password, $dbname);

// Definir charset para a conexão
$conn->set_charset("utf8");

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Receber os dados do formulário
$nome = $_POST['nome'];
$endereco = $_POST['endereco'];
$numero = $_POST['numero'];
$descricao = $_POST['descricao'];

// Preparar e executar a inserção dos dados na tabela 'sugestoes'
$stmt = $conn->prepare("INSERT INTO sugestoes (nome, endereco, numero, descricao) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nome, $endereco, $numero, $descricao);

if ($stmt->execute() === TRUE) {
    // Codificar a mensagem para JSON para evitar problemas com caracteres especiais
    $mensagem = json_encode("Sugestão salva com sucesso.");
    echo "<script>var msg = $mensagem; alert(msg); window.location.href = 'Suges.php';</script>";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
