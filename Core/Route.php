<?php
namespace Core;

use Core\Exception\RouterException;

class Route
{
    private $path;
    private $callable;
    private $matches;
    private $params;
    private $name;

    public function __construct($path, $callable, Application $app)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
        $this->matches[] = $app;
        $this->getParamsName();
    }

    private function getParamsName()
    {
        $regex = '#{([\w]+)}#';
        preg_match_all($regex, $this->path, $match);
        $this->params = $match[1];
    }

    public function match($url)
    {
        $url = trim(trim($url), '/');
        $path = preg_replace('#{([\w]+)}#', '([^/]+)', $this->path);
        $regex = "#^$path$#i";
        if (!preg_match($regex, $url, $matches))
            return false;
        array_shift($matches);
        $this->matches = array_merge($matches, $this->matches);
        return true;
    }

    public function call()
    {
        if (is_string($this->callable))
        {
            $tmp = explode('::', $this->callable);
            $controller = new $tmp[0];
            return call_user_func_array(array($controller, $tmp[1]), $this->matches);
        }
        else
            return call_user_func_array($this->callable, $this->matches);
    }

    public function bind($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function generate($params)
    {
        $array_from_to = array();
        foreach ($this->params as $param)
        {
            if (isset($params[$param]))
                $array_from_to['{'.$param.'}'] = $params[$param];
            else
                throw new RouterException(sprintf('Parameter %s needed.', $param));
        }
        if (!empty($array_from_to))
            $url = strtr($this->path, $array_from_to);
        else
            $url = $this->path;
        return $url;
    }
}