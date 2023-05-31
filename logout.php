<?php

include "conexaophp.php";
session_destroy();

header('Location: ../login.php');

?>