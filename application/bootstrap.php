<?php
session_start();
//session_destroy();
//unset($_SESSION);
require_once $_SERVER['DOCUMENT_ROOT'] .'/application/core/config/autoloader.php';
use application\core\config\route;
$objRoute = new Route();
$objRoute->Run(); //
