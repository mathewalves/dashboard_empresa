<?php

    $dbHost = 'localhost';
    $dbUsername = 'root';
    $dbPassword = '';
    $dbName = 'sistema';

    $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

    // if($conexao->connect_errno)
    // {
    //     echo "Erro na conexão.";
    // }
    // else
    // {
    //    echo "Conexão feita com Sucesso.";
    // }

?>
