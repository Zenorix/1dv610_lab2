<?php

namespace Controller;

class LoginController {
    private static $currentViewId = 'LoginController::currentView';

    private $view;
    private $viewBody;
    private $storage;

    public function __construct(\View\IndexPageView $view, \Model\UserStorage $storage) {
        $this->view = $view;
        $this->viewBody = $this->view->getBody();
        $this->storage = $storage;
    }

    public function read(): void {
        $this->onCookie();
        $this->onPost();
        $this->onLogin();
        $this->onLogout();
    }

    private function onCookie(): void {
        if ($this->viewBody->hasCookie()) {
            if ($this->viewBody->getCookieUser()->validateUser()) {
                if ($this->storage->loadUser()->hasUsername()) {
                    // Nothing for now
                } else {
                    $this->storage->saveUser($this->viewBody->getCookieUser());
                    $this->setCurrentView('logout');
                }
            } else {
                $this->viewBody->removeCookieUser();
            }
        }
    }

    private function onPost(): void {
        // If post has been sent to us, we are making sure it is not a duplicate (AKA Refresh using F5)
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if ('' !== $this->getCurrentView()) {
                if ($this->viewBody->getView() !== $this->getCurrentView()) {
                    unset($_POST); // We "ignore" everything in post
                }
            } else {
                $this->setCurrentView($this->viewBody->getView());
            }
        }
    }

    private function onLogin(): void {
        if ($this->viewBody->wasLoginPressed()) {
            $user = new \Model\User($this->viewBody->getUsername(), password_hash($this->viewBody->getPassword(), PASSWORD_DEFAULT));
            $this->storage->saveUser($user);
            if ($user->validateUser()) {
                if ($this->viewBody->isKeepLogin()) {
                    $this->viewBody->setCookieUser($user);
                }
                $this->setCurrentView('logout');
            }
        }
    }

    private function onLogout(): void {
        if ($this->viewBody->wasLogoutPressed()) {
            //TODO Logging out
            $this->user = new \Model\User(\Model\User::EMPTY_USERNAME, password_hash(\Model\User::EMPTY_PASSWORD, PASSWORD_DEFAULT));
            $this->storage->saveUser($this->user);
            $this->setCurrentView('login');
            if ($this->viewBody->hasCookie()) {
                $this->viewBody->removeCookieUser();
            }
        }
    }

    private function getCurrentView(): string {
        if (isset($_SESSION[self::$currentViewId])) {
            return $_SESSION[self::$currentViewId];
        }

        return '';
    }

    private function setCurrentView(string $viewString) {
        $_SESSION[self::$currentViewId] = $viewString;
    }
}
