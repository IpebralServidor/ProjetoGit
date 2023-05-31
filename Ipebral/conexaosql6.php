<?php

$referencia = $_REQUEST['referencia'];


	 
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

//Verifica se a conexao foi estabelecida com sucesso
if( $conn ) {
     echo "";
	//   echo "Conexão estabelecida.<br />";
}else{
     echo "Não foi possível conectar ao SQL Server.<br />";
     die( print_r( sqlsrv_errors(), true));
}

//Exemplo de uma consulta simples em uma tabela do SQL Server

$tsql2 = "SELECT DISTINCT PRO.CODPROD,
       PRO.REFERENCIA,
	   PRO.DESCRPROD,
	   PRO.AD_QTDMAXLOCAL,
	   round(MDV.MEDIA,2) AS MEDIA
	   



FROM TGFPRO PRO,
     TGFEST EST,
	 TGFBAR bar ,
	 AD_MEDIAVENDA MDV
WHERE EST.CODPROD = PRO.CODPROD
AND bar.CODPROD = PRO.CODPROD
AND MDV.CODPROD = PRO.CODPROD 
and est.estoque > 0
AND bar.CODBARRA like '".$referencia."%'  
AND EST.CODPARC = 0 
ORDER BY PRO.REFERENCIA
";  
//$tsql = "SELECT *FROM TFPFUN WHERE NOMEFUNC  LIKE 'JOSE W%'";  

$stmt2 = sqlsrv_query( $conn, $tsql2);  
 $row_count = sqlsrv_num_rows( $stmt2 );  
//Verifica se retornou resultado
if ( $stmt2 ) 
{  
  //  echo "A query foi executada com sucesso..<br>\n";  
	echo "";

}   
else   
{  
     echo "Houve erro na execução da query.\n";  
     die( print_r( sqlsrv_errors(), true));  
}  

?>
<h1><font face="Arial, Helvetica, sans-serif" color="#0000CC">Resultado da Pesquisa</font></h1>
<br />
<br />

<?php




//Faz um laco nas linhas retornadas e exibe as tres colunas na tela


while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
{ $codprod = $row2[0];
?>
<table width="1000" border="0"   bgcolor="#0000CC" bordercolor="#FFFFFF" >
  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
    <td width="10%" ><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Código&nbsp;</font></td>
    <td width="10%" ><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Referência&nbsp;</font></td>
    <td width="50%"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Descricao&nbsp;</font></td>
    <td width="10%" align="center"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">max.loc</font></td>
    <td width="10%" align="center"><font  face="Arial, Helvetica, sans-serif" color="#FFFF33">Média</font></td>
  </tr>
</table>
<table width="1000" border="1"  bordercolor="#EAEAEA" cellspacing="0">
  <tr>
    <td width="10%" ><font color="#0000CC"><?php echo $row2[0]; ?>&nbsp;</font></td>
    <td width="10%"><font color="#0000CC"><?php echo $row2[1]; ?>&nbsp;</font></td>
    <td width="50%" align="center"><font color="#0000CC"><?php echo $row2[2]; ?>&nbsp;</font></td>
    <td width="10%" align="center"><font color="#0000CC"><?php echo $row2[3]; ?></font></td>
    <td width="10%" align="center"><font color="#0000CC"><?php echo $row2[4]; ?></font></td>
  </tr>
  <table width="1000" border="0"  bordercolor="#EAEAEA" cellspacing="0">
    <br />
    <tr>
      <td width="50%" align="center"><font color="#0000CC"></font></td>
      <td width="30%" align="center"><font  color="#FF0000">Cod.Local</font></td>
      <td width="10%" align="center"><font color="#FF0000">Estoque</font></td>
      <td width="10%" align="center"><font color="#FF0000">Padrão?</font></td>
    </tr>
  </table>
  <?php
  $tsql = "SELECT
SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),1,1) + '-'
+SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),2,2) + '.'
            + SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),4,2) + '.'
            + SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),6,2) AS CODLOCAL,
       EST.ESTOQUE -EST.RESERVADO AS ESTOQUE,
	   CASE WHEN EST.CODLOCAL = PRO.AD_CODLOCAL
	        THEN 'X'
			ELSE ''
	   END  AS PADRAO
FROM TGFPRO PRO,
     TGFEST EST
WHERE EST.CODPROD = PRO.CODPROD
and est.estoque > 0
AND PRO.CODPROD = ".$codprod." 
AND EST.CODPARC = 0 
ORDER BY PRO.REFERENCIA,EST.CODLOCAL  
";  
$stmt= sqlsrv_query( $conn, $tsql);    

//Verifica se retornou resultado
if ( $stmt ) 
{  
  //  echo "A query foi executada com sucesso..<br>\n";  
	echo "";

}   
else   
{  
     echo "Houve erro na execução da query.\n";  
     die( print_r( sqlsrv_errors(), true));  
}  

  
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) )  
{
   ?>
  <table width="1000" border="1"  bordercolor="#EAEAEA" cellspacing="0">
    <tr>
      <td width="50%" align="right"><font color="#0000CC"></font><button name="button" >Altera Local</button></td>
      <td width="30%" align="center"><font color="#0000CC"><?php echo $row[0]; ?></font></td>
      <td width="10%" align="center"><font color="#0000CC"><?php echo $row[1]; ?></font></td>
      <td width="10%" align="center"><font color="#0000CC"><?php echo $row[2]; ?></font></td>
    </tr>
  </table>
  <?php



}   unset($row);
 unset($row2);
?>
</table>
<br/>
<?php		 
}  

//Libera os recursos do SQL server
if ($stmt) {

sqlsrv_free_stmt( $stmt);  
} 
sqlsrv_free_stmt( $stmt2);  

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

