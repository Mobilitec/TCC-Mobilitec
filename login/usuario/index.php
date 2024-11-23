<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial do Usuário</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
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
    </style>
</head>
<body>

    <div class="sidebar">
       <a href="#">Home</a>
        <a href="estabe.php">Estabelecimentos</a>
        <a href="#favoritos">Favoritos</a>
        <a href="Suges.php">Sugestões</a>
        <a href="#">Sair</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Página Inicial do Usuário</h1>
        </div>
        <div class="content">
            <h2>Bem-vindo!</h2>
            <p>Esta é a sua página inicial. Use o menu lateral para navegar entre as opções.</p>
        </div>
    </div>
   
</body>
</html>
