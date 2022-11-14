<?php
include_once 'orderClass.php';
include_once 'confirmVerification.php';
include_once 'commentClass.php';
include_once 'municipalityClass.php';
include_once 'lotClass.php';
include_once 'mailerClass.php';

date_default_timezone_set("America/Bogota");

class orderController {

    function createOrder() {

        $confirm = new confirmVerification();

        $insured = $_POST['insured'];
        $address = $_POST['address'];
        $community = $_POST['community'];
        $phone = $_POST['phone'];
        $receive_date = $_POST['receive_date'];
        $claim_number = $_POST['claim_number'];
        $contact_name = $_POST['contact_name'];
        $contact_phone = $_POST['contact_phone'];
        $municipality = $_POST['municipality'];
        $status = "Pendiente";
        $description = $_POST['description'];
        $modification_date = date('Y-m-d', '0000-00-00');
        $last_date = date('Y-m-d H:i:s');

        if ($municipality == "Otro") {
            $municipality = $_POST['other'];
            $munic_confirm = $this->municipalityCreate($municipality);
        }

        $orders[0] = $confirm->confirm_string($insured);
        $orders[1] = $confirm->confirm_string($address);
        $orders[2] = $confirm->confirm_string($community);
        $orders[3] = $confirm->confirm_string($phone);
        $orders[4] = $confirm->confirm_number($receive_date);
        $orders[5] = $confirm->confirm_number($claim_number);
        $orders[6] = $confirm->confirm_string($contact_name);
        $orders[7] = $confirm->confirm_string($contact_phone);
        $orders[8] = $confirm->confirm_string($municipality);
        $orders[9] = $confirm->confirm_string($status);
        $orders[10] = $confirm->confirm_string($description);
        $orders[11] = $receive_date;
        $orders[12] = $last_date;
        $orders[13] = "0";

        $model = new orderClass();
        if ($model->create($orders)) {
            $order_data = $model->read("id", "1 ORDER BY id DESC LIMIT 1");
            foreach ($order_data as $data) {
                
            }
            header("Location: ../views/orderView.php?order=$data[id]&confirmation=created");
        } else {
            header("Location: ../views/createOrder.php?creation=failed");
        }
    }

    function formatDate($date) {

        $months_spanish = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $date_f = strtotime($date);
        $day = date('d', $date_f);
        $month = $months_spanish[(date('n', $date_f) - 1)];
        $year = date('Y', $date_f);
        $date_formatted = "$day de $month del $year";

        return $date_formatted;
    }

    function statusColor($status) {
        switch ($status) {
            case "Pendiente":
                $color = "red_status";
                break;
            case "Recogida":
                $color = "green_status";
                break;
            case "Desechada":
                $color = "blue_status";
                break;
            case "Ofertada":
                $color = "yellow_status";
                break;
            default:
                $color = '';
                break;
        }
        return $color;
    }

    function pencil($status, $id) {
        $pencil = '';
        if ($status === "Pendiente") {
            $pencil = "<div class='table_column_margin'><a href='modifyOrder.php?order=$id'><i id='pencil' class='fas fa-pencil-alt px-2'></i></a>";
        } else {
            $pencil = "<div class='table_column'>";
        }
        return $pencil;
    }

    function readOrder() {

        $confirm = new confirmVerification();
        $model = new orderClass();

        $all_orders = $model->read("insured,received_date, state, id, claim_number,fk_lot", "1 ORDER BY `orders`.`id` DESC");
        $i = 1;

        foreach ($all_orders as $order) {
            $date = $this->formatDate($order['received_date']);
            $status_color = $this->statusColor($order['state']);
            $pencil = $this->pencil($order['state'], $order['id']);

            print "<tr>
    <th scope='row' class='first_column'>$i</th>
    <td>$order[claim_number]</td>
    <td>$order[insured]</td>
    <td>$date</td>
    <td class='$status_color'>$order[state]</td>
    <td class='$status_color'>$order[fk_lot]</td>
    <td>$pencil<a href='orderView.php?order=$order[id]'><i id='eye' class='fas fa-eye px-2'></i></a></td>
</tr>";
            $i++;
        }
    }

