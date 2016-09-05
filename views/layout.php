<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Camagru | <?php $app['view']->block_output('title') ?></title>
    <link rel="stylesheet" href="<?= $app['basepath'] ?>css/reset.css">
    <link rel="stylesheet" href="<?= $app['basepath'] ?>css/style.css">
</head>
<body>
<div class="all">
    <header class="navbar">
        <div class="wrapper">
            <div class="brand"><a href="<?= $app->url('home') ?>">Camagru</a></div>
            <div class="nav-mobile-icon" id="mobile-nav">
                <i class="fa fa-nav"></i>
            </div>
            <div class="nav" id="main-nav">
                <ul>
                    <li><a href="<?= $app->url('home') ?>">Home</a></li>
                    <li><a href="<?= $app->url('upload') ?>">Upload my image</a></li>
                </ul>
                <ul class="nav-right">
                    <?php if (!$app->isConnected()): ?>
                    <li><a href="<?= $app->url('login') ?>">Login</a></li>
                    <li><a href="<?= $app->url('register') ?>">Register</a></li>
                    <?php else: ?>
                    <li><a href="<?= $app->url('my-profil') ?>">My Profil</a></li>
                    <li><a href="<?= $app->url('logout') ?>">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>
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

    <footer>
        © tpayen - Camagru
    </footer>
</div>
    <script src="<?= $app['basepath'] ?>js/global.js"></script>
    <?php $app['view']->block_output('js') ?>
</body>
</html>