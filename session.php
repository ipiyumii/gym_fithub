<?php
//piyumi
    session_start();

    function setSession($key, $value) {
        $_SESSION[$key] = $value;
    }

    function getSession($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    function endSession() {
        // $rest= resetLoginAttempts($userId);
        $_SESSION = [];
        session_destroy();
    }
?>