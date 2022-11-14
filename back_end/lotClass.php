<?php

include_once '../config/dataBaseConection.php';

class lotClass {

    function create($values) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "INSERT INTO `lots`(`name_lot`, `state_lot`, `date`) VALUES ('$values[0]', '$values[1]', '$values[2]')";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        $id = mysqli_insert_id($con);
        return $id;
    }

    function update($set, $id) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "UPDATE `lots` SET $set WHERE id = $id";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }

    function delete($values) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "DELETE FROM `lots` WHERE 'id' = $values";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }

    function read($values) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "SELECT * FROM `lots` WHERE $values";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }
    

    function selectLots() {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "SELECT O.claim_number, O.description, O.photo, L.name_lot, O.id, O.fk_lot FROM orders O INNER JOIN lots L ON O.fk_lot = L.id WHERE L.state_lot = 'Pendiente' ORDER BY `L`.`id`";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }

}
