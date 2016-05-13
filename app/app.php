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

/* END DAO */