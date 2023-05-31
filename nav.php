<?php
	include "conexaophp.php";
	$usuconf = $_SESSION["idUsuario"];

 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Cabeçalho</title>
	<meta charset="UTF-8">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script type="text/javascript" src="ajax.js"></script>
	<!-- Font Awesome --> 
    <script defer src="font-awesome/fontawesome-all.js"></script>
	<!-- Jquery -->
	<script type="text/javascript" src="js/jquery.min.js"></script>
    <!-- Css -->
	<link rel="stylesheet" href="css/nav.css">
</head>
<body>

	<!-- pop up LOGIN e CADASTRO -->
	<div id="popAlterarSenha" style="display: none;">
	    <div class="alterasenha" id="div-altera">
	    	
	    	<button class="fechar" id="fechar" onclick="fechar();">X</button>
	    	
	    	<form method="post" id="form_alterasenha" action="">
	      	<label style="margin-bottom: 6px; margin-top: 13px;">  <i class="fas fa-at"></i> Nova Senha:</label><br>
	      		<input type="password" name="senha_alt" required><br>
	      	<label style="margin-bottom: 6px; margin-top: 13px;">  <i class="fas fa-lock"></i> Confirmar Senha:</label><br>
	      		<input type="password" name="senha_conf" required><br>
	      	<button name="AlteraSenha" id="btn-alterasenha" class="bk-azul-escuro color-white">Confirmar</button><br>
	      	<!--<p id="resp_login" ></p>-->
	      </form>
	    </div>  

	</div>

	<?php

		

		if(isset($_POST['AlteraSenha'])){
			
			//echo "<script> alert('Teste - $senhaalt, $senhaconf') </script>";

			$senhaalt = $_POST['senha_alt'];
			$senhaconf = $_POST['senha_conf'];
			
			if($senhaalt === $senhaconf){

				
				$tsql = "

					UPDATE TSIUSU SET AD_SENHA = '$senhaconf' WHERE CODUSU = $usuconf

						 ";

				$stmt = sqlsrv_query( $conn, $tsql);		

			} else {
				echo "<script> alert('As senhas são diferentes. Digite a mesma senha nas duas caixas de texto!') </script>";	
			}

			
		}

	?>





	<nav class="color-black bk-azul-claro"> 
	

		<div style="display: inline-block; ">
			<img style="width: 90%; display: inline-block; margin-top: 12px;" src="images/logo ipebral.png">
		</div>

		<div id="div-cabecalho-2">
			<a href="#" class="color-white" onclick="fecharmobile()"><p id="fecharM">fechar</p></a>

			<a href="#" class="link-nav color-black" onclick="abrir()"> 
				<span id="login-nav">
						<i class="fas fa-sign-in-alt la icons2"></i>Alterar Senha
				</span>
			</a> 

					  <span class="separador"> | </span>

			<a href="logout.php" class="link-nav color-black"> 
				<span id="cadastro-nav">
						<i class="fas fa-users icons2"></i>Sair
				</span>
			</a>

			
		</div><!-- div-cabecalho-2 -->
		 
		<div class="menu-icon">
			<a href="#" class="color-white" onclick="menuM()"><img src="images/menu.png"></a>
		</div>

	</nav>
</body>


<script type="text/javascript">

	var mobile=document.getElementById("div-cabecalho-2");

   	function abrir(){
			document.getElementById('popAlterarSenha').style.display = 'block';
		}
	function fechar(){
			document.getElementById('popAlterarSenha').style.display =  'none';
		}

	function menuM(){
    	mobile.style.display="block";
    }

    function fecharmobile(){
   		mobile.style.display="none";
   	}

</script>

</html>