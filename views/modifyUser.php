<?php
include_once 'layout/general.php';
include_once '../back_end/userController.php';
$layout = new general();
$controller = new userController();
$user = $controller->readOne($_GET['id']);
$alert = '';
if($user=="error" ){
    $alert = '<div class="alert alert-danger alert-dismissible alert_bar">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> Error de lectura.
  </div>';
}
?>

<!DOCTYPE html>
<html>  
    <?= $layout->head('Modificar Usuario'); ?>
    <body>
        <div id='wrapper'>
            <div class='overlay'></div>
            <!-- Sidebar -->
            <?= $layout->navbar('user'); ?>    
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id='page-content-wrapper'>
                <button type='button' class='hamburger animated fadeInLeft is-closed' data-toggle='offcanvas'>
                    <span class='hamb-top'></span>
                    <span class='hamb-middle'></span>
                    <span class='hamb-bottom'></span>
                </button>
                <?= $layout->navTitle("Admin", 1, "Modificar Usuario"); ?>   

                <!-- Page Content -->
                <div class="row">
                    <div class="col-8 col-md-6 col-lg-6  mx-auto mt-5">
                        <form  name='registration' onSubmit="return formValidation()" <?="action='../back_end/userController.php?type=modify&id=$user[id]'";?> method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="d-flex">
                                    <label for="user" class="col-sm-4 col-form-label">Nombre:</label>
                                    <input type="text" class="form-control pgtion" placeholder="Nombre" name="name" value="<?= $user['name']?>"  id="name" onchange="allLetter(this)" required>
                                </div>
                                <div id="userfeed" class="invalid-feedback d-none"></div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex">
                                    <label for="e-mail" class="col-sm-4 col-form-label">Correo:</label>
                                    <input type="email" class="form-control pgtion" placeholder="Correo" name="email" value="<?= $user['email']?>"  id="e-mail" onchange="ValidateEmail(this)" required>
                                </div>
                                <div id="mailfeed" class="invalid-feedback d-none"></div>
                            </div>

                            <div class="form-group ">
                                <div class="d-flex">
                                    <label for="password" class="col-sm-4 col-form-label">Contraseña:</label>
                                    <input type="password" class="form-control pgtion" placeholder="Contraseña" name="password" value="<?= $user['password']?>" id="password" onchange="password_validation(this)"  required>
                                </div>
                                <div id="passfeed" class="invalid-feedback d-none"></div>
                            </div>
                            <div class="form-group">
                                <div class="d-flex">
                                    <label for="user" class="col-sm-4 col-form-label">Usuario:</label>
                                    <input type="text" class="form-control pgtion" placeholder="Usuario" name="user" value="<?= $user['user_name']?>" id="user" onchange="user_validation(this)" required>
                                </div>
                                <div id="idfeed" class="invalid-feedback d-none"></div>
                            </div>
                            <div class="form-group d-flex pgtion">
                                <label for="gender" class="col-sm-4 col-form-label">Genero:</label>
                                <select class="form-control d-flex d-md-none border-0">
                                    <option>Genero</option>
                                    <option value="1">Femenino</option>
                                    <option value="2">Masculino</option>
                                </select>
                                <div class="form-check-inline d-none d-md-flex">

                                    <label class="form-check-label d-block d-flex " for="radio1">
                                        <input type="radio" class="form-check-input" id="radio1" name="radio1" value="option1" checked>Masculino
                                    </label>
                                </div>
                                <div class="form-check-inline d-none d-md-flex">
                                    <label class="form-check-label d-block d-flex" for="radio2">
                                        <input type="radio" class="form-check-input" id="radio2" name="radio1" value="option2">Femenino
                                    </label>
                                </div>
                            </div>
                            <div class="form-group d-flex">
                                <label for="photo" class="col-sm-4 col-form-label">Foto:</label>
                                <div class="input-group mb-3">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" accept=".jpg, .png, .jpeg" name="photo" id="inputGroupFile03" onchange="labelfile(this.value)">
                                        <label id="labelFile" class="custom-file-label d-block" for="inputGroupFile03">Seleccionar Archivo..</label>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <button type="submit" class="btn btn-primary" onclick="">Modificar</button>
                        </form>
                    </div>
                </div>

            </div>
            <!-- /#page-content-wrapper -->
        </div>
        <script src="../assets/js/userVerification.js" type="text/javascript"></script>
        <!-- /#wrapper -->
    </body>
</html> 
