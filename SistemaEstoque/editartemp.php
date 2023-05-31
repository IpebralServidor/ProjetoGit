<?php  
include "../conexaophp.php";

session_start();

$nunotaorig = $_SESSION["nunotaorig"];

//$produtoeditar = $_POST["PRODUTOEDIT"];
$quantidadeeditar = $_POST["QUANTIDADEEDITTEMP"];

$sequencia = $_REQUEST["sequencia"];

/*echo $nunotaorig;
echo "<br>".$enderecoeditar;
echo "<br>".$quantidadeeditar;
echo "<br>".$sequencia;*/



$tsql3 = "
	
	UPDATE TEMP_PRODUTOS_COLETOR SET QTDNEG = $quantidadeeditar 
	WHERE SEQUENCIA = $sequencia

		 ";

$stmt3 = sqlsrv_query( $conn, $tsql3);



header('Location: insereestoque.php?Itens=3');
?>