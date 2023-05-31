<?php

include "../conexaophp.php";


$linhas = filter_input_array(INPUT_POST, FILTER_DEFAULT);

var_dump($linhas);


foreach ($linhas['id'] as $id => $linha) {
    $nunota = substr($id, stripos($id, "/")+1);
    $codbarra = substr($id, 0, stripos($id, "/"));
    //echo "ID da linha: $id<br>";
    //echo "$nunota<br>";
    //echo "$codbarra<br>";

    $tsql2 = "  DELETE
                from TGFIVC 
                where codbarra = trim('$codbarra')
                  and NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
            "; 

    $stmt2 = sqlsrv_query( $conn, $tsql2); 

    $tsql3 = "  DELETE 
                FROM TGFCOI2 
                WHERE TGFCOI2.NUCONF = (select NUCONFATUAL FROM TGFCAB WHERE NUNOTA = $nunota)
                  AND TGFCOI2.CODBARRA = trim('$codbarra')
            "; 

    $stmt3 = sqlsrv_query( $conn, $tsql3); 

    $mensagem = "Itens excluídos com sucesso!";

    echo "<script language='javascript'>";
    echo "alert('".$mensagem."');";
    echo "</script>";

    header("Location: detalhesconferencia.php?nunota=$nunota&codbarra=0");
    //echo "<script> alert('Itens Excluídos com Sucesso!.'); </script>"; 
}

?>