    function municipalityRead() {

        $confirm = new confirmVerification();
        $model = new municipalityClass();
        $all_municipality = $model->read("1 ORDER BY `municipality`.`municipality` ASC");

        foreach ($all_municipality as $municipality) {
            $munic = $municipality['municipality'];
            print "<option value='$munic'>$munic</option>";
        }
    }

    function municipalityCreate($munic) {

        $confirm = new confirmVerification();

        $municipality = $confirm->confirm_string($munic);

        $model = new municipalityClass();
        if ($model->create($municipality)) {
            return true;
        } else {
            return false;
        }
    }

    function orderInfo() {

        $confirm = new confirmVerification();
        $model = new orderClass();

        $actual_month = idate("m");
        $actual_year = date("Y");
        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
        $orders = ["Mes" => $meses[$actual_month - 2], "Pendientes" => 0, "Recogidas" => 0, "Desechadas" => 0, "Ofertadas" => 0, "Total_Pendientes" => 0, "Otra_Ciudad" => 0];

        $pending_orders = $model->read("state", "state = 'Pendiente'");
        foreach ($pending_orders as $order) {
            $orders['Total_Pendientes']++;
        }

        $month_orders = $model->read("state, municipality", "(YEAR(received_date) = $actual_year AND MONTH(received_date) = $actual_month) OR (YEAR(modification_date) = $actual_year AND MONTH(modification_date) = $actual_month)");
        foreach ($month_orders as $order) {
            switch ($order['state']) {
                case "Pendiente":
                    $orders['Pendientes']++;
                    break;
                case "Recogida":
                    $orders['Recogidas']++;
                    break;
                case "Desechada":
                    $orders['Desechadas']++;
                    break;
                case "Ofertada":
                    $orders['Ofertadas']++;
                    break;
            }
            if ($order['municipality'] == "Otra Ciudad") {
                $orders['Otra_Ciudad']++;
            }
        }
        return $orders;
    }

    function yearOrdersStatus() {
        $confirm = new confirmVerification();
        $model = new orderClass();

        $actual_month = idate("m");
        $actual_year = idate("Y");

        $all_orders = $model->read("state, municipality, received_date, modification_date", "YEAR(received_date) = $actual_year OR YEAR(modification_date) = $actual_year");

        $orders = array();
        for ($j = 1; $j <= $actual_month; $j++) {
            $orders[$j] = ["Pendientes" => 0, "Recogidas" => 0, "Desechadas" => 0, "Ofertadas" => 0, "Otra_Ciudad" => 0, "Totales" => 0];
        }

        foreach ($all_orders as $order) {
            $date = strtotime($order['received_date']);
            $month = idate("m", $date);
            $date_1 = strtotime($order['modification_date']);
            $month_1 = idate("m", $date_1);

            for ($i = 1; $i <= $actual_month; $i++) {
                if ($month == $i) {
                    $orders[$i]["Pendientes"]++;
                }
                if ($month_1 == $i) {
                    switch ($order['state']) {
                        case "Recogida":
                            $orders[$i]["Recogidas"]++;
                            break;
                        case "Desechada":
                            $orders[$i]["Desechadas"]++;
                            break;
                        case "Ofertada":
                            $orders[$i]["Ofertadas"]++;
                            break;
                    }
                }
                if ($order['municipality'] == "Otra Ciudad") {
                    $orders[$i]["Otra_Ciudad"]++;
                }
                $orders[$i]["Totales"] = $orders[$i]["Pendientes"] + $orders[$i]["Recogidas"] + $orders[$i]["Desechadas"] + $orders[$i]["Ofertadas"];
            }
        }
        return $orders;
    }

