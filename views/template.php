<?php
include_once 'layout/general.php';
$layout = new general();
?>

<!DOCTYPE html>
<html>
    <?= $layout->head('titlle'); ?>
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
                <?= $layout->navTitle("Admin", 1, "Ordenes Pendientes");?>

                <!-- Page Content -->

            </div>
        </div>
    </body>
</html> 

