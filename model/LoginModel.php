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
}
