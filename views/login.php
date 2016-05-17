<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?>Login - Register<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
<div class="login-page">
<div class="left">
    <h1>Login</h1>
<form action="<?= $app->url('login') ?>" method="post">
    <div class="form-group">
        <label for="username">Username :</label>
        <input type="text" name="username" id="username" value="<?= $user->getUsername() ?>" required="required">
    </div>
    <div class="form-group">
        <label for="password">Password :</label>
        <input type="password" name="password" id="password" required="required">
    </div>
    <div class="form-group">
        <button class="btn" type="submit">
            Connexion
        </button>
    </div>
</form>
</div>
<div class="right">
    <h1>Register</h1>
<form action="<?= $app->url('register') ?>" method="post">
    <div class="form-group">
        <label for="username">Username :</label>
        <input type="text" name="username" id="username" value="<?= $user->getUsername() ?>" required="required">
    </div>
    <div class="form-group">
        <label for="email">E-mail :</label>
        <input type="email" name="email" id="email" value="<?= $user->getEmail() ?>" required="required">
    </div>
    <div class="form-group">
        <label for="password">Password :</label>
        <input type="password" name="password" id="password" required="required">
    </div>
    <div class="form-group">
        <label for="password2">Password Confirmation :</label>
        <input type="password" name="password2" id="password2" required="required">
    </div>
    <div class="form-group">
        <button class="btn" type="submit">
            Register
        </button>
    </div>
</form>
</div>
</div>
<?php $app['view']->block_end() ?>