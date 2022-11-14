<?php
include_once 'layout/general.php';
include_once '../back_end/orderController.php';

$layout = new general();
$controller = new orderController();
$update_failed = '';
$sel_1 = '';
$sel_2 = '';
$sel_3 = '';
$sel_4 = '';

$data = $controller->readSpecificOrder($_GET['order']);

switch ($data['state']) {
    case "Pendiente" :
        $sel_1 = "selected";
        break;
    case "Recogida" :
        $sel_2 = "selected";
        break;
    case "Desechada" :
        $sel_3 = "selected";
        break;
    case "Ofertada" :
        $sel_4 = "selected";
        break;
}

if(isset($_GET['update'])){
    if ($_GET['update'] == "failed") {
        $update_failed = "<div class='alert alert-danger alert_bar alert-dismissible'>
                        <a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>
                        Falla en la modificación de la orden
                    </div>";
    }
}


?>

<!DOCTYPE html>
<html>
    <?= $layout->head('Modificación de Orden'); ?>
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
                
                <?= $layout->navTitle("Admin", 1, "Modificación de Orden");?>
                
                <?= $update_failed;?>
                
                <!-- Page Content -->     

                <div class='row'>
                    <div class='col-11 col-md-10 col-lg-8  mx-auto mt-5'>
                        <form name='orders_form' onSubmit='return formValidation()' <?="action='../back_end/orderController.php?type=update&id=$data[id]'";?> method='POST'>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='insured' class='col-sm-4 col-form-label'>Asegurado:</label>
                                    <input <?="value='$data[insured]'";?> type='text' name='insured' class='form-control pgtion' placeholder='Nombre Asegurado' id='insured' required maxlength='50' onchange='return insuredCheck(this)'>   
                                </div>
                                <div id='insuredfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='address' class='col-sm-4 col-form-label'>Dirección:</label>
                                    <input <?="value='$data[address]'";?> type='text' name='address' class='form-control pgtion' placeholder='Dirección' id='address' required maxlength='100' onchange='return addressCheck(this)'>
                                </div>
                                <div id='addressfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='community' class='col-sm-4 col-form-label'>Barrio:</label>
                                    <input <?="value='$data[community]'";?> type='text' name='community' class='form-control pgtion' placeholder='Barrio' id='community' required maxlength='100' onchange='return addressCheck(this)'>
                                </div>
                                <div id='contactnamefeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='phone' class='col-sm-4 col-form-label'>Teléfono:</label>
                                    <input <?="value='$data[phone]'";?> type='text' name='phone' class='form-control pgtion' placeholder='Teléfono' id='phone' required maxlength='20' onchange='return phoneCheck(this)'>
                                </div>
                                <div id='phonefeed'></div>
                            </div>
                            <div id='phonefeed' class='invalid-feedback d-none'></div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='receive_date' class='col-sm-4 col-form-label'>Fecha Recibido:</label>
                                    <input <?="value='$data[received_date]'";?> type='date' name='receive_date' class='form-control pgtion' placeholder='Fecha de recibido' id='receive_date' required maxlength='10' onchange='return dateCheck(this)'>
                                </div>
                                <div id='datefeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='claim_number' class='col-sm-4 col-form-label'>Numero de Reclamación:</label>
                                    <input <?="value='$data[claim_number]'";?> type='text' name='claim_number' class='form-control pgtion' placeholder='Número de Reclamación' id='claim_number' required maxlength='14' onchange='return claimNumberCheck(this)'>
                                </div>
                                <div id='claimnumberfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='contact_name' class='col-sm-4 col-form-label'>Nombre del contacto:</label>
                                    <input <?="value='$data[contact_name]'";?> type='text' name='contact_name' class='form-control pgtion' placeholder='Nombre del contacto' id='contact_name' required maxlength='50' onchange='return contactNameCheck(this)'>
                                </div>
                                <div id='contactnamefeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='contact_phone' class='col-sm-4 col-form-label'>Teléfono del Contacto:</label>
                                    <input <?="value='$data[contact_phone]'";?> type='text' name='contact_phone' class='form-control pgtion' placeholder='Teléfono del Contacto' id='contact_phone' required maxlength='20' onchange='return contactPhoneCheck(this)'>
                                </div>  
                                <div id='contactphonefeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='municipality' class='col-sm-4 col-form-label'>Municipio:</label>
                                    <select id='municipality' name='municipality' class='form-control pgtion' maxlength='30' required onchange='return municipalityCheck(this)'>
                                        <option <?="value='$data[municipality]'";?> selected><?="$data[municipality]";?></option>
                                        <?=$controller->municipalityRead();?>
                                        <option value='Otro'>*Otro Municipio*</option>
                                    </select>
                                </div>
                                <div id='municipalityfeed'></div>
                            </div>
                            <div id="show_other" class="form-group d-none">
                                <div class="d-flex">
                                    <label for="other" class="col-sm-4 col-form-label">Agregar municipio:</label>
                                    <input type="text" name="other" class="form-control pgtion" placeholder="Agregar municipio" id="other" maxlength="30" onchange="return otherMunicipalityCheck(this)">
                                </div>
                                <div id="otherfeed"></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='description' class='col-sm-4 col-form-label'>Descripción del Salvamento:</label>
                                    <textarea class='form-control pgtion' rows='3' placeholder='Descripción del Salvamento' id='description' name='description' required maxlength='2000' onchange='return descriptionCheck(this)'><?="$data[description]";?></textarea>
                                </div>
                                <div id= 'descriptionfeed'></div>
                            </div>
                            <br/>
                            <button type='submit' class='btn btn-primary'>Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /#page-content-wrapper -->
        </div>
        <!-- /#wrapper -->
        <script src='../assets/js/validationModifyOrder.js' type='text/javascript'></script>
    </body>
</html>
