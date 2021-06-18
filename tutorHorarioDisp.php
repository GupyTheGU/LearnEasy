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

    $idCuenta = $_SESSION["Datos"][5];

    $Consulta = "SELECT idHorarioDisponibilidad FROM TUTOR WHERE idCuenta = {$idCuenta};";
    $Resultado=mysqli_query($Conexion, $Consulta);
    $idHorarioDisp= '0';
    while($Row = $Resultado->fetch_array()){
        $idHorarioDisp = $Row['idHorarioDisponibilidad'];
    }

    $horario = new HorarioClass();
    $horario->inicializar($idCuenta,$idHorarioDisp,'D');

    $Consulta3 = "SELECT idHorario, tipoHorario, PERIODO.idPeriodo, idDia, fecha, horaIn, horaOut, idEvento FROM PERIODO NATURAL JOIN REL_HORARIO_PERIODO WHERE idHorario = {$idHorarioDisp} ORDER BY idDia;";
    $Resultado3=mysqli_query($Conexion, $Consulta3);
    while($Row3 = $Resultado3->fetch_array()){
        $horario->add_periodo($Row3['idPeriodo'],$Row3['idDia'],$Row3['fecha'],$Row3['horaIn'],$Row3['horaOut']);
    }


    if(isset($_POST["btnAddPeriodo"])){

        $_dia = $_POST["diaPeriodo"];
        $st_time    =   strtotime("{$_POST["horaInicio"]}:00");
        $end_time   =   strtotime("{$_POST["horaSalida"]}:00");

        //echo("$st_time < $end_time");
        if($st_time < $end_time){
            $Consulta2="CALL sp_registrarPeriodoDisp({$idHorarioDisp},{$_dia},'{$_POST["horaInicio"]}:00','{$_POST["horaSalida"]}:00');";
            $Ejecutar = mysqli_query($Conexion, $Consulta2);
            $Salida = '0005';
        }else{
            $Salida = '0017';
        }
    }

    if(isset($_POST["namePeriodo"])){
        $idPeriodo = $_POST["namePeriodo"];
        $Consulta="DELETE FROM PERIODO WHERE idperiodo ={$idPeriodo}";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        $Salida = '0005';
        //header("location:tutorPerfil.php");
    }
    
    //#######################################################################################################################
    $pointerHorario=0;

    function imprimeHorario($pointerHorario,$horario,$dia){
        
        $i=7;
        
        while($pointerHorario < count($horario->periodos)){
            $periodo = $horario->periodos[$pointerHorario];
            $boton_ = 0;
            if($periodo[1]==$dia){
                $st_time    =   strtotime($periodo[3]);
                $end_time   =   strtotime($periodo[4]);
                for($i;$i<24;$i++){
                                   
                    $cadenaHora = "$i:"."00:00";
                    $cur_time   =   strtotime($cadenaHora);

                    if($end_time <= $cur_time){
                        break;                        
                    }
                    $boton_ = imprimeCuadro($st_time,$cur_time,$end_time,$boton_,$periodo[0]);

                    $cadenaHora = "$i:"."30:00";
                    $cur_time   =   strtotime($cadenaHora);

                    $boton_ = imprimeCuadro($st_time,$cur_time,$end_time,$boton_,$periodo[0]);
                }
                $pointerHorario ++;
            }else{
                break;
            }
        }

        for($i;$i<24;$i++){
            echo "<div class='row '><div class='tablita'></div></div>";
            echo "<div class='row '><div class='tablita'></div></div>";
        }
        return $pointerHorario;
    }

    function imprimeCuadro($st_time, $cur_time, $end_time,$boton_,$idPeriodo){
        if($st_time <= $cur_time && $end_time > $cur_time){
            if($boton_==0){
                echo "<div class='row '><div class='tablita-success'> <div><button type='button' onclick='eliminaPeriodo(event);' data-id='{$idPeriodo}' class='  btn btn-outline-danger btn-sm'>X</button></div></div></div> ";
                $boton_ = 1;
            }else{
                echo "<div class='row '><div class='tablita-success'></div></div>";
            }
        }else{
            echo "<div class='row '><div class='tablita'></div></div>";
        }
        return $boton_;
    }
        
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>LearnEasy - Página principal de tutor</title>
        <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico" />
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <style>
            .tablita{
                height:35px;
                border-bottom: 1px solid #e3dfd3;
                text-align: center;
                font-size:13px;
                width: 100%;
                line-height: 35px;
            }
            .tablita-success{
                height:35px;
                border-bottom: 1px solid #72dd89;
                background-color: #c3e6cb;
                text-align: right;
                font-size:13px;
                width: 100%;
                line-height: 35px;
            }
        </style>
    </head>
    <body onload='miPost();' id="page-top">
        <!-- Navigation-->
        <nav class="navbar navbar-expand-lg navbar-dark  bg-dark fixed-top" id="mainNav">
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

                <div class="text-center">
                <br/>
                    <h2 class="section-heading text-uppercase">Mi horario de disponibilidad</h2>
                    <h3 class="section-subheading text-muted">Agregue y modifique sus tiempos dedicados a atender aprendices</h3>
                </div>

                <div class="row">
                    <div class="container-fluid px-1 px-sm-2 py-2 mx-auto">
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-10 col-lg-9 col-xl-8">
                                <div class="card border-0">
                                <form method="Post" name="periodoForm" id="periodoForm">
                                <div><h6>Agregar nuevo periodo de disponibilidad</h6></div>
                                <br/>
                                    <div class="row px-3">
                                        <div class="col-sm-9 list">
                                            <div class="mb-2 row justify-content-between px-3"> <select name="diaPeriodo" class="mb-2 mob">
                                                    <option value="1">Lunes</option>
                                                    <option value="2">Martes</option>
                                                    <option value="3">Miercoles</option>
                                                    <option value="4">Jueves</option>
                                                    <option value="5">Viernes</option>
                                                    <option value="6">Sabado</option>
                                                    <option value="7">Domingo</option>
                                                </select>
                                                <div class="mob"> <label class="text-grey mr-1">De</label> <input class="ml-1" type="time" name="horaInicio" min="07:00:00" required> </div>
                                                <div class="mob mb-2"> <label class="text-grey mr-4">a</label> <input class="ml-1" type="time" name="horaSalida" min="07:30:00" required> </div>
                                                <div class="col-sm-2"> <button class="btn btn-primary btn-lg bg-dark" name='btnAddPeriodo' type="submit">+</button></div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class='progress' style="height:5px; margin-top:10px; margin-bottom:10px;">
                <div class='progress-bar bg-warning' role='progressbar' style='width:100%' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100'></div>
            </div>

            <div class='container'>
                        <div class="row">
                            <div class="col-auto " id="horaContainer" ><div class='row text-center '><div class='tablita'><h6>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Hora&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</h6></div></div></div>
                            <div class="col" id="lunesContainer" >
                                <div class='row text-center '><div class='tablita'><h6>L</h6></div></div>
                                    <?php
                                        $pointerHorario = imprimeHorario($pointerHorario,$horario,'1');
                                    ?>
                            </div>
                            <div class="col" id="martesContainer" >
                                <div class='row text-center '><div class='tablita'><h6>M</h6></div></div>
                                    <?php
                                                $pointerHorario = imprimeHorario($pointerHorario,$horario,'2');
                                    ?>
                            </div>
                            <div class="col" id="miercolesContainer" >
                                <div class='row text-center '><div class='tablita'><h6>W</h6></div></div>
                                    <?php
                                        $pointerHorario = imprimeHorario($pointerHorario,$horario,'3');
                                    ?>
                            </div>
                            <div class="col" id="juevesContainer" >
                                <div class='row text-center '><div class='tablita'><h6>J</h6></div></div>
                                    <?php
                                        $pointerHorario = imprimeHorario($pointerHorario,$horario,'4');
                                    ?>
                            </div>
                            <div class="col" id="viernesContainer" >
                                <div class='row text-center '><div class='tablita'><h6>V</h6></div></div>
                                    <?php
                                        $pointerHorario = imprimeHorario($pointerHorario,$horario,'5');
                                    ?>
                            </div>
                            <div class="col" id="sabadoContainer" >
                                <div class='row text-center '><div class='tablita'><h6>S</h6></div></div>
                                    <?php
                                        $pointerHorario = imprimeHorario($pointerHorario,$horario,'6');
                                    ?>
                            </div>
                            <div class="col" id="domingoContainer" >
                                <div class='row text-center '><div class='tablita'><h6>D</h6></div></div>
                                    <?php
                                        $pointerHorario = imprimeHorario($pointerHorario,$horario,'7');
                                    ?>
                            </div>
                        </div>
            </div>
        </section>
        <!--FORM-->
        <form method="post" action="tutorHorarioDisp.php" name="eliminarPeriodoForm" id="eliminarPeriodoForm">
        <input type="hidden" name="namePeriodo" id="namePeriodo"/>
        </form>
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
            function miPost(){
                let horaContainer=document.getElementById('horaContainer');
                let contenidoHora=horaContainer.innerHTML;
                for (let i = 7; i < 24; i++) {
                    contenidoHora += `<div class='row '><div class='tablita'>${i}:00 - ${i}:30</div></div>`;
                    if(i==23){
                        contenidoHora += `<div class='row'><div class='tablita'>${i}:00 - 00:00</div></div>`;
                        break;   
                    }
                    contenidoHora += `<div class='row'><div class='tablita'>${i}:30 - ${(i+1)}:00</div></div>`;
                }

                horaContainer.innerHTML=contenidoHora;
                //console.log(contenidoHora);
                let salida = '<?php echo($Salida); ?>';

                if(salida == '0005'){
                    Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'Tu horario de disponibilidad se ha actualizado.',
                    type: "success"
                    }).then(function() {
                    window.location.href = "tutorHorarioDisp.php";
                    });
                }

                if(salida == '0017'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, revisa que las horas ingresadas sean válidas.',
                    });
                    return false;
                }
            }

            function eliminaPeriodo(event){
                const idPeriodo = event.target.dataset.id;
                const secreto = document.getElementById("namePeriodo");
                secreto.value = idPeriodo;
                //console.log(event);
                document.getElementById('eliminarPeriodoForm').submit();
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
    </body>
</html>