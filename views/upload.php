<?php $app['view']->extend('layout.php') ?>

<?php $app['view']->block_start('title') ?>Upload my image<? $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
    <div class="cam-preview">
        <div class="images" id="camImagesList">
            <img src="http://cliparts.co/cliparts/6Ty/5rz/6Ty5rz6ac.png" alt="" class="border">
            <img src="http://pngimg.com/upload/cat_PNG106.png" alt="" class="elem">
        </div>
        <video autoplay id="videoElement" width="100%" height="100%"></video>
    </div>

    <button class="btn btn-default" id="snap">Take a photo</button>

    <canvas id="canvas" width="640" height="480"></canvas>
<?php $app['view']->block_end() ?>