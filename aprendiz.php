<?php

session_start();

if (!isset($_SESSION['Datos'])) {
    // No existe la sesión
    header("location:index.php");
}

    $listaTutores = Array();
    include 'Clases/BD/conexion.php';
    include 'Clases/Cuenta/tutorClass.php';
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
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand js-scroll-trigger" href="index.php"><img src="assets/img/navbar-logo.svg" alt="" /></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    Menu
                    <i class="fas fa-bars ml-1"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ml-auto">
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizCuenta.php">Visualizar cuenta</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizBuscTut.php">Buscar tutorías</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizAgenda.php">Visualizar agenda</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead" style="padding-bottom: 0rem">
            <div class="container">
                <div class="masthead-subheading">¡Bienvenido a LearnEasy <?php echo $_SESSION['Datos'][0] ?>!</div>
                <div class="masthead-heading text-uppercase">Nuestro objetivo es apoyarte</div>
                <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#comenzar">Por dónde comenzar</a>
            </div>
            <div class="container">
                <div class="row p-5 ">
                    <div class="col-md-5 offset-md-0">
                        <div class="container-fluid">
                            <form class="d-flex" method="POST" name="searchForm" >
                            <input name="areaTutor" class="form-control input-lg" size="50" type="search" placeholder="¿Qué asignatura deseas buscar?" aria-label="Search"/>
                            <button type="submit" name="btnBuscarTutor" class="btn btn-outline-warning">Buscar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <!-- Services-->
        <?php
    if(isset($_POST["btnBuscarTutor"]))
    {
        $asignatura = $_POST['areaTutor'];
        $asignatura = strtoupper($asignatura);
        $Consulta= "CALL sp_BuscarArea('$asignatura')";

        $Resultado= mysqli_query($Conexion,$Consulta);
        $numCol = mysqli_num_fields($Resultado);
        
        if($numCol == 12)
        {   
            $aux = "";
            echo "<section class='page-section' id='services'>";
            echo "<div class='container'>";
            echo "<div class='text-center'>";
            echo "<h2 class='section-heading text-uppercase'>Resultados para  {$asignatura}</h2>";
            echo "</div>";
            echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
            while($Row = $Resultado-> fetch_array()){
                $maestro = new tutorClass();
                $maestro->inicializar($Row[0],$Row[1],$Row[2],$Row[3],$Row[4],$Row[5],$Row[6],$Row[7],$Row[8],$Row[9],$Row[11]);
                array_push($listaTutores,$maestro);
                $aux = $Row[11];
                
                //echo $Row[0].$Row[1].$Row[2].$Row[3].$Row[4].$Row[5].$Row[6].$Row[7].$Row[8].$Row[9];
                //echo $maestro->get_nombreCompleto();
            }
            foreach($listaTutores as $key=>$tut){
                $cortita = '';
                echo "<div class='col'>";
                echo    "<div class='card'>";
                //echo        "<img src='...' class='card-img-top' alt='...'>";
                echo        "<div class='card-body'>";
                
                echo            "<h5 class='card-title'>".$tut->get_nombreCompleto()."</h5>";
                if(strlen($tut->descripcion)>250){
                    $cortita = substr($tut->descripcion,0,240)."...";
                }else{
                    $cortita = $tut->descripcion;
                }
                echo            "<p class='card-text'>".$cortita."</p>";
                echo            "<div class='progress'>";
                echo            "<div class='progress-bar  bg-warning ' role='progressbar' style='width: {$tut->get_valoracion()}%' aria-valuenow='{($tut->valoracionT)*20}' aria-valuemin='0' aria-valuemax='100'></div>";
                echo            "</div>";
                echo            "<p class='card-text'>Valoración: ".$tut->valoracionT."</p>";
                //echo            "<form class='d-flex' method='POST' action='verPerfil.php'>";
                echo            "<input type='button' name='btnVerTutor' data-tutor='".$tut->idTutor."' class='btn btn-outline-warning bg-dark' onclick='verTutor(event);' value='Ver perfil'/>";
                echo            "&nbsp;&nbsp;<h8 class='card-text'>{$tut->singleArea}</h8>";
                echo        "</div>";
                echo    "</div>";
                echo "</div>";
            }
            echo "</div>";
            echo "</div>";
            echo "</section>";
        }
    }
?>
<!-- Services-->
<section class="page-section" id="comenzar">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Opciones disponibles</h2>
                    <br/>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                    <a href="aprendizCuenta.php">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Visualizar cuenta</h4>
                        </a>
                        <p class="text-muted">Dentro de esta opción podrás realizar las modificaciones que requieras dentro de tu cuenta</p>
                    </div>
                    <div class="col-md-4">
                    <a href="aprendizBuscTut.php">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Buscar tutorías</h4>
                        </a>
                        <p class="text-muted">Aquí podrás realizar la busqueda de las tutorías que necesites</p>
                    </div>
                    <div class="col-md-4">
                    <a href="aprendizAgenda.php">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Visualizar agenda</h4>
                        </a>
                        <p class="text-muted">Dentro de está opción encontrarás todo lo relacionado a las tutorías en las cuales te encuentras registrado</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- Footer-->
        <!-- form -->
        <form method="post" action="aprendizBuscTut.php" target='_blank' name="tutorForm" id="tutorForm">
        <input type="hidden" name="nameTutor" id="nameTutor"/>
        </form>
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
        <script>
        function verTutor(event) {
            const idTutor = event.target.dataset.tutor;
            const secreto = document.getElementById("nameTutor");
            secreto.value = idTutor;
            console.log(event);
            document.getElementById('tutorForm').submit();
        }
        </script>
    </body>
</html>