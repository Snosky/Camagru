<?php $app['view']->extend('layout.php'); ?>

<?php $app['view']->block_start('title') ?>Home<?php $app['view']->block_end(); ?>

<?php $app['view']->block_start('content') ?>

<pre>
    <?php print_r($_SERVER); ?>
</pre>

Salut <?= $user->getUsername(); ?><br>

<?php foreach ($users as $user): ?>
    <?= $user->getUsername();?> <br>
<?php endforeach; ?>
<?php $app['view']->block_end(); ?>
