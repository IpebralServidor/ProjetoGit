<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "conexaophp.php";

$codigodebarra = $_POST['codigodebarra'];
//fim da conexão com o banco de dados
$codbarra2 = $codigodebarra;

$nunota2 = $_POST['nunota'];

 
//$qtd = sqlsrv_num_rows($query);
?>

<section class="panel col-lg-9">

				<table width="1300" border="1px" bordercolor="black" style="margin-top: 5px; margin-left: 7px;" id="table">
			  <tr> 
			    <th width="10.6%" >Produto</th>
			    <th width="36.6%" style="text-align: center;">Descrição do Produto</th>
			    <th width="10.6%" align="center">UN</th>
			    <th width="12.6%" align="center">Controle</th>
			    <th width="12.6%" align="center">Ref. do Forn.</th>
			    <th width="16.6%" align="center">Código de Barras</th>


			  </tr>



			<?php 
				$tsql2 = "  DECLARE @CODEMP INT 
							DECLARE @CODPARC INT 

							SELECT @CODEMP = CODEMP,
								   @CODPARC = CODPARC
							FROM TGFCAB 
							WHERE NUNOTA = {$nunota2}

							SELECT TOP 1 TGFBAR.CODPROD, 
								   DESCRPROD,
								   TGFPRO.CODVOL,
								   TGFEST.CONTROLE,
								   ISNULL(CODPROPARC,'') AS CODPROPARC,
								   TGFBAR.CODBARRA
							FROM TGFPRO INNER JOIN
								 TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD INNER JOIN
								 TGFEST ON TGFEST.CODPROD = TGFPRO.CODPROD
									   AND TGFEST.CODPARC = 0
									   AND TGFEST.CODEMP = @CODEMP LEFT JOIN
								 TGFPAP ON TGFPAP.CODPROD = TGFPRO.CODPROD 
									   AND TGFPAP.CODPARC = @CODPARC
							WHERE TGFBAR.CODBARRA = '{$codbarra2}'
							"; 

				$stmt2 = sqlsrv_query( $conn, $tsql2);  

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

				<table width="1300" border="1" cellspacing="0" style="margin-left: 7px;">
				  <a href="detalhesconferencia.php?codprod=<?php echo $row2[0] ?>"><tr style="cursor: hand; cursor: pointer;">
				    <td width="10.6%" ><?php echo $row2[0]; ?>&nbsp;</td>
				    <td width="36.6%"><?php echo $row2[1]; ?>&nbsp;</td>
				    <td width="10.6%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
				    <td width="12.6%" align="center"><?php echo $row2[3]; ?></td>
				    <td width="12.6%" align="center"><?php echo $row2[4]; ?></td>
				    <td width="16.6%" align="center"><?php echo $row2[5]; ?></td>
				  </tr></a>
				 

			<?php
			}
			?>
		</table>

</section>