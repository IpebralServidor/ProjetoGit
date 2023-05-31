<?php  
include "../conexaophp.php";

session_start();

$nunotaorig = $_SESSION["nunotaorig"]; 
//$sequencia = $_POST["sequencia"];

$sequencia = $_REQUEST["sequencia"];

	$tsql3 = "


	DELETE FROM TEMP_PRODUTOS_COLETOR WHERE sequencia = $sequencia

		 ";

	$stmt3 = sqlsrv_query( $conn, $tsql3);

header('Location: insereestoque.php?Itens=3');


?>