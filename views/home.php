<?php $app['view']->extend('layout.php'); ?>

<?php $app['view']->block_start('title') ?>Home<?php $app['view']->block_end(); ?>

<?php $app['view']->block_start('content') ?>

    Hello
<?php if ($app->isConnected()): ?>
    <?php echo $app->user()->getUsername(); ?>
<?php else: ?>
    Visitor.
<?php endif; ?>
<?php $app['view']->block_end(); ?>