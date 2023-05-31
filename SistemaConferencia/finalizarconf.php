<?php

session_start();
include "../conexaophp.php";

//echo "string";
//$linhas = filter_input_array(INPUT_POST, FILTER_DEFAULT);

//var_dump($linhas);

$qtdvol = $_POST['QTDVOLUME'];
$volume = $_POST['VOLUME'];
$pesobruto = $_POST['PESOBRUTO'];
$nunota = $_POST['NUNOTACONF'];

echo $qtdvol;
echo "<br>";
echo $volume;
echo "<br>";
echo $pesobruto;
echo "<br>";
echo $nunota;



   $tsql2 = " UPDATE TGFCAB SET DTALTER = GETDATE(),
                                PESOBRUTO = '$pesobruto',
                                PESOBRUTOMANUAL = 'S',
                                QTDVOL = '$qtdvol',
                                VOLUME = '$volume'
              WHERE TGFCAB.NUNOTA = '$nunota'

            "; 

    $stmt2 = sqlsrv_query( $conn, $tsql2); 

    $tsql3 = " UPDATE TGFCON2 SET DHFINCONF = GETDATE(),
                                  STATUS = 'F' 
               WHERE TGFCON2.NUCONF = (SELECT NUCONFATUAL 
                                       FROM TGFCAB 
                                       WHERE NUNOTA = '$nunota')

            "; 

    $stmt3 = sqlsrv_query( $conn, $tsql3); 

    $tsql4 = " UPDATE TGFCON2 SET QTDVOL = $QTDVOL 
               WHERE TGFCON2.NUCONF = (SELECT NUCONFATUAL 
                                       FROM TGFCAB 
                                       WHERE NUNOTA = '$nunota')

             "; 

    $stmt4 = sqlsrv_query( $conn, $tsql4); 



    echo "<script> alert('Conferência finalizada.'); </script>"; 
    header("Location: listaconferencia.php");
    


?>