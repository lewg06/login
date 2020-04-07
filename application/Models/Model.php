<?php
namespace loginModel;
class Model {
    public $args;
	public $modelData;
	public $pdo;
	public $err = [];
	
	function __construct () {

		$this->pdo = $this->connectDb();
	}
	
	private function connectDb(){
		\Sayt::connectDb();
	}

	public function zapros($query,$pdo = []){
		$data = [];
		$zapros = $this->pdo->query($query);
		if (!$zapros){print_r($this->pdo->errorinfo());echo __FUNCTION__ . ' : ' . __METHOD__ ;exit();}
		while($result = $zapros->fetch(PDO::FETCH_ASSOC)){
			$data[] = $result;
		}
		return $data;
	}

	public function queryBD($query){

    }


}