<?php
namespace Camagru\Controller;

use Camagru\Domain\User;
use Core\Application;

class UserController
{
    public function loginAction(Application $app)
    {
        $user = new User();

        if (isset($_POST) AND !empty($_POST))
        {
            $form_is_valid = TRUE;

            if (!isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password']))
                $form_is_valid = FALSE;
            else if (!$app['dao.user']->findByUsername($_POST['username']) || !$app['dao.user']->findByEmail($_POST['username']))
                $form_is_valid = FALSE;

            if ($form_is_valid)
            {
                $app['flashbag']->add('success', 'You\'re now connected.');
                return $app->redirectToLast();
            }
            else
            {
                $app['flashbag']->add('error', 'User not found.');
            }
        }

        return $app->render('login.php', array(
            'user'  => $user
        ));
    }

    public function registerAction(Application $app)
    {
        $user = new User();

        if (isset($_POST) AND !empty($_POST))
        {
            $form_is_valid = TRUE;

            /* TODO : Plus de verification (longueur, caractere interdit) */
            if (!isset($_POST['username']) || empty($_POST['username']))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Username');
            }

            if (!isset($_POST['email']) || empty($_POST['email']))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'email');
            }

            if (!isset($_POST['password']) || empty($_POST['password']) || $_POST['password'] != $_POST['password2'])
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'password');
            }

            if ($form_is_valid)
            {
                $user->setUsername($_POST['username']);
                $user->setEmail($_POST['email']);

                $salt = substr(sha1(time()), 3, 32);
                $password = $app->hash($_POST['password'], $salt);

                $user->setPassword($password);
                $user->setSalt($salt);
                $user->setRole('ROLE_USER');

                $app['dao.user']->save($user);
                $app['flashbag']->add('success', 'Register ok');
                return $app->redirect($app->url('home'));
            }
        }

        return $app->render('login.php', array(
            'user'  => $user,
        ));
    }
}