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
        $_SESSION['username'] = $username;

        if ($username == '' && $password == '') {
            $_SESSION['message'] = 'Username is missing';

            $message = 'Username is missing';
            
        } else if (strlen($username) > 0 && $password == '') {
            $_SESSION['message'] = 'Password is missing';

            $message = 'Password is missing';

        } else if (strlen($password) > 0 && $username == '') {
            $_SESSION['message'] = 'Username is missing';

            $message = 'Username is missing';

        } else if ($username == 'Admin' && $password == 'Password') {
            $_SESSION['message'] = 'Welcome';
            $_SESSION['loggedIn'] = true;
            
            // return header("Location: " . $_SERVER['REQUEST_URI']);
            return;
        }

        $_SESSION['loggedIn'] = false;
        // header("Location: " . $_SERVER['REQUEST_URI']);
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

    public function rememberUsername() {
        if (isset($_SESSION['username'])) {
            return $_SESSION['username'];
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