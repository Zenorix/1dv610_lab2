<?php

require_once 'controller/Settings.php';
require_once 'controller/LoginController.php';

require_once 'model/UserStorage.php';

require_once 'view/IndexPageView.php';

$storage = new \Model\UserStorage();
$indexView = new \View\IndexPageView();
$loginController = new \Controller\LoginController($indexView, $storage);

session_start();

$loginController->read();
$indexView->draw($storage->loadUser());
