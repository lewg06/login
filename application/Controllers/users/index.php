<?php
namespace App\Users;
use App\Controller;

class Index extends Controller {
	public $viewName = '/Users/Users/Users.phtml';
	public $viewOn = TRUE;
	public $modelName;
	
	public function actionIndex(){
		if(Sayt::isRegistered() && $_SESSION['auth'] == 'user'){
		//	$this->viewName = '/Users/Admin/Admin.phtml';

		} else {

        }
        $this->modelName = 'ModelUsers';
		$this->render();
		//return self::$viewName;
		//echo 'Index - actionIndex!!!';
	}

}
