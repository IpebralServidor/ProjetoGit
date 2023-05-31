<?php  
include "../conexaophp.php";
require_once '../App/auth.php';



$usuconf = $_SESSION["idUsuario"];

if(!isset($_REQUEST["Itens"])){ $item = '';} else {$item = $_REQUEST["Itens"];}
//$item = $_REQUEST["Itens"];

//echo '<pre>';
//print_r($_SESSION);

if($item == 2){
	if(!isset($_REQUEST["sequenciaedit"])){ $sequenciaEdit = 0;} else {$sequenciaEdit = $_REQUEST["sequenciaedit"];}
}

if($item == 4){
	if(!isset($_REQUEST["sequenciaedit"])){ $sequenciaEditTemp = 0;} else {$sequenciaEditTemp = $_REQUEST["sequenciaedit"];}
}

if(isset($_SESSION["sequencia"])){ unset($_SESSION["sequencia"]); } 

if(!isset($_SESSION["nunotaorig"])){ header('Location: index.php');;} else {$nunotaorig = $_SESSION["nunotaorig"];}
//$nunotaorig = $_SESSION["nunotaorig"]; 
$toporigem   = $_SESSION["toporigem"];
if(!isset($_SESSION["produto"])){ $produtos = '';} else {$produtos = $_SESSION["produto"];}
if(!isset($_SESSION["endereco"])){ $enderecos = '';} else {$enderecos = $_SESSION["endereco"];}
if(!isset($_SESSION["quantidade"])){ $quantidades = '';} else {$quantidades = $_SESSION["quantidade"];}
if(!isset($_REQUEST["checkVariosProd"])){ $checkVariosProd = '';} else {$checkVariosProd = $_REQUEST["checkVariosProd"];}

//echo $checkVariosProd;

if($item==10){
	echo("<script> alert('Este Produto não existe na nota de origem!'); </script>");
}

if($item==20){
	echo("<script> alert('Quantidade inserida acima da quantidade contida na nota de Origem!'); </script>");
}

//echo("<script> alert('$nunotaorig'); </script>");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/insereestoque.css">
	<title>Insere Estoque</title>


	<script type="text/javascript">
		function abrir(){
			document.getElementById('popupprodutos').style.display = 'block';
		}
		function fechar(){
			document.getElementById('popupprodutos').style.display =  'none';
		}
		function abrirEditar(){
			document.getElementById('popupEditar').style.display = 'block';
		}
		function fecharEditar(){
			document.getElementById('popupEditar').style.display =  'none';
		}

		function abrirdivergencias(){
			document.getElementById('popupdivergencias').style.display = 'block';
		}
		function fechardivergencias(){
			document.getElementById('popupdivergencias').style.display =  'none';
		}

		

		function delete_confirm(){

		        var result = confirm("Tem certeza que deseja apagar esse item?");
			        if(result){
			            return true;
			        }else{
			            return false;
			        }
		    }


		function abrirtemp(){
			document.getElementById('tempprodutos').style.display = 'block';
		}
		function fechartemp(){
			document.getElementById('tempprodutos').style.display =  'none';
		}
		function abrirEditarTemp(){
			document.getElementById('popupEditarTemp').style.display = 'block';
		}
		function fecharEditarTemp(){
			document.getElementById('popupEditarTemp').style.display =  'none';
		}
		function abrirInsereEndereco(){
			document.getElementById('popupInserirEndereco').style.display = 'block';
		}
		function fecharInsereEndereco(){
			document.getElementById('popupInserirEndereco').style.display =  'none';
		}

		function marca_variosprod_confirm(){

		        var result = confirm("Tem certeza que deseja Concluir?");
			        if(result){
			        	abrirInsereEndereco();
			            return true;
			        }else{
			        	// document.getElementsByClassName("resultadoVariosProd").checked;
			            return false;
			        }
		    }
	</script>



</head>
<body <?php
			if ($produtos != '' && $quantidades != ''){
						echo ' onload="document.fmrInsreItens.ENDERECO.focus();"';
			} else if($produtos != ''){
						echo ' onload="document.fmrInsreItens.QUANTIDADE.focus();"';
			} else{
						echo ' onload="document.fmrInsreItens.PRODUTO.focus();"';
						$produtos = '';
					}

		?>
