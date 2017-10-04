<?php

namespace controller;



class MainController {

    private $loginModel;
    private $view;
    private $loginView;

    public function __construct() {
        $this->loginModel = new \model\LoginModel();
        $this->view = new \view\LayoutView();
        $this->loginView = new \view\LoginView($this->loginModel);
    }
 
    public function start() {
        
        if ($this->loginModel->isLoggedIn()) {
            $this->view->render(true, $this->loginView);
        } else {
            $this->view->render(false, $this->loginView);
        }

        //Validate login attempt
        $username = $this->loginView->getUsername();
        $password = $this->loginView->getPassword();
        
        $message = $this->loginModel->validateLoginAttempt($username, $password);
    }
}