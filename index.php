<?php session_start() ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <!-- Icone da página -->
    <link rel="shortcut icon" href="/assets/images/index_favicon.png" type="image/x-icon">

</head>
<body>

    <!-- Sitema de Login -->
    <?php

    if(!isset($_SESSION['login']))
    {
        if(isset($_POST['acao']) && isset($_POST['login']) && isset($_POST['senha']))
        {
            $login = 'admin';
            $senha = '123';

            $loginForm = $_POST['login'];
            $senhaForm = $_POST['senha'];

                if($login == $loginForm && $senha == $senhaForm)
                {
                    //logado com sucesso
                    $_SESSION['login'] = $login;
                    header('Location: index.php');
                }
                else
                {
                    //erro
                    echo 'Dados inválidos!';
                }
            }

            include('login.php');
        }
        else
        {
            if(isset($_GET['logout']))
            {
                unset($_SESSION['login']);
                session_destroy();
                header('Location: index.php');
            }
            include('home.php');
        }

    ?>
</body>
</html>
