<?php

namespace model;

class LoginModel {

    private $database;

    public function __construct(\model\DatabaseModel $database) {
        $this->database = $database;
    }


    public function validateLoginAttempt($username, $password) {
        $message = '';

        if ($username == '' && $password == '') {
            $message = 'Username is missing';
            
        } else if (strlen($username) > 0 && $password == '') {
            $message = 'Password is missing';

        } else if (strlen($password) > 0 && $username == '') {
            $message = 'Username is missing';

        } else if ($this->database->findAndVerifyUser($username, $password)) {

            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;

            return $message = 'Welcome';            
            
        } else if (!$this->database->findAndVerifyUser($username, $password)) {
            $message = 'Wrong name or password';
        }

        $_SESSION['loggedIn'] = false;

        return $message;
    }


    public function validateEditedName($username) {
        $message = '';
        if ($this->database->alreadyExist($username)) {
            $message = 'User exists, pick another username.';
        } else if (preg_match('/</',$username) || (preg_match('/>/',$username))) {
            $message = 'Username contains invalid characters.';
            // $this->registerView->setUsername(strip_tags($username));
        } else if (strlen($username) < 1) {
            $message = 'Username is too short.';
        } else {    
            $message = 'success';
            $this->database->editUsername($username, $_SESSION['username']);
        }
        // echo $message;
        return $message;
    }
}
