<?php

namespace Model;

class UserStorage {
    private static $sessionId = 'UserStorage::User';

    public function saveUser(User $user): void {
        $_SESSION[self::$sessionId] = $user;
    }

    public function loadUser(): User {
        if (isset($_SESSION[self::$sessionId])) {
            return $_SESSION[self::$sessionId];
        }

        return new User('', '');
    }

    public function test() {
        $this->user->validateUser();
    }
}
