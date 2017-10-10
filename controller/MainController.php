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
        $this->registerModel = new \model\RegisterModel($this->databaseModel);

        $this->view = new \view\LayoutView();
        $this->loginView = new \view\LoginView($this->loginModel, $this->sessionModel);
        $this->registerView = new \view\RegisterView($this->registerModel);
    }
 
    public function start() {
        //TODO:
        //gör en setView metod också som bestämmer vilken vy som renderas så jag slipper skriva render() hela jävla tiden
        //Ta bort register view från registermodel, jao

        if ($this->sessionModel->isLoggedIn()) {

            if ($this->loginView->userClicksEditName()) {   
                //EditController
                return $this->userIsEditing();
            } 
            
            if ($this->loginView->logoutAttempt() === false) {
                //Runs when user reloads page
                return $this->view->render(true, $this->loginView, $this->registerView, 'login');                
            }

        } else {
            if ($this->registerView->userClicksRegisterLink()) {
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
                } 

                return $this->view->render(false, $this->loginView, $this->registerView, 'login');
            }
        }
        
        $this->whenUserWantsLogout();
    }


    //Get messages from the user input validation methods
    private function getMessage($viewMessage) {
        if ($viewMessage == 'login') {
            return $this->loginModel->validateLoginAttempt($this->loginView->getUsername(), $this->loginView->getPassword());

        } else if ($viewMessage == 'register') {
            return $this->registerModel->validateRegisterAttempt($this->registerView->getUsername(), $this->registerView->getPassword(), $this->registerView->getRepeatedPassword());
        } else if ($viewMessage == 'edit') {
            return $this->loginModel->validateEditedName($this->loginView->getEditedName());
        }
    }

    private function userIsEditing() {
        if ($this->loginView->editAttempt()) {
            $this->view->setMessage($this->getMessage('edit'));                    
        }
        //Fixa till detta
        $this->whenUserWantsLogout();

        return $this->view->render(true, $this->loginView, $this->registerView, 'editname');
    }

    private function whenUserWantsLogout() {
        if ($this->loginView->logoutAttempt()) {
            if ($this->sessionModel->isLoggedIn()) {
                $this->view->setMessage('Bye bye!');
            } 

            $this->sessionModel->unsetSessions();
            return $this->view->render(false, $this->loginView, $this->registerView, 'login');
        }
    }
}
