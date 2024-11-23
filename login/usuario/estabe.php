<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobiliTec - Página Inicial do Usuário</title>

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC-wNM6Ocy6qxx0aFLOVoJ-2co7l_4I4j8&callback=initMap" async defer></script>

    <link rel="stylesheet" href="style.css" />
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link rel="shortcut icon" href="assets/ace.png">

    <style>
        body {
            margin: 0;
            font-family: 'Sora', sans-serif;
            display: flex;
            flex-direction: column;
        }

        .sidebar {
            height: 100vh;
            width: 250px;
            background-color: #333;
            padding-top: 20px;
            position: fixed;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            font-size: 18px;
            color: white;
            display: block;
            transition: 0.3s;
        }

        .sidebar a:hover {
            background-color: #575757;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            background-color: #f0f0f0;
        }

        .header {
            background-color: #1e90ff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .content h2 {
            color: #333;
        }

        /* Additional Styles for MobiliTec */
        #locais {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 5px;
            margin: 0;
            list-style: none;
            width: 100%;
        }

        #locais li {
            margin: 5px 0;
            cursor: pointer;
        }

        #map {
            width: 100%;
            height: 400px;
        }

        .QuemSomos {
            max-width: 70%;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        .QuemSomos h2 {
            color: #06b6d4;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .QuemSomos p, .QuemSomos ul li {
            color: #333;
            margin-bottom: 20px;
        }

        .services {
            background-color: #06121e;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 54px 14px;
        }

        .services-content {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            align-items: center;
        }

        .haircuts {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .haircut {
            margin: 10px;
            text-align: center;
        }

        .haircut-info strong {
            display: block;
            margin-top: 10px;
            color: #06b6d4;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .sidebar a {
                float: left;
                padding: 10px;
                text-align: center;
                width: 100%;
            }

            .main-content {
                margin-left: 0;
                padding: 10px;
            }

            .header {
                padding: 10px;
            }

            .content {
                padding: 10px;
            }

            .QuemSomos {
                max-width: 90%;
                padding: 10px;
            }
        }

        @media (max-width: 480px) {
            .header {
                font-size: 18px;
            }

            .sidebar a {
                font-size: 16px;
            }

            .content h2 {
                font-size: 16px;
            }

            #map {
                height: 300px;
            }

            .QuemSomos {
                padding: 5px;
            }

            .QuemSomos h2 {
                font-size: 18px;
            }

            .QuemSomos p, .QuemSomos ul li {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="index.php">Home</a>
        <a href="#">Estabelecimentos</a>
        <a href="#favoritos">Favoritos</a>
        <a href="Suges.php">Sugestões</a>
        <a href="#sair">Sair</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Estabelecimentos</h1>
        </div>

        <div class="content">
            <p>Esta é a sua página de Estabelecimentos.</p>
            <div class="container about-content">
                <div data-aos="zoom-in" data-aos-delay="250">
                    <h2>Locais</h2>
                    <ul id="locais"></ul>
                </div>

                <div class="about-description" data-aos="zoom-out-left" data-aos-delay="250">
                    <h2>Mapa</h2>
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        AOS.init();

        function initMap() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    const userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map = new google.maps.Map(document.getElementById("map"), {
                        center: userLocation,
                        zoom: 15,
                    });

                    new google.maps.Marker({
                        position: userLocation,
                        map: map,
                        title: "Sua localização",
                        icon: {
                            url: 'img/iconusserr.png',
                            scaledSize: new google.maps.Size(32, 32)
                        }
                    });

                    fetchLocais();
                }, () => {
                    defaultMap();
                });
            } else {
                defaultMap();
            }
        }

        function defaultMap() {
            const defaultLocation = { lat: -23.1171, lng: -46.5702 };
            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 15,
            });

            fetchLocais();
        }

        function fetchLocais() {
            fetch('get_locais.php')
                .then(response => response.json())
                .then(locais => {
                    locais.forEach(local => {
                        let iconUrl;
                        switch (local.classi) {
                            case 'boa':
                                iconUrl = 'img/iconloca.jpg';
                                break;
                            case 'regular':
                                iconUrl = 'img/iconlocai.png';
                                break;
                            case 'ruim':
                                iconUrl = 'img/iconlocalll.png';
                                break;
                            default:
                                iconUrl = 'img/iconlocai.png';
                        }

                        const marker = new google.maps.Marker({
                            position: { lat: parseFloat(local.latitude), lng: parseFloat(local.longitude) },
                            map: map,
                            title: local.name,
                            icon: {
                                url: iconUrl,
                                scaledSize: new google.maps.Size(32, 32)
                            }
                        });

                        marker.addListener('click', () => {
                            window.location.href = `local_details.php?id=${local.id}`;
                        });

                        const listItem = document.createElement("li");
                        listItem.textContent = local.name;
                        listItem.addEventListener("click", () => {
                            map.setCenter(marker.getPosition());
                            map.setZoom(17);
                            window.location.href = `local_details.php?id=${local.id}`;
                        });

                        document.getElementById("locais").appendChild(listItem);
                    });
                })
                .catch(error => console.error('Erro ao carregar os locais:', error));
        }
    </script>
</body>
</html>
