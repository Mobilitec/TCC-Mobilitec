<?php
    $servidor = "junction.proxy.rlwy.net:50991";
    $usuario = "root";
    $senha = "BEHlCCBlqkLeHEwoILDZuPAmmyioRysc";
    $banco = "projeto_login";

    // Conexão com o banco de dados
    $conexao = new mysqli($servidor, $usuario, $senha, $banco);
    $conexao->set_charset("utf8");

    // Verifica se a conexão foi estabelecida com sucesso
    if ($conexao->connect_error) {
        die("Erro na conexão: " . $conexao->connect_error);
    }

    // Verifica se o ID foi recebido via POST
    if(isset($_POST['id'])) {
        $id = $_POST['id'];

        // Executa a consulta de exclusão
        $sql = "DELETE FROM locations WHERE id = $id";

        if ($conexao->query($sql) === TRUE) {
            echo "<script>alert('Registro excluido com Sucesso!'); window.location.href = 'excluilocal.php';</script>";
        } else {
            echo "<script>alert('Erro ao excluir registro: " . $conexao->error . "');</script>";
        }
        
        
        
    }

    $conexao->close(); // Fecha a conexão com o banco de dados
?>
