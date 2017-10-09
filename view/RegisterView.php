<?php

namespace view;

class RegisterView {

    private static $messageId = 'RegisterView::Message';
    private static $username = 'RegisterView::UserName';
    private static $password = 'RegisterView::Password';
    private static $passwordRepeat = 'RegisterView::PasswordRepeat';
    private static $doRegistration = 'RegisterView::Register';

    private $theUsername;

    private $registerModel;
    
    public function __construct(\model\RegisterModel $registerModel	) {
        $this->registerModel = $registerModel;
    }


    public function response($message) {
        return $this->generateRegisterFormHTML($message);
    }


    public function attemptRegister() {
        return isset($_POST['RegisterView::Register']);        
    }


    public function clicksRegisterLink() {
        return isset($_GET['register']);
    }


    public function getUsername() {
		if (isset($_POST[self::$username])) {
			return $_POST[self::$username];
		}
	}


	public function getPassword() {
		if (isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
    }

    public function getRepeatedPassword() {
		if (isset($_POST[self::$passwordRepeat])) {
			return $_POST[self::$passwordRepeat];
		}
    }
    

    public function rememberUsername() {
        if (strlen($this->theUsername) > 1) {
            return $this->theUsername;
        } 

        if (isset($_POST[self::$username])) {
            return $_POST[self::$username];
        }    
    }

    public function setUsername($username) {
        $this->theUsername = $username;
    }
    

    private function generateRegisterFormHTML($message) {
        return '
        <form action="?register" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Register a new user - Write username and password</legend>
                    <p id="' . self::$messageId . '">' . $message . '</p>
                    
                    <label for="' . self::$username . '">Username :</label> 
                    <input type="text" size="20" id="' . self::$username . '" name="' . self::$username . '" value="' . $this->rememberUsername() . '" />         <br>
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
