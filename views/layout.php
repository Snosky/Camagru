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

    <?php if ($app['flashbag']->have('success')): ?>
    <div class="wrapper flash flash-success">
        <?php foreach ($app['flashbag']->get('success') as $msg): ?>
            <p><?= $msg; ?></p>
        <?php endforeach ?>
    </div>
    <?php endif; ?>

    <?php if ($app['flashbag']->have('error')): ?>
    <div class="wrapper flash flash-error">
        <?php foreach ($app['flashbag']->get('error') as $msg): ?>
            <p><?= $msg; ?></p>
        <?php endforeach ?>
    </div>
    <?php endif; ?>

    <div class="wrapper content">
        <?php $app['view']->block_output('content') ?>
    </div>

    <script src="<?= $app['basepath'] ?>js/webcam.js"></script>
</body>
</html>