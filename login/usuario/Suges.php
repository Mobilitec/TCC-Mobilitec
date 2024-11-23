<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MobiliTec - Página Inicial do Usuário</title>

    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600&display=swap" rel="stylesheet" />

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
            margin-bottom: 20px;
        }

        /* Estilos para o Formulário */
        form label {
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        form input[type="text"], form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #1e90ff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        form input[type="submit"]:hover {
            background-color: #005cbf;
        }

        /* Responsividade */
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

            form input[type="submit"] {
                padding: 15px;
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="index.php">Home</a>
        <a href="estabe.php">Estabelecimentos</a>
        <a href="#favoritos">Favoritos</a>
        <a href="Suges.php">Sugestões</a>
        <a href="#">Sair</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Sugestões</h1>
        </div>

        <div class="content">
            <h2>Sugerir Novo Local</h2>
            <form action="salvar_sugestao.php" method="POST">
                <label for="nome">Nome do Local:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="endereco">Rua:</label>
                <input type="text" id="endereco" name="endereco" required>

                <label for="numero">Número:</label>
                <input type="text" id="numero" name="numero">

                <label for="descricao">Descrição do Local:</label>
                <textarea id="descricao" name="descricao" rows="4" required></textarea>

                <input type="submit" value="Enviar Sugestão">
            </form>
        </div>
    </div>

</body>
</html>
