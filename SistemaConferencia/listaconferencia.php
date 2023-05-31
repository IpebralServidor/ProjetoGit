<?php
	include "../conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];
	$_SESSION["codbarraselecionado"] = 0;
//Verifica se retornou resultado

/*if ( $stmt2 ) 
{  
    //echo "A query foi executada com sucesso..<br>\n";  
	echo "";

}   
else   
{  
     //echo "Houve erro na execução da query.\n";  
     //die( print_r( sqlsrv_errors(), true));  
} */ 
?>

<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Lista Conferência - <?php echo $usuconf; ?></title>

	<link href="css/main.css" rel='stylesheet' type='text/css' />

</head>
	<body style="width: 100%; height: 100%; overflow: hidden;">

		<div id="Filtro" style="width:10%; height: 95%; display: inline-block; position: fixed; padding-top: 10px;">
			
			<form action="listaconferencia.php" method="post">

				<br>
				<label for="numnota">Número da Nota:</label>
				<input type="text" name="NUMNOTA" class="text">

				<br><br>
				<label for="nunota">Número Único:</label>
				<input type="text" name="nunota" class="text">

				<br><br>
				<label for="status">status:</label>
				<select name="status">
					<option value= "todos">Todos</option>
					<option value= "aguardandoconf">Aguardando Conferência</option>
					<option value= "emandamento">Em Andamento</option>
					<option value= "aguardandorecont">Aguardando Recontagem</option>
					<option value= "recontemandamento">Recontagem em Andamento</option>
				</select>

				<br><br>
				<label for="parceiro">Parceiro:</label>
				<input type="text" name="parceiro" class="text">

				<input name="aplicar" type="submit" value="Aplicar" style="position: fixed; top: 0px; margin-left: 115px; margin-top:8px;">

			</form>
			
			<?php 

				$tsql2 = "";

				if(isset($_POST["aplicar"])){

				$nunota =  $_POST["nunota"];
				if($nunota == ''){
					$nunota = -1;
				}

				$numnota =  $_POST["NUMNOTA"];
				if($numnota == ''){
					$numnota = -1;
				}				

				$parceiro =  $_POST["parceiro"];
				if($parceiro == ''){
					$parceiro = -1;
				}

				$status = $_POST["status"];

				$tsql2 = "
					DECLARE @NUNOTA INT = $nunota
					DECLARE @NUMNOTA INT = $numnota
					DECLARE @CODPARC INT = $parceiro
					DECLARE @STATUS VARCHAR(100) = '$status'

					select DISTINCT NUNOTA,
									TGFCAB.CODTIPOPER,
									CASE WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'A' THEN 'Em Andamento'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'RA' THEN 'Recontagem em Andamento'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'RC' THEN 'Aguardando Liberação de Corte'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'AC' THEN 'Aguardando Conferência'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'RR' THEN 'Aguardando Recontagem'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'R' THEN 'Aguardando Recontagem'
										ELSE SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA)
									END AS STATUSCONF,
									TGFCAB.CODPARC,
									convert(varchar(max),DTMOV,103) AS DTMOV,
									CONVERT(VARCHAR(MAX),TGFCAB.OBSERVACAO) AS OBSERVACAO,
									NUMNOTA,
									TGFCAB.CODEMP,
									NOMEPARC,
									TGFTOP.DESCROPER,
									ORDEMCARGA,
									SEQCARGA,
									TGFCAB.QTDVOL,
									--Prioridade
									CODUSUCONF,
									NOMEUSU
					from TGFCAB inner JOIN
						 TGFCON2 ON TGFCON2.NUCONF = TGFCAB.NUCONFATUAL
							    AND TGFCON2.DHFINCONF IS NULL 
								INNER JOIN
						 TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC LEFT JOIN
						 TSIUSU ON TSIUSU.CODUSU = TGFCON2.CODUSUCONF INNER JOIN
						 TGFTOP ON TGFTOP.CODTIPOPER = TGFCAB.CODTIPOPER		
							   AND TGFTOP.DHALTER = (SELECT MAX(DHALTER) FROM TGFTOP TPO WHERE TPO.CODTIPOPER = TGFTOP.CODTIPOPER)
					where TGFCAB.LIBCONF = 'S'
					  AND TGFCAB.CODTIPOPER IN (1780,1781,1782,1742)
					  AND sankhya.SNK_GET_SATUSCONFERENCIA(NUNOTA) in ('A', 'RA', 'RC', 'AC', 'R', 'RR')
					  AND (NUNOTA = @NUNOTA or isnull(@NUNOTA,-1) = -1)
					  AND (TGFCAB.NUMNOTA = @NUMNOTA OR ISNULL(@NUMNOTA,-1) = -1)
					  AND (TGFCAB.CODPARC = @CODPARC or isnull(@CODPARC,-1) = -1)
					  AND ((@STATUS = 'todos') 
					  	OR (@STATUS = 'emandamento' AND sankhya.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'A')
					  	OR (@STATUS = 'aguardandorecont' AND sankhya.SNK_GET_SATUSCONFERENCIA(NUNOTA) IN ('R','RR'))
					  	OR (@STATUS = 'recontemandamento' AND sankhya.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'RA')
						 )
					  --AND TGFCAB.NUNOTA = 2777521

					UNION

					select DISTINCT NUNOTA,
									TGFCAB.CODTIPOPER,
									CASE WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'A' THEN 'Em Andamento'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'RA' THEN 'Recontagem em Andamento'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'RC' THEN 'Aguardando Liberação de Corte'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'AC' THEN 'Aguardando Conferência'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'RR' THEN 'Aguardando Recontagem'
										WHEN SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA) = 'R' THEN 'Aguardando Recontagem'
										ELSE SANKHYA.SNK_GET_SATUSCONFERENCIA(NUNOTA)
									END AS STATUSCONF,
									TGFCAB.CODPARC,
									convert(varchar(max),DTMOV,103) AS DTMOV,
									CONVERT(VARCHAR(MAX),TGFCAB.OBSERVACAO) AS OBSERVACAO,
									NUMNOTA,
									TGFCAB.CODEMP,
									NOMEPARC,
									TGFTOP.DESCROPER,
									ORDEMCARGA,
									SEQCARGA,
									TGFCAB.QTDVOL,
									--Prioridade
									NULL AS CODUSUCONF,
									NULL AS NOMEUSU
					from TGFCAB INNER JOIN
						 TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC INNER JOIN
						 TGFTOP ON TGFTOP.CODTIPOPER = TGFCAB.CODTIPOPER		
							   AND TGFTOP.DHALTER = (SELECT MAX(DHALTER) FROM TGFTOP TPO WHERE TPO.CODTIPOPER = TGFTOP.CODTIPOPER)
					where LIBCONF = 's'
					  and sankhya.SNK_GET_SATUSCONFERENCIA(NUNOTA) IN ('AC')
					  and NUCONFATUAL is null
					  AND TGFCAB.CODTIPOPER IN (1780,1781,1782,1742)
					  AND DTNEG > DATEADD(DAY,-10,GETDATE())
					  AND (NUNOTA = @NUNOTA or isnull(@NUNOTA,-1) = -1)
					  AND (TGFCAB.NUMNOTA = @NUMNOTA OR ISNULL(@NUMNOTA,-1) = -1)
					  AND (TGFCAB.CODPARC = @CODPARC or isnull(@CODPARC,-1) = -1)
					  AND (@STATUS = 'todos' or @STATUS = 'aguardandoconf')
						";
				
				//Exemplo de uma consulta simples em uma tabela do SQL Server
				/*if($nunota == null){
					$tsql2 = "select top 20 NUCONF,
								  NUNOTAORIG,
								  STATUS,
								  NUCONFORIG,
								  CONVERT(VARCHAR(MAX),DHINICONF,103),
								  CODUSUCONF,
								  TSIUSU.NOMEUSU,
								  QTDVOL
					from TGFCON2 inner join
						 TSIUSU ON TSIUSU.CODUSU = TGFCON2.CODUSUCONF
					where DHFINCONF is null
					order by DHINICONF desc
					";  
				} else {
					$tsql2 = "select top 50 NUCONF,
								  NUNOTAORIG,
								  STATUS,
								  NUCONFORIG,
								  CONVERT(VARCHAR(MAX),DHINICONF,103),
								  CODUSUCONF,
								  TSIUSU.NOMEUSU,
								  QTDVOL
					from TGFCON2 inner join
						 TSIUSU ON TSIUSU.CODUSU = TGFCON2.CODUSUCONF
					where DHFINCONF is null
					  AND (NUNOTAORIG = ISNULL(".$nunota.",0) OR ISNULL(".$nunota.",0) = 0)
					order by DHINICONF desc
					";  
				}*/

				
				}

				$stmt2 = sqlsrv_query( $conn, $tsql2);  
				$row_count = sqlsrv_num_rows( $stmt2 ); 

			?>

			<a href="../menu.php"><aabr title="Voltar para Menu"><img style="width: 40px; margin-bottom: 2px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>

		</div> <!-- Filtro -->

		<div id="ListaConferencia" style="display: inline-block; width: 85%; float: right; float: bottom; height: 97%; margin-left: 0; overflow: auto; margin-right: 1%; margin-bottom: 0;">

			<h4 style="margin-top: 2px; margin-left: 0; margin-bottom: 0;  padding-left:0px; padding-top: 2px; width: 1000px; text-align: center; ">Lista de Conferência</h4>
			<table width="4000">
			  <tr ><font size="-1" face="Arial, Helvetica, sans-serif" >
			    <th width="6.6%" ><font  face="Arial, Helvetica, sans-serif">Nro. Único</font></th>
			    <th width="6.6%" ><font  face="Arial, Helvetica, sans-serif">Tipo Operação</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Status Conferência</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Parceiro</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Dt. do Movimento</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Observação (Nota/Pedido)</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Nro. Nota</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Empresa</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Nome Fantasia (Empresa)</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Descrição (Tipo de Operação)</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Ordem de Carga</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Sequência da Carga</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Qtd. Volumes</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Cód. Conferente</font></th>
			    <th width="6.6%"><font  face="Arial, Helvetica, sans-serif">Nome (Conferente)</font></th>
			  </tr>




<?php


while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
{ $NUCONF = $row2[0];
?>

			
			 
			  <tr onclick="<?php echo "location.href='detalhesconferencia.php?nunota=$row2[0]&codbarra=0'" ?>" style="cursor: hand; cursor: pointer;">
			    <?php echo "<a href='detalhesconferencia.php?nunota=$row2[0]&codbarra=0'>" ?>
			    <td width="6.6%" ><?php echo $row2[0]; ?></td>
			    <td width="6.6%"><?php echo $row2[1]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[2]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[3]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[4]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[5]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[6]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[7]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[8]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[9]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[10]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[11]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[12]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[13]; ?></td>
			    <td width="6.6%" align="center"><?php echo $row2[14]; ?></td>
			  </tr> 
		



<?php

}
?>
	 </table>

		</div> <!-- ListaConferencia -->

	</body>
</html>