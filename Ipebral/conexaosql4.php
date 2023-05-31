<?php
$referencia = $_POST['referencia'];
//Configuracoes do servidor SQL Server 
$serverName = "SERVER-BD"; 
$uid = "sankhya";   
$pwd = "tecsis";  
$databaseName = "SANKHYA_PROD"; 

$connectionInfo = array( "UID"=>$uid,                            
                         "PWD"=>$pwd,                            
                         "Database"=>$databaseName); 

/* Conexao com SQL Server usando autenticacao. */  
$conn = sqlsrv_connect( $serverName, $connectionInfo);  

//Verifica se a conexao foi estabelecida com sucesso
if( $conn ) {
     echo "";
	//   echo "Conexão estabelecida.<br />";
}else{
     echo "Não foi possível conectar ao SQL Server.<br />";
     die( print_r( sqlsrv_errors(), true));
}

//Exemplo de uma consulta simples em uma tabela do SQL Server
$tsql = "SELECT PRO.CODPROD,
       PRO.REFERENCIA,
	   PRO.DESCRPROD,
	   EST.CODLOCAL,
       EST.ESTOQUE -EST.RESERVADO AS ESTOQUE,
	   CASE WHEN EST.CODLOCAL = PRO.AD_CODLOCAL
	        THEN 'X'
			ELSE ''
	   END  AS PADRAO,
	   PRO.AD_QTDMAXLOCAL,
	   round(MDV.MEDIA,2)
	   



FROM TGFPRO PRO,
     TGFEST EST,
	 AD_MEDIAVENDA MDV
WHERE EST.CODPROD = PRO.CODPROD
AND MDV.CODPROD = PRO.CODPROD 
and est.estoque > 0
AND PRO.REFERENCIA like '".$referencia."%'  
AND EST.CODPARC = 0 
ORDER BY PRO.REFERENCIA,EST.CODLOCAL  
";  
$tsql2 = "SELECT DISTINCT PRO.CODPROD,
       PRO.REFERENCIA,
	   PRO.DESCRPROD,
	   PRO.AD_QTDMAXLOCAL,
	   round(MDV.MEDIA,2) AS MEDIA
	   



FROM TGFPRO PRO,
     TGFEST EST,
	 AD_MEDIAVENDA MDV
WHERE EST.CODPROD = PRO.CODPROD
AND MDV.CODPROD = PRO.CODPROD 
and est.estoque > 0
AND PRO.REFERENCIA like '".$referencia."%'  
AND EST.CODPARC = 0 
ORDER BY PRO.REFERENCIA
";  
//$tsql = "SELECT *FROM TFPFUN WHERE NOMEFUNC  LIKE 'JOSE W%'";  

$stmt = sqlsrv_query( $conn, $tsql);  
$stmt2 = sqlsrv_query( $conn, $tsql2);  

//Verifica se retornou resultado
if ( $stmt2    and $stmt ) 
{  
  //  echo "A query foi executada com sucesso..<br>\n";  
	echo "";

}   
else   
{  
     echo "Houve erro na execução da query.\n";  
     die( print_r( sqlsrv_errors(), true));  
}  




//Faz um laco nas linhas retornadas e exibe as tres colunas na tela


while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
{ $codprod = $row2[0];
?>

<table width="1000" border="1"  bordercolor="#EAEAEA" cellspacing="0">
  <tr>
    <td width="10%" ><font color="#0000CC"><?php echo $row2[0]; ?>&nbsp;</font></td>
    <td width="10%"><font color="#0000CC"><?php echo $row2[1]; ?>&nbsp;</font></td>
    <td width="50%" align="center"><font color="#0000CC"><?php echo $row2[2]; ?>&nbsp;</font></td>
    <td width="10%" align="center"><font color="#0000CC"><?php echo $row2[3]; ?></font></td>
    <td width="10%" align="center"><font color="#0000CC"><?php echo $row2[4]; ?></font></td>
  </tr>
  <?php
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) and $row[0] == $codprod)  
{
   ?>
  <table width="200" border="1"  bordercolor="#EAEAEA" cellspacing="0">
     <tr>
    <td width="50%" align="center"><font color="#0000CC"><?php echo $row[3]; ?></font></td>
    <td width="40%" align="center"><font color="#0000CC"><?php echo $row[4]; ?></font></td>
	   <td width="10%" align="center"><font color="#0000CC"><?php echo $row[5]; ?></font></td>
	</tr>
	</table>
  <?php



}   unset($row);
 unset($row2);
?>
</table>
<?php		 
}  

//Libera os recursos do SQL server
sqlsrv_free_stmt( $stmt);  

?>
<br />
<br />
<br />
<table width="1000" align="center">
  <tr>
    <td><input type="button" value="Voltar" onClick="history.go(-1)" align="middle">
    </td>
  </tr>
</table>