    function yearOrdersMunicipality() {
        $confirm = new confirmVerification();
        $model = new orderClass();
        $model_municipality = new municipalityClass();

        $actual_month = idate("m");
        $actual_year = idate("Y");

        $all_municipality = $model_municipality->read("1");
        foreach ($all_municipality as $municipality) {
            $munic[] = $municipality['municipality'];
        }

        $all_orders = $model->read("municipality, month(received_date)", "YEAR(received_date) = $actual_year");
        $orders = array();
        for ($j = 0; $j < count($munic); $j++) {
            $orders[$j] = ["Enero" => 0, "Febrero" => 0, "Marzo" => 0, "Abril" => 0, "Mayo" => 0, "Junio" => 0, "Julio" => 0, "Agosto" => 0, "Septiembre" => 0, "Octubre" => 0, "Noviembre" => 0, "Diciembre" => 0];
            foreach ($all_orders as $order) {
                if ($order['municipality'] == $munic[$j]) {
                    switch ($order['month(received_date)']) {
                        case 1:
                            $orders[$j]["Enero"]++;
                            break;
                        case 2:
                            $orders[$j]["Febrero"]++;
                            break;
                        case 3:
                            $orders[$j]["Marzo"]++;
                            break;
                        case 4:
                            $orders[$j]["Abril"]++;
                            break;
                        case 5:
                            $orders[$j]["Mayo"]++;
                            break;
                        case 6:
                            $orders[$j]["Junio"]++;
                            break;
                        case 7:
                            $orders[$j]["Julio"]++;
                            break;
                        case 8:
                            $orders[$j]["Agosto"]++;
                            break;
                        case 9:
                            $orders[$j]["Septiembre"]++;
                            break;
                        case 10:
                            $orders[$j]["Octubre"]++;
                            break;
                        case 11:
                            $orders[$j]["Noviembre"]++;
                            break;
                        case 12:
                            $orders[$j]["Diciembre"]++;
                            break;
                    }
                }
            }
        }

        return $orders;
    }

    function monthInfoStatus() {
        $confirm = new confirmVerification();
        $model = new orderClass();

        if (isset($_POST)) {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
        }
        $start_date_format = date('d/m/y', strtotime($start_date));
        $end_date_format = date('d/m/y', strtotime($end_date));

        $actual_month = idate("m");
        $actual_year = idate("Y");
        if ($actual_month === 1) {
            $actual_year -= 1;
        }
        $month = $actual_month - 1;
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

        $orders = ["Fecha Inicial" => $start_date, "Fecha Final" => $end_date, "Ingresadas(Mes)" => 0, "Pendientes(Mes)" => 0, "Recogidas" => 0, "Desechadas" => 0, "Ofertadas" => 0, "No entrego" => 0, "Venta en punto" => 0, "Otra Ciudad" => 0, "Totales" => 0, "Pendientes(General)" => 0];

        $pending_orders = $model->read("state", "state = 'Pendiente'");
        foreach ($pending_orders as $p_order) {
            $orders["Pendientes(General)"]++;
        }

        $received_orders = $model->read("state", "(received_date >= '$start_date' AND received_date <= '$end_date')");
        foreach ($received_orders as $r_o) {
            $orders["Ingresadas(Mes)"]++;
        }

        $all_orders = $model->read("state, municipality", "(received_date >= '$start_date' AND received_date <= '$end_date') OR (modification_date >= '$start_date' AND modification_date <= '$end_date')");
        foreach ($all_orders as $order) {
            switch ($order['state']) {
                case "Pendiente":
                    $orders['Pendientes(Mes)']++;
                    break;
                case "Recogida":
                    $orders['Recogidas']++;
                    break;
                case "Desechada":
                    $orders['Desechadas']++;
                    break;
                case "Ofertada":
                    $orders['Ofertadas']++;
                    break;
                case "No entrego":
                    $orders['No entrego']++;
                    break;
                case "Venta en punto":
                    $orders['Venta en punto']++;
                    break;
            }
            if ($order['municipality'] == "Otra Ciudad") {
                $orders['Otra Ciudad']++;
            }
        }
        $orders['Totales'] = $orders['Pendientes(Mes)'] + $orders['Recogidas'] + $orders['Desechadas'] + $orders['Ofertadas'] + $orders['No entrego'] + $orders['Venta en punto'];
        if ($orders['Pendientes(Mes)'] === 0) {
            $orders['Pendientes(Mes)'] = '0';
        }
        if ($orders['Recogidas'] === 0) {
            $orders['Recogidas'] = '0';
        }
        if ($orders['Desechadas'] === 0) {
            $orders['Desechadas'] = '0';
        }
        if ($orders['Ofertadas'] === 0) {
            $orders['Ofertadas'] = '0';
        }
        if ($orders['Venta en punto'] === 0) {
            $orders['Venta en punto'] = '0';
        }
        if ($orders['No entrego'] === 0) {
            $orders['No entrego'] = '0';
        }
        if ($orders['Otra Ciudad'] === 0) {
            $orders['Otra Ciudad'] = '0';
        }

        return $orders;
    }

