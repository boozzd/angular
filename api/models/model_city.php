<?php
/*=======
 City table Model
 =======*/
class Model_City extends Model_Base{

	public function getCities(){
		$this->select()
			->from('city')
			->where('c_parent IS NULL', null)
			->query();
		return $this->fetchAll();
	}

	public function getTown($city){
		$this->select()
			->from('city')
			->where('c_parent =?', $city)
			->query();
		$result = $this->fetchAll();
		return $result;
	}
}