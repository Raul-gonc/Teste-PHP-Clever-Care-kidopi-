<?php
// Função para obter os dados de um país específico
function getData($pais1, $pais2) {
    $ratePais1 = getDeathRate($pais1);
    $ratePais2 = getDeathRate($pais2);
    
    header('Content-Type: application/json');
        echo json_encode(array(
            $pais1 => $ratePais1,
            $pais2 => $ratePais2
    ));
}
function getDeathRate($pais) {

    $url = 'https://dev.kidopilabs.com.br/exercicio/covid.php?pais='.$pais;
        
    // Executa a requisição e obtém a resposta
    $response = file_get_contents($url);
    $responseArray = json_decode($response, true); // Decodifica como array associativo

    if ($response === FALSE) {
        // Se houver erro, retorna um JSON com a mensagem de erro
        header('Content-Type: application/json');
        echo json_encode(array("error" => "Erro ao acessar a API"));
    } else {
        // Inicializa as variáveis para armazenar os totais
        $mortesTotais = 0;
        $casosConfirmados = 0;
        // Itera sobre os elementos do array associativo
        foreach ($responseArray as $key => $value) {
            $mortesTotais += $value["Mortos"];
            $casosConfirmados += $value["Confirmados"];
        }
        return array(
            'Mortes' => $mortesTotais,
            'Confirmados' => $casosConfirmados,
            'Taxa' => $mortesTotais/$casosConfirmados
        );
    }
    
}



// Verifica se o parâmetro 'pais' foi enviado na solicitação
if (isset($_GET['pais1']) || isset($_GET['pais2'])) {
    // Obtém o país da solicitação e chama a função getData
    $pais1 = $_GET['pais1'];
    $pais2 = $_GET['pais2'];
    getData($pais1, $pais2);
} else {
    // Se o parâmetro 'pais' não foi enviado, retorna um JSON com a mensagem de erro
    header('Content-Type: application/json');
    echo json_encode(array("error" => "Parâmetro 'pais' não especificado na solicitação!"));
}
?>