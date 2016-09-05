<?php
namespace Camagru\Controller;

use Camagru\Domain\Image;
use Camagru\Domain\Like;
use Core\Application;

class ImageController
{
    public function indexAction(Application $app)
    {
        if (!$app->isConnected())
        {
            $app['flashbag']->add('error', 'You had to be logged to access this page.');
            return $app->redirect($app->url('login'));
        }

        $last_images = $app['dao.image']->findLast(4);

        return  $app->render('add_choice.php', array(
            'last_images'   => $last_images
        ));
        //return $app->render('upload.php');
    }

    public function uploadAction(Application $app)
    {
        if (!$app->isConnected())
        {
            $app['flashbag']->add('error', 'You had to be logged to access this page.');
            return $app->redirect($app->url('login'));
        }

        if (isset($_POST['send']) && isset($_FILES))
        {
            $form_errors = FALSE;

            if (isset($_POST['frame']) || !empty($_POST['frame']))
            {
                $src = str_replace($_SERVER['HTTP_ORIGIN'], ROOT.'web', $_POST['frame']);
                if (!file_exists($src))
                {
                    $app['flashbag']->add('error', 'Please select a valid frame.');
                    $form_errors = TRUE;
                }
            }

            if (!isset($_FILES['fileToUpload']) || empty($_FILES['fileToUpload']) || $_FILES['fileToUpload']['error'])
            {
                $app['flashbag']->add('error', 'Please upload an image.');
                $form_errors = TRUE;
            }
            else if (strpos('image/', $_FILES['fileToUpload']['type']) && !strpos('image/gif', $_FILES['fileToUpload']['type']))
            {
                $app['flashbag']->add('error', 'Please upload a valid image.');
                $form_errors = TRUE;
            }
            else if ($_FILES['fileToUpload']['error'] !== 0)
            {
                $app['flashbag']->add('error', 'Error on image upload. Please retry.');
                $form_errors = TRUE;
            }

            if (!$form_errors)
            {
                $upload = imagecreatefromstring(file_get_contents($_FILES['fileToUpload']['tmp_name']));
                list($width, $height) = getimagesize($_FILES['fileToUpload']['tmp_name']);
                $frame_size = getimagesize($_POST['frame']);

                $frame = imagecreatefrompng($_POST['frame']);
                $newframe = imagecreatetruecolor($width, $height);

                imagealphablending($newframe, false);
                imagesavealpha($newframe, true );

                imagecolortransparent($newframe);

                $error = imagecopyresampled($newframe, $frame, 0, 0, 0, 0, $width, $height, $frame_size[0], $frame_size[1]);

                if ($error)
                    $error = imagecopyresampled($upload, $newframe, 0, 0, 0, 0, $width, $height, $width, $height);

                imagedestroy($frame);
                imagedestroy($newframe);

                if ($error)
                {
                    $image = new Image();
                    $image->setUser($app->user());
                    $app['dao.image']->save($image);
                }

                if (!$error || !$image->getId())
                {
                    $app['flashbag']->add('error', 'Error on image saving. Please retry.');
                    imagedestroy($upload);
                }
                else
                {
                    if (imagepng($upload, $image->getRealPath()) === FALSE)
                    {
                        $app['flashbag']->add('error', 'Error on image saving. Please retry.');
                        $app['dao.image']->delete($image->getId());
                        imagedestroy($upload);
                        return $app->redirect($app->url('home'));
                    }
                    $app['flashbag']->add('success', 'Your image is now online ! You can see it <a href="'.WEBROOT.'image-'.$image->getId().'">here</a>');
                    imagedestroy($upload);
                    return $app->redirect($app->url('view_image', array('image_id' => $image->getId())));
                }
            }
        }

        $last_images = $app['dao.image']->findLast(4);

        return $app->render('add_by_upload.php', array(
            'last_images'   => $last_images,
        ));
    }

    public function liveAction(Application $app)
    {
        if (!$app->isConnected())
        {
            $app['flashbag']->add('error', 'You had to be logged to access this page.');
            return $app->redirect($app->url('login'));
        }

        if (isset($_POST['send']))
        {
            $form_errors = FALSE;

            if (isset($_POST['frame']) || !empty($_POST['frame']))
            {
                $src = str_replace($_SERVER['HTTP_ORIGIN'], ROOT.'web', $_POST['frame']);
                if (!file_exists($src))
                {
                    $app['flashbag']->add('error', 'Please select a valid frame.');
                    $form_errors = TRUE;
                }
            }

            if (!isset($_POST['webcamImage']) || empty($_POST['webcamImage']))
            {
                $app['flashbag']->add('error', 'Error on webcam snap save.');
                $form_errors = TRUE;
            }

            if (!$form_errors)
            {
                $base64 = explode(',', $_POST['webcamImage'])[1];
                $webcam  = imagecreatefromstring(base64_decode($base64));

                $frame_size = getimagesize($_POST['frame']);
                $frame = imagecreatefrompng($_POST['frame']);
                $newframe = imagecreatetruecolor(640, 480);

                imagealphablending($newframe, false);
                imagesavealpha($newframe, true );

                imagecolortransparent($newframe);

                $error = imagecopyresampled($newframe, $frame, 0, 0, 0, 0, 640, 480, $frame_size[0], $frame_size[1]);

                if ($error)
                    $error = imagecopyresampled($webcam, $newframe, 0, 0, 0, 0, 640, 480, 640, 480);

                imagedestroy($frame);
                imagedestroy($newframe);

                if ($error)
                {
                    $image = new Image();
                    $image->setUser($app->user());
                    $app['dao.image']->save($image);
                }

                if (!$error || !$image->getId())
                {
                    $app['flashbag']->add('error', 'Error on image saving. Please retry.');
                    imagedestroy($webcam);
                }
                else
                {
                    if (imagepng($webcam, $image->getRealPath()) === FALSE)
                    {
                        $app['flashbag']->add('error', 'Error on image saving. Please retry.');
                        $app['dao.image']->delete($image->getId());
                        imagedestroy($webcam);
                        return $app->redirect($app->url('home'));
                    }
                    $app['flashbag']->add('success', 'Your image is now online ! You can see it <a href="'.WEBROOT.'image-'.$image->getId().'">here</a>');
                    imagedestroy($webcam);
                    return $app->redirect($app->url('view_image', array('image_id' => $image->getId())));

                }
            }
        }

        $last_images = $app['dao.image']->findLast(4);

        return $app->render('add_by_camera.php', array(
            'last_images'   => $last_images,
        ));
    }

    public function viewAction($image_id, Application $app)
    {
        $image_id = (int)$image_id;

        $image = $app['dao.image']->find($image_id);
        if (!$image)
        {
            echo '404';
            die();
        }

        $comments = $app['dao.comment']->findByImage($image_id);
        $like_count = $app['dao.like']->countByImage($image_id);

        $like = new Like();
        $like->setImage($image);
        $like->setUser($app->user());
        $like_user = $app['dao.like']->exist($like);

        return $app->render('view_image.php', array(
            'image' => $image,
            'comments'  => $comments,
            'like_count'    => $like_count,
            'like_user'     => $like_user
        ));
    }

    public function deleteAction($image_id, Application $app)
    {
        if (!$app->isConnected())
            $app->redirectToLast();

        $image = $app['dao.image']->find($image_id);

        if ($image && $image->getUser()->getId() == $app->user()->getId());
        {
            $app['dao.image']->delete($image_id);
            if (file_exists($image->getRealPath()))
                unlink($image->getRealPath());
            $app['flashbag']->add('success', 'Your image has been successfully deleted.');
        }
        return $app->redirect($app->url('home'));
    }
}