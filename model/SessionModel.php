<?php

namespace model;

class SessionModel {

    public function __construct() {

    }

    //Login & Logout methods
    public function isLoggedIn() {
        if (isset($_SESSION['loggedIn'])) {
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
        unset($_SESSION['registeredName']);
    }

    public function getRegisteredName() {
        if (isset($_SESSION['registeredName'])) { 
            return $_SESSION['registeredName'];
        }
    }

    //Register methods
    public function isRegistered() {
        if (isset($_SESSION['isRegistered']) && $_SESSION['isRegistered']) {
            return $_SESSION['isRegistered'];
        }
        return false;
    }
}