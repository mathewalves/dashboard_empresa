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
    <title>Editar Cliente</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
    <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('../src/php/_header-nav.php');
    ?>
<body>
    

<main>
  
<?php

include_once('conexão.php');

// Inicializa as variáveis
$nome = $cidadeDescricao = $uf = '';

if (!empty($_GET['id'])) {
    $id = $_GET['id'];
    $sqlSelect = "SELECT clientes.*, cidade.*
                FROM clientes
                LEFT JOIN cidade ON clientes.cidade_idCid = cidade.idCid
                WHERE clientes.idCli=?";
    
    $stmt = $conexao->prepare($sqlSelect);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($user_data = $result->fetch_assoc()) { 
            $nome = $user_data['nome'];
            $cidadeDescricao = $user_data['descricao']; // Nome da cidade
            $uf = $user_data['uf'];
        }
    } else {
        header('Location: /clientes.php');
    }

    $stmt->close();
}

if (isset($_POST['editar_cliente'])) {
    // Recupera os dados do formulário
    $idCli = $_POST['idCli'];
    $nome = $_POST['nome'];
    $cidadeDescricao = $_POST['cidade']; // Altere para o nome do campo no formulário
    $uf = $_POST['uf'];

    // Verifica se a cidade já existe na tabela de cidade
    $sqlCidade = "SELECT idCid FROM cidade WHERE descricao = ? AND uf = ?";
    $stmtCidade = $conexao->prepare($sqlCidade);
    $stmtCidade->bind_param("ss", $cidadeDescricao, $uf);
    $stmtCidade->execute();

    $resultCidade = $stmtCidade->get_result();

    if ($resultCidade->num_rows > 0) {
        // Se a cidade já existir
        $rowCidade = $resultCidade->fetch_assoc();
        $idCidade = $rowCidade['idCid'];
    } else {
        // Se a cidade não existir
        echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'Cidade não existe ou não está cadastrada!',
            text: 'Por favor, cadastre a cidade antes de editar o cliente.',
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Voltar',
            cancelButtonColor: '#3085d6',
            cancelButtonIcon: 'bx bx-arrow-back'
        }).then((result) => {
            if (result.isConfirmed) {
                // Aqui você pode adicionar alguma ação se o usuário clicar no botão Confirmar
            } else {
                window.location.href = 'EditarCliente.php?id=$id';
            }
        });
    </script>";
        
    }

    // Atualiza os dados do Cliente na tabela de Clientes
    $sqlUpdateCliente = "UPDATE clientes SET nome=?, cidade_idCid=? WHERE idCli=?";
    $stmtUpdateCliente = $conexao->prepare($sqlUpdateCliente);
    $stmtUpdateCliente->bind_param("sii", $nome, $idCidade, $idCli);

    if ($stmtUpdateCliente->execute()) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Cliente editado com sucesso!',
                    showConfirmButton: false,
                    timer: 1500
                });
            </script>";
    } else {
        echo
        "<div class='erro alert alert-dismissible alert-danger'>
        <strong>Erro ao cadastrar o Cliente:  </strong> . $conexao->error .
        </div>";
    }

    $stmtCidade->close();
    $stmtUpdateCliente->close();
}
?>

    <div id="editar" class="badges-b">
        <a href="../clientes.php"> <span class="badge bg-secondary"><i class='bx bx-arrow-back' ></i> Voltar</span></a>
        <a href="Cadastros/CadastrarCliente.php"> <span class="badge bg-primary"><i class='bx bx-buildings' ></i> Cidades</span></a>
    </div>

    <div class="cadastro card bg-light mb-3">
        <div class="card-header"><strong>Editar Cliente</strong></div>
        <div id="wrap" class="form-group card-body">
            <h4 class="card-title">Insira os dados:</h4>
            <p class="card-text">Certifique-se de que os dados estejam corretos.</p>
            <form action="" method="POST">
                <input type="hidden" name="idCli" value="<?php echo $id; ?>">
                <input class="form-control" type="text" name="nome" id="nome" value="<?php echo $nome; ?>" placeholder=" Nome:" autofocus required>
                <input class="form-control" type="text" name="cidade" id="cidade" value="<?php echo $cidadeDescricao; ?>" placeholder=" Cidade:">
                <input class="form-control" type="text" name="uf" id="uf" value="<?php echo $uf; ?>" placeholder=" UF:">

                <button class="btn btn-primary" id="submit" type="submit" name="editar_cliente">
                    <i class='bx bx-edit' ></i> Editar
                </button>
            </form>
        </div>
    </div>
</main>

</body>
</html>