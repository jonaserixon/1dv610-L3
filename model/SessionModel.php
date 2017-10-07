<?php

namespace model;

class SessionModel {

    public function __construct() {

    }

    //Login & Logout methods
    public function isLoggedIn() {
        if (isset($_SESSION['loggedIn'])) {
            // unset($_SESSION['logoutMessage']);
            return $_SESSION['loggedIn'];
        }
        return false;
    }

    public function clearMessage() {
        if (isset($_SESSION['message'])) { 
            return $_SESSION['message'] = '';
        }
    }

    public function unsetSessions() {
        unset($_SESSION['loggedIn']);
        unset($_SESSION['username']);
        unset($_SESSION['message']);
    }

    public function getLogoutMessage() {
        return $_SESSION['logoutMessage'];
    }

    public function setLogoutMessage($message) {
        $_SESSION['logoutMessage'] = $message;
    }



    //Register methods
    public function isRegistered() {
        if (isset($_SESSION['isRegistered']) && $_SESSION['isRegistered']) {
            return $_SESSION['isRegistered'];
        }
        
        return false;
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