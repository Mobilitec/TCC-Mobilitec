<?php
// save_location.php

include 'db_cofing.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];

    // Use cURL to get coordinates
    $apiKey = "AIzaSyC-wNM6Ocy6qxx0aFLOVoJ-2co7l_4I4j8";
    $formattedAddress = urlencode($address);
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$formattedAddress}&key=AIzaSyC-wNM6Ocy6qxx0aFLOVoJ-2co7l_4I4j8";

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);

    if ($response !== false) {
        $data = json_decode($response);

        if ($data->status == "OK") {
            $latitude = $data->results[0]->geometry->location->lat;
            $longitude = $data->results[0]->geometry->location->lng;

            // Insert data into the database using prepared statements
            $sql = "INSERT INTO locations (name, address, latitude, longitude) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssdd", $name, $address, $latitude, $longitude);

            if ($stmt->execute()) {
                echo "Local salvo com sucesso.";
            } else {
                echo "Erro em salvar o local. " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "falha ao salvar o local.";
        }
    } else {
        echo "Error fetching data from Google Maps API: " . curl_error($curl);
    }

    curl_close($curl);
}

$conn->close();
?>
