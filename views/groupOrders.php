<?php
include_once 'layout/general.php';
include_once '../back_end/orderController.php';
$layout = new general();
$controller = new orderController();

$alert = '';

if (isset($_GET['send'])) {
    if ($_GET['send'] == 'true') {
        $alert = '<div class="alert alert-success alert-dismissible alert_bar">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> lote enviado con exito.
  </div>';
    }
}

if (isset($_GET['send'])) {
    if ($_GET['send'] == 'false') {
        $alert = '<div class="alert alert-danger alert-dismissible alert_bar col-10 col-sm-7 mx-auto">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> Error de autenticacion.
  </div>';
    }
}
?>

<!DOCTYPE html>
<html>
    <?= $layout->head('Ordenes Recogidas'); ?>
    <body>
        <div id='wrapper'>
            <div class='overlay'></div>
            <!-- Sidebar -->
            <?= $layout->navbar('group'); ?>    
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id='page-content-wrapper'>
                <button type='button' class='hamburger animated fadeInLeft is-closed' data-toggle='offcanvas'>
                    <span class='hamb-top'></span>
                    <span class='hamb-middle'></span>
                    <span class='hamb-bottom'></span>
                </button>
                <?= $layout->navTitle("Admin", 1, "Ordenes Recogidas"); ?>

                <!-- Page Content -->
                <?= $alert; ?>
                <a href="../views/lots.php"><button type="a" class="btn btn-primary marg_btn">Ver lotes</button></a>
                <div class="row table_bar">
                    <input id="myInput" type="text" placeholder=" Buscar orden..">

                    <div class="form-group"> 
                        <label for="maxRows">Cantidad de ordenes a mostrar: &nbsp</label>
                        <select class="form-control order_rows" name="state" id="maxRows">
                            <option value="5">5</option>
                            <option value="10">10</option>
                            <option value="15">15</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="70">70</option>
                            <option value="100">100</option>
                            <option value="5000">Todas</option>
                        </select>
                    </div>                    
                </div>

                <form name="orders_form" onSubmit="return formValidation()" action="../back_end/orderController.php?type=group" method="POST">

                    <div class="table-responsive">
                        <table id="table-id" class="table table-striped table-hover table-bordered">

                            <thead><tr><th></th><th></th><th scope='col'>Foto</th><th scope='col'>Descripción</th><th scope='col'><span class="table_column_2">Número de reclamación</span></th></thead>
                            <tbody id="orders_table">
                                <?php
                                $all_orders = $controller->readGroup();
                                $i = 1;
                                foreach ($all_orders as $order) {
                                    ?>
                                    <tr><th scope='row' class='first_column'><?= $i ?></th>
                                        <td><label><input class='custom-control-label' type='checkbox' id='cbox1' name='lot[]' value='<?= $order['id'] ?>'></label></td>
                                        <td class='profile_user'><button type='button' class='btn btn-link' data-toggle='modal' data-target='#myModal' onClick='mifunction("<?= $order['photo'] ?>")'><img src='../assets/img/<?= $order['photo'] ?>'class='w-100' alt='Cinque Terre' height="40px"></button></td>
                                        <td><?= $order['description'] ?></td>
                                        <td><?= $order['claim_number'] ?></td></tr>
                                    <?php
                                    $i++;
                                }
                                ?></tbody>
                        </table>
                    </div>

                    <div class='pagination-container' >
                        <nav>
                            <ul class="pagination">
                                <li data-page="prev" >
                                    <span> < <span class="sr-only">(current)</span></span>
                                </li>
                                <!--	Here the JS Function Will Add the Rows -->
                                <li data-page="next" id="prev">
                                    <span> > <span class="sr-only">(current)</span></span>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="row float-right">
                        <div class="col-6">
                            <input type="text" class="form-control " placeholder="Número del lote" name="numlot"  id="numlot" required>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary" name="enviar">Crear lote</button>
                        </div>
                    </div>

                </form>
                <!-- The Modal -->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <!-- Modal Header -->
                            <div class="modal-header">
                                <h4 class="modal-title" id="title_photo" ></h4>
                                <button type="button" class="close" data-dismiss="modal">X</button>
                            </div>

                            <!-- Modal body -->
                            <div class="modal-body" id="image_order"></div>

                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script src="../assets/js/orderSearch.js" type="text/javascript"></script>
                <script src="../assets/js/ordersTable.js" type="text/javascript"></script>
                <script>
                                        function mifunction(image) {
                                            document.getElementById('image_order').innerHTML = "<img src='../assets/img/" + image + "' height='500'> ";
                                            document.getElementById('title_photo').innerHTML = image;
                                        }
                </script>
            </div>
        </div>
    </body>
</html> 

