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
       EST.ESTOQUE,
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
//$tsql = "SELECT *FROM TFPFUN WHERE NOMEFUNC  LIKE 'JOSE W%'";  

$stmt = sqlsrv_query( $conn, $tsql);  

//Verifica se retornou resultado
if ( $stmt )  
{  
    // echo "A query foi executada com sucesso..<br>\n";  
	echo "";

}   
else   
{  
     echo "Houve erro na execução da query.\n";  
     die( print_r( sqlsrv_errors(), true));  
}  

?><h1><font face="Arial, Helvetica, sans-serif" color="#0000CC">Resultado da Pesquisa</font></h1>
 <br />
 <br />
  <table width="1000" border="1"   bgcolor="#0000CC" bordercolor="#FFFFFF" >
  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
    <td width="15%" ><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Referência&nbsp;</font></td>  
     <td width="45%"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Descricao&nbsp;</font></td>
    <td width="8%"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Cód.Local&nbsp;</font></td>
    <td width="8%"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Estoque</font></td>
    <td width="8%"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Padrão</font></td>
    <td width="8%"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">max.loc</font></td>	
    <td width="8%"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Média</font></td>				
  </tr>


  
</table>
<?php	


//Faz um laco nas linhas retornadas e exibe as tres colunas na tela


while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))  
{ 
?>
  <table width="1000" border="1"  bordercolor="#EAEAEA" cellspacing="0">
  <tr>
    <td width="15%" ><font color="#0000CC"><?php echo $row[1]; ?>&nbsp;</font></td>  
     <td width="45%"><font color="#0000CC"><?php echo $row[2]; ?>&nbsp;</font></td>
    <td width="8%" align="center"><font color="#0000CC"><?php echo $row[3]; ?>&nbsp;</font></td>
    <td width="8%" align="center"><font color="#0000CC"><?php echo $row[4]; ?></font></td>
    <td width="8%" align="center"><font color="#0000CC"><?php echo $row[5]; ?></font></td>
    <td width="8%" align="center"><font color="#0000CC"><?php echo $row[6]; ?></font></td>		
    <td width="8%" align="center"><font color="#0000CC"><?php echo $row[7]; ?></font></td>		
  </tr>


  
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
<td>
<input type="button" value="Voltar" onClick="history.go(-1)" align="middle"> 
</td>
</tr>
</table>