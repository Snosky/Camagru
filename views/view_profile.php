<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?><?= $user->getUsername() ?>'s profil<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
<div class="profil">
    <h1 class="text-center"><?= $user->getUsername() ?>'s profil</h1>

    <div class="his-images">
        <h3 class="text-center">His images</h3>
        <?php if ($his_images): ?>
            <div class="images">
            <?php foreach ($his_images as $img): ?>
                <a href="<?= $app->url('view_image', array('image_id' => $img->getId())) ?>">
                    <img src="<?= $img->getPath() ?>" alt="">
                </a>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">This user haven't upload image</p>
        <?php endif; ?>
    </div>

    <div class="his-likes">
        <h3 class="text-center">His like</h3>
        <?php if ($his_likes): ?>
            <div class="images">
            <?php foreach ($his_likes as $like): ?>
                <a href="<?= $app->url('view_image', array('image_id' => $like->getImage()->getId())) ?>">
                    <img src="<?= $like->getImage()->getPath() ?>" alt="">
                </a>
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">This user haven't like image.</p>
        <?php endif; ?>
    </div>
</div>
<?php $app['view']->block_end() ?>