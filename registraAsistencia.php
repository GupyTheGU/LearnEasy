<?php
$Salida = 0;
session_start();

if (!isset($_SESSION['Datos'])) {
    // No existe la sesión
    header("location:index.php");
}

include 'Clases/BD/conexion.php';

if (isset($_POST['asisIdTutoria'])) {
    $idTutoria=$_POST['asisIdTutoria'];
    $linkConferencia = $_POST['asisLink'];
}

$idTipo = $_SESSION['Datos'][4];
if(strcmp($idTipo,"A")==0){
    $Consulta = "UPDATE TUTORIA SET asisAprendiz = '1' WHERE idTutoria = {$idTutoria};";
    mysqli_query($Conexion, $Consulta);
    $Salida=5;
}else{
    $Consulta = "UPDATE TUTORIA SET asisTutor = '1' WHERE idTutoria = {$idTutoria};";
    mysqli_query($Conexion, $Consulta);
    $Salida=5;
}

header("location:{$linkConferencia}");
?>