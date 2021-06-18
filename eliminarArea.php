<?php
    /*foreach ($_POST as $key => $value) {
        echo "Param: $key; Value: $value<br />\n";
    }*/
    include "conexion.php";
    if(isset($_POST["nameAsignatura"])){
        $idArea = $_POST["nameAsignatura"];
        $Consulta="DELETE FROM tutor_area WHERE idArea ={$idArea}";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        header("location:tutorPerfil.php");
    }
?>