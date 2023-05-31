<?php
include "../conexaophp.php";
session_start(); //Iniciando a sessão

$nunotaorig = $_SESSION["nunotaorig"]; 
$toporigem   = $_SESSION["toporigem"];
$usuconf = $_SESSION["idUsuario"];




				$tsql = "

				DECLARE @VINCULONF INT,
	    	 		    @ULTCOD INT,
	    	 		    @NUNOTA INT = $nunotaorig,              
       				    @TOP int = $toporigem,
       				    @CODUSU INT = $usuconf

				
				exec AD_STP_INICIAPROCESSOESTOQUECD5 @NUNOTA, @TOP, @CODUSU
				
		 			";

			 	$stmt = sqlsrv_query($conn, $tsql);

echo $ULTCOD;


header('Location: insereestoque.php');


?>