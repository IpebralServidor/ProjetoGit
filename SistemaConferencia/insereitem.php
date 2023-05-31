<?php header('Content-Type: text/html; iso-8859-1');?>
<?php
include "../conexaophp.php";
session_start();

$codbarra = $_POST["codbarra"];
$quantidade = str_replace(',', '.', $_POST["quantidade"]) ;
$controle = $_POST["controle"];
$nunota2 = $_POST["nunota"];

// echo $codbarra;
// echo " / ".$quantidade;
// echo " / ".$controle;


$tsql6 = "  SELECT COUNT(*) FROM TGFBAR WHERE CODBARRA = '$codbarra'
							 "; 

$stmt6 = sqlsrv_query( $conn, $tsql6);  

	while( $row3 = sqlsrv_fetch_array( $stmt6, SQLSRV_FETCH_NUMERIC))  
	{   $qtdcodigobarra = $row3[0];

		if($qtdcodigobarra == 0){
			//echo "teste";
		echo "Esse Produto não está cadastrado";


		}

	
	}



if($quantidade == ''){
	$quantidade = 0;
}
//echo $codbarra;
//header('Location: detalhesconferencia.php?nunota='.$nunota2.'&codbarra='.$codbarra);

$tsql5 = "  DECLARE @NUNOTA INT = $nunota2
			DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
			DECLARE @CODBARRA VARCHAR(100) = trim('$codbarra')
			DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = @CODBARRA)
			DECLARE @QTDNOTA FLOAT = (SELECT SUM(QTDNEG) FROM TGFITE WHERE CODPROD = @CODPROD AND NUNOTA = @NUNOTA)
			DECLARE @QTDCONT FLOAT = (SELECT QTDCONF FROM TGFCOI2 WHERE CODPROD = @CODPROD AND NUCONF = @NUCONF)

			SELECT ISNULL(@QTDCONT,0), ISNULL(@QTDNOTA,0)
			"; 

	$stmt5 = sqlsrv_query( $conn, $tsql5);  

	while( $row3 = sqlsrv_fetch_array( $stmt5, SQLSRV_FETCH_NUMERIC))  
	{   $quantidadeconferida = $row3[0];
		$quantidadenota = $row3[1];

		if(($quantidade + $quantidadeconferida) > $quantidadenota){
			//echo "teste";
		echo "Quantidade inserida não pode ser maior do que a existente na nota!";
		//header("Refresh: 0");

		//echo "<script> abrirErroQtd() </script>";
		}

	
	}


if($quantidade > 0 && ($quantidade + $quantidadeconferida) <= $quantidadenota) {
									

	$tsql2 = "  DECLARE @NUNOTA INT = $nunota2
				DECLARE @NUCONF INT = (SELECT NUCONFATUAL from TGFCAB where NUNOTA = @NUNOTA)
				DECLARE @CODBARRA VARCHAR(100) = trim('$codbarra')
				DECLARE @QTDNEG FLOAT = $quantidade
				DECLARE @QTDNEGANT FLOAT = (SELECT QTDCONF FROM TGFCOI2 WHERE CODBARRA = @CODBARRA AND NUCONF = @NUCONF)
				DECLARE @CODPROD INT = (SELECT CODPROD FROM TGFBAR WHERE CODBARRA = @CODBARRA)
				DECLARE @QTDNOTA FLOAT = (SELECT SUM(QTDNEG) FROM TGFITE WHERE CODPROD = @CODPROD AND NUNOTA = @NUNOTA)
				DECLARE @CONTROLE VARCHAR(100) = trim('$controle')

				IF ((ISNULL(@QTDNEGANT,0) + ISNULL(@QTDNEG,0)) <= ISNULL(@QTDNOTA,0))
				BEGIN

					IF((SELECT MAX(SEQVOL) FROM TGFVCF WHERE NUCONF = @NUCONF) IS NULL)
					BEGIN
						INSERT INTO TGFVCF ( NUCONF,ORDEM,SEQVOL ) 
						SELECT  @NUCONF,
								1,
								1 
					END


					IF ((SELECT COUNT(1) FROM TGFIVC WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA) = 0)
					BEGIN
						INSERT INTO TGFIVC ( CODBARRA,CODPROD,CODVOL,CONTROLE,IMPRIMEAUTO,NUCONF,QTD,QTDVOLPAD,SEQITEM,SEQVOL ) 
						SELECT TOP 1 TGFBAR.CODBARRA,
								TGFBAR.CODPROD,
								TGFITE.CODVOL,
								CASE WHEN ISNULL(@CONTROLE,'') = '' THEN CONTROLE
									 ELSE @CONTROLE
								END AS CONTROLE,
								'S',
								NUCONF,
								@QTDNEG,
								@QTDNEG, --CONFIRMAR
								ISNULL((SELECT MAX(SEQITEM) FROM TGFIVC WHERE NUCONF = @NUCONF),0) + 1,
								1
						FROM TGFBAR INNER JOIN
								TGFITE ON TGFITE.CODPROD = TGFBAR.CODPROD INNER JOIN
								TGFCAB ON TGFCAB.NUNOTA = TGFITE.NUNOTA INNER JOIN
								TGFVCF ON TGFVCF.NUCONF = TGFCAB.NUCONFATUAL
						WHERE CODBARRA = @CODBARRA
							AND TGFCAB.NUNOTA = @NUNOTA
					END 
					ELSE BEGIN
						SET @QTDNEGANT = (SELECT QTD FROM TGFIVC WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA)
						UPDATE TGFIVC SET QTD = @QTDNEG + @QTDNEGANT, QTDVOLPAD = @QTDNEG + @QTDNEGANT WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA
					END	

					IF ((SELECT COUNT(1) FROM TGFCOI2 WHERE CODBARRA = @CODBARRA AND NUCONF = @NUCONF) = 0)
					BEGIN
						INSERT INTO TGFCOI2 ( CODBARRA,CODPROD,CODVOL,CONTROLE,COPIA,DHALTER,NUCONF,QTDCONF,QTDCONFVOLPAD,SEQCONF ) 
						SELECT TOP 1 CODBARRA,
								TGFITE.CODPROD,
								TGFITE.CODVOL,
								CASE WHEN ISNULL(@CONTROLE,'') = '' THEN CONTROLE
									 ELSE @CONTROLE
								END AS CONTROLE,
								NULL,
								GETDATE(),
								@NUCONF,
								@QTDNEG, --QUANTIDADE CONFERIDA
								@QTDNEG, 
								ISNULL((SELECT MAX(SEQCONF) FROM TGFCOI2 WHERE NUCONF = @NUCONF),0) + 1
						FROM TGFBAR INNER JOIN
								TGFITE ON TGFITE.CODPROD = TGFBAR.CODPROD
						WHERE CODBARRA = @CODBARRA
							AND NUNOTA = @NUNOTA
					END
					ELSE BEGIN
						SET @QTDNEGANT = (SELECT QTDCONF FROM TGFCOI2 WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA)


						UPDATE TGFCOI2 SET QTDCONF = @QTDNEG + @QTDNEGANT, QTDCONFVOLPAD = @QTDNEG + @QTDNEGANT WHERE NUCONF = @NUCONF AND CODBARRA = @CODBARRA
					END

				END

				"; 

	$stmt2 = sqlsrv_query( $conn, $tsql2); 

	$_SESSION['codbarraselecionado'] = $codbarra;
	//$codbarra = 0;
	//$codbarra2 = 0;

	//header('Location: detalhesconferencia.php?nunota=$nunota2');
	echo "<script> window.location.href='detalhesconferencia.php?nunota=$nunota2' </script>";
}


?>