<?php

function __autoload($classname)
{
    $file = ROOT.str_replace('\\', DIRECTORY_SEPARATOR, $classname).'.php';
    if (file_exists($file))
        include_once $file;
    else
        throw new Exception(sprintf('Class %s not found in %s.', $classname, $file));
}