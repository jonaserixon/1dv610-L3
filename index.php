<?php

session_start();

require_once('controller/MainController.php');
require_once('model/LoginModel.php');
require_once('model/RegisterModel.php');
require_once('view/LayoutView.php');
require_once('view/LoginView.php');
require_once('view/RegisterView.php');

$controller = new controller\MainController();
