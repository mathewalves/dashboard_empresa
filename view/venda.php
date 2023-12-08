<?php 

    // verificar login
        session_start();
        if (!isset($_SESSION['login'])) 
        {
            header('Location: /index.php'); // Redireciona para a página de login se não estiver logado
            exit();
        }
    // 
    
  include_once('Cadastros/conexão.php'); 

  
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


  $sql = "SELECT vendas.*, clientes.nome AS nomeCliente, produtos.descricao AS nomeProduto, produtos.valor
        FROM vendas
        LEFT JOIN clientes ON vendas.cliente_idCli = clientes.idCli
        LEFT JOIN produtos ON vendas.produto_idProd = produtos.idProd
        ORDER BY vendas.idVenda DESC";

    $result = $conexao->query($sql);

    $pagina = 1;

if (isset($_GET['pagina'])) {
$pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);
}

    if (!$pagina) 
    {
    $pagina = 1;
    }

$limite = 5;
$inicio = ($pagina * $limite) - $limite;

if (!empty($_GET['search'])) {
$searchTerm = $_GET['search'];
$sqlpesquisa = "SELECT vendas.idVenda, vendas.dataVenda, vendas.quantidade, clientes.nome AS nomeCliente, produtos.descricao AS nomeProduto, produtos.valor
FROM vendas
LEFT JOIN clientes ON vendas.cliente_idCli = clientes.idCli
LEFT JOIN produtos ON vendas.produto_idProd = produtos.idProd
WHERE clientes.nome LIKE '%$searchTerm%'
OR vendas.idVenda LIKE '%$searchTerm%'
OR vendas.dataVenda LIKE '%$searchTerm%'
OR produtos.descricao LIKE '%$searchTerm%'
ORDER BY vendas.idVenda DESC";

$result = $conexao->query($sqlpesquisa);
} else {
$sqlpesquisa = "SELECT vendas.idVenda, vendas.dataVenda, vendas.quantidade, clientes.nome AS nomeCliente, produtos.descricao AS nomeProduto, produtos.valor
FROM vendas
LEFT JOIN clientes ON vendas.cliente_idCli = clientes.idCli
LEFT JOIN produtos ON vendas.produto_idProd = produtos.idProd
ORDER BY vendas.idVenda DESC";

$result = $conexao->query($sqlpesquisa);
}

// Obtenha o total de registros
$totalRegistrosQuery = $conexao->query("SELECT COUNT(*) as count FROM ($sql) as subquery");
$totalRegistros = $totalRegistrosQuery->fetch_assoc()["count"];

$totalPaginas = ceil($totalRegistros / $limite);

// Consulta paginada
$sqlPaginado = "$sql LIMIT $inicio, $limite";
$resultPaginado = $conexao->query($sqlPaginado);

if (!$resultPaginado) {
echo "Erro na consulta paginada: " . $conexao->error;
}

if (!empty($_GET['id'])) {
    $id = $_GET['id'];

    // Excluir a venda
    $sqlDeleteVenda = "DELETE FROM vendas WHERE idVenda = $id";
    $resultDeleteVenda = $conexao->query($sqlDeleteVenda);

    if (!$resultDeleteVenda) {
        echo "Erro ao excluir a venda: " . $conexao->error;
    } else {
        echo '<script>window.location.href = window.location.href;</script>';
    }
    // Redirecionar para a página de produtos após a exclusão
    header('Location: venda.php');
}
    
?>

<!DOCTYPE html>
<head>
    <title>Sistema de Estoque</title>
</head>
<body>
    <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('src/php/_header-nav.php');
    ?>

<div class="info">
    <h2 class="title-c">Ultimas Vendas:</h2>


    
    <div class="tools">
    <form class="d-flex" onsubmit="return false;">
        <input id="pesquisar" class="form-control me-sm-2" type="search" placeholder="Pesquisar">
        <button id="btnPesquisar" class="btn btn-secondary my-2 my-sm-0" type="button" onclick="searchData()"><i class='bx bx-search-alt'></i></button>
    </form>
       <div class="badges-a">
       <a href="produtos.php"> <span class="badge bg-primary"><i class='bx bxs-objects-horizontal-left'></i> Produtos</span></a>
           <a href="Cadastros/CadastrarVenda.php"> <span class="badge bg-success"><i class='bx bx-cart-add' ></i> Fazer Venda</span></a>
       </div>
    </div>

    <div id="saida">
            <br>
            <table border="0" width="50%" height="5%" class="table table-hover">

                <thead>
                    <tr class="table-dark">
                        <th scope="col" width="30px">
                            ID
                        </th>
                        <td>
                            Cliente
                        </td>
                        <td width="150px">
                            Produto
                        </td>
                        <td>
                            Quantidade
                        </td>
                        <td width="150px">
                            Data da Venda
                        </td>
                        <td width="150px">
                            Unit.(R$)
                        </td>
                        <td width="150px">
                            Total (R$)
                        </td>
                        <td width="150px">
                            Ações
                        </td>
                    </tr>
                </thead>

                <tbody>
                        <?php
                            $somaTotal = 0;
                            while ($row = $result->fetch_assoc()) {
                                $totalVenda = $row['quantidade'] * $row['valor'];
                                $somaTotal += $totalVenda;

                                echo "<tr>";
                                echo "<td>{$row['idVenda']}</td>";
                                echo "<td>{$row['nomeCliente']}</td>";
                                echo "<td>{$row['nomeProduto']}</td>";
                                echo "<td>{$row['quantidade']}</td>";
                                echo "<td>{$row['dataVenda']}</td>";
                                echo "<td>R$ {$row['valor']}</td>";
                                echo "<td>R$ {$totalVenda}</td>";
                                echo "<td> 
                                    <button class='btn btn-primary btn-sm'><i class='bx bx-info-circle' ></i></button>
                                    <a href='venda.php?id=$row[idVenda]'><button class='btn btn-danger btn-sm' ><i class='bx bx-x-circle'></i></button></a>
                                    </td>";
                                echo "</tr>";
                    }
                    ?>
                </tbody>
    
            </table>
        </div>
        <!-- Exibe os botões de navegação -->
        <?php
                echo '<ul class="pages pagination pagination-sm">';
                echo '<li class="page-item ' . ($pagina == 1 ? 'disabled' : '') . '">';
                echo '<a class="page-link" href="?pagina=' . max($pagina - 1, 1) . '">&laquo;</a>';
                echo '</li>';

                for ($i = 1; $i <= $totalPaginas; $i++) {
                    echo '<li class="page-item ' . ($pagina == $i ? 'active' : '') . '">';
                    echo '<a class="page-link" href="?pagina=' . $i . '">' . $i . '</a>';
                    echo '</li>';
                }

                echo '<li class="page-item ' . ($pagina == $totalPaginas ? 'disabled' : '') . '">';
                echo '<a class="page-link" href="?pagina=' . min($pagina + 1, $totalPaginas) . '">&raquo;</a>';
                echo '</li>';
                echo '</ul>';
            ?>
    </div>


    <script>
        var search = document.getElementById('pesquisar');

        search.addEventListener("keydown", function(event) {
            if (event.key === "Enter") {
                searchData();
            }
        });

        function searchData() {
            var searchTerm = search.value;
            window.location.href = 'venda.php?search=' + encodeURIComponent(searchTerm);
        }
    </script>

</body>
</html>