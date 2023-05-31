<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "../conexaophp.php";

$codigodebarra = $_POST['codigodebarra'];
//fim da conexão com o banco de dados
$codbarra2 = $codigodebarra;


 
//$qtd = sqlsrv_num_rows($query);
?>

<section class="panel col-lg-9">

				<nav class="nav_tabs">
				    <ul>
				        <li>
				            <input type="radio" id="tab1" class="rd_tab" name="tabs" checked>
				            <label for="tab1" class="tab_label">Carac.</label>
				            <div class="tab-content">
				                <!--<h2>Title 1</h2>-->
				                <article>
				                <div style="margin-top: 10px;">
				                    <?php

				                    	

										$tsql2 = "SELECT   TGFPRO.CODPROD,
														   DESCRPROD,
														   REFERENCIA,
														   AD_CARACTERISTICAS,
														   PROMOCAO,
														   AGRUPMIN,
														   TGFPRO.CODVOL,
														   AD_CODLOCAL
													FROM TGFPRO INNER JOIN
														 TGFBAR ON TGFBAR.CODPROD = TGFPRO.CODPROD
													WHERE CODBARRA = '{$codbarra2}'";

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while($row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{ 

											echo "<span><strong>Cód. Produto:</strong> " .$row2[0]."</span><br>";
											echo "<strong>Descr. Produto: </strong>" .$row2[1]."<br>";
				 							echo "<strong>Referência: </strong>" .$row2[2]."<br>";
											echo "<strong>Características: </strong>" .$row2[3]."<br>";
											echo "<strong>Promoção: </strong>" .$row2[4]."<br>";
											echo "<strong>Agrup. Mínimo: </strong>" .$row2[5]."<br>";
											echo "<strong>Unidade Padrão: </strong>" .$row2[6]."<br>";
											echo "<strong>Local Padrão Ad.: </strong>" .$row2[7]."<br>";

										}
									?>
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
					                <table width="800" border="1px"    bordercolor="white" style="margin-top: 5px; " id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
									    <th width="20%" ><font  face="Arial, Helvetica, sans-serif">Referência</font></th>
									    <th width="40%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descr. Produto</font></th>
									    <th width="20%" align="center"><font  face="Arial, Helvetica, sans-serif">Qtd. Mistura.</font></th>
									    <th width="20%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Mát. Prima</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT REFERENCIA, 
														   DESCRPROD, 
														   QTDMISTURA, 
														   CODMATPRIMA 
													FROM TGFICP INNER JOIN
														 TGFPRO ON TGFPRO.CODPROD = TGFICP.CODMATPRIMA
													WHERE TGFICP.CODPROD IN (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
													  AND VARIACAO = 0
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="20%" ><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="40%"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="20%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
										    <td width="20%" align="center"><?php echo $row2[3]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab3">
				            <label for="tab3" class="tab_label">Estoque</label>
				            <div class="tab-content">
				                <!-- <h2>Title 3</h2> -->
				                <article>
				                    <table width="1500" border="1px"    bordercolor="white" style="margin-top: 5px; " id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
									    <th width="5%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Emp.</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Disponível</font></th>
									    <th width="5%" align="center"><font  face="Arial, Helvetica, sans-serif">Estoque</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Reservado</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Local</font></th>
									    <th width="20%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Desc. Local</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
									    <th width="7%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Dt. Val.</font></th>
									    <th width="10%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tipo</font></th>
									    <th width="25%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Barra</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT CODEMP,
														   (ESTOQUE - RESERVADO) AS 'DISPONIVEL',
														   ESTOQUE,
														   RESERVADO,
														   TGFEST.CODLOCAL,
														   TGFLOC.DESCRLOCAL,
														   CONTROLE,
														   CONVERT(VARCHAR(MAX),TGFEST.DTVAL,103),
														   TGFEST.TIPO,
														   CODBARRA
													FROM TGFEST INNER JOIN
														 TGFLOC ON TGFLOC.CODLOCAL = TGFEST.CODLOCAL
													WHERE CODPROD = (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
													  and TGFEST.CODPARC = 0
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="5%" align="center"><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="7%" align="center"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="5%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
										    <td width="7%" align="center"><?php echo $row2[3]; ?></td>
										    <td width="7%" align="center"><?php echo $row2[4]; ?></td>
										    <td width="20%" align="center"><?php echo $row2[5]; ?></td>
										    <td width="7%" align="center"><?php echo $row2[6]; ?></td>
										    <td width="7%" align="center"><?php echo $row2[7]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[8]; ?></td>
										    <td width="25%" align="center"><?php echo $row2[9]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab4">
				            <label for="tab4" class="tab_label">Preço</label>
				            <div class="tab-content">
				                <!-- <h2>Title 4</h2> -->
				                <article>
				                	<table width="700" border="1px" bordercolor="white" style="margin-top: 5px;  text-align: center;" id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
									    <th width="16.66%" ><font  face="Arial, Helvetica, sans-serif">Preço</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tabela</font></th>
									    <th width="16.66%" align="center"><font  face="Arial, Helvetica, sans-serif">Nome Tab.</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Qtd. Máx. Loc.</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Média Vendas</font></th>
									    <th width="16.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Promoção?</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT EXC.VLRVENDA,
														   TAB.CODTAB,
														   TGFNTA.NOMETAB,
														   --CODTABORIG,
														   AD_QTDMAXLOCAL,
														   ROUND(MEDIA6,2) AS MEDIA6,
														   PROMOCAO
													FROM TGFEXC EXC INNER JOIN 
														 TGFTAB TAB on EXC.NUTAB = TAB.NUTAB INNER JOIN
														 TGFPRO ON TGFPRO.CODPROD = EXC.CODPROD INNER JOIN
														 AD_MEDIAVENDAEMP ON AD_MEDIAVENDAEMP.CODPROD = TGFPRO.CODPROD
																		 AND CODEMP = 0 INNER JOIN
														 TGFNTA ON TGFNTA.CODTAB = TAB.CODTAB
													WHERE TAB.CODTAB = 0 
														AND EXC.CODPROD = (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
														AND TAB.DTVIGOR = (SELECT MAX(TAB1.DTVIGOR) 
																		FROM TGFEXC EXC1, 
																			TGFTAB TAB1 
																		WHERE EXC1.CODPROD = EXC.CODPROD
																			AND EXC1.NUTAB = TAB1.NUTAB 
																			AND TAB1.CODTAB = 0)
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="16.66%" ><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="16.66%"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="16.66%" align="center"><?php echo $row2[2]; ?>&nbsp;</td>
										    <td width="16.66%" align="center"><?php echo $row2[3]; ?></td>
										    <td width="16.66%" align="center"><?php echo $row2[4]; ?></td>
										    <td width="16.66%" align="center"><?php echo $row2[5]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				        <li>
				            <input type="radio" name="tabs" class="rd_tab" id="tab5">
				            <label for="tab5" class="tab_label">Res.</label>
				            <div class="tab-content">
				                <!-- <h2>Title 5</h2> -->
				                <article>
				                    <table width="2500" border="1px"    bordercolor="white" style="margin-top: 5px;" id="table">
									  <tr><font size="-1" face="Arial, Helvetica, sans-serif" >
										<th width="6.66%" ><font  face="Arial, Helvetica, sans-serif">Nro. Único</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Nro. Nota</font></th>
										<th width="6.66%" align="center"><font  face="Arial, Helvetica, sans-serif">Cód. Empresa</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Qtd. Neg.</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Dt. Neg.</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Controle</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Parceiro</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Nome Parceiro</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Cód. Vendedor</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Nome Vendedor</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">TOP</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descr. TOP</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Dt. Prev. Entrega</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Tipo de Pedido</font></th>
										<th width="6.66%" style="text-align: center;"><font  face="Arial, Helvetica, sans-serif">Descr. Tip. Pedido</font></th>
									  </tr>

									<?php 
										$tsql2 = " 	SELECT DISTINCT CAB.NUNOTA,
																	CAB.NUMNOTA,
																	CAB.CODEMP,
																	ITE.QTDNEG,
																	CONVERT(VARCHAR(MAX),CAB.DTNEG,103),
																	ITE.CONTROLE,
																	CAB.CODPARC,
																	PAR.NOMEPARC,
																	CAB.CODVEND,
																	VEN.APELIDO,
																	CAB.CODTIPOPER,
																	TPO.DESCROPER,
																	CONVERT(VARCHAR(MAX),CAB.DTPREVENT,103),
																	(SELECT TPD.CODTPD
																	    FROM TGFTPD TPD
																		WHERE TPD.CODTPD = CAB.CODTPD) AS TIPOPEDIDO,
																    (SELECT TPD.DESCRICAO
																		FROM TGFTPD TPD
																		WHERE TPD.CODTPD = CAB.CODTPD) AS DESCTIPOPEDIDO
													FROM TGFITE ITE INNER JOIN
													     TGFCAB CAB ON ITE.NUNOTA = CAB.NUNOTA INNER JOIN
													     TGFPAR PAR ON CAB.CODPARC = PAR.CODPARC INNER JOIN
														 TGFVEN VEN ON CAB.CODVEND = VEN.CODVEND INNER JOIN
														 TSIEMP EMP ON ITE.CODEMP = EMP.CODEMP INNER JOIN
														 TGFTOP TPO ON TPO.CODTIPOPER = CAB.CODTIPOPER
													WHERE ITE.CODPROD  = (select CODPROD from TGFBAR where CODBARRA = '{$codbarra2}')
																  AND ITE.PENDENTE = 'S'
																  AND ITE.RESERVA  = 'S'   
												 "; 

										$stmt2 = sqlsrv_query( $conn, $tsql2);  

										while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_NUMERIC))  
										{
									?>

										
										  <tr >
										    <td width="10%" ><?php echo $row2[0]; ?>&nbsp;</td>
										    <td width="10%"><?php echo $row2[1]; ?>&nbsp;</td>
										    <td width="10%" align="center"><?php echo $row2[2]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[3]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[4]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[5]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[6]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[7]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[8]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[9]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[10]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[11]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[12]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[13]; ?></td>
										    <td width="10%" align="center"><?php echo $row2[14]; ?></td>
										  </tr>
										 

									<?php
									}
									?>
								  </table>
				                </article>
				            </div>
				        </li>
				    </ul>
				</nav>

</section>