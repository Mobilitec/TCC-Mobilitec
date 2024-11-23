<?php
// save_location.php

include 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];

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
            echo "Falha ao salvar o local.";
        }
    } else {
        echo "Erro ao buscar dados da API do Google Maps: " . curl_error($curl);
    }

    curl_close($curl);
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Geocoding Service</title>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script>
        let map;
        let marker;
        let geocoder;
        let responseDiv;
        let response;

        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 8,
                center: { lat: -34.397, lng: 150.644 },
                mapTypeControl: false,
            });
            geocoder = new google.maps.Geocoder();

            const inputText = document.createElement("input");
            inputText.type = "text";
            inputText.id = "address";
            inputText.name = "address";
            inputText.placeholder = "Enter a location";

            const submitButton = document.createElement("input");
            submitButton.type = "button";
            submitButton.value = "Geocode";
            submitButton.classList.add("button", "button-primary");

            const clearButton = document.createElement("input");
            clearButton.type = "button";
            clearButton.value = "Clear";
            clearButton.classList.add("button", "button-secondary");

            const form = document.createElement("form");
            form.method = "POST";
            form.action = "save_location.php";

            const nameInput = document.createElement("input");
            nameInput.type = "text";
            nameInput.name = "name";
            nameInput.placeholder = "Enter a name";

            const latInput = document.createElement("input");
            latInput.type = "hidden";
            latInput.name = "latitude";
            latInput.id = "latitude";

            const lngInput = document.createElement("input");
            lngInput.type = "hidden";
            lngInput.name = "longitude";
            lngInput.id = "longitude";

            form.appendChild(nameInput);
            form.appendChild(inputText);
            form.appendChild(latInput);
            form.appendChild(lngInput);
            form.appendChild(submitButton);
            form.appendChild(clearButton);

            document.body.appendChild(form);

            marker = new google.maps.Marker({
                map,
            });

            map.addListener("click", (e) => {
                geocode({ location: e.latLng });
            });

            submitButton.addEventListener("click", () =>
                geocode({ address: inputText.value })
            );

            clearButton.addEventListener("click", () => {
                clear();
            });

            clear();
        }

        function clear() {
            marker.setMap(null);
        }

        function geocode(request) {
            clear();
            geocoder
                .geocode(request)
                .then((result) => {
                    const { results } = result;

                    map.setCenter(results[0].geometry.location);
                    marker.setPosition(results[0].geometry.location);
                    marker.setMap(map);

                    document.getElementById("latitude").value = results[0].geometry.location.lat();
                    document.getElementById("longitude").value = results[0].geometry.location.lng();
                    document.getElementById("address").value = results[0].formatted_address;
                })
                .catch((e) => {
                    alert("Geocode was not successful for the following reason: " + e);
                });
        }

        window.initMap = initMap;
    </script>
    <style>
        #map {
            height: 100%;
        }
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        input[type="text"], input[type="button"] {
            background-color: #fff;
            border: 0;
            border-radius: 2px;
            box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
            margin: 10px;
            padding: 0 0.5em;
            font: 400 18px Roboto, Arial, sans-serif;
            overflow: hidden;
            line-height: 40px;
            min-width: 25%;
        }
        input[type="button"] {
            height: 40px;
            cursor: pointer;
        }
        input[type="button"]:hover {
            background: rgb(235, 235, 235);
        }
        input[type="button"].button-primary {
            background-color: #1a73e8;
            color: white;
        }
        input[type="button"].button-primary:hover {
            background-color: #1765cc;
        }
        input[type="button"].button-secondary {
            background-color: white;
            color: #1a73e8;
        }
        input[type="button"].button-secondary:hover {
            background-color: #d2e3fc;
        }
    </style>
</head>
<body>
    <div id="map"></div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-wNM6Ocy6qxx0aFLOVoJ-2co7l_4I4j8&callback=initMap" defer></script>
</body>
</html>
