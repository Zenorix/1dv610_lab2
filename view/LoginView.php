<?php

class LoginView {
	private static $loginId = 'LoginView::Login';
	private static $logoutId = 'LoginView::Logout';
	private static $usernameId = 'LoginView::UserName';
	private static $passwordId = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keepId = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $storedUsername = "";
	private static $authenticUsername = "Admin";
	private static $authenticPassword = "Password";

	

	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
		$message = '';

		// Verifies if a username is entered and gives appropiate response if not
		if (!$this->getRequestUserName()){
			$message = "Username is missing";
		}
		// Verifies password entered, prompting user if not (Saving username)
		elseif (!$this->getRequestedPassword()){
			$message = "Password is missing";
			self::$storedUsername = $this->getRequestUserName();
		}
		//Verifies credentials are correct, if not we say one of them are wrong
		else{
			if (!($this->isUsernameAuthentic() && $this->isPasswordAuthentic())){
				$message = "Wrong name or password";
				self::$storedUsername = $this->getRequestUserName();
			}
			}
		
			$response = $this->generateLoginFormHTML($message);
		//$response .= $this->generateLogoutButtonHTML($message);
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logoutId . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$usernameId . '">Username :</label>
					<input type="text" id="' . self::$usernameId . '" name="' . self::$usernameId . '" value="' . self::$storedUsername . '" />

					<label for="' . self::$passwordId . '">Password :</label>
					<input type="password" id="' . self::$passwordId . '" name="' . self::$passwordId . '" />

					<label for="' . self::$keepId . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keepId . '" name="' . self::$keepId . '" />
					
					<input type="submit" name="' . self::$loginId . '" value="login" />
				</fieldset>
			</form>
		';
	}

	/**
	* Checks if the password provided is authentic
	* @return  boolean whenever it is authentic or not
	*/
	private function isPasswordAuthentic() {
		return $this->getRequestedPassword() == self::$authenticPassword;
	}

	/**
	* Checks if the password provided is authentic
	* @return  boolean whenever it is authentic or not
	*/
	private function isUsernameAuthentic() {
		return $this->getRequestUserName() == self::$authenticUsername;
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	private function getRequestUserName() {
		//RETURN REQUEST VARIABLE: USERNAME
		return $_POST[self::$name];
	}

	private function getRequestedPassword(){
		return $_POST[self::$password];
	}
	
}