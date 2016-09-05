<?php
/* @var \Core\Application $app */
$app->get('/', 'Camagru\Controller\HomeController::indexAction')->bind('home');
$app->get('/page-{page}', 'Camagru\Controller\HomeController::indexPageAction')->bind('home-page');

$app->get('/gallery', 'Camagru\Controller\GalleryController::indexAction')->bind('gallery');

$app->get('/my-image', 'Camagru\Controller\ImageController::indexAction')->bind('upload');
$app->match('/my-image/upload', 'Camagru\Controller\ImageController::uploadAction')->bind('upload-image');
$app->match('/my-image/camera', 'Camagru\Controller\ImageController::liveAction')->bind('upload-live');

$app->match('/image-{image_id}', 'Camagru\Controller\ImageController::viewAction')->bind('view_image');

$app->match('/login', 'Camagru\Controller\UserController::loginAction')->bind('login');
$app->get('/logout', 'Camagru\Controller\UserController::logoutAction')->bind('logout');
$app->match('/reset', 'Camagru\Controller\UserController::resetAction')->bind('reset-password');
$app->get('/resend/{user_id}', 'Camagru\Controller\UserController::sendActivationAction')->bind('resend-activation');
$app->get('/activate/{token}', 'Camagru\Controller\UserController::activateAction')->bind('activate');
$app->get('/my-profil', 'Camagru\Controller\UserController::myProfilAction')->bind('my-profil');
$app->get('/user/{user_id}', 'Camagru\Controller\UserController::viewProfilAction')->bind('view_user');

$app->match('/register', 'Camagru\Controller\UserController::registerAction')->bind('register');

$app->post('/add-comment', 'Camagru\Controller\CommentController::addAction')->bind('add_comment');

$app->get('/like/{image_id}', 'Camagru\Controller\LikeController::indexAction')->bind('like');

$app->get('/delete/{image_id}', 'Camagru\Controller\ImageController::deleteAction')->bind('delete_image');