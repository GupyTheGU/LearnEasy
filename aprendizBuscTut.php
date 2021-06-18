<?php

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
    include 'Clases/Perfil/Valoracion.php';
    
    if(isset($_POST['nameTutor'])){
        $idCuenta = $_POST['nameTutor']; //ID DEL TUTOR SELECCIONADO
    }else{
        header("location:index.php");
    }

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
        $Descripcion = str_replace(Array("\r\n", "\r", "\n"), "<br>",$Row2['descripcion']);
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
        <title>LearnEasy - Perfil de tutor</title>
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizCuenta.php">Visualizar cuenta</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendiz.php">Buscar Tutorías</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizAgenda.php">Consultar agenda</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">

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

                <div class="row">
                    <div class="col-lg-2 text-center">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Lista de precios</h4>
                        <?php
                            foreach($maestro->precios as $plan){
                                echo "<div class='card text-dark bg-light mb-3'>";
                                echo "<div class='card-header'>$ {$plan[2]}</div>";
                                echo "<div class='card-body'>";
                                echo "    <h6 class='card-title'>{$maestro->get_tipoTutoria($plan)}</h6>";
                                echo "    <p class='card-text'>{$plan[1]}</p>";
                                echo "</div>";
                                echo "</div>";
                            }
                        ?>
                    </div>

                    <div class="col-lg-8">
                    <h4 class="my-3"></h4>
                        <p class="text-start"><?php echo $maestro->descripcion;?></p>
                        
                    </div>

                    <div class="col-lg-2 text-center">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Tutorías que imparte</h4>
                            <ul class="list-group list-group-flush">
                        <?php
                            foreach($maestro->areas as $materia){
                                echo "<li class=list-group-item>{$materia[1]}</li>";
                            }
                        ?>
                            </ul>
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
        <div class="container-fluid fixed-bottom">
            <a class="navbar-brand"></a>
            <form class="d-flex" method="POST" action="solicitarTutoria.php">
            <input class="form-control me-2" type="hidden" placeholder="Search" name='nameTutor' aria-label="Search" value="<?php echo($idCuenta);?>">
            <button class="btn btn-primary btn-lg" nam="btnSolicitar" type="submit">Solicitar tutoria</button>
            </form>
        </div>
        </nav>
        <!-- Footer-->
        <footer class="footer py-4">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-4 text-lg-left">Copyright © LearnEasy 2021</div>
                </div>
            </div>
        </footer>
        <script>
            function auto_grow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight)+"px";
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
                                <div class='progress-bar progress-bar bg-warning  text-dark' role='progressbar' style='width: ${(comento.puntuacion*20)}%' aria-valuenow='${(comento.puntuacion*20)}' aria-valuemin='0' aria-valuemax='100'>${comento.puntuacion}</div>
                                </div>
                            </div>
                        </div>`;
                });
            }

            imprimeComentarios();
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