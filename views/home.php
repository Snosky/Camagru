<?php $app['view']->extend('layout.php'); ?>

<?php $app['view']->block_start('title') ?>Home<?php $app['view']->block_end(); ?>

<?php $app['view']->block_start('content') ?>
<div class="home-image-list">
<?php if ($images): ?>
    <h2>Last Uploads</h2>
    <div class="images">
    <?php foreach ($images as $image): ?>
        <a href="<?= $app->url('view_image', array('image_id' => $image->getId())) ?>">
            <img src="<?= $image->getPath(); ?>" alt="">
        </a>
    <?php endforeach; ?>
    </div>

    <?php if ($nbPage > 1): ?>
    <div class="pagination">
        <?php if ($actualPage != 1): ?>
        <a href="<?= $app->url('home-page', array('page' => 1)) ?>" title="First page"><i class="fa fa-first"></i></a>
        <a href="<?= $app->url('home-page', array('page' => $actualPage - 1)) ?>" title="Previous page"><i class="fa fa-previous"></i></a>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $nbPage; $i++): ?>
            <a href="<?= $app->url('home-page', array('page' => $i)) ?>" class="<?php if ($i == $actualPage): ?>select<?php endif ?>"><?= $i ?></a>
        <?php endfor; ?>
        <?php if ($actualPage != $nbPage): ?>
        <a href="<?= $app->url('home-page', array('page' => $actualPage + 1)) ?>" title="Next page"><i class="fa fa-next"></i></a>
        <a href="<?= $app->url('home-page', array('page' => $nbPage)) ?>" title="Last page"><i class="fa fa-last"></i></a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
<?php else: ?>
    <h1 class="text-center">No image found. Upload the first image ever <a href="<?= $app->url('upload') ?>">here</a> !</h1>
<?php endif; ?>
</div>
<?php $app['view']->block_end(); ?>