<?php

include_once '../back_end/orderController.php';
include_once '../back_end/orderClass.php';
?>
<?php

function view($idlot) {
    $controller = new orderController();
    $all_lots = $controller->lots_mail();
    $model = new lotClass();
    $model2 = new orderClass();
    $date = date('M d- Y');
    $var = " <!DOCTYPE html>
    <html>
    <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <style>
        td{
            border: 1px solid gray;
            padding-left: 5px;
            padding-right: 15px;
        }
    </style>
    </head>
    <body>";

    $all_orders = $model2->read("claim_number, description", "`fk_lot` = '$idlot' ORDER BY `orders`.`id` DESC");
    foreach ($all_lots as $lots) {
        
    }
    $var .= "<table style='border: 1px gray solid;'> 
                <thead><tr><th scope='col' class='text-center'>" . $lots['name_lot'] . "</th>
                    <th scope='col'>$date</th></tr>
                <tr><th scope='col'>Siniestro</th>
                    <th scope='col'>Descripcion</th></tr>
            </thead>";
    foreach ($all_orders as $order) {
        $var .= "<tbody>
                    <tr>
                        <td>" . $order['claim_number'] . "</td>
                        <td>" . $order['description'] . "</td>
                    </tr>
                </tbody>";
    }

    $var .= "</table>";
    $var .= "
    </body>
</html>";
    return $var;
}
