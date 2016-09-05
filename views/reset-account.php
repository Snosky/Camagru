<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?>Login - Forget Password<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
    <h1>Password reset</h1>
    <form action="<?= $app->url('reset-password') ?>" method="post">
        <div class="form-group">
            <label for="email">E-mail :</label>
            <input type="email" name="email" id="email" required="required">
        </div>
        <div class="form-group">
            <button class="btn" type="submit">
                Reset password
            </button>
        </div>
    </form>
<?php $app['view']->block_end() ?>
