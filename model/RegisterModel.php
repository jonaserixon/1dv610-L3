<?php

namespace model;

class RegisterModel {

    private $database;
    
    public function __construct($database) {
        $this->database = $database;
    }

    public function validateRegisterAttempt($username, $password) {
        
    }

    public function wantsToRenderRegister() {
        $_SESSION['register'] = true;
    }

    public function hasRenderedRegister() {
        $_SESSION['register'] = false;
    }

    public function checkRegisterState() {
        if (isset($_SESSION['register']) && $_SESSION['register'] == true) {
            return true;
        }

        return false;
    }
}