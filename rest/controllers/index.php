<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 24.08.14
 * Time: 22:04
 */
// контролер
Class Controller_Index Extends Controller_Base {

    public $layouts = "default";

    function index() {
        $this->getParam('id');
        $this->template->vars('userInfo', "test");
        $this->template->view('index');
        $user_model = new Model_Users();
//        var_dump($user_model->getUsers());
    }

    function login(){
        echo "test";
    }

}