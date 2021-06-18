<?php
    /*foreach ($_POST as $key => $value) {
        echo "Param: $key; Value: $value<br />\n";
    }*/
    include "conexion.php";
    if(isset($_POST["nameCosto"])){
        $idCosto = $_POST["nameCosto"];
        $Consulta="DELETE FROM COSTOS_TUTOR WHERE idCosto ={$idCosto}";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        header("location:tutorPerfil.php");
    }

?>