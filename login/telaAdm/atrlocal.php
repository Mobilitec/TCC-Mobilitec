<?php
include 'db_cofing.php'; // Certifique-se de que o nome do arquivo de configuração do banco de dados está correto

$name = '';
$address = '';
$type = '';
$classi = '';
$id = '';
$features = isset($_POST['features']) ? implode(', ', $_POST['features']) : '';
$itens= '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $type = $_POST['type'];
    $classi = $_POST['classi'];
    $id = $_POST['id'];
    $itens= $_POST['itens'];
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
            $sql = "UPDATE locations SET name=?, address=?, latitude=?, longitude=?, Tipo=?, classi=?, itens=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssddsssi", $name, $address, $latitude, $longitude, $type, $classi, $features, $id);

            if ($stmt->execute()) {
                echo "<script>alert('Local atualizado com sucesso.'); window.location.href = 'excluilocal.php';</script>";
            } else {
                echo "Erro ao atualizar o local: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Falha ao atualizar o local: status da API não é OK.";
        }
    } else {
        echo "Erro ao buscar dados da API do Google Maps: " . curl_error($curl);
    }

    curl_close($curl);
   } else if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch data from database
    $sql = "SELECT name, address, Tipo, classi, itens FROM locations WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($name, $address, $type, $classi, $itens);
    $stmt->fetch();
    $stmt->close();
}


$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#06b6d4" />
  <link rel="shortcut icon" href="img/ace.png">
  <link rel="stylesheet" href="style.css" />
  <title>Painel de administração</title>
  <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-wNM6Ocy6qxx0aFLOVoJ-2co7l_4I4j8&callback=initMap" defer></script>
  <script>
    let map;
    let marker;
    let geocoder;

    function initMap() {
      map = new google.maps.Map(document.getElementById("map"), {
        zoom: 15,
        center: { lat: -23.1171, lng: -46.5502 },
        mapTypeControl: false,
      });
      geocoder = new google.maps.Geocoder();
      const inputText = document.createElement("input");
inputText.type = "text";
inputText.placeholder = "Pesquisar Endereço";
inputText.id = "map-search";  // ID para o input de texto

const submitButton = document.createElement("input");
submitButton.type = "button";
submitButton.value = "Pesquisar";
submitButton.id = "map-search-submit";  // ID para o botão de pesquisa
submitButton.classList.add("button", "button-primary");

const clearButton = document.createElement("input");
clearButton.type = "button";
clearButton.value = "Limpar";
clearButton.id = "map-search-clear";  // ID para o botão de limpar
clearButton.classList.add("button", "button-secondary");

      map.controls[google.maps.ControlPosition.TOP_LEFT].push(inputText);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(submitButton);
      map.controls[google.maps.ControlPosition.TOP_LEFT].push(clearButton);

      submitButton.addEventListener("click", () => {
        geocode({ address: inputText.value });
      });

      clearButton.addEventListener("click", () => {
        clear();
      });

      const updateButton = document.getElementById("update-button");
      updateButton.addEventListener("click", () => {
        if (marker) {
          document.getElementById("latitude").value = marker.getPosition().lat();
          document.getElementById("longitude").value = marker.getPosition().lng();
        }
      });

      map.addListener("click", (e) => {
        geocode({ location: e.latLng });
      });
    }

    function clear() {
      marker.setMap(null);
      document.getElementById("map-search").value = "";
    }

    function geocode(request) {
      clear();

      geocoder
        .geocode(request)
        .then((result) => {
          const { results } = result;

          map.setCenter(results[0].geometry.location);
          marker = new google.maps.Marker({
            map,
            position: results[0].geometry.location,
            draggable: true,
          });

          return results;
        })
        .catch((e) =>
          alert("Geocode was not successful for the following reason: " + e)
        );
    }
  </script>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    #content {
      display: flex;
      min-height: 100vh;
    }

    .logo h1 {
      margin: 0;
      font-size: 24px;
    }

    .menu {
      list-style: none;
      padding: 0;
      margin: 20px 0 0 0;
    }

    .menu li {
      margin-bottom: 15px;
    }

    .menu a {
      color: white;
      text-decoration: none;
      font-size: 18px;
      display: flex;
      align-items: center;
    }

    .menu a i {
      margin-right: 10px;
    }

    main {
      flex: 1;
      padding: 20px;
    }

    #map {
      height: 580px;
      width: 100%;
      border-radius: 4px;
      margin-bottom: 20px;
    }
 #map-search-clear > input{
  background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
      margin: 10px 0;
      padding: 10px;
      font-size: 16px;
      width: 50%;


 }
    input[type="text"],select, button {
      background-color: #fff;
      border: 1px solid #ccc;
      border-radius: 4px;
      box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
      margin: 10px 0;
      padding: 10px;
      font-size: 16px;
      width: 100%;
    }

    button {
      background-color: #23497c;
      color: white;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #1a2f4f;
    }

    .button-primary {
      background-color: #1a73e8;
      color: white;
    }

    .button-primary:hover {
      background-color: #1765cc;
    }

    .button-secondary {
      background-color: white;
      color: #1a73e8;
    }

    .button-secondary:hover {
      background-color: #d2e3fc;
    }

    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: bold;
    }

    form {
      max-width: 600px;
      margin: auto;
      padding: 20px;
      background-color: #f9f9f9;
      border-radius: 4px;
      box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.1);
    }

    h2 {
      margin-bottom: 20px;
    }
    .checkbox-group {
      border: 1px solid #ccc;
      border-radius: 4px;
      padding: 10px;
      background-color: #f9f9f9;
    }

    .checkbox-group label {
      display: block;
      margin-bottom: 8px;
    }

    .checkbox-group input[type="checkbox"] {
      margin-right: 10px;
    }
  </style>