    function monthInfoMunicipality() {
        $confirm = new confirmVerification();
        $model_orders = new orderClass();
        $model_municipality = new municipalityClass();

        $actual_month = idate("m");
        $actual_year = date("Y");
        $month = $actual_month - 1;

        if (isset($_POST)) {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
        }

        $all_municipality = $model_municipality->read("1");
        foreach ($all_municipality as $municipality) {
            $munic = $municipality['municipality'];
            $month_munic[$munic] = 0;
            $all_orders = $model_orders->read("municipality", "municipality = '$munic' AND received_date >= '$start_date' AND received_date <= '$end_date'");
            foreach ($all_orders as $order) {
                if ($order['municipality'] == $munic) {
                    $month_munic[$munic]++;
                }
            }
            if ($month_munic[$munic] === 0) {
                $month_munic[$munic] = '0';
            }
        }
        return $month_munic;
    }

    function monthInfoTimes() {
        $model = new orderClass();
        if (isset($_POST)) {
            $start_date = $_POST['start_date'];
            $end_date = $_POST['end_date'];
        }
        $actual_month = idate("m");
        $actual_year = idate("Y");
        if ($actual_month === 1) {
            $actual_year -= 1;
        }
        //
        $total_collected_orders = 0;
        $total_dismissed_orders = 0;
        $total_offered_orders = 0;
        $total_sale_orders = 0;
        $total_dont_deliver_orders = 0;
        //
        $collected_days = 0;
        $dismissed_days = 0;
        $offered_days = 0;
        $sale_days = 0;
        $dont_deliver_days = 0;
        //
        $min_collected = 9999;
        $max_collected = -1;
        $min_dismissed = 9999;
        $max_dismissed = -1;
        $min_offered = 9999;
        $max_offered = -1;
        $min_sale = 9999;
        $max_sale = -1;
        $min_dont_deliver = 9999;
        $max_dont_deliver = -1;
        //
        $min_contact = 9999;

        $all_orders = $model->read("id, state, received_date, modification_date, last_date", "(received_date >= '$start_date' AND received_date <= '$end_date') OR (modification_date >= '$start_date' AND modification_date <= '$end_date')");
        foreach ($all_orders as $order) {
            $date1 = new DateTime($order['received_date']);
            $date2 = new DateTime($order['last_date']);
            $date3 = new DateTime($order['modification_date']);
            $comment = $model->customRead($order['id']);
            foreach ($comment as $date) {
                if (isset($date['DATE(MIN(date))'])) {
                    $format_date = date('Y-m-d', strtotime($date['DATE(MIN(date))']));
                    if ($format_date >= $start_date && $format_date <= $end_date) {
                        $date4 = new DateTime($date['DATE(MIN(date))']);
                        $interval_contact = $date1->diff($date4);
                        $comment_days = $interval_contact->days;
                        ($comment_days < $min_contact) ? $min_contact = $comment_days : '';
                    } 
                }
            }
            ($comment_days < $min_contact) ? $min_contact = $comment_days : '';
            switch ($order['state']) {
                case "Recogida":
                    $interval = $date1->diff($date2);
                    $collected_days += $interval->days;
                    ($interval->days > $max_collected) ? $max_collected = $interval->days : '';
                    ($interval->days < $min_collected) ? $min_collected = $interval->days : '';
                    $total_collected_orders++;
                    break;
                case "Desechada":
                    $interval = $date1->diff($date3);
                    $dismissed_days += $interval->days;
                    ($interval->days > $max_dismissed) ? $max_dismissed = $interval->days : '';
                    ($interval->days < $min_dismissed) ? $min_dismissed = $interval->days : '';
                    $total_dismissed_orders++;
                    break;
                case "Ofertada":
                    $interval = $date1->diff($date3);
                    $offered_days += $interval->days;
                    ($interval->days > $max_offered) ? $max_offered = $interval->days : '';
                    ($interval->days < $min_offered) ? $min_offered = $interval->days : '';
                    $total_offered_orders++;
                    break;
                case "No entrego":
                    $interval = $date1->diff($date3);
                    $sale_days += $interval->days;
                    ($interval->days > $max_sale) ? $max_sale = $interval->days : '';
                    ($interval->days < $min_sale) ? $min_sale = $interval->days : '';
                    $total_sale_orders++;
                    break;
                case "Venta en punto":
                    $interval = $date1->diff($date3);
                    $dont_deliver_days += $interval->days;
                    ($interval->days > $max_dont_deliver) ? $max_dont_deliver = $interval->days : '';
                    ($interval->days < $min_dont_deliver) ? $min_dont_deliver = $interval->days : '';
                    $total_dont_deliver_orders++;
                    break;
            }
            ($interval->days < $min_contact) ? $min_contact = $interval->days : '';
        }
        $collected_average = ($total_collected_orders > 0) ? round($collected_days / $total_collected_orders) : 'N/A';
        $dismissed_average = ($total_dismissed_orders > 0) ? round($dismissed_days / $total_dismissed_orders) : 'N/A';
        $offered_average = ($total_offered_orders > 0) ? round($offered_days / $total_offered_orders) : 'N/A';
        $sale_average = ($total_sale_orders > 0) ? round($sale_days / $total_sale_orders) : 'N/A';
        $dont_deliver_average = ($total_dont_deliver_orders > 0) ? round($dont_deliver_days / $total_dont_deliver_orders) : 'N/A';
        $average_array = ['Recogidas' => $collected_average, "Desechadas" => $dismissed_average, "Ofertadas" => $offered_average, "No entrego" => $dont_deliver_average, "Venta en punto" => $sale_average];
        $max_array = ['Recogidas' => $max_collected, "Desechadas" => $max_dismissed, "Ofertadas" => $max_offered, "No entrego" => $max_dont_deliver, "Venta en punto" => $max_sale];
        $min_array = ['Recogidas' => $min_collected, "Desechadas" => $min_dismissed, "Ofertadas" => $min_offered, "No entrego" => $min_dont_deliver, "Venta en punto" => $min_sale, "Primer Contacto" => $min_contact];

        foreach($average_array as $key => $value) {
            if ($value == 0) {
                $average_array[$key] = '0';
            }
        }
        foreach($max_array as $key => $value) {
            if ($value == 0) {
                $max_array[$key] = '0';
            } elseif ($value == -1) {
                $max_array[$key] = 'N/A';
            }
        }
        foreach($min_array as $key => $value) {
            if ($value == 0) {
                $min_array[$key] = '0';
            } elseif ($value == 9999) {
                $min_array[$key] = 'N/A';
            }
        }
        
        $times = ["average" => $average_array, "max" => $max_array, "min" => $min_array];

        return $times;
    }

