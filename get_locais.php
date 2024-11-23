<?php
header('Content-Type: application/json; charset=utf-8');
$servername = "junction.proxy.rlwy.net:50991";
$username = "root";
$password = "BEHlCCBlqkLeHEwoILDZuPAmmyioRysc";
$dbname = "projeto_login";

// Cria a conexão
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn, "utf8");

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se há um parâmetro de filtro (Tipo) na URL
$filter = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if (!empty($filter)) {
    // Se houver um filtro, ajusta a consulta para filtrar por Tipo
    $sql = "SELECT name, latitude, longitude, Tipo, classi, id FROM locations WHERE Tipo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    // Se não houver filtro, seleciona todos os registros
    $sql = "SELECT name, latitude, longitude, Tipo, classi, id FROM locations";
    $result = $conn->query($sql);
}

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
