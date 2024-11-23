<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
  <title>MobiliTec</title>
  <style>
  /* Estilo para tornar a lista de locais rolável */
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
    height: 50vh; /* A altura do mapa ocupará 50% da altura da tela */
    min-height: 300px; /* Define uma altura mínima para o mapa */
  }

  .container {
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
  }

  header,
  footer {
    background: #000;
    color: #fff;
    text-align: center;
    padding: 10px 0;
  }

  .header-content,
  .about-content,
  .services-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
  }

  .header-content {
    flex-direction: column;
  }

  .header-icons a,
  .footer-icons a {
    color: #fff;
    margin: 0 10px;
  }

  .hero {
    text-align: center;
    padding: 20px 0;
  }

  .QuemSomos {
    max-width: 90%;
    margin: 0 auto;
    padding: 20px;
    font-family: 'Sora', sans-serif;
    line-height: 1.6;
    background-color: #f9f9f9;
    border-radius: 8px;
  }

  .services {
    background-color: #06121e;
    color: #232224;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 54px 14px;
  }

  .QuemSomos h2 {
    color: #06b6d4;
    padding-bottom: 5px;
    margin-bottom: 10px;
  }

  .QuemSomos p {
    color: #333;
    margin-bottom: 20px;
  }

  .QuemSomos ul {
    list-style-type: none;
    padding-left: 0;
  }

  .QuemSomos ul li {
    margin-bottom: 10px;
    color: #333;
  }

  .about {
    display: flex;
    flex-direction: column;
  }

  .about-content > div {
    margin: 10px;
    flex-basis: 100%; /* Assegura que o conteúdo ocupe 100% da largura em telas menores */
  }

  /* Estilo do filtro */
  .filtro-select {
    background-color: #4e4545;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 10px;
    width: 100%;
  }

  /* Ajustes de responsividade para telas menores */
  @media (min-width: 350px) {
    .about-content {
      flex-direction: row;
    }

    .about-content > div {
      flex-basis: 48%;
    }

    #map {
      height: 300px; /* Aumenta a altura em telas maiores */
    }
  }

  @media (max-width: 350px) {
    .hero h1 {
      font-size: 1.5em;
    }

    .hero h2 {
      font-size: 1.2em;
    }

    #map {
      height: 60vh; /* O mapa ocupará 60% da altura da tela em dispositivos menores */
    }
  }
</style>

</head>

<body>
  <script>
    let map;

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

    function fetchLocais(filter = '') {
      const url = filter ? `get_locais.php?tipo=${filter}` : 'get_locais.php';

      fetch(url)
        .then(response => response.json())
        .then(locais => {
          const locaisList = document.getElementById("locais");
          locaisList.innerHTML = ''; // Limpa a lista antes de adicionar os novos itens

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

            locaisList.appendChild(listItem);
          });
        })
        .catch(error => console.error('Erro ao carregar os locais:', error));
    }

    function onFilterChange() {
      const filterSelect = document.getElementById('filtro-select');
      const selectedFilter = filterSelect.value;
      fetchLocais(selectedFilter);
    }
  </script>

  <!-- HEADER -->
  <div class="bg-home">
    <header>
      <nav class="header-content container">
        <div class="header-icons" data-aos="fade-down">
        
        </div>

        <div class="header-logo" data-aos="fade-up" data-aos-delay="300">
          <img data-aos="flip-up" data-aos-delay="300" data-aos-duration="1500" src="assets/ace.png" />
        </div>

        <div data-aos="fade-down">
          <a class="header-button" href="login/index.php">Login</a>
        </div>
      </nav>
      <main class="hero container" data-aos="fade-up" data-aos-delay="400">
        <h1>Acessibilidade para Todos, Promovendo um mundo mais inclusivo e acessível.</h1>
        <br>
        <center>
          <h2 style="color: #ffffff;">Estabelecimentos com estrutura para pessoas com falta de mobilidade.</h2>
        </center>
      </main>
    </header>
  </div>

  <!-- ABOUT -->
  <section class="about">
    <div class="container about-content">
      <div data-aos="zoom-in" data-aos-delay="250">
        <br>
        <br>
        <h2>Locais</h2>
       <center>
       <h4>Filtrar</h4>
       </center>
        <select id="filtro-select" class="filtro-select" onchange="onFilterChange()">
    <option value="">Todos</option>
    <option value="escola">Escola</option>
    <option value="restaurante">Restaurante</option>
    <option value="farmacia">Farmácia</option>
    <option value="mercado">Mercado</option>
     <option value="Comercios">Comercios</option>
                <option value="lazer">Areas de lazer</option>
