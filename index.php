<?php

require_once('controller/MainController.php');

require_once('model/LoginModel.php');
require_once('model/RegisterModel.php');
require_once('model/DatabaseModel.php');
require_once('model/SessionModel.php');


require_once('view/LayoutView.php');
require_once('view/LoginView.php');
require_once('view/RegisterView.php');

// error_reporting(E_ALL);
// ini_set('display_errors', 'On');

session_start();

$controller = new \controller\MainController();
$controller->start();
