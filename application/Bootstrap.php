<?php
function PutModels ($class){
    $file = PUT_APP . '/Models/' . ltrim($class, 'App\\') . '.php';
    if (file_exists($file)){
        include($file);
    }
}

function PutControllers ($class){
    $file = PUT_APP . '/' . ltrim($class, 'App\\') . '.php';
    //$file = PUT_APP . '/Controllers/' . $class . '.php';
    echo $file;
    echo '===' . $class;
    echo '----<br>';
    if (file_exists($file)){
        include($file);
    }
}

function PutViews ($class){
    $file = PUT_APP . '/Views/' . ltrim($class, 'App\\') . '.php';
    if (file_exists($file)){
        include($file);
    }
}

function PutSayt ($class){
	$file = PUT_LIB . '/' . ltrim($class, 'Lib\\') . '.php';
	if (file_exists($file)){
		include($file);
	}
}

spl_autoload_register('PutSayt');
spl_autoload_register('PutControllers');
//spl_autoload_register('PutModels');
//spl_autoload_register('PutViews');
//include('Config.cnfg');
