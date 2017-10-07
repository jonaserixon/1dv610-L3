<?php

namespace controller;

class MainController {

    private $loginModel;
    private $registerModel;
    private $databaseModel;
    private $sessionModel;

    private $view;
    private $loginView;
    private $registerView;

    public function __construct() {
        $this->databaseModel = new \model\DatabaseModel();
        $this->loginModel = new \model\LoginModel($this->databaseModel);
        $this->sessionModel = new \model\SessionModel();

        $this->view = new \view\LayoutView();
        $this->loginView = new \view\LoginView($this->loginModel, $this->sessionModel);
        $this->registerView = new \view\RegisterView($this->registerModel);

        //Tveksamt
        $this->registerModel = new \model\RegisterModel($this->databaseModel, $this->registerView);
    }
 
    public function start() {
        ob_start(); //För att undvika error 'Warning: Cannot modify header information - headers already sent by...'


        //Kollar om man redan är inloggad eller inte
        if ($this->sessionModel->isLoggedIn()) {
            //Då skall message clearas
            $this->view->render(true, $this->loginView, $this->registerView, $this->sessionModel->clearMessage(), 'login');

        } else {
            //Annars kollar vad användaren vill göra för något:


            //Om användaren klickar på register a taggen
            if ($this->registerView->clicksRegisterLink()) {
                //Sätter sessionsvariabel till true
                $this->sessionModel->wantsToRenderRegister();
            }

            //Försöker logga in
            if ($this->loginView->loginAttempt()) {
                
                $message = $this->getMessage('login');
    
                if ($this->sessionModel->isLoggedIn()) {
                    $this->view->render(true, $this->loginView, $this->registerView, 'Welcome', 'login');
                } else {
                    $this->view->render(false, $this->loginView, $this->registerView, $message, 'login');
                }

            //Kolla om användaren vill se register vyn
            } else if ($this->sessionModel->checkRegisterState()) {
                
                $this->sessionModel->hasRenderedRegister();

                $message = '';
                
                if ($this->registerView->attemptRegister()) {
                    $message = $this->getMessage('register');

                }
                $this->view->render(false, $this->loginView, $this->registerView, $message , 'register');
            
            } else {
                //Rendera 'home' vyn

                //Fixa detta
                if (isset($_SESSION['logoutMessage'])) {

                    $this->view->render(false, $this->loginView, $this->registerView, '', 'login');     
                    //Ytterst tveksamt
                    // $this->sessionModel->setLogoutMessage('');

                } else if ($this->sessionModel->isRegistered()) {
                    $this->view->render(false, $this->loginView, $this->registerView, 'Registered new user.', 'login');
                } else {
                    $this->view->render(false, $this->loginView, $this->registerView, '', 'login');
                }
            }
        }

        if ($this->loginView->logoutAttempt()) {
            $this->sessionModel->unsetSessions();

            // $this->sessionModel->setLogoutMessage('Bye bye!');

            return header("Location: /1dv610-L3/");
        }
    }

    //Get messages from the input validation methods
    private function getMessage($decideWhichView) {

        if ($decideWhichView == 'login') {
            return $this->loginModel->validateLoginAttempt($this->loginView->getUsername(), $this->loginView->getPassword());

        } else if ($decideWhichView == 'register') {
            return $this->registerModel->validateRegisterAttempt($this->registerView->getUsername(), $this->registerView->getPassword(), $this->registerView->getRepeatedPassword());
        }
    }
}
