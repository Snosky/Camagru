<?php
namespace Camagru\Controller;

use Camagru\Domain\Like;
use Core\Application;

class LikeController
{
    public function indexAction($image_id, Application $app)
    {
        $image_id = (int)$image_id;
        $image = NULL;
        if (!$app->isConnected())
        {
            $app['flashbag']->add('error', 'Please connect you to like an image.');
        }
        else if ($image_id)
        {
            if (!($image = $app['dao.image']->find($image_id)))
            {
                $app['flashbag']->add('error', 'Error : image not found.');
            }
            else
            {

                $like = new Like();
                $like->setImage($image);
                $like->setUser($app->user());

                if ($app['dao.like']->exist($like))
                {
                    $app['dao.like']->delete($like);
                    $app['flashbag']->add('success', 'Your like has been removed.');
                }
                else
                {
                    $app['dao.like']->save($like);
                    $app['flashbag']->add('success', 'You like has been added.');
                }
            }
        }
        else
        {
            $app['flashbag']->add('error', 'Your like can\'t be saved due to server error. Please retry.');
        }

        if ($image)
            return $app->redirect($app->url('view_image', array('image_id' => $image->getId())));
        return $app->redirect($app->url('view_image', array('image_id' => $image_id)));
    }
}