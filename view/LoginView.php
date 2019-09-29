<?php

namespace View;

use Exception;

require_once 'view/DateTimeView.php';
require_once 'model/UserMessages.php';
require_once 'model/User.php';

class LoginView {
    private static $cookieExpiry = (60 * 60 * 24 * 30); // seconds * minutes * hours * days = 30 days
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
        $this->user = new \Model\User(\Model\User::EMPTY_USERNAME, password_hash(\Model\User::EMPTY_PASSWORD, PASSWORD_DEFAULT));
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

    /**
     * Generate HTML code for the title.
     *
     * @param mixed $isLoggedIn
     *
     * @return string,
     */
    private function generateTitleHTML(): string {
        if ($this->user->validateUser() && $this->user->validateSession()) {
            return '<h2>Logged in</h2>';
        }

        return '<h2>Not logged in</h2>';
    }

    /**
     * Generate HTML code for the body.
     *
     * @return string,
     */
    private function generateBodyHTML(): string {
        $response = '';
        if ($this->user->validateUser() && $this->user->validateSession()) {
            $response .= $this->generateLogoutHTML();
        } else {
            $response .= $this->generateLoginHTML();
        }

        return $response;
    }

    /**
     * Generate HTML code for the logout.
     *
     * @return string
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
     * Generate HTML code for the login
     *
     * @return string ,
     */
    private function generateLoginHTML(): string {
        return '
			<form method="post" >
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="'.self::$messageId.'">'.$this->getLoginMessage().'</p>
					
					<label for="'.self::$usernameId.'">Username :</label>
					<input type="text" id="'.self::$usernameId.'" name="'.self::$usernameId.'" value="'.$this->user->getUsername().'" />

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

    private function getLoginMessage(): string {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if ($this->wasLogoutPressed()) {
                return $this->local::LOGOUT;
            }

            if ($this->wasLoginPressed()) {
                if (!$this->user->hasUsername()) {
                    return $this->local::MISSING_USERNAME;
                }
                if (!$this->user->hasPassword()) {
                    return $this->local::MISSING_PASSWORD;
                }
                if (!$this->user->validateUser()) {
                    return $this->local::INVALD_LOGIN;
                }
                if ($this->hasCookie() && $this->user->validateUser()) {
                    return $this->local::COOKIE_LOGIN;
                }
            }
        } elseif ($this->hasCookie()) {
            if (!$this->getCookieUser()->validateUser()) {
                return $this->local::BAD_COOKIE;
            }

            throw new Exception('Should not be here, valid cookie at login');

            return 'Nope';
        }

        return $this->local::EMPTY_MESSAGE;
    }

    private function getLogoutMessage(): string {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if ($this->wasLoginPressed()) {
                if ($this->isKeepLogin()) {
                    return $this->local::REMEMBER_LOGIN;
                }

                return $this->local::LOGIN;
            }
        } elseif ($this->hasCookie()) {
            if ($this->hasSession()) {
                return $this->local::EMPTY_MESSAGE;
            }

            return $this->local::COOKIE_LOGIN;
        }

        return $this->local::EMPTY_MESSAGE;
    }

    private function hasSession(): bool {
        return isset($_COOKIE['PHPSESSID']);
    }

    /**
     * User related functions
     */

    public function setUser(\Model\User $user) {
        $this->user = $user;
    }

    public function getUsername(): string {
        if (isset($_POST[self::$usernameId])) {
            return $this->getPostInput(self::$usernameId);
        }
        if (isset($_SESSION[self::$usernameId]) && \Model\User::EMPTY_USERNAME != $_SESSION[self::$usernameId]) {
            return $_SESSION[self::$usernameId];
        }

        return \Model\User::EMPTY_USERNAME;
    }

    public function saveUsername(): void {
        $_SESSION[self::$usernameId] = $this->getPostInput(self::$usernameId);
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
        if (\Model\User::EMPTY_USERNAME == $_POST[self::$usernameId]) {
            return false;
        }

        return isset($_POST[self::$usernameId]);
    }

    public function wasPasswordEntered(): bool {
        if (\Model\User::EMPTY_PASSWORD == $_POST[self::$passwordId]) {
            return false;
        }

        return isset($_POST[self::$passwordId]);
    }

    /**
     * View related functions
     */

    public function getView(): string {
        if ($this->wasLoginPressed()) {
            return $this->getPostInput(self::$loginId);
        }
        if ($this->wasLogoutPressed()) {
            return $this->getPostInput(self::$logoutId);
        }

        return '';
    }

    public function isKeepLogin(): bool {
        return isset($_POST[self::$keepId]);
    }

    /**
     * Cookies related functions
     */

    public function hasCookie(): bool {
        return isset($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
    }

    public function getCookieUser(): \Model\User {
        return new \Model\User($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
    }

    public function setCookieUser(\Model\User $user): void {
        setcookie(self::$cookieName, $user->getUsername(), time() + self::$cookieExpiry);
        setcookie(self::$cookiePassword, $user->getHashPassword(), time() + self::$cookieExpiry);
    }

    public function removeCookieUser(): void {
        // unset($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);

        setcookie(self::$cookieName, '', time() - self::$cookieExpiry);
        setcookie(self::$cookiePassword, '', time() - self::$cookieExpiry);
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


}
