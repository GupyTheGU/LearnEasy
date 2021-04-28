<?php

    session_start();

    if (!isset($_SESSION['Datos'])) {
        // No existe la sesión
        header("location:index.php");
    }

    include 'conexion.php';
    include 'tutorClass.php';
    
    $idCuenta = $_SESSION['Datos'][5];

    $Consulta="SELECT * FROM cuenta WHERE idCuenta='$idCuenta'";
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

    $Consulta2="SELECT * FROM tutor WHERE idCuenta='$idCuenta'";
    $Resultado2=mysqli_query($Conexion, $Consulta2);

    while($Row2 = $Resultado2->fetch_array()){
        $Descripcion = $Row2['descripcion'];
        $idHorarioDisp = $Row2['idHorarioDisponibilidad'];
        $Valoracion = $Row2['valoracionTotal'];
    }

    $maestro = new tutorClass();
    $maestro->inicializar($idCuenta,$Nombre,$PriApe,$SegApe,$Telefono,$Edad,$Correo,$Descripcion,$idHorarioDisp,$Valoracion);
    $Consulta3="SELECT * FROM area_conocimiento inner Join tutor_area on area_conocimiento.idArea=tutor_area.idArea where tutor_area.idTutor='$idCuenta'";
    $Resultado3=mysqli_query($Conexion, $Consulta3);
    while($Row3 = $Resultado3->fetch_array()){
        $maestro->add_areaConocimiento($Row3['idArea'],$Row3['descripcion']);
    }

    $Consulta4 = "SELECT * FROM costos_tutor where idTutor='$idCuenta'";
    $Resultado4=mysqli_query($Conexion, $Consulta4);
    while($Row4 = $Resultado4->fetch_array()){
        $maestro->add_precio($Row4['idCosto'],$Row4['descripcion'],$Row4['monto'],$Row4['tipoTutoria']);
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
        <!-- Masthead-->
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">

                <div class="text-center">
                    <h2 class="section-heading text-uppercase"><?php echo $maestro->get_nombreCompleto(); ?></h2>
                    <?php
                        echo            "<div class='progress'>";
                        echo            "<div class='progress-bar progress-bar-striped bg-warning progress-bar-animated' role='progressbar' style='width: {$maestro->get_valoracion()}%' aria-valuenow='{$maestro->get_valoracion()}' aria-valuemin='0' aria-valuemax='100'></div>";
                        echo            "</div>";
                    ?>
                    <h4 class="my-3 text-muted"><?php echo $maestro->valoracionT; ?></h4>
                </div>

                <div class="row text-center">
                    <div class="col-md-2">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Mi lista de precios</h4>
                        <?php
                            foreach($maestro->precios as $plan){
                                echo "<div class='card text-dark bg-light mb-3' style='max-width: 18rem;'>";
                                echo "<div class='card-header'>$ {$plan[2]}</div>";
                                echo "<div class='card-body'>";
                                echo "    <h6 class='card-title'>{$maestro->get_tipoTutoria($plan)}</h6>";
                                echo "    <p class='card-text'>{$plan[1]}</p>";
                                echo "</div>";
                                echo "</div>";
                            }
                        ?>
                    </div>

                    <div class="col-md-8">
                    <h4 class="my-3"></h4>
                        <p class="text-muted"><?php echo $maestro->descripcion;?></p>
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                        </span>
                    </div>

                    <div class="col-md-2">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Tutorías</h4>
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
        </section>
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