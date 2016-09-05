<?php
namespace Camagru\Controller;

use Camagru\Domain\Comment;
use Core\Application;
use Core\EMail;

class CommentController
{
    public function addAction(Application $app)
    {
        if (!$app->isConnected())
        {
            $app['flashbag']->add('error', 'You had to be logged to add a comment.');
            return $app->redirect($app->url('login'));
        }

        if (isset($_POST['send']))
        {
            $valid_form = TRUE;

            if (!isset($_POST['image_id']) || empty($_POST['image_id']))
            {
                $app['flashbag']->add('error', 'Error');
                $valid_form = FALSE;
            }
            else if (!($image = $app['dao.image']->find($_POST['image_id'])))
            {
                $app['flashbag']->add('error', 'Image doesn\'t exist.');
                $valid_form = FALSE;
            }

            if (!isset($_POST['content']) || empty($_POST['content']))
            {
                $app['flashbag']->add('error', 'Your comment is not valid.');
                $valid_form = FALSE;
            }

            if ($valid_form)
            {
                $comment =  new Comment();
                $comment->setUser($app->user());
                $comment->setImage($image);
                $comment->setContent($_POST['content']);

                $app['dao.comment']->save($comment);

                $app['flashbag']->add('success', 'Your comment had been added.');

                if ($comment->getUser()->getId() != $image->getUser()->getId())
                {
                    $mail = new EMail();
                    $mail->setTo($image->getUser()->getEmail());
                    $mail->setSubject('[Camagru]New comment');
                    $mail->setMessage($comment->getUser()->getUsername() . ' have write a comment on one of your image. <a href="' . $app->url('view_image', array('image_id' => $image->getId())) . '#comment-' . $comment->getId() . '">Click here to see it</a>');
                    $mail->send();
                }
            }
        }

        if (isset($_POST['image_id']) && $_POST['image_id'])
            return $app->redirect($app->url('view_image', array('image_id' => $_POST['image_id'])));
        return $app->redirect($app->url('home'));
    }
}