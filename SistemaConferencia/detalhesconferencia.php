<?php
	include "../conexaophp.php";
	require_once 'App/auth.php';

	$usuconf = $_SESSION["idUsuario"];

	if(isset($_SESSION["codbarraselecionado"])){
		$codbarraselecionado = $_SESSION["codbarraselecionado"];
	} else {
		$codbarraselecionado = 0;
	}

	$nunota2 = $_REQUEST["nunota"];
	//$codbarra2 = $codbarra;

	// if($codbarra == 0){
	// 	$codbarra = '';
	// }

	$tsql2 = "  SELECT NUMNOTA,
					   CONVERT(VARCHAR(MAX),TGFCAB.CODVEND) + ' - ' + APELIDO,
					   CONVERT(VARCHAR(MAX),TGFCAB.CODPARC) + ' - ' + TRIM(RAZAOSOCIAL)
				FROM TGFCAB INNER JOIN
					 TGFPAR ON TGFPAR.CODPARC = TGFCAB.CODPARC INNER JOIN
					 TGFVEN ON TGFVEN.CODVEND = TGFCAB.CODVEND
				WHERE NUNOTA = {$nunota2}
							"; 

	$stmt2 = sqlsrv_query( $conn, $tsql2);  

	while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
	{ $NUMNOTA = $row2[0];
	  $VENDEDOR = $row2[1];
	  $PARCEIRO = $row2[2];

	}

	$tsql3 = "  DECLARE @NUNOTA INT = {$nunota2}
				DECLARE @ULTCOD INT 

				IF ((SELECT NUCONFATUAL FROM TGFCAB WHERE NUNOTA = @NUNOTA) IS NULL)
				BEGIN
					
					SET @ULTCOD = (SELECT ULTCOD + 1 FROM TGFNUM WHERE ARQUIVO = 'TGFCON2')
					UPDATE TGFNUM SET ULTCOD = @ULTCOD WHERE ARQUIVO = 'TGFCON2'
					
					INSERT INTO TGFCON2 (CODUSUCONF,DHFINCONF,DHINICONF,NUCONF,NUCONFORIG,NUNOTADEV,NUNOTAORIG,NUPEDCOMP,QTDVOL,STATUS)
					SELECT $usuconf,
							NULL,
							GETDATE(),
							@ULTCOD,
							NULL,
							NULL,
							@NUNOTA,
							NULL,
							0,
							'A'

					UPDATE TGFCAB SET NUCONFATUAL = @ULTCOD, LIBCONF = 'S' WHERE NUNOTA = @NUNOTA

				END
						"; 
		
		$stmt3 = sqlsrv_query( $conn, $tsql3);

		$tsql4 = " exec [sankhya].[AD_STP_MARCALINHA_CONFERENCIA] '$codbarraselecionado', $nunota2";
		$stmt4 = sqlsrv_query( $conn, $tsql4);
		while( $row2 = sqlsrv_fetch_array( $stmt4, SQLSRV_FETCH_NUMERIC))  
		{ $linhamarcada = $row2[0];
		}

?>

<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="content-type" ="text/html; charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalhes Conferência - <?php echo $usuconf; echo $linhamarcada;?></title>


	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">

	<link href="css/style.css" rel="stylesheet" type="text/css" media="all"/>

	<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>


	<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); }>
</script>

