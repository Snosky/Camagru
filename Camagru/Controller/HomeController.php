<?php
namespace Camagru\Controller;

use Camagru\Domain\User;
use Core\Application;

class HomeController
{
    public function indexAction(Application $app)
    {
        /*$user = new User();
        $user->setUsername('Test Insert');
        $app['dao.user']->save($user);*/


        $app['dao.user']->delete(2);

        $user = $app['dao.user']->find(1);
        $users = $app['dao.user']->findAll();

        return $app->render('home.php', array(
            'user'  => $user,
            'users' => $users
        ));
    }
}