<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "conexaophp.php";


$nunota2 = $_POST['nunota'];

 
//$qtd = sqlsrv_num_rows($query);
?>

<section class="panel col-lg-9">


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
			</table>		 


</section>