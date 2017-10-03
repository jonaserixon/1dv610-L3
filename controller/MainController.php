<?php

namespace controller;

class MainController {
 
    public function start() {
        $loginModel = new \model\LoginModel();
        

        $view = new \view\LayoutView();
        $loginView = new \view\LoginView($loginModel);


        if ($loginModel->isLoggedIn()) {
            $view->render(true, $loginView);
        } else {
            $view->render(false, $loginView);
        }

        //Validate login attempt
        $loginModel->validateLoginAttempt($loginView->getUsername(), $loginView->getPassword());
        
    }
}