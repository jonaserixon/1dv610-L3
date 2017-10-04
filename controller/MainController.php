<?php

namespace controller;



class MainController {

    private $loginModel;

    private $view;
    private $loginView;

    public function __construct() {
        $this->loginModel = new \model\LoginModel(new \model\DatabaseModel());

        $this->view = new \view\LayoutView();
        $this->loginView = new \view\LoginView($this->loginModel);
    }
 
    public function start() {
        
        //Kollar om man redan är inloggad eller inte
        if ($this->loginModel->isLoggedIn()) {
            $this->view->render(true, $this->loginView, "yolo");
        } else {

            if ($this->loginView->loginAttempt()) {
                
                $username = $this->loginView->getUsername();
                $password = $this->loginView->getPassword();
    
                //Set message and login attemp result in isLoggedIn()
                $message = $this->loginModel->validateLoginAttempt($username, $password);
    
                if ($this->loginModel->isLoggedIn()) {
                    $this->view->render(true, $this->loginView, 'Welcome');
                } else {
                    $this->view->render(false, $this->loginView, $message);
                }
            } else {
                $this->view->render(false, $this->loginView, "");
            }

        }
    }
}