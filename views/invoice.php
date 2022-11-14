<?php
include_once 'layout/general.php';
include_once '../back_end/orderController.php';

$layout = new general();
$controller = new orderController();
?>

<!DOCTYPE html>
<html>
    <?= $layout->head('Facturas'); ?>
    <body>
        <div id='wrapper'>
            <div class='overlay'></div>
            <!-- Sidebar -->
            <?= $layout->navbar('invoice'); ?>    
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id='page-content-wrapper'>
                <button type='button' class='hamburger animated fadeInLeft is-closed' data-toggle='offcanvas'>
                    <span class='hamb-top'></span>
                    <span class='hamb-middle'></span>
                    <span class='hamb-bottom'></span>
                </button>
                <?= $layout->navTitle("Admin", 1, "Facturas"); ?>

                <!-- Page Content -->
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
                <form class="mt-2" name="excel2" onSubmit="return formValidation();" action="../back_end/excel2.php" method="POST">
                    <div class="table-responsive">
                        <table id="table-id" class="table table-striped table-hover table-bordered">
                            <thead><tr><th></th><th scope='col'>Fecha</th><th scope='col'>Estado</th><th scope='col'>Número de reclamación</th><th scope='col' style="width:10%;">Valor</th></thead>
                            <tbody id="orders_table">
                                <?php
                                $all_orders = $controller->invoiceOrder();
                                $i = 0;
                                foreach ($all_orders as $order) {
                                    $date = $controller->formatDate($order['received_date']);
                                    $status_color = $controller->statusColor($order['state']);
                                    ?>
                                    <tr>
                                        <th scope='row' class='first_column'>
                                            <input type='checkbox' name='select[]' class='messageCheckbox' id='select<?= $i; ?>' value=' <?= $order['id']; ?>' onchange='return showValue();'>
                                        </th>
                                        <td>
                                            <?= $date; ?>
                                        </td>
                                        <td class='<?= $status_color; ?>'>
                                            <?= $order['state']; ?>
                                        </td>
                                        <td>
                                            <?= $order['claim_number']; ?>
                                        </td>
                                        <td>
                                            <input type='text' name='price[]' class='form-control pgtion d-none' value='86488' id='price<?= $i; ?>' maxlength='7' onchange='return priceCheck(this);'>
                                        </td>
                                    </tr>"
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-primary btn-excel">Exportar Excel</button>
                </form>
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
                <script src='../assets/js/validationInvoice.js' type='text/javascript'></script>
                <script src="../assets/js/orderSearch.js" type="text/javascript"></script>
                <script src="../assets/js/ordersTable.js" type="text/javascript"></script>
            </div>
        </div>
    </body>
</html> 
