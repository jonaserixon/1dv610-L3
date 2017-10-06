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

        $this->view = new \view\LayoutView();
        $this->loginView = new \view\LoginView($this->loginModel);
        $this->registerView = new \view\RegisterView($this->registerModel);

        //Tveksamt
        $this->registerModel = new \model\RegisterModel($this->databaseModel, $this->registerView);
    }
 
    public function start() {
        ob_start();
        
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
                
                if (isset($_SESSION['logoutMessage'])) {
                    $this->view->render(false, $this->loginView, $this->registerView, $_SESSION['logoutMessage'], 'login');     
                    $_SESSION['logoutMessage'] = '';               
                } else if (isset($_SESSION['isRegistered']) && $_SESSION['isRegistered'] = true) {
                    $this->view->render(false, $this->loginView, $this->registerView, "Registered new user.", 'login');
                } else {
                    $this->view->render(false, $this->loginView, $this->registerView, '', 'login');
                }
                
            }
        }

        if ($this->loginView->logoutAttempt()) {
            $this->loginModel->unsetSession();
            header("Location: " . $_SERVER['REQUEST_URI']);
            exit;          
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
