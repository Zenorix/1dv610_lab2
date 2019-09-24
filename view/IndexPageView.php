<?php

namespace View;

require_once 'view/HeadView.php';
require_once 'view/LoginView.php';

class IndexPageView {
    private $head;
    private $body;

    public function __construct() {
        $this->head = new HeadView();
        $this->body = new LoginView();
    }

    public function draw(\Model\User $user): void {
        $this->getBody()->setUser($user);
        echo $this->getHead()->generateHTML().
        $this->getBody()->generateHTML();
    }

    public function getBody(): LoginView {
        return $this->body;
    }

    public function getHead(): HeadView {
        return $this->head;
    }
}
