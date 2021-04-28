<?php

session_start();

if (!isset($_SESSION['Datos'])) {
    // No existe la sesión
    header("location:index.php");
}

    $listaTutores = Array();
    include 'conexion.php';
    include 'tutorClass.php';
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendiz.php">Buscar tutorías</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizAgenda.php">Visualizar agenda</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Masthead-->
        <header class="masthead" style="padding-bottom: 0rem">
            <div class="container">
                <div class="masthead-subheading">¡Bienvenido a LearnEasy!</div>
                <div class="masthead-heading text-uppercase">Nuestro objetivo es apoyarte</div>
                <a class="btn btn-primary btn-xl text-uppercase js-scroll-trigger" href="#services">Conocer más</a>
            </div>
            <div class="container">
                <div class="row p-5 ">
                    <div class="col-md-5 offset-md-0">
                        <div class="container-fluid">
                            <form class="d-flex" method="POST" name="searchForm" >
                            <input name="areaTutor" class="form-control input-lg" type="search" placeholder="¿En que asignatura necesitas ayuda?" aria-label="Search">
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
            echo "<div class='row row-cols-1 row-cols-md-3 g-4'>";
            while($Row = $Resultado-> fetch_array()){
                $maestro = new tutorClass();
                $maestro->inicializar($Row[0],$Row[1],$Row[2],$Row[3],$Row[4],$Row[5],$Row[6],$Row[7],$Row[8],$Row[9]);
                array_push($listaTutores,$maestro);
                $aux = $Row[11];
                //echo $Row[0].$Row[1].$Row[2].$Row[3].$Row[4].$Row[5].$Row[6].$Row[7].$Row[8].$Row[9];
                //echo $maestro->get_nombreCompleto();
            }
            foreach($listaTutores as $key=>$tut){
                echo "<div class='col'>";
                echo    "<div class='card'>";
                echo        "<img src='...' class='card-img-top' alt='...'>";
                echo        "<div class='card-body'>";
                echo            "<h5 class='card-title'>".$tut->get_nombreCompleto()."</h5>";
                echo            "<p class='card-text'>".$tut->descripcion."</p>";
                echo            "<div class='progress'>";
                echo            "<div class='progress-bar progress-bar-striped bg-warning progress-bar-animated' role='progressbar' style='width: {$tut->get_valoracion()}%' aria-valuenow='{($tut->valoracionT)*20}' aria-valuemin='0' aria-valuemax='100'></div>";
                echo            "</div>";
                echo            "<p class='card-text'>".$tut->valoracionT."</p>";
                echo            "<form class='d-flex' method='POST' action='verPerfil.php'>";
                echo            "<input type='submit' class='btn btn-outline-warning bg-dark' value='Ver perfil'/>";
                echo            "&nbsp;&nbsp;<h8 class='card-text'>{$aux}</h8>";
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