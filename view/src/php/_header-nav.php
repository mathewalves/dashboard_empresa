<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icone/Favicon -->
    <link rel="icon favicon" href="/assets/images/index_favicon.png" type="image/x-icon">

    <!-- Links CSS -->
   <link rel="stylesheet" href="/tools/scss/main.css">
   <link rel="stylesheet" href="https://bootswatch.com/_vendor/bootstrap/dist/css/bootstrap.min.css">
    <!-- boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>


</head>


<body>
    <!-- Cabeçalho dá página ----------------------------------------------------->
    <header>
        <a href="/index.php" class="logo"><i class='bx bx-desktop'></i> Informática Tarumã</a>
    </header>

    <div class="navigation">
        <ul>
            <li>
                <a href="/index.php">
                    <span class="icon"><i class='bx bx-tachometer' ></i></span>
                    <span class="title">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="/view/clientes.php">
                    <span class="icon"><i class='bx bxs-user-detail'></i></span>
                    <span class="title">Clientes</span>
                </a>
            </li>
            <li>
                <a href="/view/produtos.php">
                    <span class="icon"><i class='bx bxs-component'></i></span>
                    <span class="title">Produtos</span>
                </a>
            </li>
            <li>
                <a href="/view/fornecedores.php">
                    <span class="icon"><i class='bx bx-collection' ></i></span>
                    <span class="title">Fornecedores</span>
                </a>
            </li>
            <li>
                <a href="/view/venda.php">
                    <span class="icon"><i class='bx bx-store'></i></span>
                    <span class="title">Vendas</span>
                </a>
            </li>
           <li>
                <a href="/index.php?logout">
                    <span class="icon"><i class='bx bx-log-out'></i></span>
                    <span class="title">Sair</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="toggle" onclick="toggleMenu();"></div>
    <script type="text/javascript">
        function toggleMenu() {
            // Seleciona o elemento HTML com a classe 'navigation'
            let navigation = document.querySelector('.navigation');
            
            // Seleciona o elemento HTML com a classe 'toggle'
            let toggle = document.querySelector('.toggle');
            
            // Alterna a classe 'active' no elemento 'navigation'
            navigation.classList.toggle('active');
            
            // Alterna a classe 'active' no elemento 'toggle'
            toggle.classList.toggle('active');
        }
    </script>
</body>