>

<a href="index.php"><aabr title="Voltar"><img style="width: 40px; margin-top: 2px; margin-left: 10px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>

<div class="container">

	<div class="infonota">
		<div class="infonotadiv">
			<?php
				$tsql2 = "
							
							DECLARE @NUNOTA INT = $nunotaorig

							SELECT CABORIGEM.CODPARC,
								   SUBSTRING(PARORIGEM.RAZAOSOCIAL,1,7),
								   CABORIGEM.CODTIPOPER,
								   CABORIGEM.NUNOTA,
								   CABDESTINO.CODTIPOPER,
								   CABDESTINO.NUNOTA
							FROM TGFCAB CABORIGEM INNER JOIN	 
								 TGFCAB CABDESTINO ON CABDESTINO.NUNOTA = CABORIGEM.AD_VINCULONF INNER JOIN
								 TGFPAR PARORIGEM ON PARORIGEM.CODPARC = CABORIGEM.CODPARC INNER JOIN
								 TGFPAR PARDESTINO ON PARDESTINO.CODPARC = CABDESTINO.CODPARC
							WHERE CABORIGEM.NUNOTA = @NUNOTA

						 ";

				$stmt2 = sqlsrv_query( $conn, $tsql2); 

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $codparcorig = $row2[0];
				  $nomeparcorig = $row2[1];
				  $toporig = $row2[2];
				  $notaorig = $row2[3];
				  $topdest = $row2[4];
				  $notadest = $row2[5];
				}

				/*echo '<span class="infonotatext">Parc: {$codparcorig}</span><br>';
				echo '<span class="infonotatext">Nome Parc:</span><br>';
				echo '<span class="infonotatext">TOP Origem:</span><br>';
				echo '<span class="infonotatext">Núm. Ún. Orig.:</span><br>';
				echo '<span class="infonotatext">TOP Destino:</span><br>';
				echo '<span class="infonotatext">Núm. Ún. Dest.:</span><br>';*/

			?>

			<span class="infonotatext">Parc: <?php if(!isset($codparcorig)){ echo '';} else { echo $codparcorig;} ?></span><br>
			<span class="infonotatext">Nome Parc: <?php if(!isset($nomeparcorig)){ echo '';} else { echo $nomeparcorig;} ?></span><br>
			<span class="infonotatext">TOP Origem: <?php if(!isset($toporig)){ echo '';} else { echo $toporig;} ?></span><br>
			<span class="infonotatext">Núm. Ún. Orig.: <?php if(!isset($notaorig)){ echo '';} else { echo $notaorig;} ?></span><br>
			<span class="infonotatext">TOP Destino: <?php if(!isset($topdest)){ echo '';} else { echo $topdest;} ?></span><br>
			<span class="infonotatext">Núm. Ún. Dest.: <?php if(!isset($notadest)){ echo '';} else { echo $notadest;} ?></span><br>
		</div>
	</div> <!-- Fim infonota -->

	<div class="acoes"> 

		<div id="popupprodutos" class="popupprodutos">
			<button class="fechar" id="fechar" onclick="fechar();">X</button>
			<div style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">
					<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr> 
						  	<th width="4%" ></th>
						    <th width="2%" ></th>
						    <th width="10%" ></th>
						    <th width="25%" align="center">Referência</th>
						    <th width="25%" style="text-align: center;">Local Dest.</th>
						    <th width="25%" align="center">Qtd. Neg.</th>
						  </tr>


						  <?php 
							$tsql2 = "  
								DECLARE @NUNOTADEST INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)

								SELECT ROW_NUMBER() OVER(ORDER BY TGFITE.SEQUENCIA),
									   TGFPRO.REFERENCIA,
									   (SELECT ITE.CODLOCALORIG 
									    FROM TGFITE ITE 
										WHERE ITE.NUNOTA = TGFITE.NUNOTA 
										AND ITE.SEQUENCIA*-1 = TGFITE.SEQUENCIA) as CODLOCALDEST, 
									   TGFITE.CODLOCALORIG, 
									   TGFITE.QTDNEG,
									   TGFITE.SEQUENCIA
								FROM TGFITE INNER JOIN
									 TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
								WHERE NUNOTA = @NUNOTADEST
								  AND SEQUENCIA > 0
										"; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ $NUCONF = $row2[0];
						?>

							  <tr style="cursor: hand; cursor: pointer;">
							   <tr> 
							   	<td width="4%"><?php echo $row2[0]; ?>&nbsp;</td>
							    <td width="2%"><a href="delete.php?sequencia=<?php echo $row2[5]; ?>"><button class="excluir" onclick="return delete_confirm();">X</button></a>&nbsp;</td>
							    <td width="10%"><a href="insereestoque.php?Itens=2&sequenciaedit=<?php echo $row2[5]; ?>"><button class="editar" ">Editar</button></a>&nbsp;</td>
							    <td width="25%"><?php echo $row2[1]; ?>&nbsp;</td>
							    <td width="25%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
							    <td width="25%" align="center"><?php echo $row2[4]; ?></td>
							  </tr></a>
							 

						<?php

							//echo $checkVariosProd;
						}
						?>

					</table>
				</div>
		</div>

		<!-- Edição de Produtos -->
		<div id="popupEditar" class="popupEditar">
			<button class="fechar" id="fechar" onclick="fecharEditar();">X</button>
			
			<div style="width: 100%; height:100%;">

				<form action="editar.php?sequencia=<?php echo $sequenciaEdit; ?>" method="post" name="fmrEditaItens">
				
					<?php

						$tsql3 = "

							DECLARE @NUNOTA INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)

							SELECT REFERENCIA, 
								   (SELECT ITE.CODLOCALORIG 
									FROM TGFITE ITE 
									WHERE ITE.NUNOTA = TGFITE.NUNOTA 
									AND ITE.SEQUENCIA*-1 = TGFITE.SEQUENCIA) as CODLOCALDEST,
								   QTDNEG
							FROM TGFITE INNER JOIN	 
								 TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
							WHERE NUNOTA = @NUNOTA AND SEQUENCIA = $sequenciaEdit

					 			";

					 	$stmt3 = sqlsrv_query( $conn, $tsql3);

					 	if($stmt3){

						 	while( $row2 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))  
									{

									if(!isset($row2[0])){ $produtoedit = '';} else {$produtoedit = $row2[0];}
									if(!isset($row2[1])){ $localdestedit = '';} else {$localdestedit = $row2[1];}
									if(!isset($row2[2])){ $quantidadeedit = '';} else {$quantidadeedit = $row2[2];}
									}
						}

					if(isset($_POST["Editar"])){
						//$produtoeditar = $_POST["PRODUTOEDIT"];
						$enderecoeditar = $_POST["LOCALDESTEDIT"];
						$quantidadeeditar = $_POST["QUANTIDADEEDIT"];
						
						//echo "<script> window.location.href='editar.php?sequencia=$sequenciaEdit </script>";

						//header('Location: editar.php?sequencia='.$sequenciaEdit); 
					}


				 	?>

					<br>
					<label>Produto:</label><br>
					<input class="cxtexto" type="text" name="PRODUTOEDIT" class="text" value="<?php echo $produtoedit; ?>" disabled>

					<br><BR><label>Local Destino:</label><br>
					<input class="cxtexto" type="text" name="LOCALDESTEDIT" class="text" value="<?php echo $localdestedit; ?>">

					<br><br>
					<label>Quantidade:</label><br>
					<input class="cxtexto" type="text" name="QUANTIDADEEDIT" class="text" value="<?php echo $quantidadeedit; ?>">

					<br><br>
					<input id="Editar" name="Editar" type="submit" value="Editar">					 

				</form>
			</div>
		</div> <!-- POP UP para Editar Produtos -->


		<!-- Edição de Produtos Temp-->
		<div id="popupEditarTemp" class="popupEditarTemp">
			<button class="fechar" id="fechar" onclick="fecharEditarTemp();">X</button>
			
			<div style="width: 100%; height:100%;">

				<form action="editartemp.php?sequencia=<?php echo $sequenciaEditTemp; ?>" method="post" name="fmrEditaItens">
				
					<?php

						$tsql3 = "

							DECLARE @NUNOTADEST INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)

							SELECT REFERENCIA, 
								   QTDNEG
							FROM TEMP_PRODUTOS_COLETOR
							WHERE SEQUENCIA = $sequenciaEditTemp
							  AND CODUSU = $usuconf
							  and NUNOTA = @NUNOTADEST
							  

					 			";

					 	$stmt3 = sqlsrv_query( $conn, $tsql3);

					 	if($stmt3){

						 	while( $row2 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))  
									{

									if(!isset($row2[0])){ $produtoedittemp = '';} else {$produtoedittemp = $row2[0];}
									if(!isset($row2[1])){ $quantidadeedittemp = '';} else {$quantidadeedittemp = $row2[1];}
									}
						}

					if(isset($_POST["EditarTemp"])){
						//$produtoeditar = $_POST["PRODUTOEDIT"];
						//$enderecoeditar = $_POST["LOCALDESTEDIT"];
						$quantidadeeditar = $_POST["QUANTIDADEEDITTEMP"];
						
						//echo "<script> window.location.href='editar.php?sequencia=$sequenciaEdit </script>";

						//header('Location: editar.php?sequencia='.$sequenciaEdit); 
					}


				 	?>

					<br>
					<label>Produto:</label><br>
					<input class="cxtexto" type="text" name="PRODUTOEDITTEMP" class="text" value="<?php echo $produtoedittemp; ?>" disabled>

					<br><br>
					<label>Quantidade:</label><br>
					<input class="cxtexto" type="text" name="QUANTIDADEEDITTEMP" class="text" value="<?php echo $quantidadeedittemp; ?>">

					<br><br>
					<input id="Editar" name="EditarTemp" type="submit" value="Editar">					 

				</form>
			</div>
		</div> <!-- POP UP para Editar Produtos Temp-->

		<!-- Edição de Produtos Temp-->
		<div id="popupInserirEndereco" class="popupInserirEndereco">
			<button class="fechar" id="fechar" onclick="fecharInsereEndereco();">X</button>
			
			<div style="width: 100%; height:100%;">

				<form action="inserirTempITE.php" method="post" name="fmrEditaItens">
				

					<br>
					<label>Endereço:</label><br>
					<input class="cxtexto" type="text" name="ENDERECOTEMP" class="text" value="">


					<br><br>
					<input id="InserirTemp" name="InserirTemp" type="submit" value="Confirmar">					 

				</form>
			</div>
		</div> <!-- POP UP para Editar Produtos Temp-->


		<div id="tempprodutos" class="tempprodutos">
			<button class="fechar" id="fechar" onclick="fechartemp();">X</button>
			<div style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">
				<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr>
						  	<th width="5%" ></th> 
						    <th width="12.5%" ></th>
						    <th width="22.5%" ></th>
						    <th width="35%" align="center">Referência</th>
						    <th width="25%" align="center">Qtd. Neg.</th>
						  </tr>


						  <?php 
							$tsql2 = "  

								DECLARE @NUNOTADEST INT = (SELECT AD_VINCULONF FROM TGFCAB WHERE NUNOTA = $nunotaorig)

								select ROW_NUMBER() OVER(ORDER BY SEQUENCIA),
									   NUNOTA,
									   SEQUENCIA,
									   REFERENCIA,
									   QTDNEG,
									   CODUSU
								from TEMP_PRODUTOS_COLETOR
								where CODUSU = $usuconf
								  AND NUNOTA = @NUNOTADEST

										"; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ $NUCONF = $row2[0];
						?>

							  <tr style="cursor: hand; cursor: pointer;">
							   	<td width="5%"><?php echo $row2[0]; ?>&nbsp;</td> 
							    <td width="12.5%"><a href="deletetemp.php?sequencia=<?php echo $row2[2]; ?>"><button class="excluir" onclick="return delete_confirm();">X</button></a>&nbsp;</td>
							    <td width="22.5%"><a href="insereestoque.php?Itens=4&sequenciaedit=<?php echo $row2[2]; ?>"><button class="editar">Editar</button></a>&nbsp;</td>
							    <td width="35%"><?php echo $row2[3]; ?>&nbsp;</td>
							    <td width="25%" align="center"><?php echo $row2[4]; ?></td>
							  </tr></a>
							 

						<?php

							//echo $checkVariosProd;
						}
						?>

					</table>
			</div>
		</div> <!-- Temp dos Produtos -->

		<!-- POP UP Divergências -->
		<div id="popupdivergencias" class="popupdivergencias">
			<button class="fechar" id="fechar" onclick="fechardivergencias();">X</button>
			<div style=" width: 91%; height: 90%; position: absolute; overflow: auto; margin-top: 5px;">
				<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr>
						    <th width="40%" >Referência</th>
						    <th width="20%" >Qtd. Orig.</th>
						    <th width="20%" align="center">Qtd. Dest.</th>
						    <th width="20%" align="center">Diferença</th>
						  </tr>


						  <?php 
							$tsql2 = "  

								SELECT * FROM SANKHYA.AD_FNT_PRODUTOSDIVERGENTES_SISTEMAESTOQUE($nunotaorig)

										"; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ $NUCONF = $row2[0];
						?>

							  <tr style="cursor: hand; cursor: pointer;">
								<td width="40%"><?php echo $row2[0]; ?>&nbsp;</td>
							    <td width="20%"><?php echo $row2[1]; ?>&nbsp;</td>
							    <td width="20%"><?php echo $row2[2]; ?>&nbsp;</td>
							    <td width="20%" align="center"><?php echo $row2[3]; ?></td>
							  </tr></a>
							 

						<?php

							//echo $checkVariosProd;
						}
						?>

					</table>
			</div>
		</div> <!-- POP UP Divergências -->

		<div class="botoes">

			<form action="" method="post" name="fmrFinalizar">
				<input id="finalizar" name="Finalizar" type="submit" value="Finalizar">	
				<!-- <button id="finalizar" onclick="">Finalizar</button><br>-->

				<?php

					if(isset($_POST["Finalizar"])){

						//header('Location: finalizar.php'); 
						echo "<script> window.location.href='finalizar.php' </script>";

					}

				?>

			</form>

			<button class="editarbtn" onclick="abrir();">Editar Prod.</button>

			<button class="divergenciasbtn" onclick="abrirdivergencias();">Divergências</button>

			<button class="editarbtn" name="editarTempBtn" id="editarTempBtn" onclick="abrirtemp();">Editar Temp.</button>

		</div>
	</div> <!-- Fim acoes -->



