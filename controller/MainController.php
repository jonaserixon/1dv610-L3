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
            $this->view->render(true, $this->loginView, $this->registerView, $this->loginModel->clearMessage(), 'login');
        } else if ($this->registerModel->checkRegisterState()) {
            $this->view->render(false, $this->loginView, $this->registerView, 'register bro' , 'register');

            $this->registerModel->hasRenderedRegister();
        } else {

            if ($this->loginView->loginAttempt()) {
                
                $message = $this->getMessage();
    
                if ($this->loginModel->isLoggedIn()) {
                    $this->view->render(true, $this->loginView, $this->registerView, 'Welcome', 'login');
                } else {
                    $this->view->render(false, $this->loginView, $this->registerView, $message, 'login');
                }

            } else {
                $this->view->render(false, $this->loginView, $this->registerView, "", 'login');
            }
        }

        if ($this->loginView->logoutAttempt()) {
            $this->loginModel->unsetSession();
            return header("Location: " . $_SERVER['REQUEST_URI']);
        }


        if ($this->registerView->clicksRegisterLink()) {
            $this->registerModel->wantsToRenderRegister();
            return header("Location: /1dv610-L3/index.php");
        }
    }

    

    private function getMessage() {
        $username = $this->loginView->getUsername();
        $password = $this->loginView->getPassword();

        //get message from login attempt
        return $this->loginModel->validateLoginAttempt($username, $password);
    }
}