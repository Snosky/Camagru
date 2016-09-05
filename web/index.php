<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('ROOT', str_replace('web/index.php', '', $_SERVER['SCRIPT_FILENAME']));
define('WEBROOT', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
define('DS', DIRECTORY_SEPARATOR);



include '../autoload.php';

$app = new \Core\Application();

include '../config/database.php';
include '../app/app.php';
include '../app/routes.php';

$app->run();