</head>
<body>
  <div id="content">
    <aside>
      <div class="logo">
        <h1>Painel</h1>
      </div>
      <ul class="menu">
        <li>
          <a href="index.html"><i class="bi bi-house"></i>Início</a>
        </li>
        <li>
          <a href="chat.php"><i class="bi bi-chat"></i>Sugestões</a>
        </li>
        <li>
          <a href="addlocal.html"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-building-add" viewBox="0 0 16 16">
              <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0"/>
              <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z"/>
              <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
            </svg>Adicionar local</a>
        </li>
        <li>
          <a href="excluilocal.php"><i class="bi bi-map"></i>Alterar local</a>
        </li>
      </ul>
    </aside>
    <main>
      <section class="anuncios">
        <div id="map"></div>
      </section>
      <section class="em-alta">
        <center>
          <h2>Atualizar local</h2>
        </center>
        <div>
          <form action="atrlocal.php" method="post">
            <center>
              <label for="name">Nome do local:</label>
              <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
              <label for="address">Endereço:</label>
              <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($address); ?>" required>
              
              <label for="type">Tipo de estabelecimento:</label>
              <select name="type" id="type" required>
                <option value="">Selecione o tipo</option>
                <option value="restaurante" <?php if ($type == 'restaurante') echo 'selected'; ?>>Restaurante</option>
                <option value="escola" <?php if ($type == 'escola') echo 'selected'; ?>>Escola</option>
                <option value="mercado" <?php if ($type == 'mercado') echo 'selected'; ?>>Mercado</option>
                <option value="farmacia" <?php if ($type == 'farmacia') echo 'selected'; ?>>Farmácia</option>
                <option value="Comercios"<?php if ($type == 'Comercios') echo 'selected'; ?>>Comercios</option>
                <option value="lazer"<?php if ($type == 'lazer') echo 'selected'; ?>>Areas de lazer</option>
              </select>

              <label for="features">Itens de Acessibilidade do local:</label>
              <div class="checkbox-group">
                <label><input type="checkbox" name="features[]" value="Elevador" <?php if (strpos($itens, 'Elevador') !== false) echo 'checked' ?>>Elevador</label>                
                <label><input type="checkbox" name="features[]" value="Estacionamento" <?php if (strpos($itens, 'Estacionamento') !== false) echo 'checked' ?>>Vaga de estacionamento</label>
                <label><input type="checkbox" name="features[]" value="Rampa"  <?php if (strpos($itens, 'Rampa') !== false) echo 'checked' ?>>Rampa</label>
                <label><input type="checkbox" name="features[]" value="Banheiro"  <?php if (strpos($itens, 'Banheiro') !== false) echo 'checked' ?>>Banheiro</label>
              </div>

              <label for="classi">Classificação:</label>
              <select name="classi" id="classi" required>
                <option value="">Selecione a classificação</option>
                <option value="boa" <?php if ($classi == 'boa') echo 'selected'; ?>>Boa</option>
                <option value="regular" <?php if ($classi == 'regular') echo 'selected'; ?>>Regular</option>
                <option value="ruim" <?php if ($classi == 'ruim') echo 'selected'; ?>>Ruim</option>
              </select>
              
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
              <button type="submit">Atualizar</button>
            </center>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
