<?php

	$stmt ="";

	//Configuracoes do servidor SQL Server 
	$serverName = "10.0.0.228"; 
	$uid = "sankhya";   
	$pwd = "tecsis";  
	$databaseName = "SANKHYA_PROD"; 

	$connectionInfo = array( "UID"=>$uid,                            
	                         "PWD"=>$pwd,                            
	                         "Database"=>$databaseName); 

	/* Conexao com SQL Server usando autenticacao. */  
	$conn = sqlsrv_connect( $serverName, $connectionInfo); 

?>