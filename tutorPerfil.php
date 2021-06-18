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
    include 'Clases/Perfil/Valoracion.php';
    
    $idCuenta = $_SESSION['Datos'][5];

    $Consulta="SELECT * FROM CUENTA WHERE idCuenta='$idCuenta'";
    $Resultado=mysqli_query($Conexion, $Consulta);

    while($Row = $Resultado->fetch_array()){
        $Nombre = $Row['nombre'];
        $PriApe = $Row['pApellido'];
        $SegApe = $Row['sApellido'];
        $Correo = $Row['correo'];
        $Pass1 = $Row['pass'];
        $Telefono = $Row['telefono'];
        $Edad = $Row['edad'];
    }

    $Consulta2="SELECT * FROM TUTOR WHERE idCuenta='$idCuenta'";
    $Resultado2=mysqli_query($Conexion, $Consulta2);

    while($Row2 = $Resultado2->fetch_array()){
        $Descripcion = $Row2['descripcion'];
        $idHorarioDisp = $Row2['idHorarioDisponibilidad'];
        $Valoracion = $Row2['valoracionTotal'];
    }

    $maestro = new tutorClass();
    $maestro->inicializar($idCuenta,$Nombre,$PriApe,$SegApe,$Telefono,$Edad,$Correo,$Descripcion,$idHorarioDisp,$Valoracion);
    $Consulta3="SELECT * FROM AREA_CONOCIMIENTO inner Join TUTOR_AREA on AREA_CONOCIMIENTO.idArea=TUTOR_AREA.idArea where TUTOR_AREA.idTutor='$idCuenta'";
    $Resultado3=mysqli_query($Conexion, $Consulta3);
    while($Row3 = $Resultado3->fetch_array()){
        $maestro->add_areaConocimiento($Row3['idArea'],$Row3['descripcion']);
    }

    $Consulta4 = "SELECT * FROM COSTOS_TUTOR where idTutor='$idCuenta'";
    $Resultado4=mysqli_query($Conexion, $Consulta4);
    while($Row4 = $Resultado4->fetch_array()){
        $maestro->add_precio($Row4['idCosto'],$Row4['descripcion'],$Row4['monto'],$Row4['tipoTutoria']);
    }
    $listaComentarios = Array();
    $Consulta5 = "SELECT idValoracion,nombre,fecha,comentario,puntuacion FROM VALORACION INNER JOIN CUENTA WHERE idCuenta=idAprendiz AND idTutor= {$idCuenta} ;";
    $Resultado5=mysqli_query($Conexion, $Consulta5);
    while($Row5 = $Resultado5->fetch_array()){
        $comento = new Valoracion();
        $comento->inicializar($Row5[0],$Row5[1],$Row5[2],$Row5[3],$Row5[4]);
        array_push($listaComentarios,$comento);
    }

    $jsonComentarios =  json_encode($listaComentarios);

    if(isset($_POST['txtTipoCosto'])){
        $Consulta="INSERT INTO COSTOS_TUTOR(idTutor, descripcion, monto, tipoTutoria) VALUES ({$idCuenta}, '{$_POST['txtDesCosto']}',{$_POST['txtMontoCosto']},'{$_POST['txtTipoCosto']}');";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        $Salida = '0005';
        //header("location:tutorPerfil.php");
    }

    if(isset($_POST["nameCosto"])){
        $idCosto = $_POST["nameCosto"];
        $Consulta="DELETE FROM COSTOS_TUTOR WHERE idCosto ={$idCosto}";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        $Salida = '0005';
        //header("location:tutorPerfil.php");
    }

    if(isset($_POST["btnModDesc"])){
        $Consulta="UPDATE TUTOR SET descripcion = '{$_POST['textareaDesc']}' Where idCuenta = {$idCuenta};";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        $Salida = '0005';
        //header("location:tutorPerfil.php");
    }

    if(isset($_POST["txtMateria"])){
        $especialidad = strtolower($_POST['txtMateria']);
        $Consulta="call sp_agregarArea({$idCuenta},'{$especialidad}');";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        $Salida = '0005';
        //header("location:tutorPerfil.php");
    }

    if(isset($_POST["nameAsignatura"])){
        $idArea = $_POST["nameAsignatura"];
        $Consulta="DELETE FROM TUTOR_AREA WHERE idArea ={$idArea}";
        $Ejecutar = mysqli_query($Conexion, $Consulta);
        $Salida = '0005';
        //header("location:tutorPerfil.php");
    }
