<?php  
/* Turn off the default behavior of treating errors as warnings.  
Note: Turning off the default behavior is done here for demonstration  
purposes only. If setting the configuration fails, display errors and  
exit the script. */  
if( sqlsrv_configure("WarningsReturnAsErrors", 0) === false)  
{  
     DisplayErrors();  
     die;  
}  
  
/* Connect to the local server using Windows Authentication and   
specify the AdventureWorks database as the database in use. */  
$serverName = "10.0.0.228"; 
$uid = "sankhya";   
$pwd = "tecsis";  
$databaseName = "SANKHYA_TESTE";  

$connectionInfo = array( "UID"=>$uid,                            
                         "PWD"=>$pwd,                            
                         "Database"=>$databaseName); 

$conn = sqlsrv_connect( $serverName, $connectionInfo); 
  


// /* If the connection fails, display errors and exit the script. */  
// if( $conn === false )  
// {  
//      DisplayErrors();  
//      die;  
// }  
// /* Display any warnings. */  
// DisplayWarnings();  
  
// /* Drop the stored procedure if it already exists. */  
// $tsql1 = "IF OBJECT_ID('SubtractVacationHours', 'P') IS NOT NULL  
//                 DROP PROCEDURE SubtractVacationHours";  
// $stmt1 = sqlsrv_query($conn, $tsql1);  
  
// /* If the query fails, display errors and exit the script. */  
// if( $stmt1 === false)  
// {  
//      DisplayErrors();  
//      die;  
// }  
// /* Display any warnings. */  
// DisplayWarnings();  
  
// /* Free the statement resources. */  
// sqlsrv_free_stmt( $stmt1 );  
  
/* Create the stored procedure. */  
$tsql2 = "
     UPDATE TGFITE SET ATUALESTOQUE = -1  where NUNOTA = 2803389 AND SEQUENCIA = 1
";  
$stmt2 = sqlsrv_query( $conn, $tsql2 );  
  
/* If the query fails, display errors and exit the script. */  
if( $stmt2 === false)  
{  
     //print_r( sqlsrv_errors(), true);
     $linhas = filter_input_array(INPUT_POST, FILTER_DEFAULT);

     var_dump($linhas);

     $msgerro = DisplayErrors();  
     // echo $msgerro;
     echo "<pre>";
     echo $msgerro;
     echo "<script> alert('$msgerro'); </script>";
     die;  
}  
/* Display any warnings. */  
//DisplayWarnings();  
  
/* Free the statement resources. */  
sqlsrv_free_stmt( $stmt2 );  

  
  
// /* Define and prepare the query to subtract used vacation hours. */  
// $tsql3 = "
//           UPDATE TGFITE SET ATUALESTOQUE = -1  where NUNOTA = 2803389 AND SEQUENCIA = 1
//          ";  
// $stmt3 = sqlsrv_prepare($conn, $tsql3);  
  
// /* If the statement preparation fails, display errors and exit the script. */  
// if( $stmt3 === false)  
// {  
//      DisplayErrors();  
//      die;  
// }  
// /* Display any warnings. */  
// DisplayWarnings();  
  
// /* Loop through the employee=>vacation hours array. Update parameter  
//  values before statement execution. */  
// foreach(array_keys($emp_hrs) as $employeeId)  
// {  
//      $vacationHrs = $emp_hrs[$employeeId];  
//      /* Execute the query.  If it fails, display the errors. */  
//      if( sqlsrv_execute($stmt3) === false)  
//      {  
//           DisplayErrors();  
//           die;  
//      }  
//      /* Display any warnings. */  
//      DisplayWarnings();  
  
//      /*Move to the next result returned by the stored procedure. */  
//      if( sqlsrv_next_result($stmt3) === false)  
//      {  
//           DisplayErrors();  
//           die;  
//      }  
//      /* Display any warnings. */  
//      DisplayWarnings();  
  
//      /* Display updated vacation hours. */  
//      echo "EmployeeID $employeeId has $vacationHrs ";  
//      echo "remaining vacation hours.\n";  
// }  
  
// /* Free the statement and connection resources. */  
// sqlsrv_free_stmt( $stmt3 );  
// sqlsrv_close( $conn );  
  
/* ------------- Error Handling Functions --------------*/  
function DisplayErrors()  
{  
     $errors = sqlsrv_errors(SQLSRV_ERR_ERRORS);  
     foreach( $errors as $error )  
     {  
          $erro = $error['message'];
          $errostring = implode(",", $error);

          return $errostring;

          //echo $errostring;


          // foreach($error as $row){
          //      echo implode(",", $row)."\n";
          // }
          //echo "<script> alert('$errostring'); </script>";
          // echo "<pre>";
          // var_dump($erro);
          // echo "Error: ".$error['message']."\n"; 
          // echo "<script> alert('".$error['message']."') </script>";
     }  
}  
  
function DisplayWarnings()  
{  
     $warnings = sqlsrv_errors(SQLSRV_ERR_WARNINGS);  
     if(!is_null($warnings))  
     {  
          foreach( $warnings as $warning )  
          {  
               // echo "Warning: ".$warning['message']."\n";  
          }  
     }  
}  
?>  