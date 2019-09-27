<?php

namespace Model;

class UserMessages {
    const EMPTY_MESSAGE = '';
    const MISSING_USERNAME = 'Username is missing';
    const MISSING_PASSWORD = 'Password is missing';
    const INVALD_LOGIN = 'Wrong name or password';
    const LOGIN = 'Welcome';
    const LOGOUT = 'Bye bye!';
    const COOKIE_LOGIN = 'Welcome and you will be remembered';
    const COOKIE = 'Welcome back with cookie';
    const COOKIE_LOGOUT = '';
    // const EXAMPLE = "UsrMsg::";

    private $language;

    public function __construct($language) {
        $this->language = $language;
    }

    public function getLocalizedMesssage($messageConst): string {
        // TODO Implement localization
        throw new Exception('Not implemented');

        return '';
    }
}
