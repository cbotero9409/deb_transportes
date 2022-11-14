<?php
include_once 'layout/general.php';
include_once '../back_end/userController.php';
$layout = new general();
$controller = new userController();
$alert = '';
if (isset($_GET['creation'])) {
    if ($_GET['creation'] == 'true') {
        $alert = '<div class="alert alert-success alert-dismissible alert_bar">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> Usuario creado con exito.
  </div>';
    }
}
if (isset($_GET['modification'])) {
    if ($_GET['modification'] == 'true') {
        $alert = '<div class="alert alert-success alert-dismissible alert_bar">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> Usuario Modificado con exito.
  </div>';
    }
}
    
if (isset($_GET['type'])) {
    if ($_GET['type'] == 'delet') {
        $controller->deleteUser($_GET['id']);
        $alert = '<div class="alert alert-success alert-dismissible alert_bar">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> Usuario Borrado.
  </div>';
    }
}
?>

<!DOCTYPE html>
<html>  
    <?= $layout->head('Usuarios'); ?>
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
                <?= $layout->navTitle("Admin", 1, "Usuarios"); ?>
                <a href="../views/CreateUsers.php"><button type="a" class="btn btn-primary marg_btn">Usuario Nuevo</button></a>
                
                <?= $alert; ?>
                <div class="table-responsive">
                    <table class="table ml-3 mt-50">
                        <?= $controller->readUser(); ?>
                    </table>
                </div>
                <!-- Page Content -->

            </div>
        </div>
    </body>
</html> 
