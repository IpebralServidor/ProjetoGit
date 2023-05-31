<?php  
include "../conexaophp.php";

session_start();

$nunotaorig = $_SESSION["nunotaorig"];

//$produtoeditar = $_POST["PRODUTOEDIT"];
$enderecoeditar = $_POST["LOCALDESTEDIT"];
$quantidadeeditar = $_POST["QUANTIDADEEDIT"];

$sequencia = $_REQUEST["sequencia"];

/*echo $nunotaorig;
echo "<br>".$enderecoeditar;
echo "<br>".$quantidadeeditar;
echo "<br>".$sequencia;*/

$tsql2 = "

	DECLARE @NUNOTA INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)

	UPDATE TGFITE SET CODLOCALORIG = $enderecoeditar 
	WHERE NUNOTA = @NUNOTA 
	  AND SEQUENCIA = $sequencia*-1

		 ";

$stmt2 = sqlsrv_query( $conn, $tsql2);


$tsql3 = "

	DECLARE @NUNOTA INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)
	
	UPDATE TGFITE SET QTDNEG = $quantidadeeditar 
	WHERE NUNOTA = @NUNOTA 
	  AND SEQUENCIA = $sequencia

		 ";

$stmt3 = sqlsrv_query( $conn, $tsql3);



 header('Location: insereestoque.php?Itens=1');
//$_SESSION["checkVariosProdutos"]) = null;
?>