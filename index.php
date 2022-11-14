<?php
$alert = '';
if (isset($_GET['access'])) {
    if ($_GET['access'] == 'false') {
        $alert = '<div class="alert alert-danger alert-dismissible alert_bar col-10 col-sm-7 mx-auto">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> Error de autenticacion.
  </div>';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>DEB Trasnsportes</title>
        <link href="assets/css/bootstrap.css" rel="stylesheet" type="text/css"/>
        <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css'>
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/css/tether.min.css'>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'>

        <script src='https://code.jquery.com/jquery-3.3.1.slim.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js'></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.4/js/tether.min.js'></script>
        <script src='../assets/js/jquery.twbsPagination.min.js'></script>
        <script src="assets/js/bootstrap.js" type="text/javascript"></script>        
    </head>
    <body class="bg-login">
        <div class="row">
            <div class="col-10 col-sm-7 col-md-6 col-lg-4 mx-auto mt-5">
                <div class="card-deck">
                    <div class="card bg-light">
                        <div class="container">
                            <div class="col-6 col-sm-6 col-md-6 col-lg-5 col-xl-5 mx-auto mt-5">
                                <img src="assets/img/debLogo.png" class="img-fluid"> 
                            </div>
                            <h2>DEB Transporte</h2>
                            <h5 class="mt-2">Introduzca sus datos de acceso</h5>
                            <?= $alert; ?>
                            <form class="mb-7" action="back_end/userController.php?type=login" method="POST">
                                <div class="mt-2 form-group">
                                    <label for="user">Usuario:</label>
                                    <input type="user" class="form-control" id="user" placeholder="Usuario" name="user">
                                </div>
                                <div class="mt-2 form-group">
                                    <label for="pwd">Contraseña:</label>
                                    <input type="password" class="form-control" id="pwd" placeholder="Contraseña" name="pwd">
                                </div>
                                <div class="form-group form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" name="remember"> Recordar Usuario
                                    </label>
                                </div>
                                <br/>
                                <button type="Submit" class="btn btn-primary">Ingresar</button>
                            </form>
                        </div>
                        <div class="footerIndex"  ></div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html> 