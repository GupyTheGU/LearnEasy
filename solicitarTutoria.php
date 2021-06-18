<?php
$Salida = '0';
    session_start();

    if (!isset($_SESSION['Datos'])) {
        // No existe la sesión
        header("location:index.php");
    }else{
        $idTipo = $_SESSION['Datos'][4];
        if(strcmp($idTipo,"T")==0){
            header("location:index.php");
        }
    }
    include 'Clases/BD/conexion.php';
    include 'Clases/Cuenta/tutorClass.php';
    include 'Clases/Agenda/horarioClass.php';

    $idCuenta = $_SESSION['Datos'][5];
    $idTutor = '';
    if(isset($_POST["nameTutor"])){
        $idTutor = $_POST["nameTutor"];
        //header("location:tutorPerfil.php");
    }else{
        header("location:index.php");
    }

    $Consulta="SELECT * FROM CUENTA WHERE idCuenta= $idTutor";
    $Resultado=mysqli_query($Conexion, $Consulta);

    while($Row = $Resultado->fetch_array()){
        $Nombre = $Row['nombre'];
        $PriApe = $Row['pApellido'];
        $SegApe = $Row['sApellido'];
        $Correo = $Row['correo'];
        $Telefono = $Row['telefono'];
        $Edad = $Row['edad'];
    }

    $Consulta2="SELECT idHorarioDisponibilidad FROM TUTOR WHERE idCuenta= $idTutor ";
    $Resultado2=mysqli_query($Conexion, $Consulta2);

    while($Row2 = $Resultado2->fetch_array()){
        $idHorarioDisp = $Row2['idHorarioDisponibilidad'];
    }

    $maestro = new tutorClass();
    $maestro->inicializar($idTutor,$Nombre,$PriApe,$SegApe,$Telefono,$Edad,$Correo,'',$idHorarioDisp,'5');
    $Consulta3="SELECT * FROM AREA_CONOCIMIENTO inner Join TUTOR_AREA on AREA_CONOCIMIENTO.idArea=TUTOR_AREA.idArea where TUTOR_AREA.idTutor='$idTutor'";
    $Resultado3=mysqli_query($Conexion, $Consulta3);
    while($Row3 = $Resultado3->fetch_array()){
        $maestro->add_areaConocimiento($Row3['idArea'],$Row3['descripcion']);
    }

    $Consulta4 = "SELECT * FROM COSTOS_TUTOR where idTutor='$idTutor'";
    $Resultado4=mysqli_query($Conexion, $Consulta4);
    while($Row4 = $Resultado4->fetch_array()){
        $maestro->add_precio($Row4['idCosto'],$Row4['descripcion'],$Row4['monto'],$Row4['tipoTutoria']);
    }

    $horario = new HorarioClass();
    $horario->inicializar($idTutor,$idHorarioDisp,'D');

    $Consulta5 = "SELECT idHorario, tipoHorario, PERIODO.idPeriodo, idDia, fecha, horaIn, horaOut, idEvento FROM PERIODO NATURAL JOIN REL_HORARIO_PERIODO WHERE idHorario = {$idHorarioDisp} ORDER BY idDia;";
    $Resultado5=mysqli_query($Conexion, $Consulta5);
    while($Row5 = $Resultado5->fetch_array()){
        $horario->add_periodo($Row5['idPeriodo'],$Row5['idDia'],$Row5['fecha'],$Row5['horaIn'],$Row5['horaOut']);
    }
    //#############################################################################################################
    if(isset($_POST["txtSolArea"])){
        
        $_dia = $_POST["diaPeriodoSol"];
        $st_time    =   strtotime("{$_POST["horaInicioSol"]}:00");
        $end_time   =   strtotime("{$_POST["horaSalidaSol"]}:00");

        if($st_time < $end_time){
            $Consulta2="CALL sp_registrarSolicitud({$idCuenta},{$idTutor},{$_POST["txtSolCosto"]},'{$_POST["textareaDesc"]}',{$_POST["txtSolArea"]},'$_dia','{$_POST["horaInicioSol"]}:00','{$_POST["horaSalidaSol"]}:00');";
            $Ejecutar = mysqli_query($Conexion, $Consulta2);
            $Salida = '0005';
        }else{
            $Salida = '0017';
        }
    }
    //#############################################################################################################
    $pointerHorario=0;

    function imprimeHorario($pointerHorario,$horario,$dia){
        
        $i=7;
        
        while($pointerHorario < count($horario->periodos)){
            $periodo = $horario->periodos[$pointerHorario];
            if($periodo[1]==$dia){
                $st_time    =   strtotime($periodo[3]);
                $end_time   =   strtotime($periodo[4]);
                for($i;$i<24;$i++){
                                   
                    $cadenaHora = "$i:"."00:00";
                    $cur_time   =   strtotime($cadenaHora);

                    if($end_time <= $cur_time){
                        break;                        
                    }
                    imprimeCuadro($st_time,$cur_time,$end_time);

                    $cadenaHora = "$i:"."30:00";
                    $cur_time   =   strtotime($cadenaHora);

                    imprimeCuadro($st_time,$cur_time,$end_time);
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

    function imprimeCuadro($st_time, $cur_time, $end_time){
        if($st_time <= $cur_time && $end_time > $cur_time){
                echo "<div class='row '><div class='tablita-success'></div></div>";
        }else{
            echo "<div class='row '><div class='tablita'></div></div>";
        }
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

            textarea {
            resize: none;
            overflow: hidden;
            min-height: 50px;
            max-height: 300px;
        }
        </style>
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizBuscTut.php">Buscar tutorias</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizAgenda.php">Consultar agenda</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">
                <div class="text-center">
                    <br>
                    <h2 class="section-heading text-uppercase">Formulario de solicitud</h2>
                    <h3 class="section-subheading text-muted">Llene los siguientes campos para poder solicitar una tutoria a <?php echo $maestro->get_nombreCompleto(); ?></h3>
                </div>
                <div class="row">
                <div class="container-fluid px-1 px-sm-2 py-2 mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-10 col-lg-9 col-xl-8">
                            <div class="card border-0">

                            <form method="Post" name="solicitarTutoriaForm">
                                <div class="row px-3">
                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Area de conocimiento:</label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"><select name="txtSolArea" id="txtSolArea" style='width:30rem; height:2.5rem;' class="mob mb-2 ">
                                                <?php
                                                    foreach($maestro->areas as $materia){
                                                        echo "<option value='{$materia[0]}'>{$materia[1]}</option>";
                                                    }
                                                ?>
                                            </select>
                                            <div class='mob mb-2'> <label class='text-white mr-1'>|</label></div>
                                        </div>
                                    </div>

                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Plan de tutoría:</label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3">
                                            <select name="txtSolCosto" onchange='actualizaLabels();' id='txtSolCosto' style='width:30rem; height:2.5rem;' class="mb-2 mob"></select>
                                            <div class='mob mb-2'> <label class='text-grey mr-1' id='lblCostoTutoria'></label></div>
                                            <div class='mob mb-2'> <label class='text-grey mr-4' id='lblTipoTutoria' ></label></div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Fecha de inicio: </label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"> <input type='date' name="diaPeriodoSol" id="diaPeriodoSol" style='width:30rem; height:2.5rem;' value='<?php echo(date("Y-m-d"));?>' min='<?php echo(date("Y-m-d"));?>' class="mb-2 mob" required>
                                            
                                        </div>
                                    </div>
                                    
                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Horario: </label></div>
                                    <div class="col-sm-9 list">
                                    <div class="mb-2 row justify-content-between px-3">
                                        <div class="mob mb-2">
                                            <label class="text-grey"></label><input class="ml-1" type="time" name="horaInicioSol" style='height:2.5rem;' id="horaInicioSol" min='07:00:00' required> 
                                            <label class="text-grey">a</label><input class="ml-1" type="time" name="horaSalidaSol" style='height:2.5rem;' id="horaSalidaSol" min='07:30:00' required>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <input type="hidden" name='nameTutor' value='<?php echo($idTutor);?>'>
                                <div class="row px-3 mt-3">
                                <label class="text-grey mr-1">Descripción</label>
                                <textarea class="form-control border-0" maxlength='400' name="textareaDesc" placeholder='Escribe un comentario que le ayude a saber al tutor lo que necesitas de la tutoría' id="textareaSol" oninput="auto_grow(this)" rows="5" cols="10" required></textarea>
                                    <div class="col-sm-4"><button class="btn btn-primary btn-lg bg-dark" type="submit">Enviar solicitud</button></div>
                                    <div class="col-sm"><a class="btn btn-success btn-lg js-scroll-trigger" href="#horarioDisp">Ver horario de disponibilidad</a></div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

            </div>
        </section>
            <div class='progress' style="height:5px; margin-top:10px; margin-bottom:10px;">
                <div class='progress-bar bg-warning' role='progressbar' style='width:100%' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100'></div>
            </div>
        <section class="page-section" id="horarioDisp" style='padding-top:2rem;'>
            <div class='container'>
                <div class="row text-center">
                    <h2 class="section-heading text-uppercase" >Horario de disponibilidad</h2>
                    <h3 class="section-subheading text-muted">¡Si solicitas una tutoría tomando en cuenta los tiempos libres de <?php echo $maestro->get_nombreCompleto();?> será más fácil que acepte tu solicitud!</h3>
                </div>
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
        <!-- Sweetalert2 https://sweetalert2.github.io/ -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <!-- Alerta -->
        <script>

            let planes = [ <?php
                $pointer = 0;
                foreach($maestro->precios as $plan){
                    echo"['{$plan[0]}','{$plan[1]}','{$plan[2]}','{$maestro->get_tipoTutoria($plan)}']";
                    $pointer++;
                    if($pointer < count($maestro->precios)){
                        echo",";
                    }
                }
            ?>];
            //--------------------------------------------------------------------------------------------
            function miPost(){
                //--------------------------------------------------------------------------------------------
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
                //--------------------------------------------------------------------------------------------
                
                let precioContainer = document.getElementById('txtSolCosto');
                planes.forEach(plan => {
                    precioContainer.innerHTML += `<option value='${plan[0]}'>${plan[1]}</option>`;
                });
                actualizaLabels();

                //--------------------------------------------------------------------------------------------
                let salida = '<?php echo($Salida); ?>';

                if(salida == '0005'){
                    Swal.fire({
                    icon: 'success',
                    title: 'Enviado',
                    text: 'Tu solicitud de tutoría se ha enviado exitosamente',
                    type: "success"
                    });
                }

                if(salida == '0017'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, revisa que las horas ingresadas sean válidas',
                    });
                    return false;
                }
            }
            //--------------------------------------------------------------------------------------------
            function actualizaLabels(){
                let indice_ = document.getElementById('txtSolCosto').selectedIndex;
                console.log(indice_);
                document.getElementById('lblCostoTutoria').innerHTML = `Costo: $  ${planes[indice_][2]}`;
                document.getElementById('lblTipoTutoria').innerHTML = `Tipo de tutoria:  ${planes[indice_][3]}`;
            }
            //--------------------------------------------------------------------------------------------
            function auto_grow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight)+"px";
            }
            //--------------------------------------------------------------------------------------------
            function alertaSolicitarTutoria(){

                console.log(document.getElementById('diaPeriodoSol').value)
                /*
                if (document.getElementById('horaInicio').value == '09:00'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Enviado',
                        text: 'Tu solicitud se envío exitosamente',
                    });
                    
                
                if (document.getElementById('horaInicio').value == '09:00'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Verifica que todos los campos hayan sido llenados de forma correcta',
                    });
                    */
                return false;
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
    </body>
</html>