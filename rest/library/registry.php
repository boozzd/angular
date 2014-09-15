<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 24.08.14
 * Time: 21:29
 */

class Registry {
    private $vars = array();
    function set($key, $value){
        if(isset($this->vars[$key]) == true){
            throw new Exception('Unable to set var '.$key.'. Is already set.');
        }
        $this->vars[$key] = $value;
        return true;
    }

    function get($key){
        if(isset($this->vars[$key]) == false){
            return null;
        }
        return $this->vars[$key];
    }
    function remove($key){
        unset($this->vars[$key]);
    }
} 