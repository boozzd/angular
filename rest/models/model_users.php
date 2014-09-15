<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 30.08.14
 * Time: 18:48
 */
class Model_Users extends Model_Base{

    public function getUsers($name = null, $password = null){
        $this->select()
            ->from('users')
            ->where('u_name =?', $name)
            ->where('u_password =?', $password)
            ->query();
        return $this->fetchRow();

    }
    public function getUser($name){
        $this->select()
            ->from('users')
            ->where('u_name =?', $name)
            ->query();
        return $this->fetchRow();
    }
}