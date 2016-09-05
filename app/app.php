<?php
/* @var \Core\Application $app */

/* DB */
/*
try {
    $pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $app['db'] = $pdo;
}
catch (PDOException $e) {
    echo 'Echec lors de la connexion : '. $e->getMessage();
}
*/
$app['db'] = new \Core\MyPDO($DB_DSN, $DB_USER, $DB_PASSWORD);
/* End DB */

$app['flashbag'] = new \Core\Flashbag();

/* DAO */
$app['dao.user'] = new \Camagru\DAO\UserDAO($app['db']);
$app['dao.image'] = (function () use ($app){
    $dao = new \Camagru\DAO\ImageDAO($app['db']);
    $dao->setUserDAO($app['dao.user']);
    return $dao;
})();
$app['dao.comment'] = (function () use ($app){
    $dao = new \Camagru\DAO\CommentDAO($app['db']);
    $dao->setUserDAO($app['dao.user']);
    $dao->setImageDAO($app['dao.image']);
    return $dao;
})();
$app['dao.like'] = (function () use ($app){
    $dao = new \Camagru\DAO\LikeDAO($app['db']);
    $dao->setUserDAO($app['dao.user']);
    $dao->setImageDAO($app['dao.image']);
    return $dao;
})();

/* END DAO */