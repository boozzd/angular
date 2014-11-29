<?php

// абстрактый класс контроллера
Abstract Class Controller_Base {

    protected $registry;
    protected $template;
    protected $layouts;

    public $vars = array();
    function __construct($registry) {
        $this->registry = $registry;
        $this->template = new Template($this->layouts, get_class($this));
    }

    abstract function index();

    function getParam($param = null){
        if(!$param){
            return null;
        }
        if(isset($_GET[$param])){
            return $_GET[$param];
        }
        if(isset($_POST[$param])){
            return $_POST[$param];
        }
    }

}