<?php
$Salida = '0';

session_start();

if (!isset($_SESSION['Datos'])) {
    // No existe la sesión
    header("location:index.php");
} else {
    $idTipo = $_SESSION['Datos'][4];
    if (strcmp($idTipo, "T") == 0) {
        header("location:index.php");
    }
}

include 'Clases/BD/conexion.php';

$idCuenta = $_SESSION["Datos"][5];

if(isset($_POST['certIdTutoria'])){
    $idTutoria = $_POST['certIdTutoria'];
}else{
    header("location:index.php");
}

$Consulta = "CALL sp_getCertificado ({$idCuenta},{$idTutoria})";
$Resultado=mysqli_query($Conexion, $Consulta);
while($Row = $Resultado->fetch_array()){
    $nombre = $Row[1];
    $pApellido = $Row[2];
    $sApellido = $Row[3];
    $area = strtoupper($Row[4]);
    $fecha = $Row[5];
    $horaEntrada = $Row[6];
    $horaSalida = $Row[7];
}
    require('FPDF_183/fpdf.php');

    $Fondo = 'Fondo.png';

    $Title = utf8_decode("Certificado de finalización");
    $Nombre = utf8_decode($nombre);
    $PriApe = utf8_decode($pApellido);
    $SegApe = utf8_decode($sApellido);

    $Curso = utf8_decode($area);
    // $Horas = date("H:i",strtotime($horaSalida)-strtotime($horaEntrada));
    $Horas = date("G",strtotime($horaSalida)-strtotime($horaEntrada));

    $Dia = date("d",strtotime($fecha));
    setlocale(LC_TIME, "spanish");
    $Mes = strftime("%B",strtotime($fecha));
    $Anio = date("Y",strtotime($fecha));

    $PDF = new FPDF('L', 'mm', 'A4');
    $PDF->SetTitle($Title);
    $PDF->AddPage('L', 'A4');
    $PDF->Image($Fondo, 10, 10, -100);

    $PDF->SetFont('Arial', 'BI', 36);
    $PDF->Cell(0, 70, utf8_decode('LearnEasy'), 0 , 1, 'C');

    $PDF->SetFont('Arial', '', 20);
    $PDF->Cell(0, 8, utf8_decode('Este documento certifica que'), 0 , 1, 'C');
    $PDF->SetFont('Arial', 'BI', 20);
    $PDF->Cell(0, 8, $Nombre.' '.$PriApe.' '.$SegApe, 0 , 1, 'C');
    $PDF->SetFont('Arial', '', 20);
    $PDF->Cell(0, 8, utf8_decode('ha completado con éxito la tutoría en línea'), 0 , 1, 'C');
    $PDF->SetFont('Arial', 'BI', 20);
    $PDF->Cell(0, 8, $Curso, 0 , 1, 'C');
    $PDF->SetFont('Arial', '', 20);
    $PDF->Cell(0, 8, utf8_decode('con un total de'), 0 , 1, 'C');
    $PDF->SetFont('Arial', 'BI', 20);
    $PDF->Cell(0, 8, utf8_decode($Horas.' horas'), 0 , 1, 'C');
    $PDF->SetFont('Arial', '', 20);
    $PDF->Cell(0, 8, utf8_decode('el '. $Dia.' de '.$Mes.' de '. $Anio), 0 , 1, 'C');

    $PDF->SetFont('Arial', 'I', 12);
    $PDF->Cell(0, 50, utf8_decode('El presente documento acredita que la tutoría fue finalizada con éxito.'), 0 , 1, 'C');
    $PDF->Output('Certificado.pdf','I');

?>