/*
    $array_final = array();
    foreach ($Resultado3 as $result){
       $array_final[] = $result;
    }
    print_r($array_final);

    
    while($Row3 = $Resultado3->fetch_array()){
        $Descripcion = $Row2['descripcion'];
        $Valoracion = $Row2['valoracionTotal'];
    }
*/


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
        <style>
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorCuenta.php">Visualizar cuenta</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorPerfil.php">Visualizar perfil</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="tutorAgenda.php">Consultar agenda</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <!-- Services-->
        
        <section class="page-section" id="services">
            <div class="container" >

                <div class="text-center">
                <br/>
                    <h2 class="section-heading text-uppercase"><?php echo $maestro->get_nombreCompleto(); ?></h2>
                    <?php
                        echo            "<div class='progress'>";
                        echo            "<div class='progress-bar progress-bar-striped bg-warning progress-bar-animated' role='progressbar' style='width: {$maestro->get_valoracion()}%' aria-valuenow='{$maestro->get_valoracion()}' aria-valuemin='0' aria-valuemax='100'></div>";
                        echo            "</div>";
                    ?>
                    <h4 class="my-3 text-muted">Valoración: <?php echo $maestro->valoracionT; ?></h4>
                </div>

                <div class="row justify-content-md-center">
                    <div class="col-lg-2">
                        <span class="fa-stack fa-4x" style=' margin: auto; display: block;'>
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3  text-center">Mi lista de precios</h4>
                        <?php
                            foreach($maestro->precios as $plan){
                                echo "<div class='card text-dark bg-light mb-3 text-center'>";
                                echo "<div class='card-header'><div class='row'> <div class='col-md-8 font-bold'> $ {$plan[2]} </div>";
                                echo "<div class='col-md-2'><button type='button' data-plan='{$plan[0]}' onclick='eliminarPlan(event);' class='btn btn-outline-danger btn-sm'>X</button></div>";
                                echo "</div></div>";
                                echo "<div class='card-body'>";
                                echo "    <h6 class='card-title'>{$maestro->get_tipoTutoria($plan)}</h6>";
                                echo "    <p class='card-text'>{$plan[1]}</p>";
                                echo "</div>";
                                echo "</div>";
                            }
                        ?>
                        <div class='progress' style="height:5px; margin-top:10px; margin-bottom:10px;">
                        <div class='progress-bar bg-warning' role='progressbar' style='width:100%' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100'></div>
                        </div>
                        <form class="input-group" method="POST"  name='formAddCosto' id="formAddCosto">
                            <div class="row">
                                <div class="col-lg  text-center"><h5>Agregar nueva tutoría</h5></div>
                            </div>
                            <div class="row">
                                    <div class="col-lg-12"><br/><textarea rows="8" maxlength="100" class="form-control" placeholder="Añade una breve descripción de tu tutoría*" name='txtDesCosto' id="txtDesCosto" onkeydown="return limiteChars(event,100,'txtDesCosto',2);" required></textarea></div>
                            </div>
                            <div class="row">
                                    
                                    <div class="col-lg"><input type="number" min="0" maxlenght="5"  class="form-control form-control-sm" placeholder="Precio*" name='txtMontoCosto' id="txtMontoCosto" onkeydown="return numeros(event,5,'txtMontoCosto');" required/></div>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <select id="txtTipoCosto" name="txtTipoCosto" class="form-control" required>
                                        <option value="N" selected>Tipo de tutoría*</option>
                                        <option value="I">Única</option>
                                        <option value="E">Extendida</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row"></div>
                            <div class="row">
                                <div class="col-lg-3">
                                <br/><br/>
                                <button type="button" class="btn btn-primary btn-lg bg-dark" name='btnAddCosto' onclick="agregaCosto();">+</button> </div>
                                
                                <div class="col-lg-9  text-center"><br/><h5>Añadir a mi lista de precios</h5></div>
                            </div>
                        </form>
                    </div>

                    <div class="col-lg-8">
                        <label id="emailHelp" class="form-text text-muted">Descripción:</label>
                            <h4 class="my-3"></h4>
                              <form class="d-flex" method="POST">
                              
                              <textarea class="form-control border-0" maxlength="2000" name="textareaDesc" placeholder="Puedes dejar en blanco tu descripción, pero recomendamos que escribas sobre ti y tu forma de interactuar con los aprendices, así tendrás más probabilidades de recibir más solicitudes." id="textareaDesc" oninput="auto_grow(this)" rows="20" cols="80"><?php echo $maestro->descripcion;?></textarea>
                              <button name="btnModDesc" class="btn btn-primary btn-lg bg-dark" id="Guardar" value="Guardar" type="submit">Guardar</button>
                              </form>
                     </div>
                     
                    <div class="col-lg-2">
                        <span class="fa-stack fa-4x" style=' margin: auto; display: block;'>
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3 text-center">Tutorías que imparto</h4>
                            <ul class="list-group list-group-flush">
                        <?php
                            foreach($maestro->areas as $materia){
                                echo "<li class=list-group-item> <div class='row'> <div class='col-md-9' >{$materia[1]}</div>";
                                echo "<div class='col-md-3'><button type='button' data-asignatura='{$materia[0]}' onclick='eliminarMateria(event);' class='btn btn-outline-danger btn-sm'>X</button> </div> </div>";
                                echo "</li>";
                            }
                        ?>
                            </ul>
                            <div class='progress' style="height:5px; margin-top:10px; margin-bottom:10px;">
                                <div class='progress-bar bg-warning' role='progressbar' style='width:100%' aria-valuenow='100' aria-valuemin='0' aria-valuemax='100'></div>
                            </div>
                            <form class="input-group" method="POST"  name='formAddArea' id="formAddArea">
                            <div class="row">
                                <div class="col-lg  text-center"><h5>Agregar nueva especialidad</h5></div>
                            </div>
                                <div class="row" > 
                                    
                                    <div class="col col-lg" ><br/><textarea rows="4" maxlength="40" class="form-control form-control-sm" placeholder="Añade una especialidad a tu perfil*"  name='txtMateria' id="txtMateria" onkeydown="return limiteChars(event,40,'txtMateria',2);" required></textarea></div>
                                    
                                </div>
                                <div class="row">
                                <div class="col col-lg-3" ><br/><br/><button class="btn btn-primary btn-lg bg-dark" name='btnAddArea' type="button" onclick='agregaArea();' >+</button></div>
                                <div class="col-lg-9  text-center"><br/><h5>Añadir a tutorías que imparto</h5></div>
                            </div>
                            </form>
                    </div>             
                </div>
            </div>

            <div class="container" id="comentariosContainer">
            <div class="row p-5">
                <div class="col">
                    <h1>COMENTARIOS</h1>
                </div>
            </div>
            </div>

        </section>

        <nav class="navbar navbar-light bg-light">
        <div class="container-fluid fixed-bottom ">
            <a class="navbar-brand"></a>
            <form class="d-flex" method="POST" action="tutorHorarioDisp.php">
            <button class="btn btn-success btn-lg" nam="btnModHorario" type="submit">Disponibilidad de horario</button>
            </form>
        </div>
        </nav>
        <!-- form -->
        <form method="post" action="tutorPerfil.php" name="eliminarPlanForm" id="eliminarPlanForm">
        <input type="hidden" name="nameCosto" id="nameCosto"/>
        </form>

        <form method="post" action="tutorPerfil.php" name="eliminarAreaForm" id="eliminarAreaForm">
        <input type="hidden" name="nameAsignatura" id="nameAsignatura"/>
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
            function auto_grow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight)+"px";
            }
            
            function eliminarPlan(event){
                const idPlan = event.target.dataset.plan;
                const secreto = document.getElementById("nameCosto");
                secreto.value = idPlan;
                //console.log(event);
                document.getElementById('eliminarPlanForm').submit();
            }
            function eliminarMateria(event){
                const idMateria = event.target.dataset.asignatura;
                const secreto = document.getElementById("nameAsignatura");
                secreto.value = idMateria;
                //console.log(event);
                document.getElementById('eliminarAreaForm').submit();
            }
        </script>
                <!-- Alerta -->
                <script defer>
            function numeros(e,maximo,_texto) {
                let keynum;
                let cadena = document.getElementById(_texto).value.length;
                if (window.vent)
                    keynum = e.keyCode;
                else if (e.which)
                    keynum = e.which;

                if(cadena < maximo|| keynum == 8){
                    if ((keynum > 47 && keynum < 58)||(keynum > 96 && keynum < 106)|| keynum == 8)
                        return true;
                    else
                        return false;
                }else
                    return false;
            }

            function limiteChars(e,maximo,_texto,white){
                let keynum;
                let cadena = document.getElementById(_texto).value.length;
                if (window.vent)
                    keynum = e.keyCode;
                else if (e.which)
                    keynum = e.which;

                if(cadena < maximo|| keynum == 8){
                    if(white == 1 && keynum == 32){
                        return false;
                    }
                    return true;
                }else
                    return false;
            }

            function agregaArea(){
                let _materia = document.getElementById('txtMateria').value;
                let _pattern = /(.|\s)*\S(.|\s)*/;
                if(_materia.match(_pattern)){
                    document.getElementById("formAddArea").submit();
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hay campos obligatorios en blanco, vuelve a revisar los datos',
                    });
                    return false;
                }
            }
            
            function agregaCosto(){
                
                let elementos = [];
                let contador = 0;
                elementos.push(document.getElementById("txtDesCosto").value);
                elementos.push(document.getElementById("txtMontoCosto").value);
                elementos.push(document.getElementById("txtTipoCosto").value);

                let i = 0;
                for (i = 0; i < elementos.length; i++) {
                    
                    if(elementos[i] == '' || elementos[i] == ' ' ){
                        break;
                    }
                }
                if(i != elementos.length)
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hay campos obligatorios en blanco, vuelve a revisar los datos',
                    });
                    return false;
                }
                console.log("hola");
                if(elementos[2] == 'N'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor selecciona una opción válida',
                    });
                    return false;
                }        

                document.getElementById("formAddCosto").submit();

            }


            function miPost(){
                let salida = '<?php echo($Salida); ?>';

                if(salida == '0005'){
                    Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'La información de tu perfil de tutor se actualizó correctamente.',
                    type: "success"
                    }).then(function() {
                    window.location.href = "tutorPerfil.php";
                    });
                }
            }

            const listaComentarios = JSON.parse('<?= $jsonComentarios; ?>');

            function imprimeComentarios(){
                let comentContainer = document.getElementById('comentariosContainer');
                listaComentarios.forEach(comento =>{
                    comentContainer.innerHTML +=`<div class='card  mb-3'>
                            <div class='card-header'>
                                <div class='row'>
                                    <div class='col-9 text-start'>
                                        <span class=''>${comento.nombre}</span>
                                    </div>
                                    <div class='col text-end'>
                                        <span class=''>${comento.fecha}</span>
                                    </div>
                                </div>
                            </div>
                            <div class='card-body'>
                                <p class='card-text '>${comento.comentario}</p>
                                <div class='progress'>
                                <div class='progress-bar progress-bar-striped bg-warning progress-bar-animated text-dark' role='progressbar' style='width: ${(comento.puntuacion*20)}%' aria-valuenow='${(comento.puntuacion*20)}' aria-valuemin='0' aria-valuemax='100'>${comento.puntuacion}</div>
                                </div>
                            </div>
                        </div>`;
                });
            }

            imprimeComentarios();
        </script>
    </body>
</html>