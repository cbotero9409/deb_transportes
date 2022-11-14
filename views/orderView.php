<?php
include_once 'layout/general.php';
include_once '../back_end/orderController.php';

$layout = new general();
$controller = new orderController();

$data = $controller->readSpecificOrder($_GET['order']);
$date = $controller->formatDate($data['received_date']);
if ($data['state'] !== "Pendiente") {
    $mod_date = $controller->formatDate($data['modification_date']);
} else {
    $mod_date = '';
}
$view = $controller->viewOrder($data['state'], $data['photo']);
$error_photo = '';
$msg_failed = '';

$order_confirmation = '';

if (isset($_GET['confirmation'])) {
    if ($_GET['confirmation'] == "created") {
        $order_confirmation = "<div class='alert alert-success alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Orden creada con éxito!
                    </div>";
    } elseif ($_GET['confirmation'] == "updated") {
        $order_confirmation = "<div class='alert alert-success alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Orden modificada con éxito!
                    </div>";
    }
}

if (isset($_GET['photo_error'])) {
    if ($_GET['photo_error'] === '1') {
        $error_photo = "<div class='alert alert-danger alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        El archivo cargado no es una imagen</div>";
    } elseif ($_GET['photo_error'] === '2') {
        $error_photo = "<div class='alert alert-danger alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        La imagen cargada excede el tamaño máximo permitido</div>";
    } elseif ($_GET['photo_error'] === '3') {
        $error_photo = "<div class='alert alert-danger alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Formato de archivo cargado no permitido</div>";
    }
}

if (isset($_GET['comment_creation'])) {
    if ($_GET['comment_creation'] == "failed") {
        $msg_failed = "<div class='alert alert-danger alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Falla en la creación del comentario
                    </div>";
    }
}
if (isset($_GET['update'])) {
    if ($_GET['update'] == "failed") {
        $msg_failed = "<div class='alert alert-danger alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Falla en la modificación del estado
                    </div>";
    }
}
?>

<!DOCTYPE html>
<html>
    <?= $layout->head('Visualización de Orden'); ?>
    <body>
        <div id='wrapper'>
            <div class='overlay'></div>
            <!-- Sidebar -->
            <?= $layout->navbar('orders'); ?>    
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id='page-content-wrapper'>
                <button type='button' class='hamburger animated fadeInLeft is-closed' data-toggle='offcanvas'>
                    <span class='hamb-top'></span>
                    <span class='hamb-middle'></span>
                    <span class='hamb-bottom'></span>
                </button>

                <?= $layout->navTitle("Admin", 1, "Visualización de Orden"); ?>

                <!-- Page Content -->

                <?= $msg_failed; ?>
                <?= $order_confirmation; ?>


                <div class='row border-top border-bottom margin_vis <?= $view['border_color']; ?>'>
                    <div class='col-lg-6 col-sm-12'>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Asegurado: </span></div><div class='col-6'><span><?= "$data[insured]"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Teléfono: </span></div><div class='col-6'><span><?= "$data[phone]"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Nombre del Contacto: </span></div><div class='col-6'><span><?= "$data[contact_name]"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Municipio: </span></div><div class='col-6'><span><?= "$data[municipality]"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Estado: </span></div><div class='col-6 <?= $view['color']; ?>'><span><?= "$data[state]"; ?></span></div></div>
                    </div>
                    <div class='col-lg-6 col-sm-12'>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Dirección: </span></div><div class='col-6'><span><?= "$data[address]"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Fecha Recibido: </span></div><div class='col-6'><span><?= "$date"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>N° de Reclamación: </span></div><div class='col-6'><span><?= "$data[claim_number]"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Teléfono del Contacto: </span></div><div class='col-6'><span><?= "$data[contact_phone]"; ?></span></div></div>
                        <div class='row'><div class='col-6 font-weight-bold'><span>Fecha de Cierre: </span></div><div class='col-6'><span><?= "$mod_date"; ?></span></div></div>
                    </div>

                    <div class="col-12">
                        <div class='row'><div class='col-lg-3 col-6 font-weight-bold'><span>Salvamento: </span></div><div class='col-lg-9 col-6'><span><?= "$data[description]"; ?></span></div></div>
                    </div>

                </div>

                <div class='row'>
                    <div class='col-12'> 
                        <form name='orders_form' <?= "action='../back_end/orderController.php?form=create&id=$data[id]'"; ?> method='POST' enctype='multipart/form-data'>
                            <div class="row">                            
                                <div class="col-12 col-lg-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <?= $view['photo']; ?>
                                        </div>                                        
                                    </div><div class="row">
                                        <div class="col-12">
                                            <input class='pt-2 pb-4' type='file' name='fileToUpload' id='fileToUpload' accept='.jpg, .jpeg'>
                                            <?= $error_photo; ?>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class='col-12 col-lg-6'>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class='form-group'>
                                                <div class='d-flex'>
                                                    <label for='status' class='col-4 col-form-label'>Cambiar estado:</label>
                                                    <select id='status' name='status' class='form-control pgtion' maxlength='15' onchange="return showDate(this)">
                                                        <option value='Pendiente' <?= $view['sel_1']; ?>>Pendiente</option>
                                                        <option value='Recogida' <?= $view['sel_2']; ?>>Recogida</option>
                                                        <option value='Desechada' <?= $view['sel_3']; ?>>Desechada</option>
                                                        <option value='No entrego' <?= $view['sel_5']; ?>>No Entrego</option>
                                                        <option value='Ofertada' <?= $view['sel_4']; ?>>Ofertada</option>
                                                        <option value='Venta en punto' <?= $view['sel_6']; ?>>Venta en punto</option>
                                                        <option value='Venta en punto' <?= $view['sel_7']; ?>>Otra ciudad</option>
                                                    </select>
                                                </div>
                                                <div id='statusfeed'></div>
                                            </div>
                                            <div id="show_date" class="d-none">
                                                <div class="form-group">
                                                    <div class="d-flex">
                                                        <label for="date" class="col-sm-4 col-form-label">Fecha de cierre:</label>
                                                        <input type="date" name="date" class="form-control pgtion" placeholder="Fecha de cierre" id="date" maxlength="10">
                                                    </div>
                                                    <div id="datefeed"></div>
                                                </div>
                                            </div>
                                            <div class='form-group'>
                                                <div class='d-flex'>
                                                    <label for='comment' class='col-4 col-form-label'>Añadir comentario:</label>
                                                    <textarea class='form-control pgtion' rows='3' placeholder='Añadir comentario..' id='comment' name='comment' maxlength='2000'></textarea>
                                                </div>
                                            </div>
                                            <div class='button_right'><button type='submit' class='btn btn-primary'>Guardar</button></div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <?= $controller->readComments($_GET['order']); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <script src="../assets/js/orderClose.js" type="text/javascript"></script>
        </div>
    </body>
</html>