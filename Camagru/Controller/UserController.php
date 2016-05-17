<?php
namespace Camagru\Controller;

use Camagru\Domain\User;
use Core\Application;

class UserController
{
    public function loginAction(Application $app)
    {
        if ($app->isConnected())
        {
            $app['flashbag']->add('error', 'This page doesn\'t exist.');
            return $app->redirectToLast();
        }

        $user = new User();

        if (isset($_POST) AND !empty($_POST))
        {
            $form_is_valid = TRUE;

            if (!isset($_POST['username']) || empty($_POST['username']) || !isset($_POST['password']) || empty($_POST['password']))
                $form_is_valid = FALSE;
            else if (!($user = $app['dao.user']->findByUsername($_POST['username'])))
            {
                $user = new User();
                $form_is_valid = FALSE;
            }
            else if ($app->hash($_POST['password'], $user->getSalt()) != $user->getPassword())
                $form_is_valid = FALSE;

            if ($form_is_valid)
            {
                $app->login($user);
                $app['flashbag']->add('success', 'You\'re now connected.');
                return $app->redirect($app->url('home'));
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
        if ($app->isConnected())
        {
            $app['flashbag']->add('error', 'This page doesn\'t exist.');
            return $app->redirectToLast();
        }

        $user = new User();

        if (isset($_POST) AND !empty($_POST))
        {
            $form_is_valid = TRUE;

            $user->setUsername($_POST['username']);
            $user->setEmail($_POST['email']);

            /* Verification Username */
            if (!isset($_POST['username']) || empty($_POST['username']) || !preg_match('/^[a-z0-9\ _-]{3,32}$/i', $_POST['username']))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Username is not valid. 3 to 32 char. Alpha-numerics, spaces, underscores and - allowed');
            }
            else if ($app['dao.user']->findByUsername($_POST['username']))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Username is already taken');
            }

            /* Verification email */
            if (!isset($_POST['email']) || empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Email is not valid.');
            }
            else if ($app['dao.user']->findByEmail($_POST['email']))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Email is already taken');
            }

            /* Verification password */
            if (!isset($_POST['password']) || empty($_POST['password']) || $_POST['password'] != $_POST['password2'])
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Passwords do not match.');
            }

            if ($form_is_valid)
            {
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

    public function logoutAction(Application $app)
    {
        $app->logout();
        $app['flashbag']->add('success', 'You\'ve been disconnected.');
        return $app->redirectToLast();
    }
}