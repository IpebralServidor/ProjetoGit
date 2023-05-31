<?php  
include "../conexaophp.php";

session_start();

$nunotaorig = $_SESSION["nunotaorig"];
$endereco = $_POST['ENDERECOTEMP'];
$usuconf = $_SESSION["idUsuario"];

echo $endereco;
echo '<br>'.$nunotaorig;

$tsql = "

	exec AD_STP_INSEREPRODUTO_TEMP_ITE_PROCESSOESTOQUECD5 $nunotaorig, $endereco, $usuconf

		 ";

$stmt = sqlsrv_query( $conn, $tsql);

header('Location: insereestoque.php?Itens=0');
$_SESSION["checkVariosProdutos"] = null;

?>