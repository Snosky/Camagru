<?php
namespace Camagru\Controller;

use Core\Application;

class ImageController
{
    public function indexAction(Application $app)
    {
        return $app->render('upload.php');
    }
}