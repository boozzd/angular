<?php
/**
 * Created by PhpStorm.
 * User: boozz
 * Date: 25.08.14
 * Time: 22:34
 */
abstract class Model_Base{
    protected $db;
    protected $table;
    protected $selectData = "";
    protected $query_db;
    protected $params;

    public function __construct($id = null){
        global $dbObject;
        $this->db = $dbObject;

        $modelName = get_class($this);
        $arrExp = explode('_', $modelName);
        $tableName = strtolower($arrExp[1]);
        $this->table = $tableName;
        if($id){
            $this->data_id = $id;
            $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->data_result = $result;
            if(isset($this->data_result) OR empty($this->data_result)){
                foreach($result as $key=>$value){
                    $this->$key = $value;
                }
            }
        }
    }

    public function save(){
        $fields = $this->getFields();
        $array_set_fields = array();
        $array_set_data = array();
        foreach($fields as $field){
            if(!empty($this->$field)){
                $array_set_fields[] = '`'.$field.'`';
                $array_set_data[] = $this->$field;
            }
        }
        if(!isset($array_set_fields) OR empty($array_set_fields)){
            echo 'Data fields is empty';
            exit;
        }
        if(!isset($array_set_data) OR empty($array_set_data)){
            echo 'Data save is empty';
            exit;
        }
        $query_fields = implode(', ', $array_set_fields);
        $range_place = array_fill(0, count($array_set_fields), "?");
        $query_place = implode(', ', $range_place);
        try{
            $stmt = $this->db->prepare("INSERT INTO $this->table  ($query_fields) VALUE ($query_place)");
            $result = $stmt->execute($array_set_data);
        }catch(PDOException $e){
            echo 'ERROR DB '.$e->getMessage();
        }

        return $result;
    }

    public function update(){
        $fields = $this->getFields();
        $dataUpdate = array();
        foreach($fields as $field){
            if(!empty($this->$field)){
                if($field != 'id'){
                    $dataUpdate[] ='`'.$field.'`'.' = "'.$this->$field.'"';
                }else{
                    $whereId = $this->$field;
                }
            }
        }
        if(!isset($dataUpdate) OR empty($dataUpdate)){
            echo 'Data is empty';
            exit;
        }
        if(!isset($whereId) OR empty($whereId)){
            echo 'ID is empty';
            exit;
        }
        $dataUpdate = implode(', ', $dataUpdate);
        try{
            $stmt = $this->db->prepare("UPDATE $this->table SET $dataUpdate WHERE `id` = $whereId");var_dump($stmt);
            $stmt->execute();
        }catch(PDOException $e){
            echo 'ERROR DB '.$e->getMessage();
        }

    return $whereId;

    }

    public function delete(){
        $fields = $this->getFields();
        if(in_array('id', $fields)){
            try{
                $result = $this->db->exec("DELETE FROM$this->tabel WHERE `id` = $this->id");
            }catch(PDOException $e){
                echo "Record not found in ".$this->table;
                echo 'Error: '.$e->getMessage();
            }
        }
    }

    public function getFields(){

        try{
            $stmt = $this->db->prepare("SHOW COLUMNS FROM $this->table ");
            $stmt->execute();
            $result = $stmt->fetchAll();
            $fields = array();
            foreach($result as $value){
                $fields[] = $value['Field'];
            }
            return $fields;
        }catch (PDOException $e){
            echo "ERROR PDO ".$e->getMessage();
        }
    }

