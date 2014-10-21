<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 21.09.14
 * Time: 17:50
 */
class Model_Places extends Model_Base{

    public function getPlaces($limit = null,$page = null, $city = null){

        $this->select()
            ->from(array('p' => 'places'))
            ->join(array('c'=>'city'),'p.city = c.c_id')
            ->where('p.status =?', 1);
        if($page){
            $this->limitPage($page, 5);
        }
        if($limit){
            $this->limit($limit);
        }
        if($city){
            $this->where("c.c_id IN(?)", $city);
        }
        $this->query();
        return $this->fetchAll();
    }

    public function getPlace($url){
        $this->select()
            ->from('places')
            ->where('url =?', $url)
            ->query();
        return $this->fetchRow();
    }
}
