<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="theme-color" content="#06b6d4" />
    <link rel="shortcut icon" href="img/ace.png">
    <!-- CSS -->
    <link rel="stylesheet" href="style.css" />

    <title>sugestões</title>
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
          <li class="selecionado">
            <a href="#"><i class="bi bi-chat"></i>Sugestões</a>
          </li>
          <li>
            <a href="addlocal.html"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-building-add" viewBox="0 0 16 16">
              <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0"/>
              <path d="M2 1a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6.5a.5.5 0 0 1-1 0V1H3v14h3v-2.5a.5.5 0 0 1 .5-.5H8v4H3a1 1 0 0 1-1-1z"/>
              <path d="M4.5 2a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm-6 3a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm3 0a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z"/>
            </svg>Adicionar local</a>
          </li>
          <li >
            <a href="excluilocal.php"><i class="bi bi-map"></i>Alterar local</a>
          </li>
        </ul>
      </aside>
      <main>
        <?php   
          $servidor = "localhost";
          $usuario = "root";
          $senha = "root";
          $banco = "projeto_login";

          // Conexão com o banco de dados
          $conexao = new mysqli($servidor, $usuario, $senha, $banco);
          $conexao->set_charset("utf8");

          // Verifica se a conexão foi estabelecida com sucesso
          if ($conexao->connect_error) {
              die("Erro na conexão: " . $conexao->connect_error);
          }

          // Executa a consulta dos dados
          $sql = "SELECT * FROM sugestoes";
          $resultado = $conexao->query($sql);

          // Verifica se a consulta foi bem-sucedida
          if ($resultado) {
              echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
              echo "<tr><th style='padding: 8px; text-align: left;'>Local</th><th style='padding: 8px; text-align: left;'>Endereço</th><th style='padding: 8px; text-align: left;'>Numero</th><th style='padding: 8px; text-align: left;'>Descrição</th><th style='padding: 8px; text-align: left;'>Opção</th></tr>";
              
              while ($linha = $resultado->fetch_assoc()) {
                  echo "<tr>";
                  echo "<td style='padding: 8px;'>" . $linha["nome"] . "</td>"; 
                  echo "<td style='padding: 8px;'>" . $linha["endereco"] . "</td>";
                  echo "<td style='padding: 8px;'>" . $linha["numero"] . "</td>";
                  echo "<td style='padding: 8px;'>" . $linha["descricao"] . "</td>";
                  echo "<td style='padding: 8px;'> 
                          <form method='post' action='excluisuges.php'> 
                              <input type='hidden' name='id' value='" . $linha["id"] . "'>
                              <button type='submit' style='padding: 4px 8px; background-color: #ff0000; color: #fff; border: none; cursor: pointer;'>Excluir</button> 
                          </form> 
                        </td>";
                 
                  echo "</tr>";
              }
              echo "</table>";
          } else {
              echo "Erro na consulta de dados: " . $conexao->error;
          }

          $conexao->close(); // Fecha a conexão com o banco de dados
        ?> 
      </main>
    </div>

    
  </body>
</html>
