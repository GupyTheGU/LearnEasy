<?php

session_start();

    if (isset($_SESSION['Datos'])) {
        // Existe la sesión
        if($_SESSION['Datos'][4] == 'T'){
            header("location:tutor.php");
        }
        if($_SESSION['Datos'][4] == 'A'){
            header("location:aprendiz.php");
        }
    }

    include 'conexion.php';

    if(isset($_POST["btnAprendizRegistrar"])){
        $aprendizNombre = $_POST['aprendizNombre'];
        $aprendizPriApe = $_POST['aprendizPriApe'];
        $aprendizSegApe = $_POST['aprendizSegApe'];
        $aprendizEmail = $_POST['aprendizEmail'];
        $aprendizPass = $_POST['aprendizContraseña'];
        $aprendizPassV = $_POST['aprendizContraseñaV'];
        $aprendizCelular = $_POST['aprendizCelular'];
        $aprendizEdad = $_POST['aprendizEdad'];

        $Consulta="INSERT INTO cuenta(idCuenta, nombre, pApellido, sApellido, correo, pass, idTipo, idAgenda, idHorario) VALUES ('','$aprendizNombre','$aprendizPriApe','$aprendizSegApe','$aprendizEmail','$aprendizPass','A', NULL, NULL)";
        $Ejecutar = mysqli_query($Conexion, $Consulta);

    }




?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>LearnEasy - Registro de aprendiz</title>
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="login.php">Iniciar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Contact -->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Registrar a un aprendiz</h2>
                    <h3 class="section-subheading text-muted">Ingresa los datos solicitados</h3>
                </div>
                <form id="aprendizForm"  method="POST" name="aprendizForm" novalidate="novalidate">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="aprendizNombre" type="text" placeholder="Nombre *" required="required" data-validation-required-message="Por favor ingresa tu nombre" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="aprendizPriApe" type="email" placeholder="Primer apellido *" required="required" data-validation-required-message="Por favor ingresa tu primer apellido" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="aprendizSegApe" type="text" placeholder="Segundo apellido *" required="required" data-validation-required-message="Por favor ingresa tu segundo apellido" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="aprendizEmail" type="text" placeholder="Correo electrónico *" required="required" data-validation-required-message="Por favor ingresa tu correo electrónico" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="aprendizContraseña" type="password" placeholder="Contraseña *" required="required" data-validation-required-message="Por favor ingresa tu contraseña" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="aprendizContraseñaV" type="password" placeholder="Ingresa tu contraseña nuevamente *" required="required" data-validation-required-message="Por favor ingresa tu contraseña nuevamente" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="aprendizCelular" type="text" placeholder="Teléfono celular"/>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="aprendizEdad" type="number" placeholder="Edad *" required="required" data-validation-required-message="Por favor ingresa tu edad"/>
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
                        <button class="btn btn-primary btn-xl text-uppercase" name="btnAprendizRegistrar" type="submit">Registrarse</button>
                    </div>
                    
                </form>
            </div>
        </section>
        
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