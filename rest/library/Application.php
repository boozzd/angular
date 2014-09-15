<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 24.08.14
 * Time: 21:15
 */
function __autoload($classname){
    $filename = strtolower($classname).'.php';
    $expArr = explode('_',$classname);
    if(empty($expArr[1]) OR $expArr[1] == 'Base'){
        $folder = 'library';
    }else{
        switch(strtolower($expArr[0])){
            case 'controller':
                $folder = 'controllers';
                break;
            case 'model':
                $folder = 'models';
                break;
            default:
                $folder = 'library';
        }
    }
    $file = SITE_PATH.$folder.DS.$filename;
    if(file_exists($file) == false){
        return false;
    }else{
        require_once($file);
    }
}
$registry = new Registry;