</select>
        <ul id="locais">
          <!-- Lista dos locais será preenchida via JavaScript -->
        </ul>
      </div>

      <div class="about-description" data-aos="zoom-out-left" data-aos-delay="250">
        <h2>Mapa</h2>
        <div id="map"></div>
      </div>
    </div>
    <br>

  </section>

  <!-- SERVICES -->
  <section class="services">
    <h2 style="color:#fff;">Nossos Serviços</h2>
    <div class="services-content container"></div>

    <section class="haircuts">
      <div class="haircut" data-aos="fade-up" data-aos-delay="150">
        <img src="assets/mapa.jpg" alt="Mapa" />
        <div class="haircut-info">
          <strong>mapa com locais</strong>
        </div>
      </div>

      <div class="haircut" data-aos="fade-up" data-aos-delay="250">
        <img src="assets/duvida.png" alt="Duvidas" />
        <div class="haircut-info">
          <strong>Esclarecer Duvidas</strong>
        </div>
      </div>

      <div class="haircut" data-aos="fade-up" data-aos-delay="400">
        <img src="assets/informacao.png" alt="informações" />
        <div class="haircut-info">
          <strong>informações</strong>
        </div>
      </div>
    </section>

  </section>

  <!-- FOOTER -->
  <center>

    <div class="QuemSomos">
      <h2>Quem Somos</h2>
      <p>Somos a MobiliTec, um site dedicado a promover a acessibilidade para pessoas com falta de mobilidade. Nosso compromisso é criar um ambiente inclusivo, onde todas as pessoas possam conhecer estabelecimentos de maneira fácil e eficiente.</p>

      <h2>Nossa Missão</h2>
      <p>Nossa missão é eliminar as barreiras e promover a inclusão. Acreditamos que a acessibilidade é um direito fundamental, e trabalhamos incansavelmente para garantir que todos tenham acesso igualitário às informações e serviços de estabelecimentos.</p>

      <h2>Nossos Valores</h2>
      <ul>
        <li><strong>Inclusão:</strong> Valorizamos a diversidade e nos esforçamos para criar soluções que atendam às necessidades de todos.</li>
        <li><strong>Inovação:</strong> Buscamos constantemente novas tecnologias e métodos para melhorar a informação sobre a acessibilidade de estabelecimentos.</li>
        <li><strong>Empatia:</strong> Entendemos as dificuldades enfrentadas por pessoas com falta de mobilidade e nos comprometemos a oferecer suporte e soluções adequadas.</li>
        <li><strong>Transparência:</strong> Mantemos uma comunicação aberta e honesta com nossos usuários, parceiros e a comunidade em geral.</li>
      </ul>

      <h2>Nossa Equipe</h2>
      <p>Nossa equipe é composta por alunos da escola Etec de Atibaia. Trabalhamos juntos para desenvolver ferramentas e recursos que ajudem a ter informações sobre estabelecimentos acessíveis. Com uma combinação de habilidades e técnicas de progamação e uma compreensão profunda das necessidades dos nossos usuários, estamos empenhados em fazer a diferença.</p>
    </div>
  </center>
  <footer class="footer">
    <div class="footer-icons">
      <a href="#">
        <i class="fa-brands fa-instagram fa-2x"></i>
      </a>
    </div>

    <div>
      <img src="assets/ace.png" />
    </div>

    <p>2024 Acessibilidade para Todos</p>
  </footer>

  <!-- Button Whatsapp -->
  <script src="script.js"></script>
</body>

</html>
