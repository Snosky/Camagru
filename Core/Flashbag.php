<?php
namespace Core;

class Flashbag
{
    public function __construct()
    {
        if (!isset($_SESSION['flashbags']))
            $_SESSION['flashbags'] = array();
    }

    public function add($type, $content)
    {
        $_SESSION['flashbags'][$type][] = $content;
    }

    public function get($type)
    {
        if (isset($_SESSION['flashbags'][$type]))
        {
            $return = $_SESSION['flashbags'][$type];
            unset($_SESSION['flashbags'][$type]);
            return $return;
        }
        else
            return array();
    }

    public function have($type)
    {
        return isset($_SESSION['flashbags'][$type]) ? !empty($_SESSION['flashbags'][$type]) : FALSE;
    }

    public function clear()
    {
        $_SESSION['flashbags'] = NULL;
    }

}