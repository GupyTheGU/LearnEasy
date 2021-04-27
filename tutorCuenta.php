<?php

    session_start();

    if (!isset($_SESSION['Datos'])) {
        // No existe la sesión
        header("location:index.php");
    }

    include 'conexion.php';

    $idCuenta = $_SESSION['Datos'][5];
    $Consulta="SELECT * FROM cuenta WHERE idCuenta='$idCuenta'";
    $Resultado=mysqli_query($Conexion, $Consulta);
    $numFilas = mysqli_num_rows($Resultado);

    while($Row = $Resultado->fetch_array()){
        $Nombre = $Row['nombre'];
        $PriApe = $Row['pApellido'];
        $SegApe = $Row['sApellido'];
        $Correo = $Row['correo'];
        $Pass1 = $Row['pass'];
        $Telefono = $Row['telefono'];
        $Edad = $Row['edad'];
    }

    if(isset($_POST["btnTutorActualizar"])){
        $tutorCuentaNombre = $_POST['tutorCuentaNombre'];
        $tutorCuentaPriApe = $_POST['tutorCuentaPriApe'];
        $tutorCuentaSegApe = $_POST['tutorCuentaSegApe'];
        $tutorCuentaEmail = $_POST['tutorCuentaEmail'];
        $tutorCuentaPass = $_POST['tutorCuentaPass'];
        $tutorCuentaPassV = $_POST['tutorCuentaPassV'];
        $tutorCuentaCelular = $_POST['tutorCuentaCelular'];
        $tutorCuentaEdad = $_POST['tutorCuentaEdad'];

        $Consulta="UPDATE cuenta SET nombre='".$tutorCuentaNombre."', pApellido='".$tutorCuentaPriApe."', sApellido='".$tutorCuentaSegApe."', telefono='".$tutorCuentaCelular."', edad='".$tutorCuentaEdad."', correo='".$tutorCuentaEmail."', pass='".$tutorCuentaPass."' WHERE idCuenta = '$idCuenta'";
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
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
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
        <!-- Contact-->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">MI CUENTA</h2>
                    <h3 class="section-subheading text-muted">A continuación podrás modificar los datos de tu cuenta</h3>
                </div>
                <form id="tutorCuentaForm" method="POST" name="tutorCuentaForm" novalidate="novalidate">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaNombre" type="text" placeholder="Nombre *" value="<?php echo $Nombre;?>" required="required" data-validation-required-message="Por favor ingresa tu nombre" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaPriApe" type="email" placeholder="Primer apellido *" value="<?php echo $PriApe;?>" required="required" data-validation-required-message="Por favor ingresa tu primer apellido" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaSegApe" type="text" placeholder="Segundo apellido *" value="<?php echo $SegApe;?>" required="required" data-validation-required-message="Por favor ingresa tu segundo apellido" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaEmail" type="text" placeholder="Correo electrónico *" value="<?php echo $Correo;?>"  required="required" data-validation-required-message="Por favor ingresa tu correo electrónico" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaPassA" type="password" placeholder="Contraseña original *" value="<?php echo $Pass1;?>"  required="required" data-validation-required-message="Por favor ingresa tu contraseña" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaPass" type="password" placeholder="Nueva contraseña *" value="<?php ?>"  data-validation-required-message="Por favor ingresa tu contraseña" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaPassV" type="password" placeholder="Ingresa tu contraseña nuevamente *" value="<?php echo $Pass1;?>"  data-validation-required-message="Por favor ingresa tu contraseña nuevamente" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaCelular" type="text" placeholder="Teléfono celular" value="<?php echo $Telefono;?>" />
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="tutorCuentaEdad" type="number" placeholder="Edad *" value="<?php echo $Edad;?>" required="required" data-validation-required-message="Por favor ingresa tu edad"/>
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
                        <button class="btn btn-primary btn-xl text-uppercase" name="btnTutorActualizar" type="submit">Actualizar datos</button>
                    </div>
                    
                </form>
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