    function readCollectedOrder() {

        $confirm = new confirmVerification();
        $model = new orderClass();

        $all_orders = $model->read("received_date, municipality, claim_number, state, id", "state = 'Pendiente' ORDER BY `orders`.`id` DESC");
        $i = 1;

        foreach ($all_orders as $order) {
            $date = $this->formatDate($order['received_date']);
            $pencil = $this->pencil($order['state'], $order['id']);

            print "<tr><th scope='row' class='first_column'>$i</th><td>$date</td><td>$order[municipality]</td><td><span class='table_column_1'>$order[claim_number]</span>$pencil<a href='orderView.php?order=$order[id]'><i id='eye' class='fas fa-eye px-2'></i></a></td></tr>";
            $i++;
        }
    }

    function readGroup() {

        $confirm = new confirmVerification();
        $model = new orderClass();

        $all_orders = $model->read("photo, description, claim_number, id", "`state` = 'recogida' AND `photo` != '0.png' AND `fk_lot` = 0  ORDER BY `orders`.`id` DESC");

        return $all_orders;
    }

    function Createlots() {

        $confirm = new confirmVerification();
        $model = new orderClass();
        $model2 = new lotClass();
        $num_lot = $_POST['numlot'];
        $name_lote = "lote " . $num_lot;

        $values[0] = $name_lote;
        $values[1] = "Pendiente";
        $values[2] = date('Y-m-d H:i:s');

        if ($id = $model2->create($values)) {

            header("Location: ../views/lots.php?creation=true");
        }
        $set = "fk_lot= $id";

        foreach ($_POST['lot'] as $id_lots) {

            if ($model->update($set, $id_lots)) {
                
            }
        }
    }

