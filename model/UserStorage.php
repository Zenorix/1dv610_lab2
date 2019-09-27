<?php

namespace Model;

class UserStorage {
    private static $userId = 'UserStorage::User';

    public function saveUser(User $user): void {
        $_SESSION[self::$userId] = $user;
    }

    public function loadUser(): User {
        if (isset($_SESSION[self::$userId])) {
            return $_SESSION[self::$userId];
        }

        return new User(\Model\User::EMPTY_USERNAME, \Model\User::EMPTY_PASSWORD);
    }
}
