<?php

$servername = "junction.proxy.rlwy.net:50991";
$username = "root";
$password = "BEHlCCBlqkLeHEwoILDZuPAmmyioRysc";
$dbname = "projeto_login";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
?>
