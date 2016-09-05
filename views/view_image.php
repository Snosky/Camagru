<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?>Image<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
<div class="image-view">
    <h5>
        Upload by <a href="<?= $app->url('view_user', array('user_id' => $image->getUser()->getId())) ?>"><?= $image->getUser()->getUsername() ?></a> - <?= $image->getCreated() ?>
        <a href="<?= $app->url('delete_image', array('image_id' => $image->getId())) ?>" onclick="return confirm('Are you sure ?')">
            <i class="fa fa-trash"></i> Delete my image !
        </a>
    </h5>
    <img src="<?= $image->getPath() ?>" alt="" class="main-image">

    <div class="vote">
        <div class="vote-buttons">
            <!--href="<?= $app->url('like', array('image_id' => $image->getId())) ?>"-->
            <a href="<?= $app->url('like', array('image_id' => $image->getId())) ?>" id="vote-up" class="<?= ($like_user) ? 'liked' : ''?>">
                <i class="fa fa-up"></i> <?= $like_count ?> like<?php if ($like_count > 1): ?>s<?php endif; ?>
            </a>
        </div>
    </div>

    <div class="comments">
        <h2><?= count($comments) ?> Comments : </h2>
        <div class="your-comment">
            <form action="<?= $app->url('add_comment') ?>" method="post">
                <textarea name="content" id="yourComment" cols="30" rows="10" placeholder="Your comment..."></textarea>
                <input type="hidden" name="image_id" value="<?= $image->getId() ?>">
                <input type="hidden" name="send">
                <button type="submit">Send comment</button>
            </form>
        </div>

        <?php
            $v = 'odd';
            foreach ($comments as $comment): ?>
            <p class="<?= $v ?>" id="comment-<?= $comment->getId() ?>">
                <span><?= $comment->getUser()->getUsername() ?> >></span> <?= $comment->getContent() ?>
            </p>
        <?php
            $v = ($v == 'odd') ? 'event' : 'odd';
            endforeach; ?>
    </div>
</div>
<?php $app['view']->block_end() ?>