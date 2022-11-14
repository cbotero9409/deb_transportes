<?php
include_once 'layout/general.php';
include_once '../back_end/orderController.php';

$layout = new general();
$controller = new orderController();

?>

<!DOCTYPE html>
<html>
    <?= $layout->head('Ordenes Pendientes'); ?>
    <body>
        <div id='wrapper'>
            <div class='overlay'></div>
            <!-- Sidebar -->
            <?= $layout->navbar('collected'); ?>    
            <!-- /#sidebar-wrapper -->

            <!-- Page Content -->
            <div id='page-content-wrapper'>
                <button type='button' class='hamburger animated fadeInLeft is-closed' data-toggle='offcanvas'>
                    <span class='hamb-top'></span>
                    <span class='hamb-middle'></span>
                    <span class='hamb-bottom'></span>
                </button>
                
                <?= $layout->navTitle("Admin", 1, "Ordenes Pendientes");?>
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
                <div class="table-responsive">
                    <table id="table-id" class="table table-striped table-hover table-bordered">
                        <thead><tr><th></th><th scope='col'>Fecha</th><th scope='col'>Municipio</th><th scope='col'><span class="table_column_2">Número de reclamación</span></th></thead>
                        <tbody id="orders_table"><?=$controller->readCollectedOrder();?></tbody>
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
             
                
                
                
                <script src="../assets/js/orderSearch.js" type="text/javascript"></script>
                <script src="../assets/js/ordersTable.js" type="text/javascript"></script>