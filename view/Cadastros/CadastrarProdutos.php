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
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
        <title>Cadastrar Produto</title>
    
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    </head>

<?php
include ('../src/php/_header-nav.php');


include_once('conexão.php');

if (isset($_POST['submit'])) {
    $nome = $_POST['nome'];
    $quantidade = $_POST['quantidade'];
    $valor = $_POST['valor'];
    $fornecedores = $_POST['fornecedores'];

    // Validar os dados aqui, se necessário

    // Verificar se o fornecedor já existe
    $fornecedorExists = false;
    $stmtVerificarFornecedor = $conexao->prepare("SELECT idFor FROM fornecedores WHERE descricao = ?");
    $stmtVerificarFornecedor->bind_param("s", $fornecedores);
    $stmtVerificarFornecedor->execute();
    $stmtVerificarFornecedor->store_result();

    if ($stmtVerificarFornecedor->num_rows > 0) {
        $fornecedorExists = true;
        $stmtVerificarFornecedor->bind_result($idFor);
        $stmtVerificarFornecedor->fetch();
    }

    $stmtVerificarFornecedor->close();

    // Se o fornecedor não existe, exibir mensagem e impedir a inserção do produto
    if (!$fornecedorExists) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Fornecedor não existe ou não está cadastrado!',
                text: 'Por favor, cadastre o fornecedor antes de cadastrar o produto.',
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Voltar',
                cancelButtonColor: '#3085d6'
            }).then((result) => {
                if (!result.isConfirmed) {
                    window.location.href = 'CadastrarProdutos.php';
                }
            });
        });
    </script>";
    } else {
        // O fornecedor existe, continuar com a inserção do produto

        // Iniciar uma transação
        $conexao->begin_transaction();

        try {
            // Inserir o produto
            $insert_produto_sql = "INSERT INTO produtos (descricao, quantidade, valor) VALUES (?, ?, ?)";
            $stmtProduto = $conexao->prepare($insert_produto_sql);
            $stmtProduto->bind_param("ssd", $nome, $quantidade, $valor);

            if ($stmtProduto->execute()) {
                $idProduto = $conexao->insert_id;

                // Inserir as relações na tabela de junção ProdutosFornecedores
                $insert_relacao_sql = "INSERT INTO produtofornecedor (Produtos_idProd, Fornecedores_idFor) VALUES (?, ?)";
                $stmtRelacao = $conexao->prepare($insert_relacao_sql);
                $stmtRelacao->bind_param("ii", $idProduto, $idFor);

                if (!$stmtRelacao->execute()) {
                    throw new Exception("Erro ao inserir a relação Produto-Fornecedor: " . $stmtRelacao->error);
                }
        
                

                // Confirmar a transação
                $conexao->commit();

                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Produto cadastrado com sucesso!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                });
            </script>";

            } else {
                throw new Exception("Erro ao cadastrar o produto: " . $conexao->error);
            }
        } catch (Exception $e) {
            // Rollback em caso de exceção
            $conexao->rollback();

            echo "Erro: " . $e->getMessage();
        }
    }
}

?>


<body>

<main>

<div class="info">
    <div class="tools">
            <div class="badges-b">
                <a href="../produtos.php"> <span class="badge bg-secondary"><i class='bx bx-arrow-back' ></i> Voltar</span></a>
                <a href="../fornecedores.php"> <span class="badge bg-primary"><i class='bx bx-buildings' ></i> Fornecedores</span></a>
            </div>
        </div>
    </div>
    
<div class="cadastro card bg-light mb-3">
<div  class="card-header">Cadastrar produto:</div>
    <div id="wrap" class="form-group card-body">
            <h4 class="card-title">Insira os dados:</h4>
            <p class="card-text">Certifique-se de que os dados estejam corretos.</p>
        <form action="CadastrarProdutos.php" method="POST">
            <input class="form-control" type="text" name="nome" id="nome" placeholder=" Nome do Produto:">
            <input class="form-control" type="number" name="quantidade" id="quantidade" placeholder=" Quantidade:">
            <input class="form-control" type="number" step="0.01" min="0" name="valor" id="valor" placeholder=" Preço R$:" aria-label="Valor em R$">
            <input class="form-control" type="text" name="fornecedores" id="fornecedores" placeholder=" Fornecedor:">
            

            <button class="btn btn-primary" id="submit" type="submit" name="submit">
                <i class='bx bx-edit' ></i> Cadastar
            </button>
        </form>
    </div>
</div>

</main>

</body>
</html>