    function lots() {

        $confirm = new confirmVerification();
        $model = new lotClass();

        $all_lots = $model->selectLots();

        return $all_lots;
    }

    function modifyLots() {
        $model = new orderClass();

        $id_lots = $_POST['nameLot'];

        $i = 0;
        foreach ($_POST['orders'] as $id) {
            $lot = $id_lots[$i];
            $set = "fk_lot= $lot";
            if ($model->update($set, $id)) {
                
            }
            $i++;
        }
        header("Location: ../views/lots.php");
    }

    function lots_mail() {

        $confirm = new confirmVerification();
        $model = new lotClass();
        $model2 = new orderClass();
        $date = date('M d- Y');

        $all_lots = $model->read(" `state_lot` = 'Pendiente' ORDER BY `lots`.`id` DESC");
        return $all_lots;
        foreach ($all_lots as $lots) {
            $dataLo = $lots['id'];
            $all_orders = $model2->read("`fk_lot` = '$dataLo' ORDER BY `orders`.`id` DESC");
            ?><table class="table table-bordered mt-5 ml-5 col-6"> 
                <thead><tr><th scope="col" class="text-center"><?= $lots['name_lot']; ?></th>
                        <th scope="col"><?= $date; ?></th></tr>
                    <tr><th scope="col">Siniestro</th>
                        <th scope="col">Descripción</th></tr>
                </thead>
                <?php
                foreach ($all_orders as $order) {
                    ?>                 
                    <tbody>
                        <tr>
                            <td class="col-6"><?= $order['claim_number'] ?></td>
                            <td class="col-6"><?= $order['description'] ?></td>
                        </tr>
                    </tbody>
                    <?php
                }
            }
            print "</table>";
        }

        function sendMail() {

            $confirm = new confirmVerification();
            $model = new lotClass();
            $model2 = new orderClass();
            $mailer = new mailerClass();

            $email = $_POST['exampleEmail'];
            $asunt = $_POST["exampleAsunt"];
            $bodyMail = $_POST["exampleBody"];
            $idlot = $_POST["sendLot"];

            $lotImag = $model2->read("photo", " `fk_lot` = '$idlot' ORDER BY `orders`.`id` DESC");

            foreach ($lotImag as $photoLot) {
                $photoImag[] = $photoLot['photo'];
            }
            $status_lots = $mailer->sendMails($email, $asunt, $bodyMail, $photoImag, $idlot);
            $set = "`state_lot` = 'Enviado'";
            if ($status_lots) {
                if ($model->update($set, $idlot)) {
                    header("Location: ../views/groupOrders.php?send=true");
                }
            } else {
                header("Location: ../views/groupOrders.php?send=error $status_lots");
            }
        }

        function invoiceExcel() {

            $confirm = new confirmVerification();
            $model = new orderClass();

            $start = $_POST['startDate'];
            $end = $_POST['endDate'];
            $start_format = $this->formatDate($start);
            $end_format = $this->formatDate($end);
            $start_format1 = date('d/m/y', strtotime($start));
            $end_format1 = date('d/m/y', strtotime($end));

            $all_orders = $model->read("claim_number, municipality, description, contact_name", "state != 'Pendiente' AND modification_date >= '$start' AND modification_date <= '$end'");

            foreach ($all_orders as $order) {
                $claim[] = $order['claim_number'];
                $munic[] = $order['municipality'];
                $desc[] = $order['description'];
                $contact[] = $order['contact_name'];
            }

            $main_array = array('Numbers' => $claim, 'Municipality' => $munic, 'Description' => $desc, 'Contact' => $contact, 'Start Date' => $start_format1, 'End Date' => $end_format1, 'Fecha Inicial' => $start_format, 'Fecha Final' => $end_format);
            return $main_array;
        }

        function readSpecificOrder($order_id) {

            $confirm = new confirmVerification();
            $model = new orderClass();

            $order_data = $model->read("*", "id = $order_id");

            foreach ($order_data as $data) {
                
            }

            return $data;
        }

