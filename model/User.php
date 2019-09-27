<?php

namespace Model;

class User {
    const EMPTY_PASSWORD = '';
    const EMPTY_USERNAME = '';

    private $username;
    private $hashPassword;

    public function __construct($username, $password) {
        $this->username = $username;
        if (self::EMPTY_PASSWORD != $password) {
            $this->hashPassword = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $this->hashPassword = self::EMPTY_PASSWORD;
        }
    }

    public function validateUser(): bool {
        return 'Admin' == $this->username && password_verify('Password', $this->hashPassword);
    }

    public function hasPassword(): bool {
        return self::EMPTY_PASSWORD != $this->hashPassword;
    }

    public function hasUsername(): bool {
        return self::EMPTY_USERNAME != $this->username;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getHashPassword(): string {
        return $this->hashPassword;
    }
}
