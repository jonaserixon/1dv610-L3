<?php

namespace model;

class LoginModel {

    public function validateLoginAttempt($username, $password) {
        $_SESSION['message'] = '';
        $_SESSION['username'] = $username;

        if ($username == '' && $password == '') {
            $_SESSION['message'] = 'Username is missing';
            
        } else if (strlen($username) > 0 && $password == '') {
            $_SESSION['message'] = 'Password is missing';

        } else if (strlen($password) > 0 && $username == '') {
            $_SESSION['message'] = 'Username is missing';

        } else if ($username == 'Admin' && $password == 'Password') {
            $_SESSION['message'] = 'Welcome';
            $_SESSION['loggedIn'] = true;
            
            return header("Location: http://localhost/1dv610-L3/index.php");
        }

        $_SESSION['loggedIn'] = false;
        

        echo "logged ute";
    }
    

    public function isLoggedIn() {
        if (isset($_SESSION['loggedIn'])) {
            return $_SESSION['loggedIn'];
        }
        
        return false;
    }

    public function outputMessage() {
        return $_SESSION['message'];
    }

    public function rememberUsername() {
        return $_SESSION['username'];
    }

}