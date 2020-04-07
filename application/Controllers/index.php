<?php namespace App;
use App\Controller;
class Index extends Controller {
	public $viewName = '/Index/Index.phtml';
	public $viewOn = TRUE;
	public $modelName;

	
	public function actionIndex(){
		$this->render();
		//return self::$viewName;
		//echo 'Index - actionIndex!!!';
	}
}
