<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Camagru | <?php $app['view']->block_output('title') ?></title>
    <link rel="stylesheet" href="<?= $app['basepath'] ?>css/reset.css">
    <link rel="stylesheet" href="<?= $app['basepath'] ?>css/style.css">
</head>
<body>
    <header class="navbar">
        <div class="wrapper">
            <div class="brand"><a href="<?= $app->url('home') ?>">Camagru</a></div>
            <ul class="nav">
                <li><a href="<?= $app->url('home') ?>">Home</a></li>
                <li><a href="<?= $app->url('gallery') ?>">Gallery</a></li>
                <li><a href="<?= $app->url('upload') ?>">Upload my image</a></li>
            </ul>
            <ul class="nav nav-right">
                <li><a href="<?= $app->url('login') ?>">Login</a></li>
                <li><a href="<?= $app->url('register') ?>">Register</a></li>
            </ul>
        </div>
    </header>

    <div class="wrapper content">
        <?php $app['view']->block_output('content') ?>
    </div>
</body>
</html>