<?php
namespace Core;

/*
 * TODO : Flashbag and redirect
 */

class Application implements \ArrayAccess
{
    /** @var Router */
    private $router;

    /** @var  ViewRender */
    private $viewRender;

    /** ArrayAccess */
    private $values = array();

    public function __construct()
    {
        $this->router = new Router($this);
        $this->viewRender = new ViewRender($this);

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