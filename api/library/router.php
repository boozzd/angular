<?php

class Router {
    private $registry;
    private $path;
    private $args = array();

    function __contruct($registry){
        $this->registry = $registry;
    }

    function setPath($path){
        $path = rtrim($path, '/\\');
        $path .= DS;
        if(is_dir($path) == false){
            throw new Exception('Invalid controller path');
        }
        $this->path = $path;
    }

    function getController(&$file, &$controller, &$action, &$args){
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
        unset($_GET['route']);
        if(empty($route)){
            $route = 'index';
        }
        $route = trim($route, '/\\');
        $parts = explode('/', $route);
        $cmd_path = $this->path;
        foreach($parts as $part){
            $fullpath = $cmd_path.$part;
            if(is_dir($fullpath) == true){
                $cmd_path .=  $part.'DS';
                array_shift($parts);
                continue;
            }
            if(is_file($fullpath.'.php')){
                $controller = $part;
                array_shift($parts);
                break;
            }
        }
        if(empty($controller)){
            $controller = 'index';
        }

        $action = array_shift($parts);
        if(empty($action)){
            $action = 'index';
        }
        $file = $cmd_path.$controller.'.php';
        $args = $parts;
    }

    function start() {
        $this->getController($file, $controller, $action, $args);
        if (is_readable($file) == false) {
            die ('404 Not Found');
        }
        require_once ($file);
        $class = 'Controller_' . $controller;
        $controller = new $class($this->registry);
        if (is_callable(array($controller, $action)) == false) {
            die ('404 Not Found');
        }
        $controller->$action();
    }

}