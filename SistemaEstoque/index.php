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

	<title>Estoque CD3</title>
</head>
<body>

	<a href="../menu.php"><aabr title="Voltar para Menu"><img style="width: 40px; margin-top: 10px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>

		<div class="container">
			<div class="conteudo">
				<form action="index.php" method="post">


					<label for="nunota">Número Único:</label><br>
					<input type="text" name="NUNOTA" class="text">

					<br><br>
					<label for="codtipoper">TOP:</label><br>
					<input type="text" name="CODTIPOPER" class="text">

					<br><br>
					<input name="aplicar" type="submit" value="Confirmar">

				</form>

				<?php 

					if(isset($_POST["aplicar"])){

						$nunotaorig = $_POST["NUNOTA"];
						$_SESSION['nunotaorig'] = $nunotaorig;
						
						$toporigem = $_POST["CODTIPOPER"];
						$_SESSION['toporigem'] = $toporigem;
						

						//echo("<script> alert('$nunotaorig/$toporigem'); </script>");
						header('Location: action.php');

					}
				?>
			</div>
		</div> <!-- Container -->
</body>
</html>