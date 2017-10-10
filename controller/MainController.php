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

        //Tveksamt
        $this->registerModel = new \model\RegisterModel($this->databaseModel);
        $this->registerView = new \view\RegisterView($this->registerModel);
    }
 
    public function start() {
        //TODO:
        //gör en variabel i layoutvyn som tar emot messages genom en en typ setMessage()
        //gör en setView metod också som bestämmer vilken vy som renderas så jag slipper skriva render() hela jävla tiden
        //Ta bort register view från registermodel, jao

        //Kollar om man redan är inloggad och rensar message
        if ($this->sessionModel->isLoggedIn()) {

            if ($this->loginView->clicksChangeName()) {
                                
                if ($this->loginView->editAttempt()) {
                    $this->view->setMessage($this->loginModel->validateEditedName($this->loginView->getEditedName()));
                }

                return $this->view->render(true, $this->loginView, $this->registerView, 'editname');
                
            } else if ($this->loginView->logoutAttempt() === false) {

                //tomt message
                return $this->view->render(true, $this->loginView, $this->registerView, 'login');                
            }
        } else {
            //Om användaren klickar på register länken
            if ($this->registerView->clicksRegisterLink()) {
                $this->sessionModel->wantsToRenderRegister();
            }

            //Försöker logga in
            if ($this->loginView->loginAttempt()) {
                
                $this->view->setMessage($this->getMessage('login'));
    
                if ($this->sessionModel->isLoggedIn()) {
                    return $this->view->render(true, $this->loginView, $this->registerView, 'login');
                } else {
                    return $this->view->render(false, $this->loginView, $this->registerView, 'login');
                }
                
            //Kolla om användaren vill se register vyn
            } else if ($this->sessionModel->checkRegisterState()) {
                
                $this->sessionModel->hasRenderedRegister();
                
                if ($this->registerView->attemptRegister()) {
                    $this->view->setMessage($this->getMessage('register'));
                }
                return $this->view->render(false, $this->loginView, $this->registerView, 'register');
            
            } else {
                //Rendera 'home' vyn
                if ($this->sessionModel->isRegistered()) {
                    $this->view->setMessage('Registered new user.');
                    return $this->view->render(false, $this->loginView, $this->registerView, 'login');
                } else {
                    return $this->view->render(false, $this->loginView, $this->registerView, 'login');
                }
            }
        }

        
        if ($this->loginView->logoutAttempt()) {
            if ($this->sessionModel->isLoggedIn()) {
                $this->view->setMessage('Bye bye!');
            } 

            $this->sessionModel->unsetSessions();
            return $this->view->render(false, $this->loginView, $this->registerView, 'login');
        }
    }

    //Get messages from the user input validation methods
    private function getMessage($viewMessage) {

        if ($viewMessage == 'login') {
            return $this->loginModel->validateLoginAttempt($this->loginView->getUsername(), $this->loginView->getPassword());

        } else if ($viewMessage == 'register') {
            return $this->registerModel->validateRegisterAttempt($this->registerView->getUsername(), $this->registerView->getPassword(), $this->registerView->getRepeatedPassword());
        }
    }
}
