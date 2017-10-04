<?php

namespace controller;



class MainController {

    private $loginModel;
    private $registerModel;
    private $databaseModel;

    private $view;
    private $loginView;
    private $registerView;

    public function __construct() {
        $this->databaseModel = new \model\DatabaseModel();
        $this->loginModel = new \model\LoginModel($this->databaseModel);
        $this->registerModel = new \model\RegisterModel($this->databaseModel);

        $this->view = new \view\LayoutView();
        $this->loginView = new \view\LoginView($this->loginModel);
        $this->registerView = new \view\RegisterView($this->registerModel);
    }
 
    public function start() {
        
        //Kollar om man redan är inloggad eller inte
        if ($this->loginModel->isLoggedIn()) {
            //cleara session message
            $this->view->render(true, $this->loginView, $this->loginModel->clearMessage());
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

        if ($this->loginView->logoutAttempt()) {
            $this->loginModel->unsetSession();
            return header("Location: " . $_SERVER['REQUEST_URI']);
        }


        if ($this->registerView->clicksRegisterLink()) {
            echo "HDSADJLJKASLDJASK";
        }
    }
}