<?php

namespace view;

class RegisterView {

    private static $messageId = 'RegisterView::Message';
    private static $username = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $doRegistration = 'RegisterView::Register';


    private $registerModel;
    
    public function __construct($registerModel	) {
        $this->registerModel = $registerModel;
    }


    public function response() {

        return $this->generateRegisterFormHTML('');
    }

    public function attemptRegister() {
        return isset($_POST[self::$doRegistration]);        
    }

    public function clicksRegisterLink() {
        return isset($_GET['register']);
    }
    

    private function generateRegisterFormHTML($message) {
        return '
        <form action="?register" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Register a new user - Write username and password</legend>
                    <p id="' . self::$messageId . '">' . $message . '</p>
                    
                    <label for="' . self::$username . '">Username :</label> 
                    <input type="text" size="20" id="' . self::$username . '" name="' . self::$username . '" value="' . "" . '" />         <br>
                    <label for="' . self::$password . '">Password :</label>
                    <input id="' . self::$password . '" size="20"  name="' . self::$password . '"  value="" type="password"/>              <br>
                    <label for="' . self::$passwordRepeat . '">Repeat password  :</label>
                    <input id="' . self::$passwordRepeat . '" size="20"  name="' . self::$passwordRepeat . '"  value="" type="password"/>  <br>
                    
                    <input id="submit" type="submit" name="' . self::$doRegistration . '" value="Register" />
                </fieldset>
            </form>
        ';
    }

}