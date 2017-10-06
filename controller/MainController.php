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
            //Då skall message clearas
            $this->view->render(true, $this->loginView, $this->registerView, $this->loginModel->clearMessage(), 'login');
                        
        } else {
            //Annars kollar vad användaren vill göra för något:


            //Om användaren klickar på register a taggen
            if ($this->registerView->clicksRegisterLink()) {
                //Sätter sessionsvariabel till true
                $this->registerModel->wantsToRenderRegister();
            }

            //Försöker logga in
            if ($this->loginView->loginAttempt()) {
                
                $message = $this->getMessage('login');
    
                if ($this->loginModel->isLoggedIn()) {
                    $this->view->render(true, $this->loginView, $this->registerView, 'Welcome', 'login');
                } else {
                    $this->view->render(false, $this->loginView, $this->registerView, $message, 'login');
                }

            //Kolla om användaren vill se register vyn
            } else if ($this->registerModel->checkRegisterState()) {
                
                $this->registerModel->hasRenderedRegister();

                $message = "";
                
                if ($this->registerView->attemptRegister()) {
                    $message = $this->getMessage('register');
                }
                $this->view->render(false, $this->loginView, $this->registerView, $message , 'register');
            
            } else {
                //Rendera 'home' vyn
                $this->view->render(false, $this->loginView, $this->registerView, "", 'login');
                
            }
        }


        if ($this->loginView->logoutAttempt()) {
            $this->loginModel->unsetSession();
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;
        }

    }


    private function getMessage($decideWhichView) {

        if ($decideWhichView == 'login') {
            $username = $this->loginView->getUsername();
            $password = $this->loginView->getPassword();

            return $this->loginModel->validateLoginAttempt($username, $password);

        } else if ($decideWhichView == 'register') {
            $username = $this->registerView->getUsername();
            $password = $this->registerView->getPassword();
            $passwordRepeat = $this->registerView->getRepeatedPassword();
            
            return $this->registerModel->validateRegisterAttempt($username, $password, $passwordRepeat);
        }
    }
}