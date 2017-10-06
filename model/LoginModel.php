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
            $message = 'Username is missing';
            
        } else if (strlen($username) > 0 && $password == '') {
            $message = 'Password is missing';

        } else if (strlen($password) > 0 && $username == '') {
            $message = 'Username is missing';

        } else if ($this->database->findAndVerifyUser($username, $password)) {
            $_SESSION['message'] = 'Welcome';
            $_SESSION['loggedIn'] = true;
            return;
            
        } else if (!$this->database->findAndVerifyUser($username, $password)) {
            $message = 'Wrong name or password';
        }

        $_SESSION['loggedIn'] = false;

        return $message;
    }
    
    public function isLoggedIn() {
        if (isset($_SESSION['loggedIn'])) {
            unset($_SESSION['logoutMessage']);
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
        $_SESSION['logoutMessage'] = 'Bye bye!';
    }
}
