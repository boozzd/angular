<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 09.09.14
 * Time: 22:30
 */
Class Controller_User Extends Controller_Base {

    public $layouts = "default";

    function index() {
        echo 'test';
    }

    function login(){
        if(isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) {
            echo json_encode($_SESSION['auth']);
            exit;
        }
        $request = new Request();
        $request_data = $request->getParams();
        $users_model = new Model_Users();
        if($request_data){
            $user = $users_model->getUsers($request_data->name, md5($request_data->password));
            if($user){
                unset($user['u_password']);
                echo json_encode($user);
                $_SESSION['auth'] = $user;
            }else{
                echo json_encode(0);
            }
        }else{
            echo json_encode(0);
        }
    }

    function logout(){
        if(isset($_SESSION['auth']) AND !empty($_SESSION['auth'])) {
            unset($_SESSION['auth']);
            echo json_encode(0);
        }
    }

    function register(){
        $request = new Request();
        $request_data = $request->getParams();
        $users_model = new Model_Users();
        if(!empty($request_data)){
            $user = $users_model->getUser($request_data->name);
            if(!isset($user['u_name'])){
                $users_model->u_name = $request_data->name;
                $users_model->u_password = md5($request_data->password);
                $users_model->u_email = $request_data->email;
                $users_model->save();
                echo json_encode(1);
                exit;
            }
            echo json_encode(0);
        }else{
            echo json_encode(0);
        }
    }

}