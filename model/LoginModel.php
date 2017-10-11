<?php

namespace model;

class LoginModel {

    private $database;
    private $message = '';

    public function __construct(\model\DatabaseModel $database) {
        $this->database = $database;
    }

    
    public function validateLoginAttempt($username, $password) {
        if ($username == '' && $password == '') {
            $this->message = 'Username is missing';
            
        } else if (strlen($username) > 0 && $password == '') {
            $this->message = 'Password is missing';

        } else if (strlen($password) > 0 && $username == '') {
            $this->message = 'Username is missing';

        } else if ($this->database->findAndVerifyUser($username, $password)) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['username'] = $username;

            return $this->message = 'Welcome';            
            
        } else if (!$this->database->findAndVerifyUser($username, $password)) {
            $this->message = 'Wrong name or password';
        }
        $_SESSION['loggedIn'] = false;

        return $this->message;
    }


    public function validateEditedName($username) {
        if ($this->database->alreadyExist($username)) {
            $this->message = 'User exists, pick another username.';

        } else if (preg_match('/</',$username) || (preg_match('/>/',$username))) {
            $this->message = 'Username contains invalid characters.';

        } else if (strlen($username) === 0) {
            $this->message = 'Username is too short.';
            
        } else {    
            $this->message = 'Successfully changed your username to ' . $username . '!';
            //Save to database
            $this->database->editUsername($username, $_SESSION['username']);
        }
        return $this->message;
    }
}
