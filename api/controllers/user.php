<?php

Class Controller_User Extends Controller_Base {

    public $layouts = "default";

    function index() {

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
                $tmp = $users_model->getRole($user['id']);
                $user['role'] = $tmp['r_role'];
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
        $role_id = $users_model->getRoleId('user');
        if(!empty($request_data)){
            $user = $users_model->getUser($request_data->name);
            if(!isset($user[0]['u_name'])){
                $users_model->u_name = $request_data->name;
                $users_model->u_password = md5($request_data->password);
                $users_model->u_email = $request_data->email;
                $users_model->u_role = (int)$role_id;
                $users_model->save();
                echo json_encode(1);
                exit;
            }
            echo json_encode(0);
        }else{
            echo json_encode(0);
        }
    }

    function getuser(){
        $request = new Request();
        $id = $request->getParam('id', null);
        if(isset($_SESSION['auth']) && $id){
            $auth_id = $_SESSION['auth']['id'];
            $user_model = new Model_Users();
            $role = $user_model->getRole($auth_id);
            if($role['r_role'] == 'admin'){
                $result = $user_model->getUser(null, (int)$id);
            }elseif($auth_id == $id){
                $result = $user_model->getUser(null, (int)$auth_id);                
            }else{
                echo json_encode(0);
                exit;
            }
            foreach($result as $key=>$value){
                unset($result[$key]['u_password']);
            }
            echo json_encode($result);
        }else{
            echo json_encode(0);
        }
    }

    function getusers(){
        if(isset($_SESSION['auth'])){
            $auth_id = $_SESSION['auth']['id'];
            $user_model = new Model_Users();
            $role = $user_model->getRole($auth_id);
            if($role['r_role'] == 'admin'){
                $result = $user_model->getUser();
            }else{
                $result = $user_model->getUser(null, $auth_id);
            }
            foreach($result as $key=>$value){
                unset($result[$key]['u_password']);
            }
            echo json_encode($result);
        }else{
            echo json_encode(0);
        }
    }

    function removeuser(){
        if(isset($_SESSION['auth']) && $_SESSION['auth']['role'] == 'admin'){
            $request = new Request();
            $id = $request->getParams('userId', null);
            $user_model = new Model_Users((int)$id->id);
            if($user_model->id == $_SESSION['auth']['id'] || $_SESSION['auth']['role'] == 'admin'){
                $user_model->delete();
                echo json_encode(1);
            }else{
                echo json_encode(0);
            }
        }else{
            echo json_encode(0);
        }
    }

    function changeuser(){
        if(isset($_SESSION['auth'])){
            $request = new Request();
            $user_data = $request->getParam('user', null);
            if($user_data && ($_SESSION['auth']['role']=='admin' || $_SESSION['auth']['id'] == $user_data->id)){
                $user_model = new Model_Users($user_data->id);
                $user_model->u_sname = $user_data->u_sname;
                $user_model->u_rname = $user_data->u_rname;
                $user_model->u_about = $user_data->u_about;
                $user_model->u_email = $user_data->u_email;
                if(!empty($user_data->password)){
                    $user_model->u_password = md5($user_data->password);
                }
                $user_model->update();
                echo json_encode(1);
            }else{
                echo json_encode(0);
            }
        }else{
            echo json_encode(0);
        }
    }

}