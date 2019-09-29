<?php

namespace Model;

class User {
    const EMPTY_PASSWORD = '';
    const EMPTY_USERNAME = '';

    private $username;
    private $hashPassword;

    public function __construct($username, $hashPassword) {
        $this->username = $username;
        $this->hashPassword = $hashPassword;
    }

    public function validateUser(): bool {
        $isValidUsername = 'Admin' == $this->username;
        $isValidPassword = password_verify('Password', $this->hashPassword);

        return $isValidUsername && $isValidPassword;
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
