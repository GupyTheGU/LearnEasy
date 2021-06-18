<?php
$Salida = '0';
session_start();
include 'Clases/BD/conexion.php';

if (!isset($_SESSION['Datos'])) {
    // No existe la sesión
    header("location:index.php");
} else {
    $idTipo = $_SESSION['Datos'][4];
    if (strcmp($idTipo, "T") == 0) {
        header("location:index.php");
    }
}

if (isset($_POST['valorIdTutoria'])) {
    $idTutoria=$_POST['valorIdTutoria'];
    $idTutor=$_POST['valorIdTutor'];
}

if (isset($_POST['secreto1'])) {
    $idCuenta = $_SESSION["Datos"][5];
    $idTutoria=$_POST['secreto1'];
    $idTutor=$_POST['secreto2'];
    $comentario=$_POST['valorComent'];
    $puntuacion=$_POST['rating1'];
    $Consulta2 = "CALL sp_registrarValoracion({$idTutoria},{$idCuenta},{$idTutor},'{$comentario}',{$puntuacion});";
    mysqli_query($Conexion, $Consulta2);
    $Salida='0005';
}

$Consulta = "call sp_consultarSingleTutoria({$idTutoria});";
$Resultado = mysqli_query($Conexion, $Consulta);
while ($Row = $Resultado->fetch_array()) {
    $descTutoria = $Row[1];
    $descCosto = $Row[2];
    $monto=$Row[3];
    $tipoTutoria=$Row[4];
    $descArea = $Row[5];
}

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
        <style>
            
            .rating-css {
            height: 100px;
            width: 400px;
            padding: 0px;
            }
            .rating-css div {
            color: #ffe400;
            font-size: 30px;
            font-family: sans-serif;
            font-weight: 800;
            text-align: center;
            text-transform: uppercase;
            padding: 20px 0;
            }
            .rating-css input {
            display: none;
            }
            .rating-css input + label {
            font-size: 60px;
            text-shadow: 1px 1px 0 #ffe400;
            cursor: pointer;
            }
            .rating-css input:checked + label ~ label {
            color: #838383;
            }
            .rating-css label:active {
            transform: scale(0.8);
            transition: 0.3s ease;
            }

            textarea {
            resize: none;
            overflow: hidden;
            min-height: 50px;
            max-height: 300px;
            }
        </style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
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
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizCuenta.php">Visualizar cuenta</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendiz.php">Buscar tutorías</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="aprendizAgenda.php">Visualizar agenda</a></li>
                        <li class="nav-item"><a class="nav-link js-scroll-trigger" href="cerrarSesion.php">Cerrar sesión</a></li>
                    </ul>
                </div>
            </div>
        </nav>
<!-- Services-->
<section class="page-section" id="services">
            <div class="container">

                <div class="row text-center">
                    <h2 class="section-heading text-uppercase">Valorar tutoria</h2>
                    <h3 class="section-subheading text-muted">Valorar la tutoria ayudara mucho a Jonathan Czerwiak y los demás aprendices. Ayuda a LearnEasy a ser un mejor espacio para todos, gracias!!!</h3>
                </div>
                
                <div class="row">
                <div class="container-fluid px-1 px-sm-2 py-2 mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-10 col-lg-9 col-xl-8">
                            <div class="card border-0">
                            <form method="Post" name="valorarForm" id="valorarForm">
                                <div class="row px-3"><label class="text-grey mt-1 mb-3">Aprendiz: <?php echo"{$_SESSION['Datos'][0]} {$_SESSION['Datos'][1]} {$_SESSION['Datos'][2]}";?> </label></div>
                                <div class="row px-3">
                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Area de conocimiento:</label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"> <label> <?= $descArea;?> </label></div>
                                    </div>
                                </div>
                                <div class="row px-3">
                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Plan de tutoria:</label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"> <label> <?= $descCosto;?> </label>
                                            <div class="mob"> <label class="text-grey mr-1">Costo: $ <?= $monto;?></label></div>
                                            <div class="mob mb-2"> <label class="text-grey mr-4">Tipo de tutoria: <?= get_tipoTutoria($tipoTutoria);?></label></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row px-3 mt-3">
                                    <div class="col-sm-3"><label class="text-grey mr-1">Descripcion: </label></div>
                                    <div class="col-sm-9"><?= str_replace(Array("\r\n", "\r", "\n"), "<br>", $descTutoria);?></div>
                                </div>

                                <div class="row px-3 mt-3">
                                    <div class="col-sm-3"> <label class="text-grey mr-1">Comentario: </label></div>
                                    <div class="col-sm-9 list"><textarea maxlength="500" class="form-control border-0" oninput="auto_grow(this)" placeholder="Escribe tus pensamientos sobre la tutoría" name="valorComent" id="valorComent" rows="3" cols="3" required></textarea></div>
                                </div>
                                <div class="row px-3 mt-3">
                                    <div class="col-sm-3"> <label class="text-grey mt-1 mb-3">Puntuacion: </label> </div>
                                    
                                    <div class="col-sm-9 list">
                                        <div class="mb-2 row justify-content-between px-3"> 
                                            <div class="rating-css">
                                                <div class="star-icon">
                                                <input type="radio" name="rating1" id="rating1" value="1" checked>
                                                <label for="rating1" class="fa fa-star"></label>
                                                <input type="radio" name="rating1" id="rating2" value="2">
                                                <label for="rating2" class="fa fa-star"></label>
                                                <input type="radio" name="rating1" id="rating3" value="3">
                                                <label for="rating3" class="fa fa-star"></label>
                                                <input type="radio" name="rating1" id="rating4" value="4">
                                                <label for="rating4" class="fa fa-star"></label>
                                                <input type="radio" name="rating1" id="rating5" value="5">
                                                <label for="rating5" class="fa fa-star"></label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row px-3 mt-3">
                                    <input type="hidden" name="secreto1" id="secreto1" value="<?= $idTutoria;?>">
                                    <input type="hidden" name="secreto2" id="secreto2" value="<?= $idTutor;?>">
                                    <div class="col-sm-10"><button class="btn btn-primary btn-lg bg-dark" name='btnValorTut' id='btnValorTut' type="submit">Enviar valoración</button></div>
                                </div>
                            </form>
                            
                            </div>
                        </div>
                    </div>
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
            let salida = '<?= $Salida;?>';

            if(salida == '0005'){
                Swal.fire({
                    icon: 'success',
                    title: 'Terminado',
                    text: 'Se han registrado tu valoración de la tutoría. Gracias por tomarte el tiempo de llenar este formulario!',
                    type: "success"
                }).then(function() {
                    window.location.href = "aprendizAgenda.php";
                    });
            }
            function auto_grow(element) {
                element.style.height = "5px";
                element.style.height = (element.scrollHeight)+"px";
            }
        </script>
    </body>
</html>
<?php

    function get_tipoTutoria($tipoPrecio){

        if(strcmp($tipoPrecio,'E')==0){
            return "Extendido";
        }else{
            return "Individual";
    }
}
?>