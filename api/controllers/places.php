<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 21.09.14
 * Time: 17:47
 */
class Controller_Places Extends Controller_Base{

    public $layouts = 'default';

    public function index(){
        $places_model = new Model_Places();
        $request = new Request();
        $page = (int)$request->getParam('page', null);
        $places = $places_model->getPlaces(null, $page);
        $count = count($places);
        $response = array(
            'places'=>$places,
            'count'=>$count
        );
        echo json_encode($response);
    }
//
//    public function addplace(){
//
//        $request = new Request();
//        $request_data = $request->getParam();
//        $places_model = new Model_Places();
//        if(!empty($request_data)){
//            $places_model->name = $request_data->name;
//            $places_model->image = md5($request_data->password);
//            $places_model->u_email = $request_data->email;
//            $places_model->save();
//            echo json_encode(1);
//            exit;
//        }else{
//            echo json_encode(0);
//        }
//    }
    public function view(){
        if(isset($_SESSION['auth'])){
            $request = new Request();
            $param = $request->getParam('place', null);
            if($param){
                $place_model = new Model_Places();
                $place = $place_model->getPlace($param);
                echo json_encode($place);
            }else{
                echo json_encode(0);
            }

        }else{
            echo json_encode(0);
        }


    }
}