    public function getSelect(){
        $sql = "";
        $params = array();
        $data = $this->selectData;

        if(in_array('select',$this->selectData)){
            $sql .= 'SELECT ';
        }

        if(in_array('from',$this->selectData)){

            if(isset($data['from']['fields'])){
                $fields = $data['from']['fields'];
                $fields = implode(', ', $fields);
                $sql .= $fields;
            }else{
                $sql .= '* ';
            }
            $sql .= ' FROM ';
            $sql .= $data['from']['table'].' ';
            if(isset($data['from']['alias'])){
                $sql .= 'AS '.$data['from']['alias'].' ';
            }
        }

        if(isset($data['join'])){
            foreach($data['join'] as $value){
                $from = $value['from'];
                $as = $value['as'];
                $sql .= 'INNER JOIN '.$from['table'].' '.$from['alias'].' ON '.$as.' ';
            }
        }

        if(isset($data['joinLeft'])){
            foreach($data['joinLeft'] as $value){
                $from = $value['from'];
                $as = $value['as'];
                $sql .= 'LEFT OUTER JOIN '.$from['table'].' '.$from['alias'].' ON '.$as.' ';
            }
        }

        if(isset($data['joinLeft'])){
            foreach($data['joinLeft'] as $value){
                $from = $value['from'];
                $as = $value['as'];
                $sql .= 'RIGHT OUTER JOIN '.$from['table'].' '.$from['alias'].' ON '.$as.' ';
            }
        }

        if(isset($data['where'])){
            $where = $data['where'];
            $whereArr = array();
            foreach($where as $value){
                $this->params[] = $value['data'];
                $whereArr[] = $value['query'];
            }
            $whereArr = implode(' AND ', $whereArr);
            $sql .= 'WHERE '.$whereArr.' ';
        }

        if(isset($data['orWhere'])){
            $where = $data['orWhere'];
            $whereArr = array();
            foreach($where as $value){
                $this->params[] = $value['data'];
                $whereArr[] = ' WHERE '.$value['query'];
            }
            $whereArr = implode(' OR ', $whereArr);
            $sql .= $whereArr.' ';
        }

        if(isset($data['limit'])){
            $limit = (int)$data['limit'];
            $sql .= 'LIMIT '.$limit.' ';
        }

        if(isset($data['order'])){
            $sql .= 'ORDER BY '.$data['order'];
        }

        if(isset($data['group'])){
            $sql .= 'GROUP BY '.$data['group'];
        }


        return $sql;
    }

    public function from($data, $fields = null){

        if(is_array($data)){
            $table_alias = "";
            foreach($data as $key=>$value){
                $table_alias = $key;
                $table = $value;
            }
            $this->selectData['from'] = array('alias' => $table_alias, 'table'=> $table);
            if(!empty($fields)){
                $this->selectData['from']['fields'] = $fields;
            }
        }else{
            $table = $data;
            $this->selectData['from'] = array('table' => $table);
        }
        return $this;
    }

    public function where($query, $data){
        if($query && $data){
            $this->selectData['where'][] = array('query'=>$query, 'data' => $data);
        }
        return $this;
    }

    public function orWhere($query, $data){
        if($query && $data){
            $this->selectData['orWhere'][] = array('query'=>$query, 'data' => $data);
        }
        return $this;
    }

    public function limit($data){
        if(is_int($data)){
            $this->selectData['limit'] = $data;
        }
        return $this;
    }

    public function order($query){
        if($query){
            $this->selectData['order'] = $query;
        }
        return $this;
    }
    public function group($query){
        if($query){
            $this->selectData['group'] = $query;
        }
        return $this;
    }

    public function join($table, $condition){
        if(is_array($table) && !empty($condition)){
            $table_alias = "";
            $table_name = "";
            foreach($table as $key=>$value){
                $table_alias = $key;
                $table_name = $value;
            }
            $this->selectData['join'][] = array(
                'from' => array('alias' => $table_alias, 'table'=> $table_name),
                'as' => $condition
            );
        }
        return $this;
    }

    public function joinLeft($table, $condition){
        if(is_array($table) && !empty($condition)){
            $table_alias = "";
            $table_name = "";
            foreach($table as $key=>$value){
                $table_alias = $key;
                $table_name = $value;
            }
            $this->selectData['joinLeft'][] = array(
                'from' => array('alias' => $table_alias, 'table'=> $table_name),
                'as' => $condition
            );
        }
        return $this;
    }

    public function joinRight($table, $condition){
        if(is_array($table) && !empty($condition)){
            $table_alias = "";
            $table_name = "";
            foreach($table as $key=>$value){
                $table_alias = $key;
                $table_name = $value;
            }
            $this->selectData['joinRight'][] = array(
                'from' => array('alias' => $table_alias, 'table'=> $table_name),
                'as' => $condition
            );
        }
        return $this;
    }

    public function select(){
        $this->selectData['select'] = true;
        return $this;
    }

    public function query(){
        try{
            $this->query_db = $this->db->prepare($this->getSelect());
            $this->query_db->execute($this->params);
        }catch(PDOException $e){
            echo 'EROR '.$e->getMessage();
            echo 'Sql query is wrong: '.$this->getSelect();
        }

        return $this;
    }

    public function fetchAll(){
        return $this->query_db->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchRow(){
        return $this->query_db->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchColumn(){
        return $this->query_db->fetchColumn();
    }

    public function __toString(){
        return $this->getSelect();
    }

}












