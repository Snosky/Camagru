<?php $app['view']->extend('layout.php') ?>
<?php $app['view']->block_start('title') ?>Update my password<?php $app['view']->block_end() ?>

<?php $app['view']->block_start('content') ?>
    <form action="" method="post">
        <div class="form-group">
            <label for="old_pwd">Old Password :</label>
            <input type="password" name="old_pwd" id="olw_pwd" required="required" tabindex="1">
        </div>
        <div class="form-group">
            <label for="new_pwd">Password :</label>
            <input type="password" name="new_pwd" id="new_pwd" required="required" tabindex="2">
        </div>
        <div class="form-group">
            <label for="new_pwd2">Password Confirmation :</label>
            <input type="password" name="new_pwd2" id="new_pwd2" required="required" tabindex="3">
        </div>
        <div class="form-group">
            <button class="btn" type="submit" tabindex="4">
                Update my password
            </button>
        </div>
        <input type="hidden" name="send">
    </form>
<?php $app['view']->block_end() ?>