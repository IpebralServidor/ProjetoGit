<?php
require_once 'App/auth.php';

if($usuario && $perm){

	// header('Location: listaconferencia.php');
	header('Location: google.com');
}else{

header('Location: login.php');
}

?>