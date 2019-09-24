<?php

namespace View;

require_once 'view/DateTimeView.php';
require_once 'model/UserMessages.php';
require_once 'model/User.php';

class LoginView {
    private static $loginId = 'LoginView::Login';
    private static $logoutId = 'LoginView::Logout';
    private static $usernameId = 'LoginView::UserName';
    private static $passwordId = 'LoginView::Password';
    private static $cookieName = 'LoginView::CookieName';
    private static $cookiePassword = 'LoginView::CookiePassword';
    private static $keepId = 'LoginView::KeepMeLoggedIn';
    private static $messageId = 'LoginView::Message';

    private $local;
    private $user;

    public function __construct() {
        $this->user = new \Model\User('', '');
        $this->local = new \Model\UserMessages('en');
    }

    /**
     * Generate HTML code for the view.
     *
     * @return string,
     */
    public function generateHTML(): string {
        return '
            <body>
            <h1>Assignment 2</h1>
            '.$this->generateTitleHTML().'
            
            <div class="container">
                '.$this->generateBodyHTML().'
                
                '.$this->generateTimeHTML().'
            </div>
            </body>
        </html>
        ';
    }

    public function setUser(\Model\User $user) {
        $this->user = $user;
    }

    public function getUsername(): string {
        if (isset($_POST[self::$usernameId])) {
            return $this->getPostInput(self::$usernameId);
        }
        if (isset($_SESSION[self::$usernameId]) && '' != $_SESSION[self::$usernameId]) {
            return $_SESSION[self::$usernameId];
        }

        return '';
    }

    public function saveUsername(): void {
        $_SESSION[self::$usernameId] = $this->getPostInput(self::$usernameId);
    }

    public function setMessage($text): void {
        $this->message = $text;
    }

    public function getPassword(): string {
        return $this->getPostInput(self::$passwordId);
    }

    public function wasLoginPressed(): bool {
        return isset($_POST[self::$loginId]);
    }

    public function wasLogoutPressed(): bool {
        return isset($_POST[self::$logoutId]);
    }

    public function wasUsernameEntered(): bool {
        if ('' == $_POST[self::$usernameId]) {
            return false;
        }

        return isset($_POST[self::$usernameId]);
    }

    public function wasPasswordEntered(): bool {
        if ('' == $_POST[self::$passwordId]) {
            return false;
        }

        return isset($_POST[self::$passwordId]);
    }

    /**
     * Generate HTML code for the title.
     *
     * @param mixed $isLoggedIn
     *
     * @return string, BUT writes to standard output!
     */
    private function generateTitleHTML(): string {
        if ($this->user->validateUser()) {
            return '<h2>Logged in</h2>';
        }

        return '<h2>Not logged in</h2>';
    }

    /**
     * Generate HTML code for the body.
     *
     * @return void, BUT writes to standard output!
     */
    private function generateBodyHTML(): string {
        $response = '';
        if ($this->user->validateUser()) {
            $response .= $this->generateLogoutHTML();
        } else {
            $response .= $this->generateLoginHTML();
        }

        return $response;
    }

    /**
     * Generate HTML code for the logout.
     *
     * @param $message, String output message
     *
     * @return void, BUT writes to standard output!
     */
    private function generateLogoutHTML(): string {
        return '
			<form  method="post" >
				<p id="'.self::$messageId.'">'.$this->getLogoutMessage().'</p>
				<input type="submit" name="'.self::$logoutId.'" value="logout"/>
			</form>
		';
    }

    /**
     * Generate HTML code for the login.
     *
     * @param $message, String output message
     *
     * @return void, BUT writes to standard output!
     */
    private function generateLoginHTML(): string {
        return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="'.self::$messageId.'">'.$this->getLoginMessage().'</p>
					
					<label for="'.self::$usernameId.'">Username :</label>
					<input type="text" id="'.self::$usernameId.'" name="'.self::$usernameId.'" value="'.$this->getUsername().'" />

					<label for="'.self::$passwordId.'">Password :</label>
					<input type="password" id="'.self::$passwordId.'" name="'.self::$passwordId.'" />

					<label for="'.self::$keepId.'">Keep me logged in  :</label>
					<input type="checkbox" id="'.self::$keepId.'" name="'.self::$keepId.'" />
					
					<input type="submit" name="'.self::$loginId.'" value="login" />
				</fieldset>
			</form>
		';
    }

    /**
     * Generate HTML code for the time.
     *
     * @return string, the data and time in a readable text
     */
    private function generateTimeHTML(): string {
        $timeView = new \View\DateTimeView();

        return $timeView->generateHTML();
    }

    /**
     * Returns string value of paramtered form in a safe way.
     *
     * @param string, String Id of the form element which we want value from
     * @param mixed $postData
     *
     * @return string, controlled input of POST
     */
    private function getPostInput($postData): string {
        // To remove unnecessary characters and blackslashes, as well prevent code injection
        // Source: https://www.w3schools.com/php/php_form_validation.asp
        if (isset($_POST[$postData])) {
            return htmlspecialchars(stripslashes(trim($_POST[$postData])));
        }

        return '';
    }

    private function getLoginMessage(): string {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (!$this->user->hasUsername()) {
                return $this->local::MISSING_USERNAME;
            }
            if (!$this->user->hasPassword()) {
                return $this->local::MISSING_PASSWORD;
            }
            if (!$this->user->validateUser()) {
                return $this->local::INVALD_LOGIN;
            }
        }

        return '';
    }

    private function getLogoutMessage(): string {
        // TODO

        return 'Welcome';
    }
}
