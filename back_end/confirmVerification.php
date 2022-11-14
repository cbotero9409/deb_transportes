<?php

class confirmVerification {

    function confirm_string($string) {

        $result = filter_var($string, FILTER_SANITIZE_STRING);
        return $result;
    }

    function confirm_email($email) {

        $result = filter_var($email, FILTER_SANITIZE_EMAIL);
        return $result;
    }

    function confirm_number($num) {
        $result = filter_var($num, FILTER_SANITIZE_NUMBER_INT);
        return $result;
    }
    
}
