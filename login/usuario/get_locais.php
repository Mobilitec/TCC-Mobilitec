<?php
header('Content-Type: application/json; charset=utf-8');
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "projeto_login";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$sql = "SELECT name, latitude, longitude, Tipo, classi, id FROM locations";
$result = $conn->query($sql);

$locais = array();

if ($result->num_rows > 0) {
    // Adiciona os dados a um array
    while($row = $result->fetch_assoc()) {
        $locais[] = $row;
    }
} 

$conn->close();

// Retorna os dados em formato JSON
echo json_encode($locais);
?>
