<?php
    // verificar login
        session_start();
        if (!isset($_SESSION['login'])) 
        {
            header('Location: /index.php'); // Redireciona para a página de login se não estiver logado
            exit();
        }
    // 
?>

<!DOCTYPE html>
<head>
    <title>Cadastrar Fornecedor</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
</head>

<?php

include_once('conexão.php');

    if(isset($_POST['submit']))
    {
        $nome = $_POST['nome'];

        // Verifica se o fornecedor já existe na tabela de cidade
        $select_sql = "SELECT idFor FROM fornecedores WHERE descricao = '$nome'";
        $result = $conexao->query($select_sql);

        if ($result->num_rows > 0) 
        {
            echo "
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                icon: 'error',
                title: 'Este fornecedor já está cadastrado!',
                    text: 'Você não pode cadastrar um fornecedor que já esta cadastrado.',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Voltar',
                    cancelButtonColor: '#3085d6',
                    cancelButtonIcon: 'bx bx-arrow-back'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // alguma ação se o usuário clicar no botão Confirmar
                    } else {
                        window.location.href = 'CadastrarFornecedor.php';
                    }
                });
            });
            </script>";
        } 
        else
        {
            // Coloca os dados do Fornecedor na tabela de fornecedores
            $insert_cliente_sql = "INSERT INTO fornecedores(descricao) VALUES ('$nome')";
        }

        if ($conexao->query($insert_cliente_sql) === TRUE)
        {
         echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Fornecedor cadastrado com sucesso!',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
            </script>";
        }
        else 
        {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                icon: 'error',
                title: 'Erro ao cadastrar fornecedor!',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Voltar',
                    cancelButtonColor: '#3085d6',
                    cancelButtonIcon: 'bx bx-arrow-back'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // alguma ação se o usuário clicar no botão Confirmar
                    } else {
                        window.location.href = 'CadastrarFornecedor.php';
                    }
                });
            });
            </script>";

            $conexao->error;
          }

        // Fecha a conexão com o banco de dados
        $conexao->close();
    }

    
?>


<body>
  <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('../src/php/_header-nav.php');
    ?>
<main>
    
    <div id="wrap">
        <form action="CadastrarFornecedor.php" method="POST">
            <h2>Cadastrar Fornecedor:</h2>
            <br>
            <i class='bx bxs-package'></i>
            <input type="text" name="nome" id="nome" placeholder=" Nome:" requrired>
            <br>
       
                <button class="button" id="submit" type="submit" name="submit">
                <i class='bx bx-edit' ></i> Cadastar
                </button>
        </form>
    </div>

</main>



</body>
</html>