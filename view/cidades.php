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


    // Passo 1: Contar a quantidade de clientes por cidade
    $sqlContagem = "SELECT cidade_idCid, COUNT(*) AS totalClientes FROM clientes GROUP BY cidade_idCid";
    $resultContagem = $conexao->query($sqlContagem);

    // Passo 2: Atualizar o campo 'qtdeCli' na tabela de cidades
    while ($rowContagem = $resultContagem->fetch_assoc()) 
    {
        $cidadeId = $rowContagem['cidade_idCid'];
        $totalClientes = $rowContagem['totalClientes'];

        // Atualizar o campo 'qtdeCli' na tabela de cidades
        $sqlUpdateQtdeCli = "UPDATE cidade SET qtdeCli = $totalClientes WHERE idCid = $cidadeId";
        $conexao->query($sqlUpdateQtdeCli);
    }

//geração automática das cidades
$cidades = 
[
    ['descricao' => 'Londrina', 'uf' => 'PR'],
    ['descricao' => 'Assis', 'uf' => 'SP'],
    ['descricao' => 'Tarumã', 'uf' => 'SP'],
    ['descricao' => 'São Paulo', 'uf' => 'SP'],
    ['descricao' => 'Rio de Janeiro', 'uf' => 'RJ'],
    ['descricao' => 'Curitiba', 'uf' => 'PR'],
    ['descricao' => 'Florianópolis', 'uf' => 'SC'],
    ['descricao' => 'Porto Alegre', 'uf' => 'RS'],
    ['descricao' => 'Belo Horizonte', 'uf' => 'MG'],
    ['descricao' => 'Salvador', 'uf' => 'BA'],
    ['descricao' => 'Fortaleza', 'uf' => 'CE'],
    ['descricao' => 'Recife', 'uf' => 'PE'],
    ['descricao' => 'Manaus', 'uf' => 'AM'],
    ['descricao' => 'Belém', 'uf' => 'PA'],
    ['descricao' => 'Vitória', 'uf' => 'ES'],
    ['descricao' => 'Cuiabá', 'uf' => 'MT'],
    ['descricao' => 'Campo Grande', 'uf' => 'MS'],
    ['descricao' => 'Palmas', 'uf' => 'TO'],
    ['descricao' => 'Natal', 'uf' => 'RN'],
    // Adicione outras cidades conforme necessário
];

foreach ($cidades as $cidade) {
    $nome = $cidade['descricao'];
    $uf = $cidade['uf'];

    $insert_sql = "INSERT IGNORE INTO cidade (descricao, uf) VALUES ('$nome', '$uf')";
    if ($conexao->query($insert_sql) !== TRUE) {
        echo "Erro ao inserir cidade: " . $conexao->error;
    }
}


    // Paginação dos dados
    $pagina = 1;

    if (isset($_GET['pagina'])) {
        $pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);
    }

    if (!$pagina) {
        $pagina = 1;
    }

    $limite = 5;
    $inicio = ($pagina * $limite) - $limite;

    // Lógica de pesquisa
    if (!empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $sql = "SELECT cidade.* 
                FROM cidade
                WHERE idCid LIKE '%$searchTerm%'
                OR descricao LIKE '%$searchTerm%'
                OR uf LIKE '%$searchTerm%'
                ORDER BY idCid";
    } else {
        // Se não houver pesquisa, execute a consulta original
        $sql = "SELECT cidade.* 
                FROM cidade
                ORDER BY idCid";
    }

    // Obtenha o total de registros
    $totalRegistrosQuery = $conexao->query("SELECT COUNT(*) as count FROM ($sql) as subquery");
    $totalRegistros = $totalRegistrosQuery->fetch_assoc()["count"];

    $totalPaginas = ceil($totalRegistros / $limite);

    // Consulta paginada
    $sqlPaginado = "$sql LIMIT $inicio, $limite";
    $result = $conexao->query($sqlPaginado);

    if (!$result) 
    {
        echo "Erro na consulta: " . $conexao->error;
    }

  
?>

<!DOCTYPE html>
<head>
    <title>Cidades</title>
</head>
<body>
    <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('src/php/_header-nav.php');
    ?>


    <div class="info">
    <h2 class="title-c">Cidades Cadastradas:</h2>

        <div class="tools">
        <form class="d-flex" onsubmit="return false;">
        <input id="pesquisar" class="form-control me-sm-2" type="search" placeholder="Pesquisar">
        <button id="btnPesquisar" class="btn btn-secondary my-2 my-sm-0" type="button" onclick="searchData()"><i class='bx bx-search-alt'></i></button>
        </form>
           <div class="badges-a">
               <a href="clientes.php"> <span class="badge bg-success"><i class='bx bx-arrow-back' ></i> Voltar para Clientes</span></a>
               <a href="cidades.php"> <span class="badge bg-primary"><i class='bx bx-buildings' ></i> Cidades</span></a>
           </div>
        </div>
        <div id="saida">
                    <br>
                    <table id="cidades" border="0" width="50%" height="5%" class="table table-hover">
                        <thead>
                            <tr class="table-dark">
                                <th scope="col" width="30px">
                                    id
                                </th>
                                <td>
                                    Cidade
                                </td>
                                <td>
                                    Sigla/UF
                                </td>
                                <td>
                                    Total de Clientes
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                while($client_data = mysqli_fetch_assoc($result))
                                {
                                    echo "<tr>";
                                    echo "<td>".$client_data['idCid']."</td>";
                                    echo "<td>".$client_data['descricao']."</td>";
                                    echo "<td>".$client_data['uf']."</td>";
                                    echo "<td>".$client_data['qtdeCli']."</td>";
                                }
                            ?>
                        </tbody>
                    </table>
                    
                </div>
                
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
            window.location.href = 'cidades.php?search=' + searchTerm;
        }
    </script>
</body>
