<?php
namespace Core;

use Core\Exception\RouterException;

class Router
{
    private $app;
    private $url;
    private $routes = array();

    public function __construct(Application $app)
    {
        $this->url = trim($_SERVER['REQUEST_URI'], '/');
        $this->app = $app;
    }

    public function get($path, $callable)
    {
        $route = new Route($path, $callable, $this->app);
        $this->routes['GET'][] = $route;
        return $route;
    }

    public function post($path, $callable)
    {
        $route = new Route($path, $callable, $this->app);
        $this->routes['POST'][] = $route;
        return $route;
    }

    public function match($path, $callable)
    {
        $route = new Route($path, $callable, $this->app);
        $this->routes['POST'][] = $route;
        $this->routes['GET'][] = $route;
        return $route;
    }

    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']]))
            throw new RouterException('REQUEST_METHOD not found.');

        $routes = $this->routes[$_SERVER['REQUEST_METHOD']];

        /** @var Route $route */
        foreach ($routes as $route)
            if ($route->match($this->url))
                return $route->call();
        //throw new RouterException('Route not found');
        return $this->app->page_not_found();
    }

    public function generate($name, $params = array())
    {
        foreach ($this->routes as $method => $routes) {
            /** @var Route $route */
            foreach ($routes as $route)
                if ($route->getName() == $name) {
                    return $_SERVER['REQUEST_SCHEME']. '://' .$_SERVER['HTTP_HOST']. '/' . $route->generate($params);
                }
        }
        throw new RouterException(sprintf('Route %s doesn\'t exist', $name));
    }
}