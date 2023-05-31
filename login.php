<?php
	include "conexaophp.php";

	if(isset($_REQUEST['alert'])){
		$alert = $_REQUEST['alert'];
		if($alert == 2){
			echo "<script>alert('Senha Inválida!');</script>";
		}	

		if($alert == 1){
			echo "<script>alert('Usuário Inexistente!');</script>";
		}
	}

	
?>

<!DOCTYPE html>
<html>
<head>
		<meta charset="utf-8">
		<link href="css/login.css" rel='stylesheet' type='text/css' />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!--<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>-->
		<!--webfonts-->
		<!--<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800' rel='stylesheet' type='text.css'/>-->
		<!--//webfonts-->
		<title>Login</title>
</head>
 
<body>
	<div class="main">
		<form method="post" action="App/session.php">
    		<h1><span>Login</span> <lable> Ipebral </lable> </h1>
  			<div class="inset">
	  			<p>
	    		 <label for="email">USUÁRIO</label>
   	 			<input type="text" name="username" class="text" autocomplete="off" required>
				</p>
  				<p>
				    <label for="password">SENHA</label>
				    <input type="password" name="password" class="text" required>
  				</p>
				  <!--<p>
				    <input type="checkbox" name="remember" id="remember">
				    <label for="remember">Remember me for 14 days</label>
				  </p>-->
 			 </div>

 	 
			  <p class="p-container">
			    <!--<span><a href="#">Esqueceu a Senha?</a></span>-->
			    <input type="submit" value="Login" name="submit" id="sub">
			  </p>
		</form>
	</div>  
</body>
</html>


<?php

		$stmt ="";


		/*
		if (isset($_POST['submit'])) {
			$un=$_POST['username'];
			$pw=$_POST['password'];
			$sltsenha="select senha from teste_leandro where usuario='".$un."'";
			$sqls=sqlsrv_query($conn, $sltsenha);
			//$row=sqlsrv_num_rows($sqls);

			//echo $row;

			if($row=sqlsrv_fetch_array($sqls, SQLSRV_FETCH_NUMERIC)){
				if ($pw==$row[0]) {
					header("location:listaconferencia.php");
					exit();
				}
				else
					echo "<script>alert('Senha Inválida!');</script>";
			}
			else 
				echo "<script>alert('Usuário Inválido!');</script>";
				//echo $sqls;
				//echo $row[0];
				//echo $pw;
		}*/

?>