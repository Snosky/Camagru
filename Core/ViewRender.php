<?php
namespace Core;

use Core\Exception\ViewRenderException;

class ViewRender
{
    /** @var  Application */
    private $app;
    private $last_block;
    private $blocks = array();
    private $extend;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function render($file, $vars = array())
    {
        $file = ROOT.'views'.DS.$file;
        if (!file_exists($file)) 
            throw new ViewRenderException(sprintf('View file %s not found.', $file));

        $app = $this->app;
        extract($vars);
        ob_start();
        require $file;
        $this->call_extend();
        return ob_get_clean();
    }

    public function call_extend()
    {
        $app = $this->app;
        if (!empty($this->extend))
            include $this->extend;
    }


    public function extend($file)
    {
        $file = ROOT.'views'.DS.$file;
        if (!file_exists($file))
            throw new ViewRenderException(sprintf('View file %s not found.', $file));
        else
            $this->extend = $file;
    }

    public function block_start($name)
    {
        if (empty($this->last_block))
        {
            $this->last_block = $name;
            ob_start();
        }
        else
            throw new ViewRenderException('You can\'t start a new block if you don\'t close the last one.');

    }

    public function block_end()
    {
        $this->blocks[$this->last_block] = ob_get_clean();
        $this->last_block = null;
    }

    public function block_output($name)
    {
        if (isset($this->blocks[$name]))
            echo $this->blocks[$name];
    }
}