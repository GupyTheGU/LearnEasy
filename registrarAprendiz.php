<?php

session_start();
    $Salida = '0';
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
    

    if(isset($_POST['tutorNombre'])){
        $tutorNombre = $_POST['tutorNombre'];
        $tutorPriApe = $_POST['tutorPriApe'];
        $tutorSegApe = $_POST['tutorSegApe'];
        $tutorEmail = $_POST['tutorEmail'];
        $tutorPass = md5($_POST['tutorPass']);
        $tutorPassV = md5($_POST['tutorPassV']);
        $tutorCelular = $_POST['tutorCelular'];
        $tutorEdad = $_POST['tutorEdad'];

        $Consulta = "CALL sp_registroCuenta('{$tutorNombre}','{$tutorPriApe}','{$tutorSegApe}','{$tutorCelular}',{$tutorEdad},'{$tutorEmail}','{$tutorPass}','A');";
        //echo("<div>{$Consulta}</div>");
        
        $Resultado = mysqli_query($Conexion, $Consulta);
        
        while($Row = $Resultado->fetch_array()){
            $Salida = $Row['salida'];
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="login.php">Iniciar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Contact -->
        <section class="page-section" id="contact">
            <div class="container">
                <div class="text-center">
                    <h2 class="section-heading text-uppercase">Registrate como Aprendiz</h2>
                    <h2 class="section-subheading text-muted">Ingresa los datos solicitados</h2>
                    <br/>
                </div>
                <form id="tutorForm" method="POST" name="tutorForm">
                    <div class="row align-items-stretch mb-6">
                        <div class="col-md-7">
                            <label id="emailHelp" class="form-text" style="color:#fed136;"> Los campos marcados con * son obligatorios</label>
                            <br/>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="tutorNombre" class="text-white">* Nombre</label>
                                    <input class="form-control" maxlength="30" name="tutorNombre" id="tutorNombre" onkeydown="return limiteChars(event,30,'tutorNombre',2);" type="text" placeholder="Nombre" data-validation-required-message="Por favor ingresa tu nombre" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tutorPriApe" class="text-white">* Primer apellido</label>
                                    <input class="form-control" maxlength="30" name="tutorPriApe" id="tutorPriApe" onkeydown="return limiteChars(event,30,'tutorPriApe',2);" type="text" placeholder="Primer apellido" required="required" data-validation-required-message="Por favor ingresa tu primer apellido" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="tutorSegApe" class="text-white">Segundo apellido</label>
                                    <input class="form-control " maxlength="30" name="tutorSegApe" id="tutorSegApe" onkeydown="return limiteChars(event,30,'tutorSegApe',2);" type="text" placeholder="Segundo apellido" required="required" data-validation-required-message="Por favor ingresa tu segundo apellido" required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="tutorEmail" class="text-white">* Correo electrónico</label>
                                    <input class="form-control" maxlength="30" name="tutorEmail" id="tutorEmail"  onkeydown="return limiteChars(event,30,'tutorEmail',1);" type="email" placeholder="Correo electrónico" required="required" data-validation-required-message="Por favor ingresa tu correo electrónico"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="tutorPass" class="text-white">* Contraseña</label>
                                    <input class="form-control" maxlength="30" name="tutorPass" id="tutorPass"  onkeydown="return limiteChars(event,30,'tutorPass',1);" type="password" placeholder="Contraseña" required="required" data-validation-required-message="Por favor ingresa tu contraseña"  required/>
                                    <p></p>
                                    <input class="form-control bg-secondary" maxlength="30" name="tutorPassV" id="tutorPassV"  onkeydown="return limiteChars(event,30,'tutorPassV',1);" type="password" placeholder="Ingresa tu contraseña nuevamente" required="required" data-validation-required-message="Por favor ingresa tu contraseña nuevamente"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="tutorCelular" class="text-white">Teléfono celular</label>
                                    <input class="form-control" maxlength="10" name="tutorCelular" id="tutorCelular" type="number" placeholder="Teléfono celular" onkeydown="return numeros(event,10,'tutorCelular');" required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="tutorEdad" class="text-white">* Edad</label>
                                    <input class="form-control" maxlength="3" name="tutorEdad" id="tutorEdad" type="number" placeholder="Edad" required="required" onkeydown="return numeros(event,3,'tutorEdad');" data-validation-required-message="Por favor ingresa tu edad" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div id="success"></div>
                        <input type="button" class="btn btn-primary btn-xl text-uppercase" name="btnTutorRegistrar" value="Registrarse" onclick="registrarTutor();"> 
                    </div>
                    
                </form>
            </div>
        </section>
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
            function numeros(e,maximo,_texto) {
                let keynum;
                let cadena = document.getElementById(_texto).value.length;
                if (window.vent)
                    keynum = e.keyCode;
                else if (e.which)
                    keynum = e.which;

                if(cadena < maximo|| keynum == 8||(keynum>36 && keynum <41)){
                    if ((keynum > 47 && keynum < 58)||(keynum > 95 && keynum < 106)|| keynum == 8||(keynum>36 && keynum <41))
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

                if(cadena < maximo|| keynum == 8||(keynum>36 && keynum <41)){
                    if(white == 1 && keynum == 32){
                        return false;
                    }
                    return true;
                }else
                    return false;
            }

            function registrarTutor(){
                let elementos = [];
                let contador = 0;
                var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                elementos.push(document.getElementById("tutorNombre").value);
                elementos.push(document.getElementById("tutorPriApe").value);
                elementos.push(document.getElementById("tutorEmail").value);
                elementos.push(document.getElementById("tutorPass").value);
                elementos.push(document.getElementById("tutorPassV").value);
                elementos.push(document.getElementById("tutorEdad").value);

                
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
                        text: 'Los datos que ingresaste son incorrectos, verifica la información e intentalo nuevamente',
                    });
                    return false;
                }

                if(!elementos[2].match(mailformat))
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ingresa un correo electrónico válido',
                    });
                    return false;
                }

                if(elementos[3] != elementos[4] ){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las contraseñas no coinciden, vuelve a confirmar tu contraseña',
                    });
                    return false;
                }

                document.getElementById("tutorForm").submit();

            }

            function miPost(){
                let salida = '<?php echo($Salida); ?>';
                if(salida == '0001'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'El número de usos para ese correo ya excede el limite',
                    });
                    return false;
                }

                if(salida == '0002'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ya se utilizó el mismo correo para este tipo de cuenta',
                    });
                    return false;
                }

                if(salida == '0006'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, utiliza otra contraseña',
                    });
                    return false;
                }

                if(salida == '0003'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrado',
                        text: '¡El usuario se registró exitosamente!',
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