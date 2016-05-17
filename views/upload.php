<?php $app['view']->extend('layout.php') ?>

<?php $app['view']->block_start('title') ?>Upload my image<? $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
    <div class="img-list" id="cadres">
        <ul>
            <li><img src="img/upload/22668_CADRE_MARRON.png" alt="" class="border"></li>
            <li><img src="img/upload/6ce70168.png" alt="" class="border"></li>
            <li><img src="img/upload/PJRygr0e4JpohTNuPi7gjdOcO6s.png" alt="" class="border"></li>
        </ul>
    </div>

    <div class="cam-preview">
        <div class="images" id="camImagesList">
            <!--<img src="img/upload/22668_CADRE_MARRON.png" alt="" class="border">-->
            <!--<img src="http://pngimg.com/upload/cat_PNG106.png" alt="" class="elem" style="left: -200px;">-->
            <!--<img src="http://pngimg.com/upload/hat_PNG5696.png" style="width: 200px;left:200px;">-->
        </div>
        <video autoplay id="videoElement" width="100%" height="100%"></video>
        <canvas id="canvas" width="640" height="480"></canvas>
    </div>

    <div class="img-list" id="emotes">
        <ul>
            <li><img src="img/upload/cat_PNG100.png"></li>
            <li><img src="img/upload/cat_PNG1631.png"></li>
            <li><img src="img/upload/Cat.png"></li>
        </ul>
    </div>

    <div class="btn-group">
        <button class="btn btn-default" id="snap">Take a photo</button>
        <button class="btn btn-default" id="retry">Retry</button>
        <button class="btn btn-default disabled" id="save">Save & Upload</button>
    </div>
<?php $app['view']->block_end() ?>