                <div class='row'>
                    <div class='col-11 col-md-10 col-lg-8  mx-auto mt-5'>
                        <form name='orders_form' onSubmit='return formValidation()' action='../back_end/orderController.php?type=create' method='POST'>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='insured' class='col-sm-4 col-form-label'>Asegurado:</label>
                                    <input type='text' name='insured' class='form-control pgtion' placeholder='Nombre Asegurado' id='insured' required maxlength='50' onchange='return insuredCheck(this)'>   
                                </div>
                                <div id='insuredfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='address' class='col-sm-4 col-form-label'>Dirección:</label>
                                    <input type='text' name='address' class='form-control pgtion' placeholder='Dirección' id='address' required maxlength='100' onchange='return addressCheck(this)'>
                                </div>
                                <div id='addressfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='phone' class='col-sm-4 col-form-label'>Teléfono:</label>
                                    <input type='text' name='phone' class='form-control pgtion' placeholder='Teléfono' id='phone' required maxlength='20' onchange='return phoneCheck(this)'>
                                </div>
                                <div id='phonefeed'></div>
                            </div>
                            <div id='phonefeed' class='invalid-feedback d-none'></div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='receive_date' class='col-sm-4 col-form-label'>Fecha Recibido:</label>
                                    <input type='date' name='receive_date' class='form-control pgtion' placeholder='Fecha de recibido' id='receive_date' required maxlength='10' onchange='return dateCheck(this)'>
                                </div>
                                <div id='datefeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='claim_number' class='col-sm-4 col-form-label'>Numero de Reclamación:</label>
                                    <input type='text' name='claim_number' class='form-control pgtion' placeholder='Número de Reclamación' id='claim_number' required maxlength='14' onchange='return claimNumberCheck(this)'>
                                </div>
                                <div id='claimnumberfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='contact_name' class='col-sm-4 col-form-label'>Nombre del contacto:</label>
                                    <input type='text' name='contact_name' class='form-control pgtion' placeholder='Nombre del contacto' id='contact_name' required maxlength='50' onchange='return contactNameCheck(this)'>
                                </div>
                                <div id='contactnamefeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='contact_phone' class='col-sm-4 col-form-label'>Teléfono del Contacto:</label>
                                    <input type='text' name='contact_phone' class='form-control pgtion' placeholder='Teléfono del Contacto' id='contact_phone' required maxlength='20' onchange='return contactPhoneCheck(this)'>
                                </div>  
                                <div id='contactphonefeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='municipality' class='col-sm-4 col-form-label'>Municipio:</label>
                                    <select id='municipality' name='municipality' class='form-control pgtion' maxlength='30' required onchange='return municipalityCheck(this)'>
                                        <option value>Municipio</option>
                                        <option value='Medellin'>Medellín</option>
                                        <option value='Sabaneta'>Sabaneta</option>
                                    </select>
                                </div>
                                <div id='municipalityfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='status' class='col-sm-4 col-form-label'>Estado:</label>
                                    <select id='status' name='status' class='form-control pgtion' maxlength='15' required onchange='return statusCheck(this)'>
                                        <option value>Estado</option>
                                        <option value='Pendiente'>Pendiente</option>
                                        <option value='Recogida'>Recogida</option>
                                        <option value='Desechada'>Desechada</option>
                                        <option value='Ofertada'>Ofertada</option>
                                    </select>
                                </div>
                                <div id='statusfeed'></div>
                            </div>
                            <div class='form-group'>
                                <div class='d-flex'>
                                    <label for='description' class='col-sm-4 col-form-label'>Descripción del Salvamento:</label>
                                    <textarea class='form-control pgtion' rows='3' placeholder='Descripción del Salvamento' id='description' name='description' required maxlength='2000' onchange='return descriptionCheck(this)'></textarea>
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
        <script src='../assets/js/validationOrder.js' type='text/javascript'></script>
    </body>
</html> 
