<?php

    $servername = "localhost";
    $database = "u742109558_LearnEasy";
    $username = "u742109558_LearnEasy";
    $password = "L34rnE5yPr0";
    // Create connection
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $Conexion = mysqli_connect($servername, $username, $password, $database);
    // Check connection
    if (!$Conexion) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //echo "Connected successfully";
    if (!$Conexion) {
      die("Connection failed: " . mysqli_connect_error());
    }
?>