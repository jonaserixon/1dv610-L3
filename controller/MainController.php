<?php

namespace controller;

class MainController {
 
    public function start() {

        $view = new \view\LayoutView();
        $loginView = new \view\LoginView();

        $loginModel = new \model\LoginModel();

        if ($loginModel->isLoggedIn()) {
            $view->render(true, $loginView);
        } else {
            $view->render(false, $loginView);
        }

        //Validate login attempt
        if ($loginModel->validateLoginAttempt($loginView->getUsername(), $loginView->getPassword())) {
            return header("Location: " . $_SERVER['REQUEST_URI']);
        } else {
            return;
        }

    }
}