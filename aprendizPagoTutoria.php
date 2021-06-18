<?php
$Salida='0';
$ClientID = "ASTh7jOIF78sQnOQoPC14mMKhe7tW9iqmC-TaCaBIJPKl0cYmfplPq5ku6R9JaNuswTXhAa4FQ1xufYp";
$Currency = "MXN";

$Source = "https://www.paypal.com/sdk/js?client-id=". $ClientID ."&amp;currency=". $Currency;

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

if (isset($_POST['pagoIdTutoria'])) {
    $idTutoria=$_POST['pagoIdTutoria'];
}

if (isset($_POST['secreto'])) {
    $idTutoria=$_POST['secreto'];
    $Consulta2 = "UPDATE TUTORIA SET estadoPago = '1' WHERE idTutoria = {$idTutoria};";
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
        <!-- Font Awesome icons (free version)-->
        <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
        <!-- Google fonts-->
        <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic" rel="stylesheet" type="text/css" />
        <link href="https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700" rel="stylesheet" type="text/css" />
        <!-- Core theme CSS (includes Bootstrap)-->
        <link href="css/styles.css" rel="stylesheet" />
        <script src='<?= $Source;?>'></script>
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
                    <h2 class="section-heading text-uppercase">Pago de tutoría</h2>
                    
                </div>
                <h3 class="section-subheading text-muted">Revisa los siguientes datos para hacer tu pago.</h3>
                
                <div class="row">
                <div class="container-fluid px-1 px-sm-2 py-2 mx-auto">
                    <div class="row d-flex justify-content-center">
                        <div class="col-md-10 col-lg-9 col-xl-8">
                            <div class="card border-0">
                            <form method="Post" name="pagoForm" id="pagoForm">
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
                                    <div class="col-sm-9 list"><p><?= str_replace(Array("\r\n", "\r", "\n"), "<br>", $descTutoria);?></p></div>
                                </div>
                            </form>
                            
                            </div>

                            <div id="paypal-button-container"></div>
                        </div>
                    </div>
                </div>
                </div>

            </div>
        </section>
        <!-- form -->
        <form method="post" name="pagoASD" id="pagoASD">
            <input type="hidden" name="secreto" id="secreto"/>
        </form>
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
            function initPayPalButton() {
                paypal.Buttons({
                    style: {
                        shape: 'pill',
                        color: 'black',
                        layout: 'horizontal',
                        label: 'pay',
                        tagline: false
          
                    },
                    createOrder: function(data, actions) {
                        return actions.order.create({
                        purchase_units: [{'amount':{'currency_code':'MXN','value':<?= $monto;?>}}]
                        });
                    },
                    onApprove: function(data, actions) {
                        return actions.order.capture().then(function(details) {
                            document.getElementById('secreto').value='<?= $idTutoria;?>';
                            document.getElementById('pagoASD').submit();
                        });
                    },
                    onError: function(err) {
                        console.log(err);
                    }
                }).render('#paypal-button-container');
            }

            if(salida == '0'){
                initPayPalButton();
            }
            
            if(salida == '0005'){
                Swal.fire({
                                icon: 'success',
                                title: 'Pagado',
                                text: 'Tu pago de tutoría se ha realizado con éxito.',
                            });
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