<?php

include_once '../config/dataBaseConection.php';

class municipalityClass {
    
    function create($value){
       $db = new dataBaseConection();
       $con = $db->conected();
       $query = "INSERT INTO `municipality`(`municipality`) VALUES ('$value')";
       $result = $con->query($query);
       
       if (!$result){
          $result = "Query failed: $con->error";
          echo "<script>console.log('$result');</script>";
          return 'error';
       }
       return $result;
    }
    
    function update($values){
       $db = new dataBaseConection();
       $con = $db->conected();
       $query = "UPDATE `municipality` SET `municipality`='$values[1]' WHERE id = $values[0]";
       $result = $con->query($query);
       
       if (!$result){
          $result = "Query failed: $con->error";
          echo "<script>console.log('$result');</script>";
          return 'error';
       }
       return $result;
    }
    
    function delete($values){
       $db = new dataBaseConection();
       $con = $db->conected();
       $query = "DELETE FROM `municipality` WHERE 'id' = $values";
       $result = $con->query($query);
       
       if (!$result){
          $result = "Query failed: $con->error";
          echo "<script>console.log('$result');</script>";
          return 'error';
       }
       return $result;
    }
    
    function read($values){
       $db = new dataBaseConection();
       $con = $db->conected();
       $query = "SELECT * FROM `municipality` WHERE $values";
       $result = $con->query($query);
       
       if (!$result){
          $result = "Query failed: $con->error";
          echo "<script>console.log('$result');</script>";
          return 'error';
       }
       return $result;
    }    
}
