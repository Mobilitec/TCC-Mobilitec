<?php

require_once 'classes/Usuarios.php';
$u = new Usuarios();

?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="shortcut icon" href="imagens/ace.png">
</head>

<body>

<div id="corpo-form">
    <h1>Login</h1>

    <form method="POST">
        <input type="email" name="email" placeholder="Usuário"/>
        <input type="password" name="senha" placeholder="Senha"/>
        <input type="submit" value="ACESSAR" name=""/>
        <a href="cadastrar.php">Ainda não tem uma conta? <strong>crie uma!</strong></a>
    </form>
<div>
</body>

<?php


 
if(isset($_POST['email'])):
    
    $email = addslashes($_POST['email']);
    $senha = addslashes($_POST['senha']);

    if(!empty($email) && !empty($senha)):

        $u->conectar("projeto_login", "localhost", "root", "root");

        if($u->msgERRO == ""):
            
            if ($email == 'Adm@gmail.com') {
                // Redireciona para a página de administração
                header("location: telaAdm/index.html");
            } else {
                // Redireciona para a página normal
                header("location: usuario/index.php");
            }
        
            else: 

                ?>

                <div class="msg-erro">
                    E-mail e/ou Senha Incorretos!
                </div>

                <?php

            endif;

        else:

            ?>
           
           

            <?php


        
        endif;

    else:

        ?>

            

        <?php




    endif;






?>

</html>
