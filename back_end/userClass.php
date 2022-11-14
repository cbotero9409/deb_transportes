<?php

include_once '../config/dataBaseConection.php';

class userClass {

    //put your code here
    function create($values) {
        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "INSERT INTO `users`(`name`, `email`, `password`, `user_name`, `gender`, `picture`, `last_entrace`) VALUES ('$values[0]','$values[1]','$values[2]','$values[3]','$values[4]','$values[5]','$values[6]')";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result' );</script>";
            return false;
        }
        return $result;
    }

    function update($values) {

        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "UPDATE `users` SET `name`='$values[1]',`email`='$values[2]',`password`='$values[3]',`user_name`='$values[4]',`gender`='$values[5]',`picture`='$values[6]',`last_entrace`='$values[7]' WHERE id = $values[0] ";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result' );</script>";
            return 'error';
        }
        return $result;
    }

    function delet($values) {

        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "DELETE FROM `users` WHERE id = $values";
        $result = $con->query($query);        
        
        
        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result' );</script>";
            return 'error';
        }
        return $result;
    }

    function read($values) {

        $db = new dataBaseConection();
        $con = $db->conected();
        $query = "SELECT * FROM `users` WHERE $values";
        $result = $con->query($query);

        if (!$result) {
            $result = "Query failed: $con->error";
            echo "<script>console.log('$result' );</script>";
            return 'error';
        }
        return $result;
    }

}
