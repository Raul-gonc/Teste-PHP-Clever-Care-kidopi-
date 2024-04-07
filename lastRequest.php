<?php

    $database = array(
        'Host' => 'localhost',
        'User' => 'root',
        'Password' => '',
        'DBName' => 'kidopi'
    );

    //conecta ao banco
    $conexao = mysqli_connect($database['Host'], $database['User'], $database['Password'], $database['DBName']);

    //monta a querry
    $sqlQuerry = "SELECT pais, data_hora
    FROM requests
    WHERE id = (select max(id) from requests)";
    
    //or die ("Configuração de Banco de Dados Errada!");
    
    $result = mysqli_query($conexao, $sqlQuerry);
    $last = mysqli_fetch_array($result);

    header('Content-Type: application/json');
    echo json_encode(array(
        "pais" => $last['pais'],
        "data" => $last['data_hora']
    ));

    mysqli_close($conexao);

?>