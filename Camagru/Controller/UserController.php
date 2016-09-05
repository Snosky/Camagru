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

            if ($form_is_valid && !$user->getActivate())
            {
                $app['flashbag']->add('error', 'You have to activate your account. Please click on the link on the email. You don\'t have the email ? <a href="'.$app->url('resend-activation', array('user_id' => $user->getId())).'">click here to resend it</a>');
            }
            else if ($form_is_valid)
            {
                $app->login($user);
                $app['flashbag']->add('success', 'You\'re now connected.');
                return $app->redirect($app->url('home'));
            }
            else
            {
                $app['flashbag']->add('error', 'User not found or combo login/password is wrong.');
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
            else if (strlen($_POST['password']) < 3)
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'You\'re password should do at least 3 characters.');
            }

            if ($form_is_valid)
            {
                $salt = substr(sha1(time()), 3, 32);
                $password = $app->hash($_POST['password'], $salt);

                $user->setPassword($password);
                $user->setSalt($salt);
                $user->setRole('ROLE_USER');
                $user->setActivate(FALSE);
                $token = md5($user->getUsername().rand());
                $user->setToken($token);

                $app['dao.user']->save($user);
                $app['flashbag']->add('success', 'An email have been send to you. Please click on the link to activate your account.');

                $mail = $app->email();
                $mail->setTo($user->getEmail());
                $mail->setSubject('[Camagru]Account Activate.');
                $mail->setMessage('<a href="'. $app->url('activate', array('token' => $user->getToken())) .'">Please click here to activate your account.</a>');
                $mail->send();

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

    public function resetAction(Application $app)
    {
        if ($app->isConnected())
        {
            $app['flashbag']->add('error', 'This page doesn\'t exist.');
            return $app->redirectToLast();
        }

        if (isset($_POST) AND !empty($_POST))
        {
            $form_is_valid = TRUE;
            $user = NULL;

            /* Verification email */
            if (!isset($_POST['email']) || empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Email is not valid.');
            }
            else if (!($user = $app['dao.user']->findByEmail($_POST['email'])))
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'Email doesn\'t exist.');
            }
            else if (!$user->getActivate())
            {
                $form_is_valid = FALSE;
                $app['flashbag']->add('error', 'This account is not activate.');
            }

            if ($form_is_valid)
            {
                $token = md5($user->getUsername().rand());
                $user->setToken($token);
                $app['dao.user']->save($user);

                $mail = $app->email();
                $mail->setTo($user->getEmail());
                $mail->setSubject('[Camagru]Password Reset');
                $mail->setMessage('You requested a password reset. There is a link to reset your password <a href=" '. $app->url('reset2', array('token' => $user->getToken())) .' ">Click here</a>');
                $mail->send();

                $app['flashbag']->add('success', 'An email as been send to your email address with a link to reset your password.');
                return $app->redirect('login');
            }
        }
        
        return $app->render('reset-account.php');
    }

    public function resetTwoAction($token, Application $app)
    {
        if ($app->isConnected())
        {
            $app['flashbag']->add('error', 'This page doesn\'t exist.');
            return $app->redirectToLast();
        }

        $user = $app['dao.user']->findByToken($token);

        if (!$user)
        {
            $app['flashbag']->add('error', 'This page doesn\'t exist.');
            return $app->redirectToLast();
        }

        if (isset($_POST['send']))
        {
            $valid_form = TRUE;

            if (!isset($_POST['new_pwd']) || empty($_POST['new_pwd']) || !isset($_POST['new_pwd2']))

            {
                $app['flashbag']->add('error', 'Please enter a new password.');
                $valid_form = FALSE;
            }
            else if ($_POST['new_pwd'] != $_POST['new_pwd2'])
            {
                $app['flashbag']->add('error', 'New password not match.');
                $valid_form = FALSE;
            }
            else if (strlen($_POST['new_pwd']) < 3)
            {
                $app['flashbag']->add('error', 'You\'re password should do at least 3 characters.');
                $valid_form = FALSE;
            }

            if ($valid_form)
            {
                $salt = substr(sha1(time()), 3, 32);
                $password = $app->hash($_POST['new_pwd'], $salt);

                $user->setSalt($salt);
                $user->setPassword($password);
                $user->setToken('');

                $app['dao.user']->save($user);
                $app['flashbag']->add('success', 'You password had been updated.');
                return $app->redirect($app->url('login'));
            }
        }

        return $app->render('new-password.php', array(
            'reset' => TRUE,
        ));
    }

    public function changePasswordAction(Application $app)
    {
        if (!$app->isConnected())
        {
            $app['flashbag']->add('error', 'You have to be connected to access this page.');
            return $app->redirect($app->url('login'));
        }


        if (isset($_POST['send']))
        {
            $valid_form = TRUE;

            if (!isset($_POST['old_pwd']) && empty($_POST['old_pwd']))
            {
                $app['flashbag']->add('error', 'Please enter your old password.');
                $valid_form = FALSE;
            }
            else if ($app->hash($_POST['old_pwd'], $app->user()->getSalt()) !== $app->user()->getPassword())
            {
                $app['flashbag']->add('error', 'Old password is wrong.');
                $valid_form = FALSE;
            }

            if (!isset($_POST['new_pwd']) || empty($_POST['new_pwd']) || !isset($_POST['new_pwd2']))

            {
                $app['flashbag']->add('error', 'Please enter a new password.');
                $valid_form = FALSE;
            }
            else if ($_POST['new_pwd'] != $_POST['new_pwd2'])
            {
                $app['flashbag']->add('error', 'New password not match.');
                $valid_form = FALSE;
            }
            else if (strlen($_POST['new_pwd']) < 3)
            {
                $app['flashbag']->add('error', 'You\'re password should do at least 3 characters.');
                $valid_form = FALSE;
            }

            if ($valid_form)
            {
                $salt = substr(sha1(time()), 3, 32);
                $password = $app->hash($_POST['new_pwd'], $salt);

                $app->user()->setSalt($salt);
                $app->user()->setPassword($password);

                $app['dao.user']->save($app->user());
                $app['flashbag']->add('success', 'You password had been updated.');
                return $app->redirect($app->url('view_user', array('user_id' => $app->user()->getId())));
            }
        }

        return $app->render('new-password.php');
    }

    public function sendActivationAction($user_id, Application $app)
    {
        $user = $app['dao.user']->find($user_id);

        if ($user && !$user->getActivate())
        {
            $mail = $app->email();
            $mail->setTo($user->getEmail());
            $mail->setSubject('[Camagru]Account Activate.');
            $mail->setMessage('<a href="'. $app->url('activate', array('token' => $user->getToken())) .'">Please click here to activate your account.</a>');
            $mail->send();

            $app['flashbag']->add('success', 'An email have been send to you. Please click on the link to activate your account.');
        }
        return $app->redirect($app->url('login'));
    }

    public function activateAction($token, Application $app)
    {
        $user = $app['dao.user']->findByToken($token);

        if ($user)
        {
            $user->setActivate(TRUE);
            $user->setToken(NULL);
            $app['dao.user']->save($user);
            $app['flashbag']->add('success', 'Your account is activated.');
        }
        else
        {
            $app['flashbag']->add('error', 'Error on activation.');
        }
        return $app->redirect($app->url('login'));
    }

    public function viewProfilAction($user_id, Application $app)
    {
        $user = $app['dao.user']->find($user_id);
        if (!$user)
        {
            $app['flashbag']->add('error', 'User not found.');
            return $app->redirect($app->url('home'));
        }

        $his_images = $app['dao.image']->findByUser($user->getId());
        $his_likes = $app['dao.like']->findByUser($user->getId());

        return $app->render('view_profile.php', array(
            'user'  => $user,
            'his_images'    => $his_images,
            'his_likes'     => $his_likes
        ));
    }
}