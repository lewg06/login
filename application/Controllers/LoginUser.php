<?php namespace App\Controllers;
use App\Controllers;
class LoginUser extends Controller
{
    public $viewName = '/Reg/Reg.phtml';
    public $viewOn = true;
    public $modelName;

    public function actionIndex()
    {
        echo __NAMESPACE__ . '<br>------';
        //parent::conctructor();
        $this->render();

    }
}