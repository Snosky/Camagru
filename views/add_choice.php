<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?>Add my image<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
<div class="main-content add-choice">
    <h1 class="text-center">Add my image</h1>
    <hr>
    <div class="left text-center">
        <h1>Upload an image</h1>
        <a href="<?= $app->url('upload-image') ?>" class=""><i class="fa fa-photo"></i></a>
    </div>
    <div class="right text-center">
        <h1>Use my webcam</h1>
        <a href="<?= $app->url('upload-live') ?>"><i class="fa fa-camera"></i></a>
    </div>
</div>

<div class="sidebar">
    <h1 class="text-center">Last upload</h1>
    <?php if ($last_images): ?>
        <?php foreach ($last_images as $last_image): ?>
            <a href="<?= $app->url('view_image', array('image_id' => $last_image->getId())) ?>">
                <img src="<?= $last_image->getPath() ?>" alt="">
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        Nothing :(
    <?php endif ;?>
</div>
<?php $app['view']->block_end() ?>