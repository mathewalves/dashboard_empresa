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

  $sql = "SELECT clientes.*, cidade.* 
  FROM clientes 
  LEFT JOIN cidade ON clientes.cidade_idCid = cidade.idCid
  ORDER BY clientes.idCli DESC";

  $result = $conexao->query($sql);

  if(!empty($_GET['id']))
  {
   $id =$_GET['id'];

   $sqlSelect = "SELECT * FROM clientes WHERE idCli=$id";

   $result = $conexao->query($sqlSelect);

      if($result->num_rows > 0)
      {
       $sqlDelete = "DELETE FROM clientes WHERE idCli=$id";
       $resultDelete = $conexao->query($sqlDelete);
       echo '<script>window.location.href = window.location.href;</script>';
      }
      header('Location: clientes.php');
    }

    $pagina = 1;

    if (isset($_GET['pagina'])) {
        $pagina = filter_input(INPUT_GET, "pagina", FILTER_VALIDATE_INT);
    }

    if (!$pagina) {
        $pagina = 1;
    }

    $limite = 5;
    $inicio = ($pagina * $limite) - $limite;

    if(!empty($_GET['search']))
    {
        $searchTerm = $_GET['search'];
        $sql = "SELECT clientes.*, cidade.* 
                FROM clientes 
                LEFT JOIN cidade ON clientes.cidade_idCid = cidade.idCid
                WHERE clientes.nome LIKE '%$searchTerm%'
                OR cidade.descricao LIKE '%$searchTerm%'
                OR cidade.uf LIKE '%$searchTerm%'
                ORDER BY clientes.idCli DESC";

        $result = $conexao->query($sql);
    }
    else
    {
        // Se não houver pesquisa, execute a consulta original
        $sql = "SELECT clientes.*, cidade.* 
                FROM clientes 
                LEFT JOIN cidade ON clientes.cidade_idCid = cidade.idCid
                ORDER BY clientes.idCli DESC";

        $result = $conexao->query($sql);
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
    <title>Sistema de Estoque</title>
    <link rel="shortcut icon" href="/assets/images/index_favicon.png" type="image/x-icon">
</head>
<body>
    <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('src/php/_header-nav.php');
    ?>

<div class="info">
    <h2 class="title-c">Clientes Cadastrados:</h2>


    
    <div class="tools">
    <form class="d-flex" onsubmit="return false;">
        <input id="pesquisar" class="form-control me-sm-2" type="search" placeholder="Pesquisar">
        <button id="btnPesquisar" class="btn btn-secondary my-2 my-sm-0" type="button" onclick="searchData()"><i class='bx bx-search-alt'></i></button>
    </form>
       <div class="badges-a">
           <a href="Cadastros/CadastrarCliente.php"> <span class="badge bg-success"><i class='bx bx-user-plus'></i> Novo Cliente</span></a>
           <a href="cidades.php"> <span class="badge bg-primary"><i class='bx bx-buildings' ></i> Cidades</span></a>
       </div>
    </div>

    <div id="saida">
            <br>
            <table border="0" width="50%" height="5%" class="table table-hover">

                <thead>
                    <tr class="table-dark">
                        <th scope="col" width="30px">
                            id
                        </th>
                        <td>
                            Nome
                        </td>
                        <td>
                            Cidade
                        </td>
                        <td>
                            Sigla/UF
                        </td>
                        <td width="150px">
                            Ações
                        </td>
                    </tr>
                </thead>

                <tbody>
                    <?php
                        while($client_data = mysqli_fetch_assoc($result))
                        {
                            echo "<tr>";
                            echo "<td>".$client_data['idCli']."</td>";
                            echo "<td>".$client_data['nome']."</td>";
                            echo "<td>".$client_data['descricao']."</td>";
                            echo "<td>".$client_data['uf']."</td>";
                            echo "<td> 
                            <a href='Cadastros/EditarCliente.php?id=$client_data[idCli]'><button class='btn btn-secondary btn-sm'><i class='bx bxs-edit'></i></button></a>
                            <a href='clientes.php?id=$client_data[idCli]'><button class='btn btn-danger btn-sm' ><i class='bx bxs-trash-alt' ></i></button></a>
                            </td>";
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
            window.location.href = 'clientes.php?search=' + searchTerm;
        }
    </script>

</body>
</html>