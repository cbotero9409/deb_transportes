<?php

include_once '../config/dataBaseConection.php';

class commentClass {
    
    function create($values){
       $db = new dataBaseConection();
       $con = $db->conected();
       $query = "INSERT INTO `comments`(`fk_order`, `comment`, `date`) VALUES ('$values[0]', '$values[1]', '$values[2]')";
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
       $query = "UPDATE `comments` SET `comments`='$values[1]', `date`='$values[2]' WHERE id = $values[0]";
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
       $query = "DELETE FROM `comments` WHERE 'id' = $values";
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
       $query = "SELECT * FROM `comments` WHERE $values";
       $result = $con->query($query);
       
       if (!$result){
          $result = "Query failed: $con->error";
          echo "<script>console.log('$result');</script>";
          return 'error';
       }
       return $result;
    }    
}
