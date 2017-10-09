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

                $message = '';
                                
                if ($this->loginView->editAttempt()) {
                    $message = $this->loginModel->validateEditedName($this->loginView->getEditedName());
                }

                return $this->view->render(true, $this->loginView, $this->registerView, $message, 'editname');
                
            } else if ($this->loginView->logoutAttempt() === false) {

                return $this->view->render(true, $this->loginView, $this->registerView, $this->sessionModel->clearMessage(), 'login');                
            }
        } else {
            //Om användaren klickar på register länken
            if ($this->registerView->clicksRegisterLink()) {
                $this->sessionModel->wantsToRenderRegister();
            }

            //Försöker logga in
            if ($this->loginView->loginAttempt()) {
                
                $message = $this->getMessage('login');
    
                if ($this->sessionModel->isLoggedIn()) {
                    
                    return $this->view->render(true, $this->loginView, $this->registerView, 'Welcome', 'login');
                } else {
                    return $this->view->render(false, $this->loginView, $this->registerView, $message, 'login');
                }
                
            //Kolla om användaren vill se register vyn
            } else if ($this->sessionModel->checkRegisterState()) {
                
                $this->sessionModel->hasRenderedRegister();

                $message = '';
                
                if ($this->registerView->attemptRegister()) {
                    $message = $this->getMessage('register');

                }
                return $this->view->render(false, $this->loginView, $this->registerView, $message , 'register');
            
            } else {
                //Rendera 'home' vyn

                if ($this->sessionModel->isRegistered()) {
                    return $this->view->render(false, $this->loginView, $this->registerView, 'Registered new user.', 'login');
                } else {
                    return $this->view->render(false, $this->loginView, $this->registerView, '', 'login');
                }
            }
        }

        
        if ($this->loginView->logoutAttempt()) {
            if ($this->sessionModel->isLoggedIn()) {
                $message = 'Bye bye!';
            } else {
                $message = '';
            }

            $this->sessionModel->unsetSessions();

            $this->sessionModel->setSpecificMessage('logoutMessage' , 'Bye bye!');

            return $this->view->render(false, $this->loginView, $this->registerView, $message, 'login');
            // return header("Location: /1dv610-L3/");
            // header("Location: /1dv610-L3/");
            // die();
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
