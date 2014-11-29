<?php

class Request{
    private $data;

    function __construct(){

        if(file_get_contents('php://input')){
            $data = file_get_contents('php://input');
            foreach(json_decode($data) as $key => $value){
                $this->data->$key = $value;
            }

        }
        if($_GET){
            foreach($_GET as $key=>$value){
                $this->data->$key = $value;
            }
        }

    }
    public function getParam($param = null, $default){

        if(isset($this->data->$param)){
            $value = $this->data->$param;
        }
        if(($value === null || $value === '') && ($default !== null)){
            $value = $default;
        }
        return $value;
    }

    public function getParams(){
        return $this->data;
    }
}