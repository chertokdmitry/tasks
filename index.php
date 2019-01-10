<?php

use Controllers\IndexController;

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require_once (__DIR__ . "/controllers/IndexController.php");
require_once (__DIR__ . "/models/DB.php");
require_once (__DIR__ . "/models/Tasks.php");
require_once (__DIR__ . "/models/config.php");
require_once (__DIR__ . "/views/View.php");

$controller = new IndexController();
$controller->index();
