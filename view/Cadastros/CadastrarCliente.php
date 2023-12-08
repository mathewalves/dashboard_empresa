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
    <title>Cadastrar Clientes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<body>

    <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('../src/php/_header-nav.php');
    ?>

<main>
    <div class="mensagem"> 
        <?php

        include_once('conexão.php');

        if(isset($_POST['submit']))
        {

            $nome = $_POST['nome'];
            $cidade = $_POST['cidade'];
            $uf = $_POST['uf'];

            // Verifica se a cidade já existe na tabela de cidade
            $select_sql = "SELECT idCid FROM cidade WHERE descricao = '$cidade' AND uf = '$uf'";
            $result = $conexao->query($select_sql);


            if ($result->num_rows > 0) 
            {
                // Se a cidade já existir
                $row = $result->fetch_assoc();
                $idCidade = $row['idCid'];
            } 
            else 
            {
                // Se a cidade não existir
                echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Cidade não existe ou não está cadastrada!',
                    text: 'Por favor, cadastre a cidade antes de cadastrar o cliente.',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Voltar',
                    cancelButtonColor: '#3085d6',
                    cancelButtonIcon: 'bx bx-arrow-back'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aqui você pode adicionar alguma ação se o usuário clicar no botão Confirmar
                    } else {
                        window.location.href = 'CadastrarCliente.php';
                    }
                });
            </script>";
            }

            // Coloca os dados do Cliente na tabela de Clientes (inserção no banco de dados)
            $insert_cliente_sql = "INSERT INTO clientes(nome, cidade_idCid) VALUES ('$nome', $idCidade)";
            if ($conexao->query($insert_cliente_sql) === TRUE)
            {
                //alert de sucesso
                echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Cliente cadastrado com sucesso!',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>";
            }
            else 
            {
                //alert de erro
                echo "
                <div class='alert alert-dismissible alert-danger'>
                <strong>Erro ao cadastrar o Cliente:  </strong> . $conexao->error .
                </div>";
            }

            // Fecha a conexão com o banco de dados
            $conexao->close();
        }
        ?>
    </div> 

    <div class="info">
    <div class="tools">
            <div class="badges-b">
                <a href="../clientes.php"> <span class="badge bg-secondary"><i class='bx bx-arrow-back' ></i> Voltar</span></a>
                <a href="../cidades.php"> <span class="badge bg-primary"><i class='bx bx-buildings' ></i> Cidades</span></a>
            </div>
        </div>
    </div>

    <div class="cadastro card bg-light mb-3">
        <div class="card-header"><strong>Cadastrar Cliente</strong></div>
        <div id="wrap" class="form-group card-body">
            <h4 class="card-title">Insira os dados:</h4>
            <p class="card-text">Certifique-se de que os dados estejam corretos.</p>
            <form action="CadastrarCliente.php" method="POST">
                <input class="form-control" type="text" name="nome" id="nome" placeholder=" Nome:" autofocus requrired>
                <input class="form-control" type="text" name="cidade" id="cidade" placeholder=" Cidade:">
                <input class="form-control" type="text" name="uf" id="uf" placeholder=" UF:">
                <button class="btn btn-primary" id="submit" type="submit" name="submit">
                    <i class='bx bx-edit' ></i> Cadastar
                </button>
            </form>
        </div>
    </div>

   
</main>

</body>
</html>