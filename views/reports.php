<?php

function excelReport() {

    return " 
<div class='modal' id='reportsModal' tabindex='-1'>
    <div class='modal-dialog'>
        <div class='modal-content'>
            <div class='modal-header'>
                <h5 class='modal-title'>Generación de Informes</h5>
                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                    <span aria-hidden='true'>&times;</span>
                </button>
            </div>
            <form name='excel' action='' id='reports_form' method='POST'>
                <div class='modal-body'>
                    <span>Seleccione el rango de fechas:</span><br>
                    <div class='form-group mt-3'>
                        <div class='d-flex'>
                            <label for='start_date' class='col-sm-4 col-form-label'>Fecha Inicial:</label>
                            <input type='date' name='start_date' class='form-control pgtion' placeholder='Fecha inicial' id='start_date' required maxlength='10' onchange='return startDateCheck();'>
                        </div>
                        <div id='start_date_feed'></div>
                    </div>
                    <div class='form-group mt-3'>
                        <div class='d-flex'>
                            <label for='end_date' class='col-sm-4 col-form-label'>Fecha Final:</label>
                            <input type='date' name='end_date' class='form-control pgtion' placeholder='Fecha final' id='end_date' required maxlength='10' onchange='return endDateCheck();'>
                        </div>
                        <div id='end_date_feed'></div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cerrar</button>
                    <button type='button' name='btnsubmit2' onclick='return formValidation2();' class='btn btn-success'>Informe Tiempos</button>
                    <button type='button' name='btnsubmit' onclick='return formValidation();' class='btn btn-primary'>Informe General</button>
                </div>
            </form>            
        </div>
    </div>
</div>

<script src='../assets/js/reportsDate.js' type='text/javascript'></script>
";
}
?>