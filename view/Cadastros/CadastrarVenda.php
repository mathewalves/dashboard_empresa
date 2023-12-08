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
<html lang="pt-br">
<head>
    <title>Cadastrar Venda</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>

<?php

    include_once('conexão.php');

    $nomeCliente = $_POST['nome_cliente'];
    $nomeProduto = $_POST['nome_produto'];
    $quantidade = $_POST['quantidade'];
    $dataVenda = $_POST['dtvenda'];
    
    // Consulta para obter o ID do cliente
    $sqlCliente = "SELECT idCli FROM clientes WHERE nome = ?";
    $stmtCliente = $conexao->prepare($sqlCliente);
    $stmtCliente->bind_param("s", $nomeCliente);
    $stmtCliente->execute();
    $stmtCliente->bind_result($idCliente);
    $stmtCliente->fetch();
    $stmtCliente->close();
    
    // Consulta para obter o ID do produto
    $sqlProduto = "SELECT idProd FROM produtos WHERE descricao = ?";
    $stmtProduto = $conexao->prepare($sqlProduto);
    $stmtProduto->bind_param("s", $nomeProduto);
    $stmtProduto->execute();
    $stmtProduto->bind_result($idProduto);
    $stmtProduto->fetch();
    $stmtProduto->close();
    
    if (isset($_POST['submit'])) {
        $sqlInserirVenda = "INSERT INTO vendas (cliente_idCli, produto_idProd, quantidade, dataVenda) VALUES (?, ?, ?, ?)";
        $stmtInserirVenda = $conexao->prepare($sqlInserirVenda);
        
        // Definir as variáveis $dataVenda
        $dataVenda = $dataVenda;
    
        $stmtInserirVenda->bind_param("iiss", $idCliente, $idProduto, $quantidade, $dataVenda);
    
        if ($stmtInserirVenda->execute()) {
            // Atualizar o estoque do produto
            $sqlAtualizarEstoque = "UPDATE produtos SET quantidade = quantidade - ? WHERE idProd = ?";
            $stmtAtualizarEstoque = $conexao->prepare($sqlAtualizarEstoque);
            $stmtAtualizarEstoque->bind_param("ii", $quantidade, $idProduto);
        
            if ($stmtAtualizarEstoque->execute()) {
                // Código de sucesso
                echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Venda cadastrada com sucesso!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>";
            } else {
                // Código de erro na atualização do estoque
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro ao atualizar o estoque!',
                        text: 'A venda foi registrada, mas houve um erro ao atualizar o estoque do produto.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                </script>";
            }
        
            // Fechar a declaração preparada
            $stmtAtualizarEstoque->close();
        } else {
            // Código de erro na inserção da venda
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro ao cadastrar a venda!',
                    text: 'Por favor, verifique os dados e tente novamente.',
                    showConfirmButton: false,
                    showCancelButton: true,
                    cancelButtonText: 'Voltar',
                    cancelButtonColor: '#3085d6',
                    cancelButtonIcon: 'bx bx-arrow-back'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aqui você pode adicionar alguma ação se o usuário clicar no botão Confirmar
                    } else {
                        window.location.href = 'CadastrarVenda.php';
                    }
                });
            </script>";
        }
        
        // Fechar a declaração preparada
        $stmtInserirVenda->close();
    }

?>

<body>
    <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('../src/php/_header-nav.php');
    ?>

<main>

    <!-- voltar ou ir para produtos -->
    <div class="info">
        <div class="tools">
            <div id="editar" class="badges-b">
                    <a href="../venda.php"> <span class="badge bg-secondary"><i class='bx bx-arrow-back' ></i> Voltar</span></a>
                    <a href="../produtos.php"> <span class="badge bg-primary"><i class='bx bx-buildings' ></i> Produtos</span></a>
            </div>
        </div>
    </div>

    <div class="cadastro card bg-light mb-3">
        <div  class="card-header">Fazer Venda:</div>
        <div id="wrap" class="form-group card-body">
            <form action="CadastrarVenda.php" method="POST">
                <input class="form-control" type="text" name="nome_cliente" id="nome_cliente" placeholder=" Nome do cliente:">
                <input class="form-control" type="text" name="nome_produto" id="nome_produto" placeholder=" Produto:" >
                <input class="form-control" type="number" name="quantidade" min="0" id="quantidade" placeholder=" Quantidade:">
                <label class="col-form-label col-form-label-sm mt-4" for="dtvenda"> Data da Venda:</label>
                <input class="form-control form-control-sm" type="date" name="dtvenda" id="dtvenda" placeholder=" Data da Venda:">
        
                <button class="btn btn-primary" id="submit" type="submit" name="submit">
                <i class='bx bx-edit' ></i> Cadastar
                </button>
            </form>
        </div>
    </div>


</main>

</body>
</html>