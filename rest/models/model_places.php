<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 21.09.14
 * Time: 17:50
 */
class Model_Places extends Model_Base{

    public function getPlaces(){
        $this->select()
            ->from('places')
            ->where('status =?', 1)
            ->query();
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