<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
					$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>


	<link href="css/main.css" rel='stylesheet' type='text/css' />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

	<?php 
		$tsql2 = "  select COUNT(*)
					from TGFITE inner join
							TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA FULL OUTER JOIN
							TGFCOI2 on TGFCOI2.NUCONF = TGFCAB.NUCONFATUAL
								AND TGFCOI2.CODPROD = TGFITE.CODPROD INNER JOIN
							TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
					where TGFITE.NUNOTA = '{$nunota2}'
						and (TGFCOI2.QTDCONF <> TGFITE.QTDNEG or TGFCOI2.QTDCONF is null)
				"; 

		$stmt2 = sqlsrv_query( $conn, $tsql2);  

		while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
		{ $QtdDivergencias = $row2[0];
		}

		$tsql3 = "  select COUNT(*)
					from TGFITE inner join
							TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA LEFT JOIN
							TGFCOI2 on TGFCOI2.NUCONF = TGFCAB.NUCONFATUAL
								AND TGFCOI2.CODPROD = TGFITE.CODPROD INNER JOIN
							TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
					where TGFITE.NUNOTA = '{$nunota2}'
						and ISNULL(TGFCOI2.QTDCONF,0) <> TGFITE.QTDNEG
				"; 

		$stmt3 = sqlsrv_query( $conn, $tsql3);  

		while( $row2 = sqlsrv_fetch_array( $stmt3, SQLSRV_FETCH_NUMERIC))  
		{ $QtdDivCorte = $row2[0];
		}

		//echo '<h4> '.$QtdDivergencias.'</h4>	';
	?>

	<script type="text/javascript">
		


		$(document).ready(function(){
	    $('#select_all').on('click',function(){
	        if(this.checked){
	            $('.checkbox').each(function(){
	                this.checked = true;
	            });
	        }else{
	             $('.checkbox').each(function(){
	                this.checked = false;
	            });
	        }
	    });
		
	    $('.checkbox').on('click',function(){
	        if($('.checkbox:checked').length == $('.checkbox').length){
	            $('#select_all').prop('checked',true);
	        }else{
	            $('#select_all').prop('checked',false);
	        }
	    });
	});


	function delete_confirm(){
    if($('.checkbox:checked').length > 0){
        var result = confirm("Tem certeza que deseja apagar esse(s) item(ns)?");
        if(result){
            return true;
        }else{
            return false;
        }
    }else{
        alert('Selecione pelo menos uma linha para poder excluir!');
        return false;
    }
}
	
	function confirmar_conf() {
		var result = confirm("Tem certeza que deseja confirmar essa conferência?");
        if(result){
        	if(<?php echo $QtdDivergencias ?> == 0 && <?php echo $QtdDivCorte ?> == 0){
	        	abrirconf();
	            return true;
        	} else if (<?php echo $QtdDivCorte ?> > 0){
				abrirconfdivcorte();
				return true;
			} else {
        		abrirconfdivergencia();
            return true;
        	}
        }else{
            return false;
        }
	}

	</script>

		<script type="text/javascript">
			function abrirpendencias(){
				document.getElementById('popuppendencias').style.display = 'block';
			}
			function fecharpendencias(){
				document.getElementById('popuppendencias').style.display =  'none';
			}
			function abrirdivergencias(){
				document.getElementById('popupdivergencias').style.display = 'block';
			}
			function fechardivergencias(){
				document.getElementById('popupdivergencias').style.display =  'none';
			}

			function abrirconf(){
				document.getElementById('popupconf').style.display = 'block';
			}
			function fecharconf(){
				document.getElementById('popupconf').style.display =  'none';
			}
			function abrirconfdivergencia(){
				document.getElementById('popupconfdivergencia').style.display =  'block';
			}
			function fecharconfdivergencia(){
				document.getElementById('popupconfdivergencia').style.display =  'none';
			}
			function abrirconfdivcorte(){
				document.getElementById('popupconfdivcorte').style.display =  'block';
			}
			function fecharconfdivcorte(){
				document.getElementById('popupconfdivcorte').style.display =  'none';
			}
			function abrirErroQtd(){
				document.getElementById('ErroQtdMaior').style.display =  'block';
			}
			function fecharErroQtd(){
				document.getElementById('ErroQtdMaior').style.display =  'none';
			}
		</script>

