<?php
$Salida = '0';
    session_start();

    if (!isset($_SESSION['Datos'])) {
        // No existe la sesión
        header("location:index.php");
    }else{
        $idTipo = $_SESSION['Datos'][4];
        if(strcmp($idTipo,"A")==0){
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
        $linkConferencia = "https://meet.jit.si/LearnEasy-".md5("{$_POST['nameSolicitud']}");
        $Consulta2 = "CALL sp_aceptarSolicitud ({$_POST['nameHorario']},{$idHorario},{$_POST['nameSolicitud']}, '{$linkConferencia}')";
        $Resultado2=mysqli_query($Conexion, $Consulta2);
        $Salida = '0005';
    }

    if(isset($_POST['certIdTutoria'])){
        $Consulta2 = "UPDATE TUTORIA SET certificado = {$_POST['certEdo']} WHERE idTutoria = {$_POST['certIdTutoria']};";
        mysqli_query($Conexion, $Consulta2);
    }
    
    $Consulta3 = "CALL sp_consultarTutorias ({$idCuenta},0);";
    if ($Resultado3 = mysqli_prepare($Conexion, $Consulta3)) {
        /* execute statement */
        mysqli_stmt_execute($Resultado3);
        /* bind result variables */
        $Row3;
        mysqli_stmt_bind_result($Resultado3, $Row3[0], $Row3[1], $Row3[2], $Row3[3], $Row3[4], $Row3[5], $Row3[6], $Row3[7], $Row3[8], $Row3[9], $Row3[10], $Row3[11], $Row3[12], $Row3[13], $Row3[14], $Row3[15], $Row3[16], $Row3[17], $Row3[18], $Row3[19]);
        /* fetch values */
        while (mysqli_stmt_fetch($Resultado3)) {
            $holderTut = new Tutoria();
            $holderTut->inicializar($Row3[0], $Row3[1], $Row3[2], $Row3[3], $Row3[4], $Row3[5], $Row3[6], $Row3[7], $Row3[8], $Row3[9], $Row3[10], $Row3[11], $Row3[12], '' ,$Row3[13], '',  $Row3[14], $Row3[15], $Row3[16], $Row3[17], $Row3[18], $Row3[19]);
            array_push($listaTutorias, $holderTut);
        }
        /* close statement */
        mysqli_stmt_close($Resultado3);
    }

    $Consulta = "CALL sp_consultarSolicitudes ({$idCuenta},0)";
    $Resultado=mysqli_query($Conexion, $Consulta);
    while($Row = $Resultado->fetch_array()){
        $holderSol = new SolicitudClass();
        $holderSol->inicializar($Row[0],$Row[1],$Row[2],$Row[3],$Row[4],$Row[5],$Row[6],$Row[7],$Row[8],$Row[9],$Row[10],$Row[11],$Row[12],'',$Row[13],'');
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
        <title>LearnEasy - Agenda de tutor</title>
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorCuenta.php">Visualizar cuenta</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorPerfil.php">Visualizar perfil</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorAgenda.php">Consultar agenda</a></li>
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
                        <a class="nav-link" data-toggle="tab" href="#tab-2">Solicitudes</a>
                     </li>
                  </ul>
               </div>
               <div class="col-sm-7 col-lg-9">
                  <div class="tab-content">
                     <div class="tab-pane active show" id="tab-1">
                        <div class="row">
                           <div class="col-lg-12 details" id="tutContainer" >
                              <h3 class="mt-3">Mis tutorias</h3> 

                           </div>
                        </div>
                     </div>
                     <div class="tab-pane" id="tab-2">
                        <div class="row">
                           <div class="col-lg-12 details" id='solContainer'>
                              <h3 class="mt-3">Solicitudes</h3>
                           </div>
                        </div>
                     </div>

                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- Sweetalert2 https://sweetalert2.github.io/ -->
      <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
      <!--FORM-->
        <form method="post" action="tutorAgenda.php" name="aceptarTutoria" id="aceptarTutoria">
        <input type="hidden" name="nameSolicitud" id="nameSolicitud"/>
        <input type="hidden" name="nameHorario" id="nameHorario"/>
        </form>

        <form method="post" action="registraAsistencia.php" target="_blank" name="asistenciaForm" id="asistenciaForm">
            <input type="hidden" name="asisIdTutoria" id="asisIdTutoria" />
            <input type="hidden" name="asisLink" id="asisLink" />
        </form>

        <form method="post" name="aprobarTutoria" id="aprobarTutoria">
        <input type="hidden" name="certIdTutoria" id="certIdTutoria"/>
        <input type="hidden" name="certEdo" id="certEdo"/>
        </form>

        <form method="post" action="tutorModTutoria.php" name="modTutoriaAgenda" id="modTutoriaAgenda">
        <input type="hidden" name="modIdTutoria" id="modIdTutoria"/>
        </form>
        <!-- Alerta -->
        <script>
            function miPost(){
                let salida = '<?php echo($Salida); ?>';

                if(salida == '0005'){
                    Swal.fire({
                    icon: 'success',
                    title: 'Éxito',
                    text: 'Se ha agregado una tutoría nueva a tu lista de tutorías',
                    type: "success"
                    }).then(function() {
                    window.location.href = "tutorAgenda.php";
                    });
                }
            }

            <?php echo"let solicitudes = [";
                $pointer = 0;
                foreach($listaSolicitudes as $solicitud){
                    $result = str_replace(array("\r\n", "\r", "\n"), "<br>", $solicitud->eventoDescripcion);
                    echo"['{$solicitud->idSolicitud}',
                        '{$solicitud->nombreAprendiz}',
                        '{$solicitud->idHorarioAprendiz}',
                        '{$solicitud->periodofecha}',
                        '".substr($solicitud->periodoHoraEntrada,0,5)."',
                        '".substr($solicitud->periodoHhoraSalida,0,5)."',
                        '{$result}',
                        '{$solicitud->descAsignatura}',
                        '{$solicitud->precioDesc}',
                        '{$solicitud->precioMonto}',
                        '{$solicitud->get_tipoTutoria()}',
                        {$solicitud->idEstadoSolicitud},
                        '{$solicitud->get_Dia()}']";
        
                    $pointer++;
                    if($pointer < count($listaSolicitudes)){
                        echo",";
                    }
                }
                echo "];";
            ?>
            const tutorias = JSON.parse('<?= $jsonListaTutorias; ?>');
            function writeSolicitudes(){
                let cadena_ = '';
                let solContainer = document.getElementById('solContainer');
                solicitudes.forEach(solicitud =>{
                    //console.log(solicitud[11]);
                    if(solicitud[11] == 0){
                        cadena_ = `<input type='button' onclick='aceptarTutoriaFunc(event)' data-solicitud='${solicitud[0]}' data-horario='${solicitud[2]}' value='Aceptar tutoria' class='btn text-dark btn-success'/>`;
                    }
                    solContainer.innerHTML += `<div class='card text-white border-warning bg-dark'>
                                    <div class='card-header row'><div class='col col-8'><h5>${solicitud[1]}</h5></div><div class='col '><h7>${solicitud[12]}</h7></div></div>
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
                    let badgeValor = '';
                    let badgeActiva = '';
                    let fondoColor = '';
                    let badgePago = '';
                    let btnCerificado = '';

                    if(currentdate.getTime() > dSalida){
                        fondoColor= "bg-dark";
                        if(tutoria.asistenciaTutor != null){
                            badgeActiva = "<span class='badge bg-light text-dark'>Asistida</span>";
                        }
                        if(tutoria.idValoracion == null){
                            badgeValor="&nbsp; &nbsp;<span class='badge text-dark bg-secondary'>Sin valorar</span>";
                        }else{
                            badgeValor="&nbsp; &nbsp;<span class='badge text-dark bg-secondary'>Valorada</span>";
                        }
                        if(tutoria.idEstadoPago=='0'){
                            badgePago = "<span class='badge text-dark bg-danger'>Pago pendiente</span>";
                        }else{
                            badgePago = "<span class='badge text-dark bg-success'>Pagado</span>";
                            if(tutoria.estadoCertificado == '0'){
                                btnCerificado = `<button type='button' onclick='aprobarFun(event,1)' data-tutoria='${tutoria.idTutoria}' class='btn btn-outline-light '>Aprobar certificado</button>`;
                            }else{
                                btnCerificado = `<button type='button' onclick='aprobarFun(event,0)' data-tutoria='${tutoria.idTutoria}' class='btn btn-outline-danger '>Desaprobar</button>`;
                            }
                        }


                    }else{
                        fondoColor= "bg-secondary";
                        if(dEntrada < currentdate.getTime() && currentdate.getTime() < dSalida ){
                            badgeActiva = "<span class='badge bg-success text-dark'>Ahora</span>";
                            if(tutoria.idEstadoPago=='0'){
                                badgePago = "<span class='badge text-dark bg-danger'>Pago pendiente</span>";
                                btnLink = "<small class='text-warning'><br>Se generará el link de la conferencia cuando el aprendiz realice el pago de la tutoría.<br></small>";
                            }else{
                                if(tutoria.estadoCertificado == '0'){
                                    btnCerificado = `<button type='button' onclick='aprobarFun(event,1)' data-tutoria='${tutoria.idTutoria}' class='btn btn-outline-light '>Aprobar certificado</button>`;
                                }else{
                                    btnCerificado = `<button type='button' onclick='aprobarFun(event,0)' data-tutoria='${tutoria.idTutoria}' class='btn btn-outline-danger '>Desaprobar</button>`;
                                }
                                badgePago = "<span class='badge text-dark bg-success'>Pagado</span>";
                                btnLink = `<button type='button' onclick='registraAsis(event);' data-conferencia='${tutoria.linkConferencia}' data-tutoria='${tutoria.idTutoria}' class='btn text-dark btn-primary'>Ir a la conferencia</button>`;
                            }
                        }else{
                            badgeActiva = "<span class='badge bg-info text-dark'>Por realizar</span>";
                            if(tutoria.idEstadoPago=='0'){
                                badgePago = "<span class='badge text-dark bg-danger'>Pago pendiente</span>";
                                btnLink = "<small class='text-warning'><br>Se generará el link de la conferencia cuando el aprendiz realice el pago de la tutoría.<br></small>";
                            }else{
                                if(tutoria.estadoCertificado == '0'){
                                    btnCerificado = `<button type='button' onclick='aprobarFun(event,1)' data-tutoria='${tutoria.idTutoria}' class='btn btn-outline-light '>Aprobar certificado</button>`;
                                }else{
                                    btnCerificado = `<button type='button' onclick='aprobarFun(event,0)' data-tutoria='${tutoria.idTutoria}' class='btn btn-outline-danger '>Desaprobar certificado</button>`;
                                }
                                badgePago = "<span class='badge text-dark bg-success'>Pagado</span>";
                                btnLink= `<button type='button' class='btn text-dark btn-primary disabled' disabled>Ir a la conferencia</button>`;
                            }
                        }
                    }
                    

                    tutContainer.innerHTML += `<div class='card text-white ${fondoColor}'>
                                            <div class='card-header row'><div class='col col-8'><h5>${tutoria.nombreAprendiz} ${badgeActiva}</h5></div><div class='col '><h7>${tutoria.dia}</h7></div></div>
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
                                                ${btnLink}<button type='button' onclick='modTutFun(event);' data-tutoria='${tutoria.idTutoria}' class='btn btn-dark '>Modificar</button> ${btnCerificado} ${badgeValor}
                                            </div>
                                        </div>
                                        <p></p>`;
                });
            }

            writeTutorias();

            writeSolicitudes();
            function aceptarTutoriaFunc(event){
                const idSolicitud = event.target.dataset.solicitud;
                const idHorario = event.target.dataset.horario;
                document.getElementById("nameSolicitud").value = idSolicitud;
                document.getElementById("nameHorario").value = idHorario;
                //console.log(event);
                document.getElementById('aceptarTutoria').submit();
            }   
            
            function registraAsis(event){
                document.getElementById("asisIdTutoria").value = event.target.dataset.tutoria;
                document.getElementById("asisLink").value = event.target.dataset.conferencia;
                document.getElementById("asistenciaForm").submit();
            }

            function aprobarFun(event,estadoCerti){
                document.getElementById("certIdTutoria").value = event.target.dataset.tutoria;
                document.getElementById("certEdo").value = estadoCerti;
                document.getElementById("aprobarTutoria").submit();
            }

            function modTutFun(event){
                document.getElementById("modIdTutoria").value = event.target.dataset.tutoria;
                document.getElementById("modTutoriaAgenda").submit();
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
                color:#fed136;
            }
            .tabs-section .nav-link.active {
                color: #333333;
                background:#fed136;
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