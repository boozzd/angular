<?php

class Controller_Places Extends Controller_Base{

    public $layouts = 'default';

    public function index(){
        $places_model = new Model_Places();
        $request = new Request();
        $page = (int)$request->getParam('page', null);
        $town = $request->getParam('town', null);
        $town = $town ? explode(',', $town) : null;
        foreach($town as $key=>$value){
            $town[$key] = (int)$town[$key];
        }
        $places = $places_model->getPlaces(null, $page, $town);
        $count = count($places);
        $response = array(
            'places'=>$places,
            'count'=>$count
        );
        echo json_encode($response);
    }

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

    public function getcity(){
        if(isset($_SESSION['auth'])){
            $model_city = new Model_City();
            $result = $model_city->getCities();
            echo json_encode($result);
        }else{
            echo json_encode(0);
        }
    }

    public function gettown(){
        $request = new Request();
        $city  = $request->getParam('city', null);
        if(isset($_SESSION['auth']) && $city){
             $model_city = new Model_City();
             $town = $model_city->getTown((int)$city);
             echo json_encode($town);
        }else{
            json_encode(0);
        }
    }
}