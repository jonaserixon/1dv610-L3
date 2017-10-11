<?php

namespace view;

class LoginView {
	
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private static $editName = 'LoginView::EditName';
	private static $newName = 'LoginView::NewName';	
	
	private $loginModel;
	private $sessionModel;
	private $database;

	public function __construct(\model\LoginModel $loginModel, \model\SessionModel $sessionModel, $database) {
		$this->loginModel = $loginModel;
		$this->sessionModel = $sessionModel;
		$this->database = $database;
	}

    public function response($message, $shouldRenderEditname) {
		//Render login, logout or edit form
		if ($this->sessionModel->isLoggedIn()) {

			if ($shouldRenderEditname) {
				return $this->generateEditUsernameHTML($message);
			}

			return $this->generateLogoutButtonHTML($message);
		} 

        return $this->generateLoginFormHTML($message);
	}


	public function loginAttempt() {
		return isset($_POST[self::$login]);
	}
	
	public function logoutAttempt() {
		return isset($_POST[self::$logout]);
	}

	public function getUsername() {
		if (isset($_POST[self::$name])) {
			return $_POST[self::$name];
		}
	}

	public function getPassword() {
		if (isset($_POST[self::$password])) {
			return $_POST[self::$password];
		}
	}

	private function rememberUsername() {
		if (isset($_SESSION['registeredName'])) {
			return $_SESSION['registeredName'];
		} else if (isset($_POST[self::$name])) {
            return $_POST[self::$name];
        }
	}

	//COOKIES
	public function userClicksCookie() {
		return isset($_POST[self::$keep]);
	}

	public function setCookies() {
		setcookie("LoginView::CookieName", $this->getUsername());
		setcookie("LoginView::CookiePassword", $this->getPassword());
	}

	public function doCookiesExist() {
		if (isset($_COOKIE['LoginView::CookieName'])) {
			return isset($_COOKIE['LoginView::CookieName']);
		}
	}
	
	
	//EDIT USERNAME
	public function userClicksEditName() {
        return isset($_GET['edit']);
	}
	
	public function editAttempt() {
		return isset($_POST[self::$editName]);
	}

	public function getEditedName() {
		if (isset($_POST[self::$newName])) {
			return $_POST[self::$newName];
		}
	}
 

    private function generateLoginFormHTML($message) {
		return '
		
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . $this->rememberUsername() . '" />
					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />
					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
			<p>Currently ' . $this->database->getAmountOfUsers() . ' registered users.</p>
		';
	}

	private function generateLogoutButtonHTML($message) {
		return '
			<form method="post" >
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}

	private function generateEditUsernameHTML($message) {
		return '
		<h1>Change your username</h1>
			<form method="post">
				<p id="' . self::$messageId . '">' . $message . '</p>
				<input type="text" id="' . self::$newName . '" name="' . self::$newName . '" placeholder="Enter new name" />
				<input type="submit" name="' . self::$editName . '" value="Edit name"/>
			</form>

			<br>

			<form action="index.php" method="post" >
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
}
