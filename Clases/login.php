<?php

session_start();

    if (isset($_SESSION['Datos']) && sizeof($_SESSION['Datos']) > 0) {
        // Existe la sesión
        if($_SESSION['Datos'][4] == 'T'){
            header("location:tutor.php");
        }
        if($_SESSION['Datos'][4] == 'A'){
            header("location:aprendiz.php");
        }
    }

    include 'conexion.php';

    if(isset($_POST["btnIniciarSesion"]))
    {
        $LoginEmail = $_POST['loginEmail'];
        $LoginPass = $_POST['loginPassword'];

        $Consulta= "CALL sp_iniciaSesion('$LoginEmail','$LoginPass')";

        $Resultado= mysqli_query($Conexion,$Consulta);
        $numCol = mysqli_num_fields($Resultado);
        
        if($numCol == 2)
        {
            while($Row = $Resultado-> fetch_array()){
                $existe = $Row[0];
                $idCuenta = $Row[1];
                echo $existe." -existe-".$idCuenta."-idCuenta";
            }
            $Consulta="SELECT * FROM cuenta WHERE idCuenta='$idCuenta'";
            mysqli_free_result($Resultado);
            do 
                if($Resultado=mysqli_store_result($Conexion)){
                    mysqli_free_result($Resultado);
            } while(mysqli_more_results($Conexion) && mysqli_next_result($Conexion));

            $Resultado= mysqli_query($Conexion,$Consulta);
    
            while($Row = $Resultado->fetch_array()){
                $Nombre = $Row['nombre'];
                $PriApe = $Row['pApellido'];
                $SegApe = $Row['sApellido'];
                $idTipo = $Row['idTipo'];
                $idHorario = $Row['idHorario'];
            }

            if($idTipo == 'T'){
                $Tutor = 1;
                $Aprendiz = 0;
            } else if($idTipo == 'A'){
                $Tutor = 0;
                $Aprendiz = 1;
            }

            $_SESSION['Datos'] = Array();
            array_push($Nombre, $PriApe, $SegApe, $LoginEmail, $idTipo, $idCuenta, $idHorario);
            if($Tutor == 1){
                echo("es tutor");
               header("location:tutor.php");
            } else {
                echo("es aprendiz");
               header("location:aprendiz.php");
            }
        } 
        else 
        {
            //header("Refresh: 0; URL=login.php");
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
        <title>LearnEasy - Inicio de sesión</title>
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="registrar.php">Registrarse</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Contact -->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Iniciar sesión</h2>
                    <h3 class="section-subheading text-muted">Ingresa los datos solicitados</h3>
                </div>
                <form id="loginForm" method="POST" name="loginForm" novalidate="novalidate" >
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="loginEmail" type="email" placeholder="Correo electrónico *" required="required" data-validation-required-message="Por favor ingresa tu correo electrónico" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="loginPassword" type="password" placeholder="Contraseña *" required="required" data-validation-required-message="Por favor ingresa tu contraseña" />
                                <p class="help-block text-danger"></p>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-textarea mb-md-0">
                                <!-- <button class="btn btn-primary btn-xl text-uppercase" id="sendMessageButton" type="submit">Iniciar sesión</button> -->
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div id="success"></div>
                        <button class="btn btn-primary btn-xl text-uppercase" name="btnIniciarSesion" id="sendMessageButton" type="submit">Iniciar sesión</button>
                    </div>
                </form>
            </div>
        </section>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Servicios</h2>
                    <h3 class="section-subheading text-muted">Estos son algunos de los servicios que ofrecemos</h3>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-shopping-cart fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">E-Commerce</h4>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Responsive Design</h4>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-lock fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Web Security</h4>
                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.</p>
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

