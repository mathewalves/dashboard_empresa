<!DOCTYPE html>
<head>
    <title>Sistema de Estoque</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>


<?php
    include_once('view/Cadastros/conexão.php'); 

    $sql = "SELECT p.descricao, SUM(v.quantidade) AS total
    FROM sistema.vendas v
    INNER JOIN produtos p
    ON v.produto_idProd = p.idProd
    GROUP BY p.descricao;";

    $sqlpivot = 
    "SELECT c.nome, 
    SUM(CASE WHEN ci.descricao = 'Tarumã' THEN v.quantidade ELSE 0 END) AS 'Tarumã',
    SUM(CASE WHEN ci.descricao = 'Assis' THEN v.quantidade ELSE 0 END) AS 'Assis'
    FROM vendas v
    INNER JOIN clientes c
    ON c.idCli = v.cliente_idCli
    INNER JOIN cidade ci
    ON ci.idCid = c.cidade_idCid
    GROUP BY c.nome;
    ";

    $result = $conexao->query($sql);

    $resultpivot = $conexao->query($sqlpivot);
?>

    <!-- Head HTML & Navegação + cabeçalho -->
    <?php
        include ('view/src/php/_header-nav.php');
    ?>

    <main>
        <?php
         echo '<h2 class="title-c" name="welcome">Bem-vindo '.$_SESSION['login'].'</h2>';
        ?>
  
<div id="dashboard">
<h4>Total de vendas por Produto:</h4>
            <div>
            <?php
                $dados = array();
                while($venda_data = mysqli_fetch_assoc($result)) {
                    $dados[] = array(
                        'descricao' => $venda_data['descricao'],
                        'total' => $venda_data['total']
                    );
                }
            ?>
            <script>
                var dados = <?php echo json_encode($dados); ?>;
            </script>
            <canvas id="graficoDoughnut" width="350" height="350"></canvas>
            <script>var ctx = document.getElementById('graficoDoughnut').getContext('2d');

// Função para gerar uma cor aleatória em formato rgba
function gerarCorAleatoria() {
    var r = Math.floor(Math.random() * 256);
    var g = Math.floor(Math.random() * 256);
    var b = Math.floor(Math.random() * 256);
    var alpha = 0.8; // ou qualquer valor de opacidade desejado
    return `rgba(${r}, ${g}, ${b}, ${alpha})`;
}

// Array para armazenar as cores
var coresAleatorias = [];

// Adiciona cores aleatórias ao array
for (var i = 0; i < dados.length; i++) {
    coresAleatorias.push(gerarCorAleatoria());
}

// Configuração do gráfico
var dadosGrafico = {
    labels: dados.map(item => item.descricao),
    datasets: [{
        data: dados.map(item => item.total),
        backgroundColor: coresAleatorias,
    }]
};


var opcoesGrafico = {
    // Adicione opções do gráfico conforme necessário
};

var graficoDoughnut = new Chart(ctx, {
    type: 'doughnut',
    data: dadosGrafico,
    options: opcoesGrafico
});</script>
            </div>
            
            <div>
                <table border="0" width="50%" height="5%" class="table table-hover">
                <h4>Total de vendas por pessoas por cidades:</h4>
                    <thead>
                        <tr class="table-warning">
                            <td>
                                Pessoas
                            </td>
                            <td>
                                Tarumã
                            </td>
                            <td>
                                Assis
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($pessoas_data = mysqli_fetch_assoc($resultpivot))
                            {
                                echo "<tr>";
                                echo "<td>".$pessoas_data['nome']."</td>";
                                echo "<td>".$pessoas_data['Tarumã']."</td>";
                                echo "<td>".$pessoas_data['Assis']."</td>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>
    
</body>
</html>