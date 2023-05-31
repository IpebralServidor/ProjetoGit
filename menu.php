<?php
include "conexaophp.php";
require_once 'App/auth.php';

include 'nav.php';


// session_start();

?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/menu.css">

	<title>Menu</title>
</head>

<body>
	<!--<h1>Menu</h1>-->

	<div class="containerMenu">
		<div class="conteudoMenu">
			<a href="./SistemaConferencia/listaconferencia.php"><button class="botao1">CONFERÊNCIA</button></a>
			<a href="./SistemaEstoque"><button class="botao1">COLETOR</button></a>
			<a href="./ConsultaEstoque"><button>CONSULTA EST.</button></a>
		</div>
	</div>

</body>


</html>