</head>
<body style="width: 100%; height: 100%; overflow: hidden; position: fixed;" onload="scrollToRow(<?php echo $linhamarcada; ?>)">

	<span style="margin-bottom: 0; margin-left: 30px; font-size: 20px;">
			<!--<button onclick="window.location.href='listaconferencia.php'"></button>-->
			<a href="listaconferencia.php"><aabr title="Voltar"><img style="width: 40px; margin-top: 45px; position: fixed;" src="images/Seta Voltar.png" /></aabr></a>
			<strong> Nro. Nota: &nbsp </strong> <?php echo $NUMNOTA ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Vendedor: </strong> <?php echo $VENDEDOR ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Nro. Único: </strong> <?php echo $nunota2; ?> &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
			<strong>Parceiro: </strong> <?php echo $PARCEIRO; ?> 
			
	</span>

	<div id="ErroQtdMaior" class="ErroQtdMaior">
		<button class="fechar" id="fechar" onclick="fechar();">X</button>

		<div style=" width: 98%; height: 100%; position: absolute; overflow: auto; margin-top: 5px;">
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display:inline-block;">Quantidade Maior do que a Permitida</h4>
		</div>
	</div>


	<!-- <form action="detalhesconferencia.php?nunota=<?php echo $nunota2 ?>&codbarra=<?php echo $codbarra ?>" method="post" style="margin-left: 80px; margin-top: -20px;" name="fmrInsreItens"> -->
		<div style="margin-left: 80px; margin-top: -20px;">
				<br><br>
				Cód. Barras: <input type="text" name="CODBAR" class="text" id='codigodebarra' style="margin-right: 30px;" required>

				Quantidade: <input type="text" name="QUANTIDADE" id="quantidade" class="text" style="margin-right: 30px; text-align: left;">

				Controle: <input type="text" name="CONTROLE" id="controle" class="text">

				<input name="conferir" id="conferir" type="submit" value="Conferir" style="margin-left: 50px;">

				<div id="insereitem" style="display: inline-block; margin-top: 5px;"></div>


			</div>
	<!-- </form> -->

	<!-- Itens em Conferência-->
	<div id="container" style="width:100%; height: 90%; position: absolute; bottom: 0; margin-bottom: 0; padding-left: 5px; padding-right: 10px;">
		
		<div id="ItensConferencia" style="width: 48%; height:48%; /*background-color: green;*/ display: inline-block; margin-right: 0; overflow: hidden; margin: 1%;">

			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display:inline-block;">Itens em Conferência</h4><h4 style="width:6%; display: inline-block; margin-bottom:0; text-align: center;"></h4>



			<button style="margin-left: 20px; margin-top: 5px; cursor: hand; cursor: pointer;" onclick="confirmar_conf();">Finalizar Conferência</button> 
			<button style="cursor: hand; cursor: pointer;" onclick="abrirdivergencias();">Produtos Divergentes</button>

			<?php 	

			$tsql2 = "select count(1) as contador from [sankhya].[AD_FN_pendencias_CONFERENCIA]($nunota2)  
										"; 

			$stmt2 = sqlsrv_query( $conn, $tsql2);  

			while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC))  
			{ 
				$auxiliar = $row2['contador'];	
				
			}

			// echo '<script>window.alert("'.$auxiliar.'")</script>';

			if($auxiliar > 0){ 										
				echo '<button id="btnPendencia" class="btnPendencia" onclick="abrirpendencias();">Pendências</button>';

			}


			?>

			<div id="popupconf" class="popupconf">

				<form action="finalizarconf.php" method="post" style="margin-left: 10px; margin-top: -10px;">
					<!-- <h6> Conferência finalizada com Sucesso! </h6> -->
					<br>Qtd. Volume: <input type="text" name="QTDVOLUME" class="text" value="" style="margin-top: 10px;" required>
					<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Volume: <input type="text" name="VOLUME" class="text" style="margin-top: 10px;" required>
					<br>&nbsp;&nbsp;&nbsp;Peso Bruto: <input type="text" name="PESOBRUTO" class="text" style="margin-top: 10px;">
					<input type="text" name="NUNOTACONF" class="text" value="<?php echo $nunota2 ?>" hidden>

					<input name="Confirmar" type="submit" value="Confirmar" style="margin-left: -35%; margin-top: 10%; margin-bottom: 5.8%; bottom: 0; position: absolute;">
 
				</form>

	
				<button style="cursor: hand; cursor: pointer; position: absolute; right: 0; bottom: 0; margin-bottom: 5.8%; margin-right: 33%;" onclick="fecharconf();">Fechar</button>

				

			</div>


			<!-- 
				POP UP de Produtos Divergentes 
			-->

			<div id="popupdivergencias" class="popupdivergencias">
				<!--<div class="modal__close close-modal" title="Close">
                        <i class='bx bx-x'></i>
                    </div>-->

				<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display:inline-block;">Produtos com Divergência</h4><h4 style="width:6%; display: inline-block; margin-bottom:0; text-align: center;"></h4>
				<div style=" width: 98%; height: 340px; position: absolute; overflow: auto; margin-top: 5px;">
					<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr> 
						    <th width="10.6%" >Produto</th>
						    <th width="36.6%" style="text-align: center;">Descrição do Produto</th>
						    <th width="10.6%" align="center">Complemento</th>
						    <th width="12.6%" align="center">Controle</th>
						    <th width="12.6%" align="center">Qtd. Conferida</th>
						    <th width="16.6%" align="center">Qtd. Pedido</th>
						  </tr>


						  <?php 
							$tsql2 = "  select TGFITE.CODPROD, 
											   DESCRPROD, 
											   COMPLDESC, 
											   TGFITE.CONTROLE, 
											   ISNULL(TGFCOI2.QTDCONF,0), 
											   TGFITE.QTDNEG
										from TGFITE inner join
											 TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA FULL OUTER JOIN
											 TGFCOI2 on TGFCOI2.NUCONF = TGFCAB.NUCONFATUAL
													AND TGFCOI2.CODPROD = TGFITE.CODPROD INNER JOIN
											 TGFPRO ON TGFPRO.CODPROD = TGFITE.CODPROD
										where TGFITE.NUNOTA = '{$nunota2}'
										  and (TGFCOI2.QTDCONF <> TGFITE.QTDNEG or TGFCOI2.QTDCONF is null)
										order by TGFCOI2.SEQCONF desc
										"; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ $NUCONF = $row2[0];
						?>

							  <tr style="cursor: hand; cursor: pointer;">
							   <tr> 
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
				</div>
				<button style="cursor: hand; cursor: pointer; position: relative; float: right; right: 0; bottom: 0; margin-bottom: 2%; margin-right: 2%; margin-top: 10px; position: absolute;" onclick="fechardivergencias();">Fechar</button>
			</div>

			<!-- Fim 
			POP UP de Produtos Divergentes 
			-->

			<div id="popuppendencias" class="popuppendencias">
				
				<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display:inline-block;">Produtos com Divergência</h4><h4 style="width:6%; display: inline-block; margin-bottom:0; text-align: center;"></h4>
				<div style=" width: 98%; height: 340px; position: absolute; overflow: auto; margin-top: 5px;">
					<table width="98%" border="1px" style="margin-top: 5px; margin-left: 7px;" id="table">
						  <tr> 
						    <th width="20.0%" align="center">Referencia</th>
						    <th width="40.0%" style="text-align: center;">Descrição do Produto</th>
						    <th width="20.0%" >Local</th>
						    <th width="20.0%" align="center">Quantidade</th>
						  </tr>


						  <?php 
							$tsql2 = "select * from [sankhya].[AD_FN_pendencias_CONFERENCIA]($nunota2)  
										"; 

							$stmt2 = sqlsrv_query( $conn, $tsql2);  

							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ 
						?>

							  <tr style="cursor: hand; cursor: pointer;">
							   <tr> 
							    <td width="20.0%" ><?php echo $row2[0]; ?></td>
							    <td width="40.0%"><?php echo $row2[1]; ?></td>
							    <td width="20.0%" align="center"><?php echo $row2[2]; ?></td>
							    <td width="20.0%" align="center"><?php echo $row2[3]; ?></td>
							  </tr>
							 

						<?php
						}
						?>

					</table>
				</div>
				<button style="cursor: hand; cursor: pointer; position: relative; float: right; right: 0; bottom: 0; margin-bottom: 2%; margin-right: 2%; margin-top: 10px; position: absolute;" onclick="fecharpendencias();">Fechar</button>
			</div>


			<!-- 
				POP UP Para Conferência Finalizada com Divergência
			-->



			<div id="popupconfdivergencia" class="popupconfdivergencia">
				<h4 style="text-align: center; margin-top: 8%;">Conferência finalizada como divergente!</h4>
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto;" onclick="" >Realizar recontagem</button>
				<!--<form>
					<input type="submit" name="btn-recontagem" value="Realizar recontagem">
				</form>-->
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="fecharconfdivergencia(); abrirconf();">Concluir</button>
			</div>

			<!-- Fim 
				POP UP Para Conferência Finalizada com Divergência
			-->


			<!-- 
				POP UP Para Conferência Finalizada com Divergência (Corte)
			-->

			<?php

				if(isset($_POST['btn-recontagem'])){
					    $tsql3 = "  DECLARE @ULTCOD INT
									select @ULTCOD = ULTCOD + 1 from TGFNUM where ARQUIVO = 'TGFCON2' and CODEMP = 1 and SERIE = '.'

									UPDATE TGFNUM SET ULTCOD = @ULTCOD WHERE ARQUIVO = 'TGFCON2' and CODEMP = 1 and SERIE = '.'

									UPDATE TGFCAB SET NUCONFATUAL = @ULTCOD WHERE TGFCAB.NUNOTA = $nunota2
					            "; 

					    $stmt3 = sqlsrv_query( $conn, $tsql3); 
									}

			?>

			<div id="popupconfdivcorte" class="popupconfdivcorte">
				<h4 style="text-align: center; margin-top: 3%;">Conferência finalizada como divergente!</h4>
				<!--<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto;" onclick="" >Realizar recontagem</button>-->
				<form method="post" style="width: 100%;">
					<input style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" type="submit" name="btn-recontagem" value="Realizar Recontagem">
				</form>
				<form method="post" action="cortaritens.php?nunota=<?php echo $nunota2; ?>" style="width: 100%;">
					<input style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" type="submit" name="btn-corte" value="Cortar Itens Divergentes">
				</form>
				<!-- <button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="" >Cortar itens divergentes</button> -->
				
				<button style="cursor: hand; cursor: pointer; display: block; width: 80%; margin-left: auto; margin-right: auto; margin-top: 3%;" onclick="fecharconfdivcorte(); abrirconf();">Concluir</button>
			</div>

			<!-- Fim 
				POP UP Para Conferência Finalizada com Divergência (Corte)
			-->

		 <div style="overflow: auto; height: 83.5%;">

		 	<div id="produtoconferencia">
			
				<table width="1300" border="1px" bordercolor="black" style="margin-top: 5px; margin-left: 7px;" id="table">
				  <tr> 
				    <th width="10.6%" >Produto</th>
				    <th width="36.6%" style="text-align: center;">Descrição do Produto</th>
				    <th width="10.6%" align="center">UN</th>
				    <th width="12.6%" align="center">Controle</th>
				    <th width="12.6%" align="center">Ref. do Forn.</th>
				    <th width="16.6%" align="center">Código de Barras</th>


				  </tr>
				</table>

			</div>

	 	 </div>

		</div> <!-- Itens Conferência -->





		<!-- Itens Conferidos-->

		<div style="width: 48%; height:48%; /*background-color: yellow;*/ display: inline-block; float: right; margin-left: 0; margin: 1%; overflow: hidden;">
			
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display: inline-block;">Itens Conferidos</h4><h4 style="width: 6%; color: black; display: inline-block; margin-bottom:0; text-align: center;padding-bottom: 1px; "><?php 
				$tsql2 = "  DECLARE @NUNOTA INT = $nunota2
							DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)

							SELECT COUNT (1)
							FROM TGFPRO INNER JOIN
								 TGFCOI2 ON TGFCOI2.CODPROD = TGFPRO.CODPROD
							WHERE TGFCOI2.NUCONF = @NUCONF
								 "; 

						$stmt2 = sqlsrv_query( $conn, $tsql2);  

						while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
						{ $QtdConferidos = $row2[0];
						}

						echo $QtdConferidos;
											  ?></h4>


		<form name="bulk_action_form" action="action.php" method="post" onSubmit="return delete_confirm();"/>
		<div style="overflow: auto; height: 92%; width: 109.5%;" id="itensconferidos">
			<table width="2500" border="1px"    bordercolor="white" style="margin-top: 30px; margin-left: 7px;" id="table">
			  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
			  	<th width="1%" style="margin-right: 0; "><input type="checkbox" id="select_all" value=""/></th> 
			    <th width="4%" ><font  face="Arial, Helvetica, sans-serif">Referência</font></th>
			    <th width="5%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Produto</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qtd. Conf.</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Barras</font></th>
			    <th width="17%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descrição (Produto)</font></th>
			    <th width="5%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">UN</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qth. Un. Pad.</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Complemento</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Ref. Forn.</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Marca</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qth. Ident.</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Tip. Ident.</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tip. Contr. Est.</font></th>
			  </tr>

			<?php 
				$tsql2 = "  DECLARE @NUNOTA INT = {$nunota2}
							DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)

							SELECT REFERENCIA,
								   TGFPRO.CODPROD,
								   TGFCOI2.QTDCONF,
								   TGFCOI2.CODBARRA,
								   DESCRPROD,
								   TGFPRO.CODVOL,
								   CONTROLE,
								   TGFCOI2.QTDCONFVOLPAD,
								   NULL AS COMPLEMENTO,
								   REFFORN,
								   MARCA,
								   QTDIDENTIF,
								   TIPOIDENTIF,
								   TIPCONTEST
							FROM TGFPRO INNER JOIN
								 TGFCOI2 ON TGFCOI2.CODPROD = TGFPRO.CODPROD
							WHERE TGFCOI2.NUCONF = @NUCONF
							ORDER BY SEQCONF DESC
						 "; 

				$stmt2 = sqlsrv_query( $conn, $tsql2);  

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

				
				  <tr >
				  	<td align="center" width="1%"><input type="checkbox" name="id[<?php echo "$row2[3]/$nunota2"; ?>]" class="checkbox"/></td>  
				    <td width="4%" ><?php echo $row2[0]; ?>&nbsp;</td>
				    <td width="5%"><?php echo $row2[1]; ?>&nbsp;</td>
				    <td width="5%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
				    <td width="10%" align="center"><?php echo $row2[3]; ?></td>
				    <td width="17%" align="center"><?php echo $row2[4]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[5]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[6]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[7]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[8]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[9]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[10]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[11]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[12]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[13]; ?></td>
				  </tr>
				 

			<?php
			}
			?>
			</table></div>
			<div style="background-color: white ; position: absolute; top: 4px; width: 2000px; z-index: 10; height: 30px; margin-top:35px;">
			<input type="submit" name="bulk_delete_submit" value="Apagar Item(ns) Selecionado(s)" style="position: absolute; top: 5px; margin-left: 0px; border-collor: white; width: 230px; text-align: left; border-radius: 5px; border-width: 1px; padding-top:1px;"></div>
		 
		</form>
		</div> <!-- Itens Conferidos -->



		<!-- Imagem e Consulta de Produtos -->
		<div id="Imagem do Produto" style="width: 48%; height: 43%; /*background-color: #D9DAFA;*/ display: inline-block; overflow: hidden; margin: 1%; ">
			
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 100%; text-align:center;">Informações do Produto</h4>

			<div style="width: 50%; height: 100%; line-height: 100%; align-items: center; display: inline-flex; margin-top: 0; padding-top: 0" id="imagemproduto">

					<?php

						$tsql2 = "SELECT IMAGEM FROM TGFPRO WHERE CODPROD = 1000 ";

						$stmt2 = sqlsrv_query( $conn, $tsql2);
						
						if($stmt2){
							$row_count = sqlsrv_num_rows( $stmt2 ); 

						
							while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
							{ 
								echo '<img style="vertical-align: middle;  max-width: 280px; margin: auto; max-height: 90%;" src="data:image/jpeg;base64,'.base64_encode($row2[0]).'"/>';
								//$imageData = $row2["image"];
							}
						} 

					?>


			</div > <!-- Parte da Imagem -->

			<!-- Parte das Características -->
			<div style=" display: inline-block; height: 90%; width: 49%; overflow-y: hidden; overflow-x: hidden; margin-top: 10px;"> 

				<div id="caracteristicas">

				<nav class="nav_tabs">
				    <ul>
				        <li>
				            <input type="radio" id="tab1" class="rd_tab" name="tabs" checked>
				            <label for="tab1" class="tab_label">Carac.</label>
				            <div class="tab-content">
				                <!--<h2>Title 1</h2>-->
				                <article>
				                <div style="margin-top: 10px;">
				                   
								</div>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab2">
				            <label for="tab2" class="tab_label">Comp.</label>
				            <div class="tab-content" style="overflow: auto;">
				                <!-- <h2>Title 2</h2> -->
				                <article>
					                
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab3">
				            <label for="tab3" class="tab_label">Estoque</label>
				            <div class="tab-content">
				                <!-- <h2>Title 3</h2> -->
				                <article>
				                    
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab4">
				            <label for="tab4" class="tab_label">Preço</label>
				            <div class="tab-content">
				                <!-- <h2>Title 4</h2> -->
				                <article>
				                	
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab5">
				            <label for="tab5" class="tab_label">Res.</label>
				            <div class="tab-content">
				                <!-- <h2>Title 5</h2> -->
				                <article>
				                    
				                </article>
				            </div>
				        </li>
				    </ul>
				</nav>

				</div>


			</div> <!-- Parte das Características -->

		</div> <!-- Imagem do Produto -->




		<div id="Itens do Pedido" style="width: 48%; height:43%; /*background-color: red;*/ display: inline-block; float: right;  margin-top: -200px; margin: 1%; overflow: hidden; margin-left: 0;">
			
			<h4 style="margin-top: 0px; margin-left: 0; margin-bottom: 0; background-color: #ADADC7; padding-left:15px; padding-top: 2px; width: 90%; display: inline-block;">Itens do Pedido</h4><h4 style="display: inline-block; margin-bottom: 0; text-align: center; width: 6%;"><?php 
				$tsql2 = "  SELECT COUNT(1)
							FROM TGFCAB CAB INNER JOIN
									TGFITE ITE ON ITE.NUNOTA = CAB.NUNOTA INNER JOIN
									TGFPRO PRO ON PRO.CODPROD = ITE.CODPROD LEFT JOIN
									TGFBAR BAR ON BAR.CODPROD = PRO.CODPROD 
											  AND BAR.DHALTER = (SELECT MAX(TGFBAR.DHALTER) 
											  					 FROM TGFBAR 
											  					 WHERE TGFBAR.CODPROD = BAR.CODPROD
											  					) INNER JOIN
									TGFVOL VOL ON VOL.CODVOL = ITE.CODVOL 
							WHERE CAB.NUNOTA = $nunota2
								 "; 

						$stmt2 = sqlsrv_query( $conn, $tsql2);  

						while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
						{ $QtdPedido = $row2[0];
						}

						echo $QtdPedido;
											  ?></h4>

			<!--<button style="margin-left: 20px; margin-top: 5px; cursor: hand; cursor: pointer; " >Espaço</button> -->
			<div style="overflow: auto; width: 100%; height: 95%;" >
			<table width="2000" border="1px" bordercolor="black" style="margin-top: 5px; margin-left: 7px;" id="minhaTabela">
			  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Referência&nbsp;</font></th>
			    <th width="5%" ><font  face="Arial, Helvetica, sans-serif">Produto&nbsp;</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Barra&nbsp;</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Qtd. Neg.</font></th>
			    <th width="25%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descrição (Produto)</font></th>
			    <th width="10%" align="center"><font  face="Arial, Helvetica, sans-serif">Nro. Único</font></th>
			    <th width="10%" align="center"><font  face="Arial, Helvetica, sans-serif">Sequência</font></th>
			    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">UN</font></th>
			    <th width="10%" align="center"><font  face="Arial, Helvetica, sans-serif">Descrição (UN)</font></th>
			    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
			  </tr>
			


						<!-- Pesquisa o Número da Nota no Banco para que sejam retornados os itens -->
			<?php 
				$tsql2 = "  SELECT PRO.REFERENCIA,
								   ITE.CODPROD,
								   BAR.CODBARRA,
								   ITE.QTDNEG,
								   PRO.DESCRPROD,
								   CAB.NUNOTA,
								   ITE.SEQUENCIA,
								   ITE.CODVOL,
								   VOL.DESCRVOL,
								   ITE.CONTROLE,
								   ITE.AD_DESCMAX,
								   ITE.AD_MOTIVOCORTE,
								   ITE.AD_DIF_ICMSST,
								   ITE.AD_EXCECAOTAB,
								   ITE.AD_CODPROPAR,
								   ITE.AD_VINCULONF,
								   ITE.AD_VINCULO_SEQ,
								   ITE.AD_MOTIVOVINCULONF,
								   PRO.REFFORN,
								   PRO.MARCA,
								   PRO.DECQTD,
								   PRO.AD_MANUFATUR,
								   PRO.AD_CODLOCSEC,
								   PRO.AD_CNPJ,
								   PRO.AD_EMBALAGEM,
								   PRO.AD_CODLOCAL,
								   PRO.AD_PROMO_FAST,
								   PRO.AD_NROCAIXA,
								   PRO.AD_CODLOCAL3,
								   PRO.AD_CODLOCAL4,
								   PRO.AD_CODLOCALPADEMP1,
								   PRO.AD_CODLOCALPADEMP1,
								   --PRO.AD_CODLOCALEMP1,
								   --PRO.AD_CODLOCALEMP7,
								   PRO.AD_DTPRIMEIRAENTRADA,
								   PRO.AD_SAZONAL
							FROM TGFCAB CAB INNER JOIN
								 TGFITE ITE ON ITE.NUNOTA = CAB.NUNOTA INNER JOIN
								 TGFPRO PRO ON PRO.CODPROD = ITE.CODPROD LEFT JOIN
									 TGFBAR BAR ON BAR.CODPROD = PRO.CODPROD 
											   AND BAR.DHALTER = (SELECT MAX(TGFBAR.DHALTER) FROM TGFBAR WHERE TGFBAR.CODPROD = BAR.CODPROD) INNER JOIN
								 TGFVOL VOL ON VOL.CODVOL = ITE.CODVOL --INNER JOIN
								 --TGFCON2 CONF ON CONF.NUCONF = CAB.NUCONFATUAL
							WHERE CAB.NUNOTA = {$nunota2}
							ORDER BY SEQUENCIA
							"; 

				$stmt2 = sqlsrv_query( $conn, $tsql2);  

				while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
				{ $NUCONF = $row2[0];
			?>

				
				  <tr style="cursor: hand; cursor: pointer;">
				    <td width="10%" ><?php echo $row2[0]; ?>&nbsp;</td>
				    <td width="5%" ><?php echo $row2[1]; ?>&nbsp;</td>
				    <td width="10%"><?php echo $row2[2]; ?>&nbsp;</td>
				    <td width="5%" align="center"><?php echo $row2[3]; ?>&nbsp;</td>
				    <td width="25%" align="center"><?php echo $row2[4]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[5]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[6]; ?></td>
				    <td width="5%" align="center"><?php echo $row2[7]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[8]; ?></td>
				    <td width="10%" align="center"><?php echo $row2[9]; ?></td>
				  </tr></a>
				
			<?php
			}
			?>
			 </table>
			 </div>

		</div> <!-- Itens do Pedido -->


	
	</div> <!--container-->


	<script>
    
        var index,
            table = document.getElementById("table");
       
        
        
        // display selected row data into input text
        function selectedRowToInput()
        {
        	 
            //

            for(var i = 1; i < table.rows.length; i++)
            {

            	//alert("teste");

                table.rows[i].onclick = function()
                {
                	   
                  if(typeof index !== "undefined" ) {
                    table.rows[index].classList.toggle("selected");
                  }

                  index = this.rowIndex;
                  this.classList.toggle("selected");


                };
            }
        }
        selectedRowToInput();
    
        
        function removeSelectedRow()
        {
            table.deleteRow(index);
        }




        function insereitens(codbarra, quantidade, controle, nunota)
            {
                //O método $.ajax(); é o responsável pela requisição
                $.ajax
                        ({
                            //Configurações
                            type: 'POST',//Método que está sendo utilizado.
                            dataType: 'html',//É o tipo de dado que a página vai retornar.
                            url: 'insereitem.php',//Indica a página que está sendo solicitada.
                            //função que vai ser executada assim que a requisição for enviada
                            beforeSend: function () {
                                //$("#itensconferidos").html("Carregando...");
                            },
                            data: {codbarra: codbarra, quantidade: quantidade, controle: controle, nunota: nunota},//Dados para consulta
                            //função que será executada quando a solicitação for finalizada.
                            success: function (msg)
                            {


                                if (msg == "Esse Produto não está cadastrado"){
                                	alert(msg);	
                                	document.getElementById("quantidade").value = "";
                                	document.getElementById("codigodebarra").focus();
                                	document.getElementById("codigodebarra").select();
                                } else if (msg == "Quantidade inserida não pode ser maior do que a existente na nota!"){
                                	alert(msg);	
                                	document.getElementById("quantidade").focus()
                                	document.getElementById("quantidade").select();
                                } else {
                                $("#insereitem").html(msg);
                                
                                 if(document.getElementById("codigodebarra").value === ""){
					             	document.getElementById("codigodebarra").focus();
					             } 

					             if(document.getElementById("quantidade").value != "1" ){
					             		document.getElementById("codigodebarra").value = "";
					             		document.getElementById("quantidade").value = "";
					             		document.getElementById("codigodebarra").focus();
					             }
					             //else if(document.getElementById("codigodebarra").value != "" &&
					            // 	   	  document.getElementById("quantidade").value == ""){
					            // 	document.getElementById("codigodebarra").focus();
					            // }

								}                                
                            }
                        });
            }
            
            
            $('#conferir').click(function () {
                insereitens($("#codigodebarra").val(), $("#quantidade").val(), $("#controle").val(), <?php echo $nunota2; ?>)
            });




        function caracteristica(codigodebarra)
            {
                //O método $.ajax(); é o responsável pela requisição
                $.ajax
                        ({
                            //Configurações
                            type: 'POST',//Método que está sendo utilizado.
                            dataType: 'html',//É o tipo de dado que a página vai retornar.
                            url: 'caracteristicas.php',//Indica a página que está sendo solicitada.
                            //função que vai ser executada assim que a requisição for enviada
                            beforeSend: function () {
                                $("#caracteristicas").html("Carregando...");
                            },
                            data: {codigodebarra: codigodebarra},//Dados para consulta
                            //função que será executada quando a solicitação for finalizada.
                            success: function (msg)
                            {
                                $("#caracteristicas").html(msg);
                            }
                        });
            }
            
            
            $('#conferir').click(function () {
                caracteristica($("#codigodebarra").val())
            });

        function produtoconferencia(codigodebarra, nunota)
            {
                //O método $.ajax(); é o responsável pela requisição
                $.ajax
                        ({
                            //Configurações
                            type: 'POST',//Método que está sendo utilizado.
                            dataType: 'html',//É o tipo de dado que a página vai retornar.
                            url: 'produtoconferencia.php',//Indica a página que está sendo solicitada.
                            //função que vai ser executada assim que a requisição for enviada
                            beforeSend: function () {
                                $("#produtoconferencia").html("Carregando...");
                            },
                            data: {codigodebarra: codigodebarra, nunota: nunota},//Dados para consulta
                            //função que será executada quando a solicitação for finalizada.
                            success: function (msg)
                            {
                                $("#produtoconferencia").html(msg);
                            }
                        });
            }
            
            
            $('#conferir').click(function () {
                produtoconferencia($("#codigodebarra").val(), <?php echo $nunota2; ?>)
            });


        function imagemproduto(codigodebarra)
            {
                //O método $.ajax(); é o responsável pela requisição
                $.ajax
                        ({
                            //Configurações
                            type: 'POST',//Método que está sendo utilizado.
                            dataType: 'html',//É o tipo de dado que a página vai retornar.
                            url: 'imagemproduto.php',//Indica a página que está sendo solicitada.
                            //função que vai ser executada assim que a requisição for enviada
                            beforeSend: function () {
                                $("#imagemproduto").html("Carregando...");
                            },
                            data: {codigodebarra: codigodebarra},//Dados para consulta
                            //função que será executada quando a solicitação for finalizada.
                            success: function (msg)
                            {
                                $("#imagemproduto").html(msg);
                            }
                        });
            }
            
            
            $('#conferir').click(function () {
                imagemproduto($("#codigodebarra").val())
            });


        function itensconferidos(nunota)
            {
                //O método $.ajax(); é o responsável pela requisição
                $.ajax
                        ({
                            //Configurações
                            type: 'POST',//Método que está sendo utilizado.
                            dataType: 'html',//É o tipo de dado que a página vai retornar.
                            url: 'itensconferidos.php',//Indica a página que está sendo solicitada.
                            //função que vai ser executada assim que a requisição for enviada
                            beforeSend: function () {
                                $("#itensconferidos").html("Carregando...");
                            },
                            data: {nunota: nunota},//Dados para consulta
                            //função que será executada quando a solicitação for finalizada.
                            success: function (msg)
                            {
                                $("#itensconferidos").html(msg);
                            }
                        });
            }
            
            
            $('#conferir').click(function () {
                itensconferidos(<?php echo $nunota2; ?>)
            });


            if(document.getElementById("codigodebarra").value === ""){
            	document.getElementById("codigodebarra").focus();
            } 

            document.addEventListener("keypress",function(e){

            	if (e.key === "Enter" ) {
            		// console.log("Apertou o Enter");
            		const btn = document.querySelector("#conferir");
            		btn.click();

            		if(document.getElementById("codigodebarra").value != ""){
            			document.getElementById("quantidade").focus();
            			document.getElementById("quantidade").value = "1";
            			document.getElementById("quantidade").select();
            		}
            	}

            });

var codbarselecionado = "<?php echo $codbarraselecionado; ?>";

if (codbarselecionado != 0){
	function scrollToRow(i) {
	  var tabela = document.getElementById("minhaTabela");
	  var linhas = tabela.getElementsByTagName("tr");

	  linhas[i].classList.toggle("selecionado");

	  setTimeout(function() {
	    linhas[i].scrollIntoView();
	  }, 50);
	}
} else {
	function scrollToRow(i) {
	  var tabela = document.getElementById("minhaTabela");
	  var linhas = tabela.getElementsByTagName("tr");

	  linhas[i].classList.toggle("");

	  setTimeout(function() {
	    linhas[i].scrollIntoView();
	  }, 50);
	}
}


    </script>
</body>
</html>