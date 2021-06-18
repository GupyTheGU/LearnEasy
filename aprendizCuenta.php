<?php
$Salida = '0';
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

if(isset($_POST['aprendizCuentaNombre'])){
    $aprendizCuentaNombre = $_POST['aprendizCuentaNombre'];
    $aprendizCuentaPriApe = $_POST['aprendizCuentaPriApe'];
    $aprendizCuentaSegApe = $_POST['aprendizCuentaSegApe'];
    $aprendizCuentaEmail = $_POST['aprendizCuentaEmail'];
    $aprendizCuentaPass = md5($_POST['aprendizCuentaPass']);
    $aprendizCuentaPassN = md5($_POST['aprendizCuentaPassN']);
    $aprendizCuentaPassNV = md5($_POST['aprendizCuentaPassNV']);
    $aprendizCuentaCelular = $_POST['aprendizCuentaCelular'];
    $aprendizCuentaEdad = $_POST['aprendizCuentaEdad'];

    if($Pass1 != $aprendizCuentaPass){
        $Salida = '0014';
    }else{
        if($aprendizCuentaPassN != $aprendizCuentaPassNV ){
            $Salida = '0015';
        }else{
            if($aprendizCuentaPassN != ""){
                $aprendizCuentaPass = $aprendizCuentaPassN;
            }
            $Salida = '0005';
            $Consulta="UPDATE CUENTA SET nombre='".$aprendizCuentaNombre."', pApellido='".$aprendizCuentaPriApe."', sApellido='".$aprendizCuentaSegApe."', telefono='".$aprendizCuentaCelular."', edad='".$aprendizCuentaEdad."', correo='".$aprendizCuentaEmail."', pass='".$aprendizCuentaPass."' WHERE idCuenta = '$idCuenta'";
            $Ejecutar = mysqli_query($Conexion, $Consulta);
            $Nombre = $aprendizCuentaNombre;
            $PriApe = $aprendizCuentaPriApe;
            $SegApe = $aprendizCuentaSegApe;
            $Correo = $aprendizCuentaEmail;
            $Pass1 = $aprendizCuentaPass;
            $Telefono = $aprendizCuentaCelular;
            $Edad = $aprendizCuentaEdad;
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizCuenta.php">Visualizar cuenta</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizBuscTut.php">Buscar Tutorías</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizAgenda.php">Consultar agenda</a></li>
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
                <form id="aprendizCuentaForm" method="POST" name="aprendizCuentaForm" novalidate="novalidate">
                    <div class="row align-items-stretch mb-5">
                        <div class="col-md-7">
                            <label id="emailHelp" class="form-text" style="color:#fed136;">No dejes en blanco los campos marcados con *.</label>
                            <br/>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="aprendizCuentaNombre" class="text-white">* Nombre</label>
                                    <input class="form-control" maxlength="30" name="aprendizCuentaNombre" id="aprendizCuentaNombre" onkeydown="return limiteChars(event,30,'aprendizCuentaNombre',2);" type="text" placeholder="Nombre*" value="<?php echo $Nombre;?>" data-validation-required-message="Por favor ingresa tu nombre" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for='aprendizCuentaPriApe' class="text-white">* Primer apellido</label>
                                    <input class="form-control" maxlength="30" name='aprendizCuentaPriApe' id='aprendizCuentaPriApe' onkeydown="return limiteChars(event,30,'aprendizCuentaPriApe',2);" type="text" placeholder="Primer apellido*" value="<?php echo $PriApe;?>" required="required" data-validation-required-message="Por favor ingresa tu primer apellido" required/>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for='aprendizCuentaSegApe' class="text-white">Segundo apellido</label>
                                    <input class="form-control " maxlength="30" name='aprendizCuentaSegApe' id='aprendizCuentaSegApe' onkeydown="return limiteChars(event,30,'aprendizCuentaSegApe',2);" type="text" placeholder="Segundo apellido" value="<?php echo $SegApe;?>" required="required" data-validation-required-message="Por favor ingresa tu segundo apellido" required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='aprendizCuentaEmail' class="text-white">* Correo electrónico</label>
                                    <input class="form-control" maxlength="30" name='aprendizCuentaEmail' id='aprendizCuentaEmail'  onkeydown="return limiteChars(event,30,'aprendizCuentaEmail');" type="email" placeholder="Correo electrónico*" value="<?php echo $Correo;?>" required="required" data-validation-required-message="Por favor ingresa tu correo electrónico"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='aprendizCuentaPass' class="text-white">* Contraseña original</label>
                                    <label id="emailHelp" class="form-text" style="color:#fed136;" >Ingresa tu contraseña original para poder realizar cambios a tu cuenta</label>
                                    <input class="form-control" maxlength="30" name='aprendizCuentaPass' id='aprendizCuentaPass'  onkeydown="return limiteChars(event,30,'aprendizCuentaPass',1);" type="password" placeholder="Contraseña*" value="<?php ?>" required="required" data-validation-required-message="Por favor ingresa tu contraseña"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='aprendizCuentaPassN' class="text-white">Nueva contraseña</label>
                                    <label id="emailHelp" class="form-text" style="color:#fed136;" >Si estas seguro de cambiar tu contraseña llena el siguiente campo</label>
                                    <input class="form-control" maxlength="30" name='aprendizCuentaPassN' id='aprendizCuentaPassN'  onkeydown="return limiteChars(event,30,'aprendizCuentaPassN',1);" type="password" placeholder="Ingresa tu nueva contraseña" value="<?php ?>" required="required" data-validation-required-message="Nueva contraseña"  required/>
                            
                                    <label id="emailHelp" class="form-text" style="color:#fed136;"> </label>
                                    <input class="form-control bg-secondary" maxlength="30" name='aprendizCuentaPassNV' id='aprendizCuentaPassNV'  onkeydown="return limiteChars(event,30,'aprendizCuentaPassNV',1);" type="password" placeholder="Vuelve a ingresar tu nueva contraseña" value="<?php ?>" required="required" data-validation-required-message="Por favor ingresa tu contraseña nuevamente"  required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='aprendizCuentaCelular' class="text-white">Teléfono celular</label>
                                    <input class="form-control" maxlength="10" name='aprendizCuentaCelular' id='aprendizCuentaCelular' type="number" placeholder="Teléfono celular" value="<?php echo $Telefono;?>" onkeydown="return numeros(event,10,'aprendizCuentaCelular');" required/>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for='aprendizCuentaEdad' class="text-white">* Edad</label>
                                    <input class="form-control" maxlength="3" name='aprendizCuentaEdad' id='aprendizCuentaEdad'  type="number" placeholder="Edad*" value="<?php echo $Edad;?>"required="required" onkeydown="return numeros(event,3,'aprendizCuentaEdad');" data-validation-required-message="Por favor ingresa tu edad" required/>
                                </div>
                            </div>
                            <div>
                                <div id="success"></div>
                                <input type="button" class="btn btn-primary btn-xl text-uppercase" name="btnAprendizActualizar" value="Actualizar datos" onclick="actualizaCuentaAprendiz();"> 
                            </div>  
                        </div>      
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
            
            function actualizaCuentaAprendiz(){
                let elementos = [];
                let contador = 0;
                var mailformat = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
                elementos.push(document.getElementById("aprendizCuentaNombre").value);
                elementos.push(document.getElementById("aprendizCuentaPriApe").value);
                elementos.push(document.getElementById("aprendizCuentaEmail").value);
                elementos.push(document.getElementById("aprendizCuentaPass").value);
                elementos.push(document.getElementById("aprendizCuentaEdad").value);

                
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

                elementos.push(document.getElementById("aprendizCuentaPassN").value);
                elementos.push(document.getElementById("aprendizCuentaPassNV").value);
                if(elementos[5] != elementos[6]){
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Las nuevas contraseñas no coinciden, vuelve a confirmar tu nueva contraseña',
                    });
                    return false;
                }

                document.getElementById("aprendizCuentaForm").submit();

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