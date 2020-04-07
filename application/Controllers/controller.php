<?php
namespace App\Controllers;

class Controller {
	public $view;
	public $viewOn = false;
	public $viewName;
	public $viewContent;
	public $modelName;
	public $modelData;
	public $req = [];
	public $data;
	public $metod = 'createModelData';
	public $zapros;
	public $args = [];
	
	public $error = false;
	public $text = 'Controller111111';
	public static function Instance (){
		return new self;
	}
	function __construct($controllerName = '', $actionName = '') {
		if ($_REQUEST) {
			foreach($_REQUEST as $ind => $data){
				$this->req[mb_strtolower($ind)] = trim($data);
			}
		}
	}
	
	public function actionIndex() {}
	
	public function adminPrava(){
		if(!Sayt::isAdmin()){
			header("Location: /reg");
		}
	}
	
	public function render() {
		if ($this->modelName) {
			//echo 'go model';
			$this->model = new $this->modelName;
			$this->modelData = $this->model->{$this->metod}($this->args);
		}
		
		if ($this->viewName) {
			$this->view = new View;
			if ($this->viewOn) {
				$this->view->viewName = $this->viewName;
				$this->view->modelData = $this->modelData;
				$this->view->createViewContent();
				$this->viewContent = $this->view->viewContent;
			} else {echo '00000000000000000000';
				$this->viewContent = $this->modelData;
			}

			//$this->viewOn = $this->view->viewOn;
		}
	}
	
	public function isAjax (){
		if(isset($_SERVER['XXX_AJAX'])){
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
