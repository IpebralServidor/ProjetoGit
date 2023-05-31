<?php

//Endpoint (url) da api
$url = 'https://webapis2.azurewebsites.net/api/Area/';

//Token - valido por 14 horas
$token = "FG4Cu__Sq0-vv9zP4msM_-w0sjVRZVUvJYhggG_RSWKMCcfpvSEduTRXmgtmvE136iOInObObjslG6_6VdmuHQP6-B9ZmLaUEQE9vXO9ZD7SOMUmc6LRC8MUAyzuxMlLWmL1RIRkDzZ0VjkGvCOpUcLHoU0VzXkNYcQ3hxwhOjxWS-MVzFPx0-7SD7rNkRYVE6HPyk48seCKXmW_LgYsDKlOGiu3mPEv-qkLE3IaVOtTBNmmHHeNNCQFMFASh8hNKasjTJwpobKkcbx0CLjvL4oP7HhFPOVKlvIhClqhXJc";

//Autorizacao para acesso aa API
$authorization = "Bearer " . $token;

//Conteudo que sera enviado - nova area a ser incluida
$conteudo = '
{
  "CdArea": 0,
  "NmArea": "ZehLuiz - Teste Area",
  "VrArea": 98,
  "VrTotalArea": 98,
  "Qttempo": 50,
  "QtAreas": 1,
  "DsArea": "Teste incluido pelo zeh luiz",
  "CdProcedimento": 0
}';

//Inicia a requisicao
$curl = curl_init($url);

//Indica que sera executado o verbo http POST.
curl_setopt($curl, CURLOPT_POST, 1);

//Monta o header a ser enviado 
$header = array
(
  'empresa: 5',
  'Authorization: ' . $authorization,
  'Content-Type: application/json'
);

//Adiciona o header aa requisicao
curl_setopt($curl, CURLOPT_HTTPHEADER, $header); 

//Anexa o conteudo aa requisicao
curl_setopt($curl, CURLOPT_POSTFIELDS, $conteudo);

//Executa a resuisicao
$result = curl_exec($curl);

//Imprime a resposta
print_r($result); 

?>



