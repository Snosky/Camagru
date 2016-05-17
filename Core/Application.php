<?php
namespace Core;

/*
 * TODO : Flashbag and redirect
 */

use Camagru\Domain\User;

class Application implements \ArrayAccess
{
    /** @var Router */
    private $router;

    /** @var  ViewRender */
    private $viewRender;

    /** ArrayAccess */
    private $values = array();

    /** @var  User */
    private $user = NULL;

    public function __construct()
    {
        session_start();
        $this->router = new Router($this);
        $this->viewRender = new ViewRender($this);

        if (isset($_SESSION['user']))
            $this->user = $_SESSION['user'];

        $this->offsetSet('view', $this->viewRender);
        $this->offsetSet('basepath', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
    }

    /**
     * Run the application
     */
    public function run()
    {
        try {
            echo $this->router->run();
        }
        catch (\Exception $e)
        {
            echo 'Error : '.$e->getMessage();
        }
    }

    /**
     * Redirect user
     * @param $url
     */
    public function redirect($url)
    {
        header('Location:'.$url);
        die();
        return NULL;
    }

    /**
     * Redirect user to same host last page or to home
     */
    public function redirectToLast()
    {
        $ref = $_SERVER['HTTP_REFERER'];
        if (strstr($ref, $_SERVER['HTTP_HOST']))
            $this->redirect($ref);
        else
            $this->redirect($this->url('home'));
    }

    public function hash($password, $salt)
    {
        $password .= $salt;
        return hash('whirlpool', $password);
    }

    /*
     * User Method
     */
    public function user()
    {
        return $this->user;
    }

    public function isConnected()
    {
        return !is_null($this->user);
    }

    public function hasRole($role)
    {
        return $this->isConnected() ? $this->user->getRole() == $role : FALSE;
    }

    public function login(User $user)
    {
        $this->user = $user;
        $_SESSION['user'] = $this->user;
    }

    public function logout()
    {
        $this->user = NULL;
        $_SESSION['user'] = NULL;
    }

    /*
     * Route methods
     */
    public function get($path, $callable)
    {
        return $this->router->get($path, $callable);
    }

    public function post($path, $callable)
    {
        return $this->router->post($path, $callable);
    }

    public function match($path, $callable)
    {
        return $this->router->match($path, $callable);
    }

    public function url($name, $params = array())
    {
        return $this->router->generate($name, $params);
    }
    /*
     * End Route
     */

    /*
     * ViewRender methods
     */
    public function render($file, $data = array())
    {
        return $this->viewRender->render($file, $data);
    }

    /*
     * ArrayAccess
     */
    public function offsetExists($offset)
    {
        return isset($this->values[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->values[$offset]) ? $this->values[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset))
            $this->values[] = $value;
        else
            $this->values[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }
    /*
     * End Array Access
     */
}