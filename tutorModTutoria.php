<?php
$Salida = '0';

session_start();
include 'Clases/BD/conexion.php';
if (!isset($_SESSION['Datos'])) {
    // No existe la sesión
    header("location:index.php");
}else{
    $idTipo = $_SESSION['Datos'][4];
    if(strcmp($idTipo,"A")==0){
        header("location:index.php");
    }
}

if (isset($_POST['modIdTutoria'])) {
    $idTutoria=$_POST['modIdTutoria'];
}

if (isset($_POST['diaPeriodoTut'])) {
    $idTutoria=$_POST['secreto'];
    $Consulta2 = "UPDATE PERIODO SET fecha = '{$_POST['diaPeriodoTut']}', horaIn= '{$_POST['horaInicioTut']}', horaOut= '{$_POST['horaSalidaTut']}'  WHERE idEvento = {$idTutoria};";
    mysqli_query($Conexion, $Consulta2);

    $Consulta2 = "UPDATE TUTORIA SET asisAprendiz = NULL, asisTutor = NULL, idValoracion = NULL WHERE idTutoria = {$idTutoria};";
    mysqli_query($Conexion, $Consulta2);
    $Salida='0005';
}

$Consulta = "call sp_consultarSingleTutoria({$idTutoria});";
if ($Resultado3 = mysqli_prepare($Conexion, $Consulta)) {
    /* execute statement */
    mysqli_stmt_execute($Resultado3);
    /* bind result variables */
    $Row3;
    mysqli_stmt_bind_result($Resultado3, $Row3[0], $Row3[1], $Row3[2], $Row3[3], $Row3[4], $Row3[5], $Row3[6], $Row3[7], $Row3[8], $Row3[9]);
    /* fetch values */
    while (mysqli_stmt_fetch($Resultado3)) {
        $descTutoria = $Row3[1];
        $descCosto = $Row3[2];
        $monto=$Row3[3];
        $tipoTutoria=$Row3[4];
        $descArea = $Row3[5];
        $fecha = $Row3[6];
        $horaIn = $Row3[7];
        $horaOut = $Row3[8];
        $idAprendiz = $Row3[9];
    }
    /* close statement */
    mysqli_stmt_close($Resultado3);
}

$Consulta = "SELECT CONCAT_WS(' ',nombre,pApellido,sApellido) FROM CUENTA WHERE idCuenta = {$idAprendiz};";
$Resultado2 = mysqli_query($Conexion, $Consulta);
while ($Row2 = $Resultado2->fetch_array()) {
    $nombreAprendiz = $Row2[0];
}


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>LearnEasy - Página principal de aprendiz</title>
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
    </head>
    <body id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="index.php"><img src="assets/img/navbar-logo.svg" alt="" /></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ml-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ml-auto">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorCuenta.php">Visualizar cuenta</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorPerfil.php">Visualizar perfil</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorAgenda.php">Consultar agenda</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
<!-- Services-->
<section class="page-section" id="services">
            <div class="container">

                <div class="row text-center">
                    <h2 class="section-heading text-uppercase">Modificar tutoria</h2>
                    
                </div>
                <h3 class="section-subheading text-muted">Como tutor puedes hacer cambios en ciertos datos de una tutoria.</h3>
                
                <div class="row">
                <div class="container-fluid px-1 px-sm-2 py-2 mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-10 col-lg-9 col-xl-8">
                            <div class="card border-0">
                            <form method="Post" name="modTutForm" id="modTutForm">
                                <div class="row px-3"><label class="text-grey mt-1 mb-3">Aprendiz: <?=$nombreAprendiz; ?>  </label></div>
                                <div class="row px-3">
                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Area de conocimiento:</label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"> <label> <?= $descArea;?> </label></div>
                                    </div>

                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Plan de tutoria:</label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"> <label> <?= $descCosto;?> </label>
                                            <div class="mob"> <label class="text-grey mr-1">Costo: $ <?= $monto;?></label></div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">Tipo de tutoria: <?= get_tipoTutoria($tipoTutoria);?></label></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Fecha de inicio: </label> </div>
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"> <input type='date' name="diaPeriodoTut" id="diaPeriodoTut" style='width:30rem; height:2.5rem;' value='<?= $fecha;?>' min='<?= date("Y-m-d", strtotime("yesterday"));?>' class="mb-2 mob" required>
                                            
                                        </div>
                                    </div>

                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Horario: </label></div>
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3">
                                            <div class="mob mb-2">
                                                <label class="text-grey"></label><input class="ml-1" type="time" name="horaInicioTut" style='height:2.5rem;' id="horaInicioTut" value="<?= date("H:i",strtotime($horaIn));?>" min="07:00:00" required> 
                                                <label class="text-grey">a</label><input class="ml-1" type="time" name="horaSalidaTut" style='height:2.5rem;' id="horaSalidaTut" value="<?= date("H:i",strtotime($horaOut));?>" min="07:30:00" required>
                                            </div>
                                        </div>
                                    </div>
                                <div class="row px-3 mt-3">
                                    <div class="col-sm-3"><label class="text-grey mr-1">Descripcion: </label></div>
                                    <div class="col-sm-9 list"><p><?= str_replace(Array("\r\n", "\r", "\n"), "<br>", $descTutoria);?></p></div>
                                </div>
                                <div class="row px-3 mt-3">
                                    <input type="hidden" name="secreto" id="secreto" value="<?= $idTutoria;?>">
                                    <div class="col-sm-10"><button class="btn btn-primary btn-lg bg-dark" name='btnModTut' id='btnModTut' type="submit">Realizar cambios</button></div>
                                </div>
                            </form>
                            
                            </div>
                        </div>
                    </div>
                </div>
                </div>

            </div>
        </section>
        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-left">Copyright © LearnEasy 2021</div>
                </div>
            </div>
        </footer>
        <!-- Sweetalert2 https://sweetalert2.github.io/ -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <!-- Bootstrap core JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Third party plugin JS-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>
        <!-- Contact form JS-->
        <script src="assets/mail/jqBootstrapValidation.js"></script>
        <script src="assets/mail/contact_me.js"></script>
        <!-- Core theme JS-->
        <script src="js/scripts.js"></script>
        <script>
            let salida = '<?= $Salida;?>';

            if(salida == '0005'){
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'Se han realizado los cambios a la tutoría exitosamente.',
                });
            }
        </script>
    </body>
</html>
<?php

    function get_tipoTutoria($tipoPrecio){

        if(strcmp($tipoPrecio,'E')==0){
            return "Extendido";
        }else{
            return "Individual";
    }
}
?>