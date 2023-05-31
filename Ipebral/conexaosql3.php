<?php

//Configuracoes do servidor SQL Server 
$serverName = "SERVER-BD"; 
$uid = "sankhya";   
$pwd = "tecsis";  
$databaseName = "SANKHYA_TESTE"; 

$connectionInfo = array( "UID"=>$uid,                            
                         "PWD"=>$pwd,                            
                         "Database"=>$databaseName); 

/* Conexao com SQL Server usando autenticacao. */  
$conn = sqlsrv_connect( $serverName, $connectionInfo);  

//Verifica se a conexao foi estabelecida com sucesso
if( $conn ) {
     echo "Conexão estabelecida.<br />";
}else{
     echo "Não foi possível conectar ao SQL Server.<br />";
     die( print_r( sqlsrv_errors(), true));
}

//Exemplo de uma consulta simples em uma tabela do SQL Server
$tsql = "SELECT *FROM TGFPAR WHERE RAZAOSOCIAL LIKE 'JOSE WI%'";  

$stmt = sqlsrv_query( $conn, $tsql);  

//Verifica se retornou resultado
if ( $stmt )  
{  
     echo "A query foi executada com sucesso..<br>\n";  
	      echo "-----------------<br>\n";  
}   
else   
{  
     echo "Houve erro na execução da query.\n";  
     die( print_r( sqlsrv_errors(), true));  
}  

//Faz um laco nas linhas retornadas e exibe as tres colunas na tela
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))  
{  
     echo "Codigo: ".$row[0]."\n";  
     echo "Razao Social: ".$row[3]."\n";  
	 echo "Pessoa: ".$row[4]."\n";  
     echo "Telefone ".$row[16]."<br>\n";  
     echo "-----------------<br>\n";  
}  

//Libera os recursos do SQL server
sqlsrv_free_stmt( $stmt);  

?>