        function modifyOrder($id) {

            $confirm = new confirmVerification();
            $data = $this->readSpecificOrder($id);

            $insured = $_POST['insured'];
            $address = $_POST['address'];
            $community = $_POST['community'];
            $phone = $_POST['phone'];
            $receive_date = $_POST['receive_date'];
            $claim_number = $_POST['claim_number'];
            $contact_name = $_POST['contact_name'];
            $contact_phone = $_POST['contact_phone'];
            $municipality = $_POST['municipality'];
            $status = "Pendiente";
            $description = $_POST['description'];
            $modification_date = $data['modification_date'];
            $last_date = date('Y-m-d H:i:s');

            if ($municipality == "Otro") {
                $municipality = $_POST['other'];
                $munic_confirm = $this->municipalityCreate($municipality);
            }

            $orders[0] = $id;
            $orders[1] = $confirm->confirm_string($insured);
            $orders[2] = $confirm->confirm_string($address);
            $orders[3] = $confirm->confirm_string($phone);
            $orders[4] = $confirm->confirm_string($community);
            $orders[5] = $confirm->confirm_number($receive_date);
            $orders[6] = $confirm->confirm_number($claim_number);
            $orders[7] = $confirm->confirm_string($contact_name);
            $orders[8] = $confirm->confirm_string($contact_phone);
            $orders[9] = $confirm->confirm_string($municipality);
            $orders[10] = $confirm->confirm_string($status);
            $orders[11] = $confirm->confirm_string($description);
            $orders[12] = $modification_date;
            $orders[13] = $last_date;
            $orders[14] = $data['photo'];

            $set = "insured='$orders[1]',address='$orders[2]',community='$orders[3]',phone='$orders[4]',received_date='$orders[5]',claim_number='$orders[6]',contact_name='$orders[7]',contact_phone='$orders[8]',municipality='$orders[9]',state='$orders[10]',description='$orders[11]',modification_date='$orders[12]',last_date='$orders[13]',photo='$orders[14]'";

            $model = new orderClass();
            if ($model->update($set, $id)) {
                header("Location: ../views/orderView.php?order=$id&confirmation=updated");
            } else {
                header("Location: ../views/modifyOrder.php?update=failed");
            }
        }

        function modifyStatus($id, $photo) {

            $confirm = new confirmVerification();
            $status = $_POST['status'];
            $data = $this->readSpecificOrder($id);
            $aux = $confirm->confirm_string($status);
            $date = date('Y-m-d H:m:s');
            $set = "state='$aux',modification_date='$date',photo='$photo'";

            $model = new orderClass();
            if ($model->update($set, $id)) {
                header("Location: ../views/orderView.php?order=$id");
            } else {
                header("Location: ../views/orderView.php?update=failed&order=$id");
            }
        }

        function viewOrder($data_state, $photo) {
            $view = ["color" => '', "border_color" => '', "sel_1" => '', "sel_2" => '', "sel_3" => '', "sel_4" => '', "button_photo" => '', "photo" => '', "loaded_photo" => false, "form" => false];

            switch ($data_state) {
                case "Pendiente" :
                    $view["sel_1"] = "selected";
                    $view["border_color"] = "border-danger";
                    $view["color"] = "red_status";
                    $view["form"] = true;
                    break;
                case "Recogida" :
                    $view["sel_2"] = "selected";
                    $view["border_color"] = "border-success";
                    $view["color"] = "green_status";
                    break;
                case "Desechada" :
                    $view["sel_3"] = "selected";
                    $view["border_color"] = "border-primary";
                    $view["color"] = "blue_status";
                    break;
                case "Ofertada" :
                    $view["sel_4"] = "selected";
                    $view["border_color"] = "border-warning";
                    $view["color"] = "yellow_status";
                    break;
            }

            if ($photo == '0') {
//            $view["button_photo"] = "<input class='pt-2' type='file' name='fileToUpload' id='fileToUpload' accept='.jpg, .jpeg'>";
                $view["photo"] = "<img src='../assets/img/default.jpg' width='50%' alt='Foto'/>";
            } else {
//            $view["button_photo"] = "<input class='pt-2' type='file' name='fileToUpload' id='fileToUpload' accept='.jpg, .jpeg'>";
                $view["photo"] = "<img src='../assets/img/$photo' width='100%' alt='Foto'/>";
            }

            return $view;
        }

