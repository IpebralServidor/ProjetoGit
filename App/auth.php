<?php
session_start(); //Iniciando a sessão

if(!isset($_SESSION["idUsuario"])){

 			header('Location: ../../login.php');
}else{

	$idUsuario = $_SESSION["idUsuario"]; 
	$username   = $_SESSION["usuario"];
	//$perm	   = $_SESSION["perm"];

}

?>