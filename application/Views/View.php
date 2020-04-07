<?php
namespace app\View;
class View {
	public $viewContent;
	public $viewOn = FAlSE;
	public $modelData;
	public $viewName;
	
	function __construct (){
		}
	
	public function createViewContent() {
		ob_start();
		include(__DIR__ . $this->viewName);
		$this->viewContent = ob_get_contents();
		ob_end_clean();
	}
	
}