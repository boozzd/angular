<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 09.09.14
 * Time: 22:07
 */
class Request{
    private $data;

    function __construct(){

        if(file_get_contents('php://input')){
            $data = file_get_contents('php://input');
            $this->data = json_decode($data);
        }else{
            $this->data = $_GET;
        }

    }
    function getParam(){
        return $this->data;
    }
}