<?php 

if($item==1){
	echo "<script> abrir(); </script>";
}

if($item==2){
	echo "<script> abrir(); abrirEditar();</script>";
}

if($item==3){
	echo "<script> abrirtemp(); </script>";
}

if($item==4){
	echo "<script> abrirtemp(); abrirEditarTemp()</script>";
}

?>


	<div class="item">
		<form action="insereestoquebtn.php" method="post" name="fmrInsreItens">
			

			<br>
			<label>Produto:</label><br>
			<input class="cxtexto" type="text" name="PRODUTO" class="text" value="<?php echo $produtos; ?>">
			<br> <input style="margin-top: 3px;" type="checkbox" class="checkVariosProdutos" name="checkVariosProdutos" id="checkVariosProdutos" value="on"  style="margin-top: 4px;" 
			<?php  if(isset($_SESSION["checkVariosProdutos"])){
					echo ' checked ';
					} ?> >
			<span id='resultadoVariosProd' style="margin-left:3px; margin-top: 0;">
			</span> <!--Retorno do resultado checkbox-->
			

			<br><br>
			<label>Quantidade:</label><br>
			<input class="cxtexto" type="text" name="QUANTIDADE" class="text" value="<?php echo $quantidades; ?>">

			<br><BR><label>Endereço:</label><br>
			<input class="cxtexto" type="text" name="ENDERECO" id="enderecoText" class="text" value="<?php echo $enderecos; ?>">

			

			<br><br>
			<input id="confirmar" name="confirmar" type="submit" value="Confirmar">


			<?php


				if(isset($_POST["confirmar"])){
					$produto = $_POST["PRODUTO"];
					$_SESSION['produto'] = $produto;

					if(isset($_POST['checkVariosProdutos'])){
					$_SESSION['checkVariosProdutos'] = $_POST['checkVariosProdutos'];};
					
					if($produto != ''){
							$quantidade = $_POST["QUANTIDADE"];
							$_SESSION['quantidade'] = $quantidade;


						if($quantidade > 0){

							if($quantidade != ''){
								$endereco = $_POST["ENDERECO"];
								$_SESSION['endereco'] = $endereco;

							$tsql = "
				
									exec AD_STP_INSEREPRODUTO_PROCESSOESTOQUECD5 $nunotaorig, $quantidade, '$produto', $endereco
									
							 			";

								 	$stmt = sqlsrv_query($conn, $tsql);


							}
						}
					}
				}

	
			?>
			
		</form>

		<div class="foto">
			<div class="foto-d"> 
				<?php

					if($produtos!=''){
						$tsql2 = " select ISNULL(IMAGEM,(SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000))
								   from TGFPRO inner join
									    --TGFITE ON TGFITE.CODPROD = TGFPRO.CODPROD INNER JOIN
										TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
								   where CODBARRA = '{$produtos}' ";
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
		</div>

		<?php

		$tsql3 = "
							DECLARE @CODBARRA VARCHAR(100) = '$produtos'

							SELECT TGFPRO.REFERENCIA,
								   ISNULL(TGFPAP.CODPROPARC,''),
								   TGFPRO.DESCRPROD
							FROM TGFPRO INNER JOIN	
								 TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD LEFT JOIN
								 TGFPAP ON TGFPAP.CODPROD = TGFPRO.CODPROD
									   AND TGFPAP.CODPARC = $codparcorig
									   AND TGFPAP.SEQUENCIA = 0
							WHERE TGFBAR.CODBARRA = @CODBARRA
						 ";

				$stmt3 = sqlsrv_query( $conn, $tsql3); 

				while( $row2 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))  
				{ 
				  if($produtos == ''){ $referencia = '';} else { $referencia = $row2[0];}
				  if($produtos == ''){ $codproparc = '';} else { $codproparc = $row2[1];}
				  if($produtos == ''){ $descrprod = '';} else { $descrprod = $row2[2];}
				}
	?>


		<br><span class="infonotatext">Referência: <?php if(!isset($referencia)){ echo '';} else { echo $referencia;} ?></span><br>
		<span class="infonotatext">Cód. Forn.: <?php if(!isset($codproparc)){ echo '';} else { echo $codproparc; } ?></span><br>
		<span class="infonotatext">Nome Prod.: <?php if(!isset($descrprod)){ echo '';} else { echo $descrprod; } ?></span><br>

		<?php //echo("<script> alert('$produtos/$enderecos'); </script>"); ?>
		

	</div><!-- Fim item -->

