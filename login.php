<?php

session_start();
$numFilas=-1;
    if (isset($_SESSION['Datos'])) {
        // Existe la sesión
        if($_SESSION['Datos'][4] == 'T'){
            header("location:tutor.php");
        }
        if($_SESSION['Datos'][4] == 'A'){
            header("location:aprendiz.php");
        }
    }

    include 'Clases/BD/conexion.php';
    if(isset($_POST['loginEmail']))
    {
        $LoginEmail = $_POST['loginEmail'];
        $LoginPass = md5($_POST['loginPassword']);

        $Consulta="SELECT * FROM CUENTA WHERE correo='$LoginEmail' and pass='$LoginPass'";
        $Resultado=mysqli_query($Conexion,$Consulta);
        $numFilas = mysqli_num_rows($Resultado);

        while($Row = $Resultado->fetch_array()){
            $Nombre = $Row['nombre'];
            $PriApe = $Row['pApellido'];
            $SegApe = $Row['sApellido'];
            $idTipo = $Row['idTipo'];
            $idCuenta = $Row['idCuenta'];
            $idHorario = $Row['idHorario'];
        }

        if($numFilas==1)
        {   
            if($idTipo == 'T'){
                $Tutor = 1;
                $Aprendiz = 0;
            } else if($idTipo == 'A'){
                $Tutor = 0;
                $Aprendiz = 1;
            }
            $_SESSION['Datos'] = array();
            array_push($_SESSION['Datos'], $Nombre, $PriApe, $SegApe, $LoginEmail, $idTipo, $idCuenta, $idHorario);
            if($Tutor == 1){
               header("location:tutor.php");
            } else {
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
    <body onload="miPost();" id="page-top">
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
                    <h2 class="section-subheading text-muted">Ingresa los datos solicitados</h2>
                    <br/>
                </div>
                <form id="loginForm" method="POST" name="loginForm">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-6">
                            <div class="form-group">
                                <input class="form-control" name="loginEmail" id="loginEmail"  type="email" placeholder="Correo electrónico*" data-validation-required-message="Por favor ingresa tu correo electrónico" requiered/>
                                <p class="help-block text-danger"></p>
                            </div>
                            <div class="form-group">
                                <input class="form-control" name="loginPassword" id="loginPass" type="password" placeholder="Contraseña*" data-validation-required-message="Por favor ingresa tu contraseña" required/>
                                <p class="help-block text-danger"></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <input type="button" class="btn btn-primary btn-xl text-uppercase" value="Iniciar Sesión" onclick="inicioSesion();"> 
                    </div>
                </form>
            </div>
        </section>
        <!-- Services-->
        <section class="page-section" id="services">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Acerca de LearnEasy</h2>
                    <h3 class="section-subheading text-muted"></h3>
                </div>
                <div class="row text-center">
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">Objetivo</h4>
                        <p class="text-muted">Nuestro objetivo es apoyar a los estudiantes de diferentes niveles académicos en su formación escolar, ofreciendo una amplia variedad de categorías en las cuales los estudiantes pueden solicitar apoyo a un tutor.</p>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                        </span>
                    </div>
                    <div class="col-md-4">
                        <span class="fa-stack fa-4x">
                            <i class="fas fa-circle fa-stack-2x text-primary"></i>
                            <i class="fas fa-laptop fa-stack-1x fa-inverse"></i>
                        </span>
                        <h4 class="my-3">¿Qué buscamos?</h4>
                        <p class="text-muted">Buscamos incentivar a las personas a prestar sus conocimientos a los estudiantes que lo necesiten, ofreciendo la posibilidad de que cualquier persona que lo desee, pueda registrarse en la plataforma.</p>
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
        <!-- Sweetalert2 https://sweetalert2.github.io/ -->
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
        <!-- Alerta -->
        <script>
            function inicioSesion(){
                const usuario = document.getElementById("loginEmail").value;
                const secreto = document.getElementById("loginPass").value;
                const loginForm = document.getElementById("loginForm");
                let contador = 0;
                var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                if(usuario.match(mailformat))
                {
                    contador ++;
                }else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ingresa un correo electrónico válido',
                    });
                return false;
                }
                if(secreto != ""){
                    contador ++;
                }

                if(contador >1){
                    loginForm.submit();
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Los datos que ingresaste son incorrectos, verifica la información e intentalo nuevamente',
                    });
                return false;
                }
            }

            function miPost(){
                let salida = <?php echo($numFilas); ?>;
                if(salida == 0){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Credenciales incorrectas, revisa tus datos e intentalo nuevamente',
                    });
                    return false;
                }
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