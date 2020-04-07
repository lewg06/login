<?php
namespace App\Admin;
use App\Controller;
use loginModel\modelAdmin\ModelAdmin;

class Index extends Controller {
	public $viewName = '/Admin/Index.phtml';
	public $viewOn = TRUE;
	public $modelName = ModelAdmin::class;
	public $method;

	public function actionIndex(){
	    $this->method = 'getAllWithoutAdmin';
		$this->render();
	}

    public function actionShowAll(){
        $this->method = 'getAllWithoutAdmin';
        $this->render();
    }

    public function actionShowActive(){
        $this->method = 'getActive';
        $this->render();
    }

    public function actionShowDeleted(){
        $this->method = 'getDeleted';
            $this->render();
    }

    public function actionDelete(){
        $this->method = 'deleteUserId';
        header("Location: /admin/");
    }

}
