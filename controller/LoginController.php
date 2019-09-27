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
        // Only to check when POST from has been sent
        $this->onPost();
        $this->onLogin();
        $this->onLogout();
    }

    private function onPost(): void {
        // If post has been sent to us, we are making sure it is not a duplicate (AKA Refresh using F5)
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if ('' !== $this->getCurrentView()) {
                if ($this->viewBody->getView() !== $this->getCurrentView()) {
                unset($_POST); // We "ignore" everything in post
            } else {
                $this->setCurrentView($this->viewBody->getView());
            }
        }
    }

    private function onLogin(): void {
        //$user;
        if ($this->viewBody->wasLoginPressed()) {
            $user = new \Model\User($this->viewBody->getUsername(), $this->viewBody->getPassword());
            $this->storage->saveUser($user);
            if ($user->validateUser()) {
        }
                $this->setCurrentView('logout');
    }
        }
    }

    private function onLogout(): void {
        if ($this->viewBody->wasLogoutPressed()) {
            //TODO Logging out
            $this->user = new \Model\User('', '');
            $this->storage->saveUser($this->user);
            $this->setCurrentView('login');
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
