<?php
include "../conexaophp.php";
require_once '../App/auth.php';


?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/main.css">

	<title>Consulta Estoque</title>
</head>
<body>

	<a href="../menu.php"><aabr title="Voltar para Menu"><img style="width: 40px; margin-top: 10px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>

	
		<!--<h1 style="margin-top:20%;"> Consulta de estoque por local </h1>-->
		
		<div class="container">

			<div class="conteudo">
				<form action="consulta.php" method="post">


					<label for="nunota">Referência ou Código de Barra:</label><br>
					<input type="text" name="REFERENCIA" class="text" style="margin-top: 5%;">

					<br><br>
					<input name="pesquisar" type="submit" value="Pesquisar">

				</form>


			</div>
		</div> <!-- Container -->
</body>
</html>