        function createComment($order_id) {

            $confirm = new confirmVerification();

            $comment = $_POST['comment'];
            $comments[0] = $order_id;
            $comments[1] = $confirm->confirm_string($comment);
            $comments[2] = date('Y-m-d H:i:s');

            $model = new commentClass();
            if ($model->create($comments)) {
                header("Location: ../views/orderView.php?order=$order_id");
            } else {
                header("Location: ../views/orderView.php?comment_creation=failed&order=$order_id");
            }
        }

        function readComments($order_id) {

            $confirm = new confirmVerification();
            $model = new commentClass();

            $comments_1 = $model->read("fk_order = $order_id ORDER BY `comments`.`id` DESC");
            $date = '';
            $hour = '';

            foreach ($comments_1 as $comments_2) {
                $comments = $comments_2;
                $date = $this->formatDate($comments['date']);
                $hour = date('H:i:s', strtotime($comments['date']));
                echo "<div class='row border rounded py-2 my-2'> $date ( $hour ) </br> $comments[comment]</div>";
            }
        }

        function photo($order_id) {

            $order = $this->readSpecificOrder($order_id);

            $target_dir = "../assets/img/";
            $target_file = basename($_FILES["fileToUpload"]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $rename = $order['claim_number'] . '.' . $imageFileType;
            $target_file_renamed = $target_dir . $rename;
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

            if ($check == false) {
                $uploadOk = 0;
                $error = "El archivo no es una imagen";
                header("Location: ../views/orderView.php?order=$order_id&photo_error=1");
            } elseif ($_FILES["fileToUpload"]["size"] > 10000000) {
                $uploadOk = 0;
                $error = "La imagen excede el tamaño maximo permitido de 10MB";
                header("Location: ../views/orderView.php?order=$order_id&photo_error=2");
            } elseif ($imageFileType != "jpg" && $imageFileType != "jpeg") {
                $uploadOk = 0;
                $error = "Formato de archivo no permitido";
                header("Location: ../views/orderView.php?order=$order_id&photo_error=3");
            } else {
                move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file_renamed);
                $this->modifyStatus($order_id, $rename);
                header("Location: ../views/orderView.php?order=$order_id");
            }
        }

    }

    $orders = new orderController();

    if (isset($_GET['type'])) {
        if ($_GET['type'] === "create") {
            $createOrder = $orders->createOrder();
        } elseif ($_GET['type'] === "update") {
            $modifyOrder = $orders->modifyOrder($_GET['id']);
        } elseif ($_GET['type'] === "group") {
            $createLot = $orders->Createlots();
        } elseif ($_GET['type'] === "mail") {
            $createLot = $orders->sendMail();
        } elseif ($_GET['type'] === "modifyLot") {
            $createLot = $orders->modifyLots();
        }
    }

    if (isset($_GET['form'])) {
        $data = $orders->readSpecificOrder($_GET['id']);

        if ($_GET['form'] === 'create') {

            if ($_POST['status'] !== $data['state'] && $_POST['comment'] && $_FILES['fileToUpload']['size'] > 10) {
                $modifyStatus = $orders->modifyStatus($_GET['id'], 0);
                $photo = $orders->photo($_GET['id']);
                $createComment = $orders->createComment($_GET['id']);
            } elseif ($_POST['status'] !== $data['state'] && $_POST['comment']) {
                $modifyStatus = $orders->modifyStatus($_GET['id'], 0);
                $createComment = $orders->createComment($_GET['id']);
            } elseif ($_POST['status'] !== $data['state'] && $_FILES['fileToUpload']['size'] > 10) {
                $modifyStatus = $orders->modifyStatus($_GET['id'], 0);
                $photo = $orders->photo($_GET['id']);
            } elseif ($_POST['comment'] && $_FILES['fileToUpload']['size'] > 10) {
                $createComment = $orders->createComment($_GET['id']);
                $photo = $orders->photo($_GET['id']);
            } elseif ($_POST['status'] !== $data['state']) {
                $modifyStatus = $orders->modifyStatus($_GET['id'], 0);
            } elseif ($_POST['comment']) {
                $createComment = $orders->createComment($_GET['id']);
            } elseif ($_FILES['fileToUpload']['size'] > 10) {
                $photo = $orders->photo($_GET['id']);
            } else {
                header("Location: ../views/orderView.php?order=$_GET[id]");
            }
        }
    }


        