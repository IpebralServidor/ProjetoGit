<?php  
include "../conexaophp.php";

session_start();

$nunotaorig = $_SESSION["nunotaorig"]; 
//$sequencia = $_POST["sequencia"];

$sequencia = $_REQUEST["sequencia"];
$usuconf = $_SESSION["idUsuario"];





$tsql = "

	exec AD_STP_FINALIZAPROCESSOESTOQUECD5 $nunotaorig, $usuconf

		 ";

$stmt = sqlsrv_query( $conn, $tsql);


header('Location: index.php');

?>