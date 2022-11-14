<?php
include_once 'layout/general.php';
include_once '../back_end/orderController.php';

$layout = new general();
$controller = new orderController();
$alert = '';

if (isset($_GET['creation'])) {
    if ($_GET['creation'] == 'true') {
        $alert = '<div class="alert alert-success alert-dismissible alert_bar">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong></strong> lote creado con exito.
  </div>';
    }
}
?>

<!DOCTYPE html>
<html>
    <?= $layout->head('Lotes'); ?>
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
                <?= $layout->navTitle("Admin", 1, "Lotes"); ?>

                <!-- Page Content -->
                <?= $alert; ?>
                <div class="row col">
                    <div class="col-6">
                        <!--boton modal editar lotes-->
                        <button type="button" class="btn btn-primary mt-5" data-toggle="modal" data-target="#modalLots">Editar lotes</button>
                    </div>
                    <div class="col-6">
                        <button type="button" class="btn btn-primary mt-5 marg_btn" data-toggle="modal" data-target="#exampleModal">Redactar correo</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table ml-3 mt-50">
                        <thead><tr><th></th><th scope='col'>Número de reclamación</th><th scope='col'>Descripción</th><th scope="col">Foto</th></tr></thead>
                        <?php
                        $lots = $controller->lots();
                        $nameLot = '';
                        print "<tbody>";
                        foreach ($lots as $order) {
                            if ($nameLot == '') {
                                $nameLot = $order['name_lot'];
                                $lot[] = $order['name_lot'];
                                $idLots[] = $order['fk_lot'];
                            }
                            if ($nameLot != $order['name_lot']) {
                                $lot[] = $order['name_lot'];
                                $idLots[] = $order['fk_lot'];

                                print "<tr><td colspan=4><br/><br/></td></tr>";
                            }
                            ?> 
                            <tr><th scope='row' class='first_column'><?= $order['name_lot']; ?></th>
                                <td><?= $order['claim_number'] ?></td>
                                <td><?= $order['description'] ?></td>
                                <td class='profile_user'><button type='button' class='btn btn-link' data-toggle='modal' data-target='#myModal' onClick='mifunction("<?= $order['photo'] ?>")'><img src='../assets/img/<?= $order['photo'] ?>'class='w-100' alt='Cinque Terre' height="40px"></button></td></tr>

                            <?php
                            $nameLot = $order['name_lot'];
                        }

                        print "</tbody>";
                        ?>
                    </table>
                </div>
                <!-- Modal photo-->
                <div class="modal fade" id="myModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="title_photo" ></h4>
                                <button type="button" class="close" data-dismiss="modal">X</button>
                            </div>
                            <div class="modal-body" id="image_order"></div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Email-->
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Datos del correo</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">X</span>
                                </button>
                            </div>
                            <div class="modal-body ">
                                <form name="email_form" action="../back_end/orderController.php?type=mail" method="POST">
                                    <tr>
                                        <td>
                                            <div class='form-group '>
                                                <label for='lots'>Elija el lote</label>
                                                <select id='sendLot' name='sendLot' class='form-control pgtion' maxlength='15' onchange='return statusCheck(this)'>

                                                    <option>--Seleccione--</option>
                                                    <?php
                                                    $max = count($idLots);
                                                    for ($i = 0; $i < $max; $i++) {
                                                        ?>
                                                        <option value="<?= $idLots[$i] ?>" ><?= $lot[$i]; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="form-group">
                                        <label for="exampleEmail">Correo receptor</label>
                                        <input type="texr" class="form-control" id="exampleEmail" name="exampleEmail" placeholder="Correo">
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleAsunt">Asunto</label>
                                        <input type="text" class="form-control" id="exampleAsunt" name="exampleAsunt" placeholder="Asunto"  >
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleBody">Cuerpo de Correo</label>
                                        <textarea class="form-control pgtion" rows="3" placeholder="Cuerpo de correo" id="exampleBody" name="exampleBody" maxlength="2000" ></textarea> 
                                    </div>
                                    <button type="submit" class="btn btn-primary marg_btn" name="enviar">Enviar correo</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Modify Lots-->
                <div class="modal fade" id="modalLots" tabindex="-1" role="dialog" aria-labelledby="modalLots" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLots">Modificar Lotes</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span>X</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form name="orders_form" action="../back_end/orderController.php?type=modifyLot" method="POST">
                                    <div class="table-responsive">
                                        <table id="table-id" class="table table-striped table-hover table-bordered">
                                            <thead><tr><th scope='col'>Lote</th><th scope='col'>Descripción</th><th scope='col'><span class="table_column_2">Número de reclamación</span></th></thead>
                                            <tbody id="orders_table">
                                                <?php
                                                foreach ($lots as $order) {
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <div class='form-group d-flex'>
                                                                <label for='status' class='col-sm-4 col-form-label'></label>
                                                                <input type="hidden" name="orders[]" value="<?= $order['id'] ?>">
                                                                <select id='nameLot' name='nameLot[]' class='form-control pgtion' maxlength='15' onchange='return statusCheck(this)'>

                                                                    <option value="<?= $order['fk_lot'] ?>" ><?= $order['name_lot']; ?></option>
                                                                    <?php
                                                                    $max = count($idLots);
                                                                    for ($i = 0; $i < $max; $i++) {
                                                                        ?>
                                                                        <option value="<?= $idLots[$i] ?>" ><?= $lot[$i]; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </td>
                                                        <td><?= $order['description'] ?></td>
                                                        <td><?= $order['claim_number'] ?></td></tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="submit" class="btn btn-primary marg_btn">Guardar</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    function mifunction(image) {
                        document.getElementById('image_order').innerHTML = "<img src='../assets/img/" + image + "' height='500'> ";
                        document.getElementById('title_photo').innerHTML = image;
                    }
                </script>
                <script src="../vendor/tinymce/tinymce/tinymce.min.js"></script>

                <script>
                    tinymce.init({
                        selector: '#exampleBody',
                        menubar: false
                    });
                </script>
            </div>
        </div>
    </body>
</html> 