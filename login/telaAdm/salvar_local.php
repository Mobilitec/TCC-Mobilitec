<?php
// save_location.php

include 'db_cofing.php'; // Certifique-se de que o nome do arquivo está correto

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $type = $_POST['type'];
    $classification = $_POST['classification'];
    $features = isset($_POST['features']) ? implode(', ', $_POST['features']) : '';

    // Use cURL to get coordinates
    $apiKey = "AIzaSyC-wNM6Ocy6qxx0aFLOVoJ-2co7l_4I4j8";
    $formattedAddress = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$formattedAddress}&key={$apiKey}";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if ($response !== false) {
        $data = json_decode($response);

        if ($data->status == "OK") {
            $latitude = $data->results[0]->geometry->location->lat;
            $longitude = $data->results[0]->geometry->location->lng;

            // Insert data into the database using prepared statements
            $sql = "INSERT INTO locations (name, address, latitude, longitude, tipo, classi, itens) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            

            if ($stmt === false) {
                die("Erro na preparação da consulta: " . $conn->error);
            }

            $stmt->bind_param("ssddsss", $name, $address, $latitude, $longitude, $type, $classification, $features);

            if ($stmt->execute()) {
                echo "<script>alert('Local salvo com sucesso.'); window.location.href = 'addlocal.html'; </script>";
            } else {
                echo "Erro ao salvar o local: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Falha ao buscar coordenadas: " . $data->status;
        }
    } else {
        echo "Erro ao buscar dados da API do Google Maps: " . curl_error($curl);
    }

    curl_close($curl);
}

$conn->close();
?>
