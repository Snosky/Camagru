<?php
/* @var \Core\Application $app */
$app->get('/', 'Camagru\Controller\HomeController::indexAction')->bind('home');

$app->get('/gallery', 'Camagru\Controller\GalleryController::indexAction')->bind('gallery');

$app->match('/upload', 'Camagru\Controller\ImageController::indexAction')->bind('upload');

$app->match('/login', 'Camagru\Controller\UserController::loginAction')->bind('login');

$app->match('/register', 'Camagru\Controller\UserController::registerAction')->bind('register');