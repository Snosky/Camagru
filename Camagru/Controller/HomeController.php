<?php
namespace Camagru\Controller;

use Camagru\Domain\User;
use Core\Application;

class HomeController
{
    public function indexAction(Application $app)
    {
        return $this->myHomePage(1, $app);
    }

    public function indexPageAction($page, Application $app)
    {
        return $this->myHomePage($page, $app);
    }

    private function myHomePage($page, Application $app)
    {
        $images = $app['dao.image']->findAllPagination($page - 1);
        $imageCount = $app['dao.image']->count();

        $nbPage = ceil($imageCount / 9);

        return $app->render('home.php', array(
            'images'        => $images,
            'imageCount'    => $imageCount,
            'actualPage'    => $page,
            'nbPage'        => $nbPage
        ));
    }
}