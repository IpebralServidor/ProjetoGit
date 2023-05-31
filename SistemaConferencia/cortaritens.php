<?php

include "../conexaophp.php";
session_start();

$usuconf = $_SESSION["idUsuario"];
$nunota2 = $_REQUEST["nunota"];

echo $usuconf;
echo "<br>".$nunota2;

$tsql = "  EXEC AD_STP_CORTAITENS_CONFERENCIA $nunota2, $usuconf
        "; 

$stmt = sqlsrv_query( $conn, $tsql); 


// $mensagem = "Itens excluídos com sucesso!";

// echo "<script language='javascript'>";
// echo "alert('".$mensagem."');";
// echo "</script>";

header("Location: listaconferencia.php");
//echo "<script> alert('Itens Excluídos com Sucesso!.'); </script>"; 


?>