<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?>Add my image<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
<div class="main-content add-cam">
    <ul class="layer-list" id="layer-list">
        <?php for($i = 1; $i <= 10; $i++): ?>
            <?php if (file_exists(ROOT.'web/img/frame/frame'.$i.'.png')): ?>
                <li><img src="<?=$app['basepath']?>img/frame/frame<?=$i?>.png" alt="" height="100px"></li>
            <?php endif; ?>
        <?php endfor; ?>
    </ul>
    <p>Your webcam doesn't work ? <a href="<?= $app->url('upload-image') ?>">You can upload an image here.</a></p>
    <div id="preview">
        <div class="cam-preview">
            <video autoplay id="videoElement" width="100%" height="100%"></video>
            <canvas id="canvas" width="640px" height="480px"></canvas>
        </div>
    </div>
    <button class="btn" type="button" id="snap">
        <i class="fa fa-camera"></i> Take a snap
    </button>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="frame" id="frame-form">
        <input type="hidden" id="formWebcamImage" name="webcamImage">
        <input type="hidden" name="send">
        <button class="btn disabled" type="submit" id="save" disabled="disabled">
            <i class="fa fa-save"></i> Save and Upload
        </button>
    </form>
    <button class="btn" type="button" id="reset">
        <i class="fa fa-repeat"></i> Clear & Retry
    </button>
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

<?php $app['view']->block_start('js') ?>
<script type="application/javascript" src="<?= $app['basepath'] ?>js/webcam.js"></script>
<?php $app['view']->block_end() ?>