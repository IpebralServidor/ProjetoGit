<?php

//Codigo da area a ser cnsultada
$codigo = 10095;

//Endpoint (url) da api
$url = 'https://webapis2.azurewebsites.net/api/Area/';
$token = "FG4Cu__Sq0-vv9zP4msM_-w0sjVRZVUvJYhggG_RSWKMCcfpvSEduTRXmgtmvE136iOInObObjslG6_6VdmuHQP6-B9ZmLaUEQE9vXO9ZD7SOMUmc6LRC8MUAyzuxMlLWmL1RIRkDzZ0VjkGvCOpUcLHoU0VzXkNYcQ3hxwhOjxWS-MVzFPx0-7SD7rNkRYVE6HPyk48seCKXmW_LgYsDKlOGiu3mPEv-qkLE3IaVOtTBNmmHHeNNCQFMFASh8hNKasjTJwpobKkcbx0CLjvL4oP7HhFPOVKlvIhClqhXJc";
$authorization = "Bearer " . $token;

//Inicia a requisicao
$curl = curl_init($url);

//Monta o header a ser enviado na requisicao
$header = array
(
  'empresa: 5',
  'Authorization: ' . $authorization
);

//Configura para retornar string
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

//Endpoint (url) a ser chamada (url + codigo da area)
curl_setopt($curl, CURLOPT_URL, $url . $codigo);

//Adiciona o header aa resuisicao
curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

// Envia a requisicao e recebe a resposta
$response = curl_exec($curl);

//Imprime a resposta na tela
print_r($response); 

?>

