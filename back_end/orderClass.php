<?php

include_once '../config/dataBaseConection.php';

class orderClass {

    function create($values) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "INSERT INTO `orders`(`insured`, `address`, `community`, `phone`, `received_date`, `claim_number`, `contact_name`, `contact_phone`, `municipality`, `state`, `description`, `modification_date`, `last_date`, `photo`) VALUES ('$values[0]', '$values[1]', '$values[2]', '$values[3]', '$values[4]', '$values[5]', '$values[6]', '$values[7]', '$values[8]', '$values[9]', '$values[10]', '$values[11]', '$values[12]', '$values[13]')";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }

    function update($set, $id) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "UPDATE `orders` SET $set WHERE id = $id";
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
        $query = "DELETE FROM `orders` WHERE 'id' = $values";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }

    function read($select, $values) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "SELECT $select FROM `orders` WHERE $values";

        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }

    function customRead($fk_order) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "SELECT DATE(MIN(date)) FROM `comments` LEFT JOIN `orders` ON comments.fk_order = orders.id WHERE comments.fk_order = $fk_order";

        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result');</script>";
            return 'error';
        }
        return $result;
    }

}