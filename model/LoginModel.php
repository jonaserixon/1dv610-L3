<?php

namespace model;

class LoginModel {

    private $database;

    public function __construct(DatabaseModel $database) {
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
            $_SESSION['username'] = $username;
            return;
            
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
        } else {
            $message = 'balle';
            echo $_SESSION['username'];
            $this->database->EditUsername($username, $_SESSION['username']);
            //success!
            
        }
        // echo $message;
        return $message;
    }
}
