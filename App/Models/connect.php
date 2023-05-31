<?php

/**
 * Conexão com o banco de dados
 */
 class Connect
 {
 	
 	//Configuracoes do servidor SQL Server 
	var $serverName = "10.0.0.228"; 
	var $uid = "sankhya";   
	var $pwd = "tecsis";  
	var $databaseName = "SANKHYA_PROD"; 
	var $SQL;
	var $teste;
	var $cont;



	public function __construct() {
	$connectionInfo = array( "UID"=>$this->uid,                            
	                         "PWD"=>$this->pwd,                            
	                         "Database"=>$this->databaseName); 
	/* Conexao com SQL Server usando autenticacao. */  
	$this->SQL = sqlsrv_connect( $this->serverName, $connectionInfo); 

	if(!$this->SQL){
			die("Conexão com o banco de dados falhou!:" . sqlsrv_errors($this->SQL)); 
		}

	}
 	


 	/*public function __construct()
 	{
 		$this->SQL = mysqli_connect($this->localhost, $this->root, $this->passwd);
		mysqli_select_db($this->SQL, $this->database);
		if(!$this->SQL){
			die("Conexão com o banco de dados falhou!:" . mysqli_connect_error($this->SQL)); 
		}
 	}*/

	
 	function login($username, $password){

 		//echo "<script> alert('1'); </script>";
 		//header("Location: ../listaconferencia.php");
 		//$params = array($username);
 		$this->query  = "select CODUSU, NOMEUSU, AD_SENHA from TSIUSU where NOMEUSU = '$username'";
 		//$query = "SELECT senha FROM teste_leandro WHERE usuario = '$username'";
 		$this->result = sqlsrv_query($this->SQL, $this->query) or die(sqlsrv_errors($this->SQL));
 		//echo "<script> alert('1'); </script>";
 		$this->total  = sqlsrv_num_rows($this->result);
 		$this->dados = sqlsrv_fetch_array($this->result, SQLSRV_FETCH_ASSOC);

 		if(is_null($this->dados['CODUSU'])){
 			$teste = '';
 			//echo("<script> alert('$teste'); </script>");
 		} else {
 			$teste = $this->dados['CODUSU'];
 			$this->senha = $this->dados['AD_SENHA'];
 		}
 		/*if($this->total >= 0){
			$teste = $this->dados['CODUSU'];
			echo("<script> alert('$teste'); </script>");
 		} else {
 			$teste = 'none';
 			echo("<script> alert('$teste'); </script>");
 		}*/

 		

 		/*if ($this->total === false) {
 			echo("<script> alert('1'); </script>");
 		}*/
 	

 		if($teste != ''){

 			//$this->dados = sqlsrv_fetch_array($this->result);
 			if(!strcmp($password, $this->senha)){

 				$_SESSION['idUsuario'] = $this->dados['CODUSU'];
 				$_SESSION['usuario']   = $this->dados['NOMEUSU'];
 				//$_SESSION['perm']      = $this->dados['Permissao'];
 				//$_SESSION['foto']      = $this->dados['imagem'];
 				
 				header("Location: ../menu.php");
 			}else{
 				header("Location: ../login.php?alert=2");
 			}
 		}else{

 				echo("<script> window.location.href='../login.php?alert=1'; </script>");
 				//header("Location: ../login.php?alert=1");
 			}
 	}
 	
 }

$connect = new Connect; 

?>