</div> <!-- Fim container -->

</body>
</html>


<!-- Evento de Clique do CheckBox para Marcar Vários Produtos -->
<script type="text/javascript">

 (function() {
    var elements = document.getElementsByClassName('checkVariosProdutos');
    var resultado = document.getElementById('resultadoVariosProd');
    var variosProdutos = 'Marcar Vários Produtos';
    
    for (var i = 0; i < elements.length; i++) {
        elements[i].onclick = function() {
            if (this.checked === false) {
                variosProdutos = 'Marcar Vários Produtos';
                <?php 
                if(isset($_SESSION["checkVariosProdutos"])){
                		echo " marca_variosprod_confirm(); 
                		";
                


                // $tsql = "
							

				// 			";

				// $stmt = sqlsrv_query( $conn, $tsql); 

                }
                ?>

                document.getElementById("enderecoText").disabled = false;
                document.getElementById("editarTempBtn").style.display = "none";
                

                 
            } else {
                variosProdutos = 'Desmarque para concluir';
                document.getElementById("enderecoText").disabled = true;
                document.getElementById("enderecoText").value = "";
                document.getElementById("editarTempBtn").style.display = "inline";

            }
            
            resultado.innerHTML = variosProdutos;
        }
        resultado.innerHTML = variosProdutos;

        <?php 

        	if(isset($_SESSION["checkVariosProdutos"])){
        		echo "resultado.innerHTML = 'Desmarque para concluir';";
        	}

        ?>
    }
})();

</script>

<?php 
	if(isset($_SESSION["checkVariosProdutos"])){
	echo '<script> document.getElementById("editarTempBtn").style.display = "inline"; document.getElementById("enderecoText").disabled = true;</script>';

}	
?>