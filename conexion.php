<?php

    $Hostname="127.0.0.1:3307";
	$Username="root";
	$Password="usbw";
	$Database="LearnEasy";
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $Conexion = mysqli_connect($Hostname, $Username, $Password, $Database);

?>