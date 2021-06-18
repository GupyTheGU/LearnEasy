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

    $idCuenta = $_SESSION['Datos'][5];
    $Consulta="SELECT * FROM CUENTA WHERE idCuenta='$idCuenta'";
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

    if(isset($_POST['tutorCuentaNombre'])){
        $tutorCuentaNombre = $_POST['tutorCuentaNombre'];
        $tutorCuentaPriApe = $_POST['tutorCuentaPriApe'];
        $tutorCuentaSegApe = $_POST['tutorCuentaSegApe'];
        $tutorCuentaEmail = $_POST['tutorCuentaEmail'];
        $tutorCuentaPass = md5($_POST['tutorCuentaPass']);
        $tutorCuentaPassN = md5($_POST['tutorCuentaPassN']);
        $tutorCuentaPassNV = md5($_POST['tutorCuentaPassNV']);
        $tutorCuentaCelular = $_POST['tutorCuentaCelular'];
        $tutorCuentaEdad = $_POST['tutorCuentaEdad'];

        if($Pass1 != $tutorCuentaPass){
            $Salida = '0014';
        }else{
            if($tutorCuentaPassN != $tutorCuentaPassNV ){
                $Salida = '0015';
            }else{
                if($tutorCuentaPassN != ""){
                    $tutorCuentaPass = $tutorCuentaPassN;
                }
                $Salida = '0005';
                $Consulta="UPDATE CUENTA SET nombre='".$tutorCuentaNombre."', pApellido='".$tutorCuentaPriApe."', sApellido='".$tutorCuentaSegApe."', telefono='".$tutorCuentaCelular."', edad='".$tutorCuentaEdad."', correo='".$tutorCuentaEmail."', pass='".$tutorCuentaPass."' WHERE idCuenta = '$idCuenta'";
                $Ejecutar = mysqli_query($Conexion, $Consulta);
                $Nombre = $tutorCuentaNombre;
                $PriApe = $tutorCuentaPriApe;
                $SegApe = $tutorCuentaSegApe;
                $Correo = $tutorCuentaEmail;
                $Pass1 = $tutorCuentaPass;
                $Telefono = $tutorCuentaCelular;
                $Edad = $tutorCuentaEdad;
            }
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
    <body onload='miPost();' id="page-top">
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
                    <h2 class="section-subheading text-muted">A continuación podrás modificar los datos de tu cuenta</h2>
                    <br/>
                </div>
                <form id="tutorCuentaForm" method="POST" name="tutorCuentaForm">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-7">
                            <label id="emailHelp" class="form-text" style="color:#fed136;">No dejes en blanco los campos marcados con *.</label>
                            <br/>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="tutorCuentaNombre" class="text-white">* Nombre</label>
                                    <input class="form-control" maxlength="30" name="tutorCuentaNombre" id="tutorCuentaNombre" onkeydown="return limiteChars(event,30,'tutorCuentaNombre',2);" type="text" placeholder="Nombre*" value="<?php echo $Nombre;?>" data-validation-required-message="Por favor ingresa tu nombre" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for='tutorCuentaPriApe' class="text-white">* Primer apellido</label>
                                    <input class="form-control" maxlength="30" name='tutorCuentaPriApe' id='tutorCuentaPriApe' onkeydown="return limiteChars(event,30,'tutorCuentaPriApe',2);" type="text" placeholder="Primer apellido*" value="<?php echo $PriApe;?>" required="required" data-validation-required-message="Por favor ingresa tu primer apellido" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for='tutorCuentaSegApe' class="text-white">Segundo apellido</label>
                                    <input class="form-control " maxlength="30" name='tutorCuentaSegApe' id='tutorCuentaSegApe' onkeydown="return limiteChars(event,30,'tutorCuentaSegApe',2);" type="text" placeholder="Segundo apellido" value="<?php echo $SegApe;?>" required="required" data-validation-required-message="Por favor ingresa tu segundo apellido" required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='tutorCuentaEmail' class="text-white">* Correo electrónico</label>
                                    <input class="form-control" maxlength="30" name='tutorCuentaEmail' id='tutorCuentaEmail'  onkeydown="return limiteChars(event,30,'tutorCuentaEmail');" type="email" placeholder="Correo electrónico*" value="<?php echo $Correo;?>" required="required" data-validation-required-message="Por favor ingresa tu correo electrónico"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='tutorCuentaPass' class="text-white">* Contraseña original</label>
                                    <label id="emailHelp" class="form-text" style="color:#fed136;" >Ingresa tu contraseña original para poder realizar cambios a tu cuenta</label>
                                    <input class="form-control" maxlength="30" name='tutorCuentaPass' id='tutorCuentaPass'  onkeydown="return limiteChars(event,30,'tutorCuentaPass',1);" type="password" placeholder="Contraseña*" value="<?php ?>" required="required" data-validation-required-message="Por favor ingresa tu contraseña"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='tutorCuentaPassN' class="text-white">Nueva contraseña</label>
                                    <label id="emailHelp" class="form-text" style="color:#fed136;">Si estas seguro de cambiar tu contraseña llena los siguientes campos</label>
                                    <input class="form-control" maxlength="30" name='tutorCuentaPassN' id='tutorCuentaPassN'  onkeydown="return limiteChars(event,30,'tutorCuentaPassN',1);" type="password" placeholder="Ingresa tu nueva contraseña" value="<?php ?>" required="required" data-validation-required-message="Nueva contraseña"  required/>
                            
                                    <label id="emailHelp" class="form-text" style="color:#fed136;"> </label>
                                    <input class="form-control bg-secondary" maxlength="30" name='tutorCuentaPassNV' id='tutorCuentaPassNV' onkeydown="return limiteChars(event,30,'tutorCuentaPassNV',1);" type="password" placeholder="Vuelve a ingresar tu nueva contraseña" value="<?php ?>" required="required" data-validation-required-message="Por favor ingresa tu contraseña nuevamente"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='tutorCuentaCelular' class="text-white">Teléfono celular</label>
                                    <input class="form-control" maxlength="10" name='tutorCuentaCelular' id='tutorCuentaCelular' type="number" placeholder="Teléfono celular" value="<?php echo $Telefono;?>" onkeydown="return numeros(event,10,'tutorCuentaCelular');" required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='tutorCuentaEdad' class="text-white">* Edad</label>
                                    <input class="form-control" maxlength="3" name='tutorCuentaEdad' id='tutorCuentaEdad'  type="number" placeholder="Edad*" value="<?php echo $Edad;?>"required="required" onkeydown="return numeros(event,3,'tutorCuentaEdad');" data-validation-required-message="Por favor ingresa tu edad" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div id="success"></div>
                        <input type="button" class="btn btn-primary btn-xl text-uppercase" name="btnTutorActualizar" value="Actualizar datos" onclick="actualizaCuentaTutor();"> 
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
            
            function actualizaCuentaTutor(){
                let elementos = [];
                let contador = 0;
                var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                elementos.push(document.getElementById("tutorCuentaNombre").value);
                elementos.push(document.getElementById("tutorCuentaPriApe").value);
                elementos.push(document.getElementById("tutorCuentaEmail").value);
                elementos.push(document.getElementById("tutorCuentaPass").value);
                elementos.push(document.getElementById("tutorCuentaEdad").value);

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
            
                if(!elementos[2].match(mailformat))
                {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ingresa un correo electrónico válido',
                    });
                    return false;
                }

                elementos.push(document.getElementById("tutorCuentaPassN").value);
                elementos.push(document.getElementById("tutorCuentaPassNV").value);
                if(elementos[5] != elementos[6]){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las nuevas contraseñas no coinciden, vuelve a confirmar tu nueva contraseña',
                    });
                    return false;
                }

                document.getElementById("tutorCuentaForm").submit();

            }

            function miPost(){
                let salida = '<?php echo($Salida); ?>';
                if(salida == '0014'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La contraseña ingresada es incorrecta. Intenta volver a ingresarla',
                    });
                    return false;
                }

                if(salida == '0015'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las nuevas contraseñas no coinciden, vuelve a confirmar tu nueva contraseña',
                    });
                    return false;
                }

                if(salida == '0005'){
                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado',
                        text: '¡La información de la cuenta se actualizó correctamente!',
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