<?php

namespace Controller;

class LoginController {
    private static $postsId = 'LoginController::Posts';

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
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (!isset($_SESSION[self::$postsId])) {
                $_SESSION[self::$postsId][] = $this->viewBody->getPostId();
            } elseif (in_array($this->viewBody->getPostId(), $_SESSION[self::$postsId])) {
                unset($_POST); // We "ignore" everything in post
            } else {
                $_SESSION[self::$postsId][] = $this->viewBody->getPostId();
            }
        }
    }

    private function onLogin(): void {
        //$user;
        if ($this->viewBody->wasLoginPressed()) {
            $this->user = new \Model\User($this->viewBody->getUsername(), $this->viewBody->getPassword());
            $this->storage->saveUser($this->user);
        }
    }

    private function onLogout(): void {
        if ($this->viewBody->wasLogoutPressed()) {
            //TODO Logging out
            $this->user = new \Model\User('', '');
            $this->storage->saveUser($this->user);
        }
    }
}
