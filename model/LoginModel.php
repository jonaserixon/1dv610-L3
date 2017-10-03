<?php

namespace model;

class LoginModel {

    public function validateLoginAttempt($username, $password) {

        $_SESSION['message'] = '';

        if ($username == '' && $password == '') {
            $_SESSION['message'] = 'Username is missing';

        } else if (strlen($username) > 0 && $password == '') {
            $_SESSION['message'] = 'Password is missing';
            
        } else if (strlen($password) > 0 && $username == '') {
            $_SESSION['message'] = 'Username is missing';

        } else if ($username == 'Admin' && $password == 'Password') {

            $_SESSION['loggedIn'] = true;

            echo "logged in";
            return true;
        }

        $_SESSION['loggedIn'] = false;

        echo "logged ute";
        return false;
    }
    

    public function isLoggedIn() {
        if (isset($_SESSION['loggedIn'])) {
            return $_SESSION['loggedIn'];
        }
        
        return false;
    }

    public function message() {
        return $_SESSION['message'];
    }
}