<?php

namespace model;

class LoginModel {

    private $database;

    public function __construct($database) {
        $this->database = $database;
    }


    public function validateLoginAttempt($username, $password) {
        $message = '';
        $_SESSION['message'] = '';

        if ($username == '' && $password == '') {
            // $_SESSION['message'] = 'Username is missing';

            $message = 'Username is missing';
            
        } else if (strlen($username) > 0 && $password == '') {
            // $_SESSION['message'] = 'Password is missing';

            $message = 'Password is missing';

        } else if (strlen($password) > 0 && $username == '') {
            // $_SESSION['message'] = 'Username is missing';

            $message = 'Username is missing';

        } else if ($username == 'Admin' && $password == 'Password') {
            $_SESSION['message'] = 'Welcome';
            $_SESSION['loggedIn'] = true;
            
            return;
        }

        $_SESSION['loggedIn'] = false;
        return $message;
    }
    



    public function isLoggedIn() {
        if (isset($_SESSION['loggedIn'])) {
            return $_SESSION['loggedIn'];
        }
        
        return false;
    }

    public function outputMessage() {
        if (isset($_SESSION['message'])) { 
            return $_SESSION['message'];
        }
    }

    

    public function clearMessage() {
        if (isset($_SESSION['message'])) { 
            return $_SESSION['message'] = '';
        }
    }

    public function unsetSession() {
        unset($_SESSION['loggedIn']);
        unset($_SESSION['username']);
        unset($_SESSION['message']);
    }

}