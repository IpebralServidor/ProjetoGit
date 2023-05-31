<?php  
include "../conexaophp.php";

//$conn = new PDO( "sqlsrv:server=10.0.0.228 ; Database = SANKHYA_TESTE", "sankhya", "tecsis"); 

session_start();

$usuconf = $_SESSION["idUsuario"];	

$nunotaorig = $_SESSION["nunotaorig"]; 
$toporigem   = $_SESSION["toporigem"];
if(isset($_POST["checkVariosProdutos"])){
	$_SESSION["checkVariosProdutos"] = "on";
} else {
	$_SESSION["checkVariosProdutos"] = null;
}


//$checkVariosProd = $_SESSION["checkVariosProdutos"];
//if(!isset($_SESSION["produto"])){ $produtos = '';} else {$produtos = $_SESSION["produto"];}
//if(!isset($_SESSION["endereco"])){ $enderecos = '';} else {$enderecos = $_SESSION["endereco"];}
//if(!isset($_SESSION["quantidade"])){ $quantidades = '';} else {$quantidades = $_SESSION["quantidade"];}

//echo("<script> alert('$nunotaorig'); </script>");

		//echo $checkVariosProd;
		echo "<br>".$nunotaorig;
		echo '<pre>';
		print_r($_POST);
		print_r($_SESSION);


$produto = $_POST["PRODUTO"];
$_SESSION['produto'] = $produto;

if($produto != '' && !isset($_POST["checkVariosProdutos"])){ //Se checkbox de vários produtos estiver desmarcada insere na tabela física

	$quantidade = $_POST["QUANTIDADE"];
	$_SESSION['quantidade'] = $quantidade;

	

	if($quantidade != ''){
		$endereco = $_POST["ENDERECO"];
		$_SESSION['endereco'] = $endereco;	

		echo "<br>".$produto;
		echo "<br>".$endereco;
		echo "<br>".$quantidade;
		echo "<br>".$nunotaorig;
		if($quantidade > 0){


				$tsql = "
				
				exec AD_STP_INSEREPRODUTO_PROCESSOESTOQUECD5 $nunotaorig, $quantidade, '$produto', $endereco
				
		 			";

			 	$stmt = sqlsrv_query($conn, $tsql); 

			 	if($stmt){
				 	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))  
					{ 
						$retorno = $row[0];
					}

					if($retorno == "ERROINEXISTENTE"){
						//echo "<script> window.location.href='SistemaEstoque/insereestoque.php?Itens=10' </script>";
						//echo "<script> window.location.href='google.com' </script>";
						header('Location: insereestoque.php?Itens=10');
					} else if($retorno == "ERROQTD"){
						header('Location: insereestoque.php?Itens=20');
					}
					 else {
						header('Location: insereestoque.php?Itens=0');
					}

				}
		 	
		 
		 	$_SESSION['produto'] = '';
			$_SESSION['endereco'] = '';
			$_SESSION['quantidade'] = '';
			//$_SESSION['checkVariosProdutos'] = '';



		}
	}

} else if($produto != '' && isset($_POST["checkVariosProdutos"])){//Se checkbox de vários produtos estiver marcada insere na tabela temporária

	$quantidade = $_POST["QUANTIDADE"];
	$_SESSION['quantidade'] = $quantidade;

	
	if($quantidade != ''){
		$endereco = $_POST["ENDERECO"];
		$_SESSION['endereco'] = $endereco;	

		echo "<br>".$produto;
		echo "<br>".$endereco;
		echo "<br>".$quantidade;
		echo "<br>".$nunotaorig;
		if($quantidade > 0){


				$tsql = "
				
				exec AD_STP_INSEREPRODUTO_TEMP_PROCESSOESTOQUECD5 $nunotaorig, $quantidade, '$produto', $usuconf
				
		 			";

			 	$stmt = sqlsrv_query($conn, $tsql);

			 	if($stmt){
				 	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))  
					{ 
						$retorno = $row[0];
					}

					if($retorno == "ERROINEXISTENTE"){
						header('Location: insereestoque.php?Itens=10');
					} else if($retorno == "ERROQTD"){
						header('Location: insereestoque.php?Itens=20');
					}
					 else {
						header('Location: insereestoque.php?Itens=0');
					}

				}

		 	
		 
		 	$_SESSION['produto'] = '';
			$_SESSION['endereco'] = '';
			$_SESSION['quantidade'] = '';
			//$_SESSION['checkVariosProdutos'] = '';



		}
	}

}



// if(isset($retorno)){
// 	if($retorno == 'OK'){
// 		header('Location: insereestoque.php?Itens=0');
// 	}
// }

// if(!isset($retorno)){
// 		header('Location: insereestoque.php?Itens=0');
// }

?>					