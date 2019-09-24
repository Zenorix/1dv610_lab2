<?php

namespace Model;

class User {
    private $username;
    private $password;

    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }

    public function validateUser(): bool {
        return 'Admin' == $this->username && 'Password' == $this->password;
    }

    public function hasPassword(): bool {
        return '' != $this->password;
    }

    public function hasUsername(): bool {
        return '' != $this->username;
    }

    public function getUsername(): string {
        return $this->username;
    }
}
