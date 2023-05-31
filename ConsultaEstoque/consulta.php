<?php
include "../conexaophp.php";

$referencia = $_POST['REFERENCIA'];


//echo $referencia;

?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="css/consulta.css">
	<title>Consulta Estoque</title>
</head>
<body>

<div class="container">
	<a href="index.php"><aabr title="Voltar para Menu"><img style="width: 40px; margin-top: 10px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>


	<div class="titulo">
		
		<h1> Consulta de Estoque </h1>

	</div>

	<div class="infoproduto">
		<div class="infoprodutodiv">
			<?php
				$tsql = "
							
					DECLARE @REFERENCIA VARCHAR(100) = '$referencia'

					
					SELECT DISTINCT PRO.CODPROD,
							PRO.REFERENCIA,
							PRO.DESCRPROD,
							PRO.AD_QTDMAXLOCAL,
							round(MDV.MEDIA6,2) AS MEDIA
					FROM TGFPRO PRO inner join
							TGFBAR BAR ON BAR.CODPROD = PRO.CODPROD LEFT JOIN
							TGFEST EST ON EST.CODPROD = PRO.CODPROD 
														and EST.CODPARC = 0 
														AND est.estoque > 0 LEFT JOIN
							AD_MEDIAVENDAEMP MDV ON MDV.CODPROD = PRO.CODPROD
					WHERE bar.CODBARRA like trim(@REFERENCIA)
						AND MDV.CODEMP = 0
						 ";

				$stmt = sqlsrv_query( $conn, $tsql); 

				while( $row2 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC))  
				{ $produto = $row2[0];
				  $codreferencia = $row2[1];
				  $descrprod = $row2[2];
				  $qtdmaxlocal = $row2[3];
				  $mediavenda = $row2[4];
				}

				/*echo '<span class="infoprodutotext">Parc: {$codparcorig}</span><br>';
				echo '<span class="infoprodutotext">Nome Parc:</span><br>';
				echo '<span class="infoprodutotext">TOP Origem:</span><br>';
				echo '<span class="infoprodutotext">Núm. Ún. Orig.:</span><br>';
				echo '<span class="infoprodutotext">TOP Destino:</span><br>';
				echo '<span class="infoprodutotext">Núm. Ún. Dest.:</span><br>';*/

			?>

			<span class="infoprodutotext"><b>Produto:</b> <?php if(!isset($produto)){ echo '';} else { echo $produto;} ?></span><br>
			<span class="infoprodutotext"><b>Refência:</b> <?php if(!isset($codreferencia)){ echo '';} else { echo $codreferencia;} ?></span><br>
			<span class="infoprodutotext"><b>Descrição Produto:</b> <?php if(!isset($descrprod)){ echo '';} else { echo $descrprod;} ?></span><br>
			<span class="infoprodutotext"><b>Qtd. Máx. Local:</b> <?php if(!isset($qtdmaxlocal)){ echo '';} else { echo $qtdmaxlocal;} ?></span><br>
			<span class="infoprodutotext"><b>Média Venda:</b> <?php if(!isset($mediavenda)){ echo '';} else { echo $mediavenda;} ?></span><br>
		</div>
	</div> <!-- Fim infoproduto -->


	<div class="foto">
			<div class="foto-d"> 
				<?php

					if($referencia!=''){
						$tsql2 = " select ISNULL(IMAGEM,(SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000))
								   from TGFPRO inner join
									    --TGFITE ON TGFITE.CODPROD = TGFPRO.CODPROD INNER JOIN
										TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
								   where CODBARRA = '{$referencia}' ";
					} else {
						$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";
						}

					$stmt2 = sqlsrv_query( $conn, $tsql2);
					
					if($stmt2){
						$row_count = sqlsrv_num_rows( $stmt2 ); 

					
						while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
						{ //$img = $row2[0];
							//echo $img;
							//$varImg=base64_encode("'".$img."'");
							//echo "<img src='data:image/jpeg;base64,$varImg'>";

							echo '<img style="vertical-align: middle; margin: auto; max-width: 100%; max-height: 166px;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
							//$imageData = $row2["image"];
						}
					} 

				?>
			</div>
		</div> <!-- Fim Imagem -->



		<div class="estoqueproduto">
			
			<div style=" width: 97%; height: 43%; position: absolute; overflow: auto; margin-top: 5px;">
					<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr> 
						    <th width="20%" align="center">Empresa</th>
						    <th width="30%" align="center">Cód. Local</th>
						    <th width="25%" style="text-align: center;">Estoque</th>
						    <th width="25%" align="center">Padrão?</th>
						  </tr>


						  <?php 
							$tsql2 = "  
								DECLARE @REFERENCIA VARCHAR(100) = '$referencia'
		
								SELECT CODEMP,
									   SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),1,1) + '-'
									    +SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),2,2) + '.'
								        +SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),4,2) + '.'
								        +SUBSTRING(CAST(EST.codlocal as VARCHAR(7)),6,2) AS CODLOCAL,
								       REPLACE(CONVERT(VARCHAR(MAX),ROUND(EST.ESTOQUE -EST.RESERVADO,4)),'.',',') AS ESTOQUE,
									   CASE WHEN EST.CODLOCAL = PRO.AD_CODLOCAL
									        THEN 'X'
											ELSE ''
									   END  AS PADRAO
								FROM TGFPRO PRO INNER JOIN
								     TGFEST EST ON EST.CODPROD = PRO.CODPROD INNER JOIN
									 TGFBAR BAR ON BAR.CODPROD = EST.CODPROD
								WHERE est.estoque > 0
								  AND BAR.CODBARRA = TRIM(@REFERENCIA)
								  AND EST.CODPARC = 0 
								ORDER BY CODEMP,PRO.REFERENCIA,EST.CODLOCAL     

										"; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ $NUCONF = $row2[0];
						?>

							  <tr style="cursor: hand; cursor: pointer;">
							    <td width="20%" align="center"><?php echo $row2[0]; ?>&nbsp;</td>
							    <td width="30%" align="center"><?php echo $row2[1]; ?>&nbsp;</td>
							    <td width="25%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
							    <td width="25%" align="center"><?php echo $row2[3]; ?></td>
							  </tr>
							 

						<?php

							//echo $checkVariosProd;
						}
						?>

					</table>
				</div>
		</div> <!-- Estoque Produto -->

</div> <!-- Fim Container -->

</body>
</html>