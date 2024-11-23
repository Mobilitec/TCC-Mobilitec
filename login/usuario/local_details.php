<?php
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

// Verifica se o id foi passado na URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Prepara a declaração SQL para prevenir SQL Injection
    $stmt = $conn->prepare("SELECT name, address, latitude, longitude, classi, itens FROM locations WHERE id = ?");
    if (!$stmt) {
        die("Erro na preparação da declaração SQL: " . $conn->error);
    }

    $stmt->bind_param("i", $id);
    if (!$stmt->execute()) {
        die("Erro na execução da declaração SQL: " . $stmt->error);
    }

    $stmt->bind_result($name, $address, $latitude, $longitude, $classi, $itens);
    if (!$stmt->fetch()) {
        echo "Nenhum local encontrado com esse ID.";
        exit;
    }

    $stmt->close();
} else {
    echo "ID do local não fornecido.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Local</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
        }
        .container1 {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 8px;
            font-family: 'Sora', sans-serif;
        }

        h1 {
            color: #06b6d4;
            margin-bottom: 20px;
        }

        p {
            color: #333;
            line-height: 1.6;
        }

        .map-container {
            width: 100%;
            height: 400px;
            margin-top: 20px;
        }

        .directions-form {
            margin-top: 20px;
        }

        .directions-button {
            padding: 10px 20px;
            background-color: #06b6d4;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .directions-button:hover {
            background-color: #0595b9;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-wNM6Ocy6qxx0aFLOVoJ-2co7l_4I4j8&libraries=places"></script>
    <script>
        let map, directionsService, directionsRenderer, userMarker, routePolyline;

        function initMap() {
            const destination = { lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?> };
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 15,
                center: destination,
            });

            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer();
            directionsRenderer.setMap(map);

            new google.maps.Marker({
                position: destination,
                map: map,
                title: "<?php echo $name; ?>",
            });

            if (navigator.geolocation) {
                navigator.geolocation.watchPosition(position => {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    if (!userMarker) {
                        userMarker = new google.maps.Marker({
                            position: userLocation,
                            map: map,
                            title: "Sua Localização",
                            icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
                        });
                    } else {
                        userMarker.setPosition(userLocation);
                    }

                    if (!routePolyline) {
                        routePolyline = new google.maps.Polyline({
                            path: [userLocation],
                            geodesic: true,
                            strokeColor: '#FF0000',
                            strokeOpacity: 1.0,
                            strokeWeight: 2,
                            map: map
                        });
                    } else {
                        const path = routePolyline.getPath();
                        path.push(userLocation);
                    }

                    calculateAndDisplayRoute(userLocation);
                }, () => {
                    alert('Não foi possível obter a localização do usuário.');
                });
            } else {
                alert('Geolocalização não é suportada por este navegador.');
            }
        }

        function calculateAndDisplayRoute(userLocation) {
            const destination = { lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?> };

            directionsService.route({
                origin: userLocation,
                destination: destination,
                travelMode: 'DRIVING',
                provideRouteAlternatives: false
            }, (response, status) => {
                if (status === 'OK') {
                    directionsRenderer.setDirections(response);
                } else {
                    console.error('Erro ao calcular a rota: ' + status);
                    alert('Não foi possível calcular a rota. Verifique a chave da API ou permissões.');
                }
            });
        }
    </script>
</head>

<body onload="initMap()">
    <br>
<div class="container1">
<a class="header-button" href="index.php">Voltar</a>
</div>
    <div class="container">
        <h1><?php echo htmlspecialchars($name); ?></h1>
        <p><strong>Endereço:</strong> <?php echo htmlspecialchars($address); ?></p>
        <p><strong>Classificação:</strong> <?php echo htmlspecialchars($classi); ?></p>
        <p><strong>Itens de acessibilidade:</strong> <?php echo htmlspecialchars($itens); ?></p>
        <div id="map" class="map-container"></div>
    </div>
</body>

</html>
