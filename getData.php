<?php
// Função para obter os dados de um país específico
function getData($pais) {
    // Define os URLs dos endpoints para os países predefinidos
    $endpoints = array(
        'Brasil' => 'https://dev.kidopilabs.com.br/exercicio/covid.php?pais=Brazil',
        'Canadá' => 'https://dev.kidopilabs.com.br/exercicio/covid.php?pais=Canada',
        'Australia' => 'https://dev.kidopilabs.com.br/exercicio/covid.php?pais=Australia'
    );


    // Verifica se o país está entre os predefinidos
    if (array_key_exists($pais, $endpoints)) {
        // Obtém a URL correspondente ao país selecionado
        $url = $endpoints[$pais];
        
        // Executa a requisição e obtém a resposta
        $response = file_get_contents($url);
        $responseArray = json_decode($response, true); // Decodifica como array associativo

        // Verifica se houve erro na requisição
        if ($response === FALSE) {
            // Se houver erro, retorna um JSON com a mensagem de erro
            header('Content-Type: application/json');
            echo json_encode(array("error" => "Erro ao acessar a API"));
        } else {
            saveRequest($pais);
            // Inicializa as variáveis para armazenar os totais
            $mortesTotais = 0;
            $casosConfirmados = 0;

            // Itera sobre os elementos do array associativo
            foreach ($responseArray as $key => $value) {
                // Atualiza os totais de mortes e casos confirmados
                $mortesTotais += $value["Mortos"];
                $casosConfirmados += $value["Confirmados"];
            }

            // Formata os dados para o JSON de retorno
            $dadosFormatados = array(
                "pais" => $pais,
                "mortesTotais" => $mortesTotais,
                "casosConfirmados" => $casosConfirmados
            );

            // Retorna o JSON combinando os dados formatados e o JSON original
            header('Content-Type: application/json');
            echo json_encode(array(
                "dadosFormatados" => $dadosFormatados,
                "dadosOriginais" => $responseArray
            ));
        }
    } else {
        // Se o país não for encontrado, retorna um JSON com a mensagem de erro
        header('Content-Type: application/json');
        echo json_encode(array("error" => "País não encontrado!"));
    }
}

function saveRequest($pais) {
    $database = array(
        'Host' => 'localhost',
        'User' => 'root',
        'Password' => '',
        'DBName' => 'kidopi'
    );

    //conecta ao banco
    $conexao = mysqli_connect($database['Host'], $database['User'], $database['Password'], $database['DBName']);

    $pais = mysqli_real_escape_string($conexao, $pais);
    //monta a querry
    $sqlQuerry = "INSERT INTO requests (pais) VALUES ('$pais')";

    
    //or die ("Configuração de Banco de Dados Errada!");
    
    $sql = mysqli_query($conexao, $sqlQuerry);

    mysqli_close($conexao);
}

function lastRequest($pais) {
    $database = array(
        'Host' => 'localhost',
        'User' => 'root',
        'Password' => '',
        'DBName' => 'kidopi'
    );

    //conecta ao banco
    $conexao = mysqli_connect($database['Host'], $database['User'], $database['Password'], $database['DBName']);

    $pais = mysqli_real_escape_string($conexao, $pais);
    //monta a querry
    $sqlQuerry = "INSERT INTO requests (pais) VALUES ('$pais')";

    
    //or die ("Configuração de Banco de Dados Errada!");
    
    $sql = mysqli_query($conexao, $sqlQuerry);

    mysqli_close($conexao);
}

// Verifica se o parâmetro 'pais' foi enviado na solicitação
if (isset($_GET['pais'])) {
    // Obtém o país da solicitação e chama a função getData
    $paisEscolhido = $_GET['pais'];
    getData($paisEscolhido);
} else {
    // Se o parâmetro 'pais' não foi enviado, retorna um JSON com a mensagem de erro
    header('Content-Type: application/json');
    echo json_encode(array("error" => "Parâmetro 'pais' não especificado na solicitação!"));
}
?>