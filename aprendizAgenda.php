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
include 'Clases/Cuenta/tutorClass.php';
include 'Clases/Agenda/horarioClass.php';
include 'Clases/Agenda/solicitudClass.php';
include 'Clases/Agenda/Tutoria.php';

$idCuenta = $_SESSION["Datos"][5];
$idHorario = $_SESSION["Datos"][6];

$listaSolicitudes = Array();
$listaTutorias = Array();
if (isset($_POST['nameSolicitud'])) {
    $Consulta2 = "DELETE FROM EVENTO WHERE idEvento = {$_POST['nameSolicitud']} ;";
    $Resultado2 = mysqli_query($Conexion, $Consulta2);
    $Salida = '0005';
}

$Consulta3 = "CALL sp_consultarTutorias ({$idCuenta},1);";
if ($Resultado3 = mysqli_prepare($Conexion, $Consulta3)) {
    /* execute statement */
    mysqli_stmt_execute($Resultado3);
    /* bind result variables */
    $Row3;
    mysqli_stmt_bind_result($Resultado3, $Row3[0], $Row3[1], $Row3[2], $Row3[3], $Row3[4], $Row3[5], $Row3[6], $Row3[7], $Row3[8], $Row3[9], $Row3[10], $Row3[11], $Row3[12], $Row3[13], $Row3[14], $Row3[15], $Row3[16], $Row3[17], $Row3[18], $Row3[19]);
    /* fetch values */
    while (mysqli_stmt_fetch($Resultado3)) {
        $holderTut = new Tutoria();
        $holderTut->inicializar($Row3[0], $Row3[1], $Row3[2], $Row3[3], $Row3[4], $Row3[5], $Row3[6], $Row3[7], $Row3[8], $Row3[9], $Row3[10], $Row3[11], '', $Row3[12], '', $Row3[13], $Row3[14], $Row3[15], $Row3[16], $Row3[17], $Row3[18], $Row3[19]);
        array_push($listaTutorias, $holderTut);
    }
    /* close statement */
    mysqli_stmt_close($Resultado3);
}

$Consulta = "CALL sp_consultarSolicitudes ({$idCuenta},1);";
$Resultado = mysqli_query($Conexion, $Consulta);
while ($Row = $Resultado->fetch_array()) {
    $holderSol = new SolicitudClass();
    $holderSol->inicializar($Row[0], $Row[1], $Row[2], $Row[3], $Row[4], $Row[5], $Row[6], $Row[7], $Row[8], $Row[9], $Row[10], $Row[11], '', $Row[12], '', $Row[13]);
    array_push($listaSolicitudes, $holderSol);
}

$jsonListaTutorias = json_encode($listaTutorias);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>LearnEasy - Cuenta de tutor</title>
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

