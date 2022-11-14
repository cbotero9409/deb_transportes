<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of dataBaseConection
 *
 * @author lorena
 */
class dataBaseConection {

    //put your code here

    const SERVERNAME = 'localhost';
    const DATABASE = 'db_transportes';
    const USERNAME = 'root';
    const PASSWORD = '';

    function conected() {
        $conn = mysqli_connect(self::SERVERNAME, self::USERNAME, self::PASSWORD, self::DATABASE);
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        mysqli_set_charset($conn,"utf8");
        return $conn;
    }
}
