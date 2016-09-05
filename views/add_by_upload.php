<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?>Add my image<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
<div class="main-content">
    <ul class="layer-list" id="layer-list">
        <?php for($i = 1; $i <= 10; $i++): ?>
            <?php if (file_exists(ROOT.'web/img/frame/frame'.$i.'.png')): ?>
            <li><img src="<?=$app['basepath']?>img/frame/frame<?=$i?>.png" alt="" height="100px"></li>
            <?php endif; ?>
        <?php endfor; ?>
    </ul>
    <div class="big-upload">
        <div id="preview"></div>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="upload-file">
                <div class="upload-zone">
                    <p><i class="fa fa-upload"></i> Drop your image here or click to upload</p>
                </div>
                <input type="file" accept="image/*" name="fileToUpload" id="fileToUpload">
            </div>
            <input type="hidden" name="frame" id="frame-form">
            <input type="hidden" name="send">
            <button class="btn disabled" type="submit" disabled="disabled" id="save">
                <i class="fa fa-save"></i> Save and Upload
            </button>
        </form>
        <button class="btn" type="button" id="reset">
            <i class="fa fa-repeat"></i> Clear & Retry
        </button>
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

<?php $app['view']->block_start('js') ?>
<script type="application/javascript" src="<?= $app['basepath'] ?>js/upload.js"></script>
<?php $app['view']->block_end() ?>