<body onload='miPost();' id="page-top">
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
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizCuenta.php">Visualizar cuenta</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendiz.php">Buscar tutorias</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizAgenda.php">Consultar agenda</a></li>
                    <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Services-->
    <section class="tabs-section text-white">
        <div class="container">
            <p style="padding:20px"></p>
            <div class="row">
                <div class="col-sm-5 col-lg-3">
                    <ul class="nav nav-tabs flex-column mb-3">
                        <li class="nav-item">
                            <a class="nav-link active show" data-toggle="tab" href="#tab-1">Mis tutorias</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#tab-2">Mis solicitudes</a>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-7 col-lg-9">
                    <div class="tab-content">
                        <div class="tab-pane active show" id="tab-1">
                            <div class="row">
                                <div class="col-lg-12 details" id="tutContainer">
                                    <h3 class="mt-3">Mis tutorías</h3>
                                    <small class='text-muted'>1.- Sólo puedes pagar las tutorías cuya fecha y hora de realización no hayan pasado.<br>2.- Si por algún motivo se pasó la fecha de realización de tutoría y no asististe o pagaste, el tutor puede modificar la fecha y hora correspondientes.<br>3.- Solo podrás valorar las tutorías a las que hayas asistido, sino sería injusto ¿No crees? seamos todos responsables para formar una mejor comunidad!</small>

                                </div>
                            </div>
                        </div>
                        <div class='tab-pane' id='tab-2'>
                            <div class='row'>
                                <div class="col-lg-12 details" id='solContainer'>
                                    <h3 class="mt-3"> Mis solicitudes</h3>
                                    <small class='text-muted'>1.- Puedes cancelar una tutoría si cambiaste de opinión, pero una vez el tutor la acepte no podrás cancelarla.
                                    </small>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--FORM-->
    <form method="post" action="aprendizAgenda.php" name="cancelarTutoria" id="cancelarTutoria">
        <input type="hidden" name="nameSolicitud" id="nameSolicitud" />
        <input type="hidden" name="nameHorario" id="nameHorario" />
    </form>

    <form method="post" action="aprendizPagoTutoria.php" name="pagarTutoriaForm" id="pagarTutoriaForm">
        <input type="hidden" name="pagoIdTutoria" id="pagoIdTutoria"/>
    </form>

    <form method="post" action="registraAsistencia.php" target="_blank" name="asistenciaForm" id="asistenciaForm">
        <input type="hidden" name="asisIdTutoria" id="asisIdTutoria" />
        <input type="hidden" name="asisLink" id="asisLink" />
    </form>

    <form method="post" action="certificado.php" target="_blank" name="certificadoForm" id="certificadoForm">
        <input type="hidden" name="certIdTutoria" id="certIdTutoria"/>
    </form>

    <form method="post" action="aprendizValorarTutoria.php" name="agendaValorarForm" id="agendaValorarForm">
        <input type="hidden" name="valorIdTutoria" id="valorIdTutoria" />
        <input type="hidden" name="valorIdTutor" id="valorIdTutor" />
    </form>
    <!-- Alerta -->
    <script>
        function miPost() {
            let salida = '<?php echo ($Salida); ?>';

            if (salida == '0005') {
                Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Se ha cancelado exitósamente su solicitud al tutor correspondiente.',
                    type: "success"
                }).then(function() {
                    window.location.href = "aprendizAgenda.php";
                });
            }
        }

        <?php echo "let solicitudes = [";
        $pointer = 0;
        foreach ($listaSolicitudes as $solicitud) {
            $result = str_replace(array("\r\n", "\r", "\n"), "<br>", $solicitud->eventoDescripcion);
            echo "['{$solicitud->idSolicitud}',
                        '{$solicitud->nombreTutor}',
                        '{$solicitud->idHorarioTutor}',
                        '{$solicitud->periodofecha}',
                        '" . substr($solicitud->periodoHoraEntrada, 0, 5) . "',
                        '" . substr($solicitud->periodoHhoraSalida, 0, 5) . "',
                        '{$result}',
                        '{$solicitud->descAsignatura}',
                        '{$solicitud->precioDesc}',
                        '{$solicitud->precioMonto}',
                        '{$solicitud->get_tipoTutoria()}',
                        {$solicitud->idEstadoSolicitud},
                        '{$solicitud->get_Dia()}']";

            $pointer++;
            if ($pointer < count($listaSolicitudes)) {
                echo ",";
            }
        }
        echo "];";
        ?>

        const tutorias = JSON.parse('<?= $jsonListaTutorias; ?>');

        function writeSolicitudes() {
            let cadena_ = '';
            let solContainer = document.getElementById('solContainer');
            solicitudes.forEach(solicitud => {
                //console.log(solicitud[11]);
                if (solicitud[11] == 0) {
                    cadena_ = `<input type='button' onclick='cancelarTutoriaFunc(event)' data-solicitud='${solicitud[0]}' data-horario='${solicitud[2]}' class='btn btn-primary btn-lg bg-dark' value='Cancelar tutoria'/>`;
                }
                solContainer.innerHTML += `<div class='card text-white border-warning bg-dark'>
                                    <div class='card-header row'><div class='col col-8'><h5>Tutor: ${solicitud[1]}</h5></div><div class='col '><h7>${solicitud[12]}</h7></div></div>
                                    <div class='card-body'>
                                    <div class='row card-text'>
                                    <div class='col '><h5 class='card-title text-uppercase'>${solicitud[7]}</h5></div>
                                    <div class='col '><p class='card-text'>${solicitud[3]}</p></div>
                                    <div class='col '><p class='card-text'>De ${solicitud[4]} a ${solicitud[5]}</p></div>
                                    </div>
                                    <p class='card-text'><span class='badge bg-light text-dark'>${solicitud[8]} $ ${solicitud[9]}</span><span class='badge text-dark bg-primary'>${solicitud[10]}</span></P>
                                    <p class='card-text'>${solicitud[6]}</p>${cadena_}
                                    </div>
                                    </div><p></p>`;
                cadena_ = '';
            });
        }
        const currentdate=new Date();
        function writeTutorias() {
            
            let tutContainer = document.getElementById('tutContainer');
            tutorias.forEach(tutoria => {
                let sIn = `${tutoria.periodoFecha}T${tutoria.periodoHoraEntrada}`;
                let sOut = `${tutoria.periodoFecha}T${tutoria.periodoHoraSalida}`;

                let dEntrada= Date.parse(sIn);
                let dSalida= Date.parse(sOut);
                //console.log(`entrada= ${dEntrada} , ahora= ${currentdate.getTime()} , salida= ${dSalida}`);
                let btnPago = '';
                let btnLink = '';
                let btnValor = '';
                let badgeActiva = '';
                let fondoColor = '';
                let badgePago = '';
                let btnCerificado = '';
                if(tutoria.estadoCertificado != '0'){
                    btnCerificado = `<button type='button' onclick='descargarCert(event)' data-tutoria='${tutoria.idTutoria}' class='btn btn-light '>Ver certificado</button>`;
                }else{
                    btnCerificado = "<small class='text-white'><br>Puedes pedir a tu tutor que autorice tu certificado de tutoría.</small>";
                }
                if(currentdate.getTime() > dSalida){
                    fondoColor= "bg-dark";
                    if(tutoria.asistenciaAprendiz != null){
                        badgeActiva = "<span class='badge bg-light text-dark'>Asistida</span>";
                        if(tutoria.idValoracion == null){
                            btnValor=`<button type='button' onclick='valorarFun(event);' data-tutoria='${tutoria.idTutoria}' data-valoracion='${tutoria.idValoracion}' data-aprendiz='${tutoria.idAprendiz}' data-tutor='${tutoria.idTutor}' class='btn btn-secondary'>Valorar</button>`;
                        }
                    }
                    if(tutoria.idEstadoPago=='0'){
                        badgePago = "<span class='badge text-dark bg-danger'>Pago pendiente</span>";
                    }else{
                        badgePago = "<span class='badge text-dark bg-success'>Pagado</span>";
                    }

                }else{
                    fondoColor= "bg-secondary";
                    if(dEntrada < currentdate.getTime() && currentdate.getTime() < dSalida ){
                        badgeActiva = "<span class='badge bg-success text-dark'>Ahora</span>";
                        if(tutoria.idEstadoPago=='0'){
                            badgePago = "<span class='badge text-dark bg-danger'>Pago pendiente</span>";
                            btnPago =  `<button type='button' onclick='pagarTutoriaFunc(event)' data-monto='${tutoria.precioMonto}' data-tutoria='${tutoria.idTutoria}' class='btn text-dark btn-success'>Pagar tutoria</button>`;
                        }else{
                            badgePago = "<span class='badge text-dark bg-success'>Pagado</span>";
                            btnLink = `<button type='button' onclick='registraAsis(event);' data-conferencia='${tutoria.linkConferencia}' data-tutoria='${tutoria.idTutoria}' class='btn text-dark btn-primary'>Ir a la conferencia</button>`;
                        }
                    }else{
                        badgeActiva = "<span class='badge bg-info text-dark'>Por realizar</span>";
                        if(tutoria.idEstadoPago=='0'){
                            badgePago = "<span class='badge text-dark bg-danger'>Pago pendiente</span>";
                            btnPago =  `<button type='button' onclick='pagarTutoriaFunc(event)' data-monto='${tutoria.precioMonto}' data-tutoria='${tutoria.idTutoria}' class='btn text-dark btn-success'>Pagar tutoria</button>`;
                        }else{
                            badgePago = "<span class='badge text-dark bg-success'>Pagado</span>";
                            btnLink= `<button type='button' class='btn text-dark btn-primary disabled' disabled>Ir a la conferencia</button><small class='text-warning'><br>Por favor, espere el día y hora correspondiente para que se active el botón.</small>`;
                        }
                    }
                }

                tutContainer.innerHTML += `<div class='card text-white ${fondoColor}'>
                                        <div class='card-header row'><div class='col col-8'><h5>Tutor: ${tutoria.nombreTutor} ${badgeActiva}</h5></div><div class='col '><h7>${tutoria.dia}</h7></div></div>
                                        <div class='card-body'>
                                            <div class='row card-text'>
                                                <div class='col '>
                                                    <h5 class='card-title text-uppercase'>${tutoria.descAsignatura}</h5>
                                                </div>
                                                <div class='col '>
                                                    <p class='card-text'>${tutoria.periodoFecha}</p>
                                                </div>
                                                <div class='col '>
                                                    <p class='card-text'>De ${tutoria.periodoHoraEntrada.substring(0,5)} a ${tutoria.periodoHoraSalida.substring(0,5)}</p>
                                                </div>
                                            </div>
                                            <p class='card-text'><span class='badge bg-light text-dark'>${tutoria.precioDesc} $ ${tutoria.precioMonto}</span><span class='badge text-dark bg-primary'>${tutoria.precioTipo}</span>${badgePago}</P>
                                            <p class='card-text'>${tutoria.eventoDescripcion}</p>
                                            ${btnLink} ${btnPago} ${btnValor} ${btnCerificado}
                                        </div>
                                    </div>
                                    <p></p>`;
            });
        }

        function imprimeHorario(){
            let diaHoy=currentdate.getDay()-1;
            let fechaHoy = currentdate;
            fechaHoy.setHours(0);
            fechaHoy.setMinutes(0);
            fechaHoy.setSeconds(0);
            let diaLunes = fechaHoy;
            let diaDomingo = fechaHoy;
            diaLunes.setDate(diaLunes.getDate()-diaHoy);
            diaDomingo.setDate(diaDomingo.getDate()+(6-diaHoy));
            tutorias.forEach(tutoria => {
            

            });
        }

        writeTutorias();
        writeSolicitudes();

        function cancelarTutoriaFunc(event) {
            const idSolicitud = event.target.dataset.solicitud;
            const idHorario = event.target.dataset.horario;
            document.getElementById("nameSolicitud").value = idSolicitud;
            document.getElementById("nameHorario").value = idHorario;
            //console.log(event);
            document.getElementById('cancelarTutoria').submit();
        }

        function pagarTutoriaFunc(event) {
            const idTuto = event.target.dataset.tutoria;
            document.getElementById("pagoIdTutoria").value = idTuto;
            document.getElementById('pagarTutoriaForm').submit();
        }

        function registraAsis(event){
            document.getElementById("asisIdTutoria").value = event.target.dataset.tutoria;
            document.getElementById("asisLink").value = event.target.dataset.conferencia;
            document.getElementById("asistenciaForm").submit();
        }

        function descargarCert(event){
            document.getElementById("certIdTutoria").value = event.target.dataset.tutoria;
            document.getElementById('certificadoForm').submit();
        }

        function valorarFun(event){
            document.getElementById("valorIdTutoria").value = event.target.dataset.tutoria;
            document.getElementById("valorIdTutor").value = event.target.dataset.tutor;
            document.getElementById("agendaValorarForm").submit();
        }
    </script>
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
    <!-- Alerta -->
    <script>
        function alertaAprendizAgenda() {

            if (document.getElementById('btnAddPeriodo').value == 'Cancelartutoria') {
                Swal.fire({
                    icon: 'success',
                    title: 'Cancelado',
                    text: 'Tu solicitud de tutoría fue cancelada con exito',
                });

                /*
                if (document.getElementById('btnAddPeriodo').value == 'Cancelartutoria'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Verifica que todos los campos hayan sido llenados de forma correcta',
                    });
                    */
                return false;
            }
        }
    </script>
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
    <!-- Bootstrap CDN JS Links -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .tabs-section {
            overflow: hidden;
            background-color: #333;
            padding: 60px 0px;
        }

        .tabs-section .feature-img {
            max-height: 255px;
            overflow: hidden;
            border-radius: 10px;
            border: 3px solid #fff;
        }

        .tabs-section .nav-tabs {
            border: 0;
        }

        .tabs-section .nav-link {
            border: 0;
            padding: 11px 15px;
            transition: 0.3s;
            color: #fff;
            border-radius: 0;
            border-right: 2px solid #fed136 !important;
            font-weight: 600;
            font-size: 15px;
        }

        .tabs-section .nav-link:hover {
            color: #fed136;
        }

        .tabs-section .nav-link.active {
            color: #333333;
            background: #fed136;
        }

        .tabs-section .nav-link:hover {
            border-right: 4px solid #fed136;
        }

        .tabs-section .tab-pane.active {
            -webkit-animation: fadeIn 0.5s ease-out;
            animation: fadeIn 0.5s ease-out;
        }

        .tabs-section .details h3 {
            font-size: 26px;
            color: #fed136;
        }

        .tabs-section .details p {
            color: #aaaaaa;
        }
    </style>
</body>

</html>