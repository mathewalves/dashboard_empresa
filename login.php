<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Tela de login em HTML e CSS</title>

    <!-- links -->
    <link rel="stylesheet" href="assets/css/login.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="assets/favicon.ico" type="image/x-icon">

</head>
<body>

    <div class="wrapper">
        <form method="post">
            <h1>Faça o Login</h1>

            <div class="input-box"> 
                <input type="text" name="login" placeholder="Nome de Usuário">
                <div class="user"><i class='bx bxs-user'></i></div>
            </div>

            <div class="input-box"> 
                <input type="password" name="senha" placeholder="Senha" required>
                <div class="lock"><i class='bx bxs-lock-alt' ></i></div>
            </div>

            <div class="remember-forgot">
                <label><input type="checkbox">Salvar usuário</label> 
            </div>

            <button type="submit" name="acao" value="Enviar!" class="btn">Login</button>

        </form>
    </div>
    
</body>
</html>