<?php

class Model_Users extends Model_Base{

    public function getUsers($name = null, $password = null){
        $this->select()
            ->from('users')
            ->where('u_name =?', $name)
            ->where('u_password =?', $password)
            ->query();
        return $this->fetchRow();

    }
    public function getUser($name = null, $id = null){
        $this->select()
            ->from(array('u' => 'users'));
        if($name){
            $this->where('u.u_name =?', $name);
        }
        if($id){
            $this->where('u.id =?', $id);
        }
        $this->query();
        return $this->fetchAll();
    }

    public function getRole($id){
        $this->select()
            ->from(array('u' => 'users'))
            ->join(array('r' => 'role'),'u.u_role = r.r_id')
            ->where('u.id =?', $id)
            ->query();
        return $this->fetchRow();
    }

    public function getRoleId($role){
        $this->select()
            ->from(array('r'=>'role'))
            ->where('r.r_role =?', $role)
            ->query();
        return $this->fetchColumn();
    }

    public function removeUser(){
        $this->select()
            ->from(array('u'=>'users'))
            ->query();
        return $this->fetchRow();
    }
}