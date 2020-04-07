<?php
use Lib\Sayt\Sayt as Sayt;

session_start();

header('Content-type: text/html; charset=utf-8');

define('PUT_APP', realpath($_SERVER['DOCUMENT_ROOT'] . '../application'));
define('PUT_LIB', realpath($_SERVER['DOCUMENT_ROOT'] . '../library'));

require_once(PUT_APP . '/Bootstrap.php');

$uri = strtolower($_SERVER['REQUEST_URI']);
$url = $uri;
$query = '';
if (strpos($uri, '?')) {
    $url = substr($uri, 0, strpos($uri, '?'));
    $query = substr($uri, strpos($uri, '?'));
}
$url = trim($url, '/');
$arUrl = explode('/', $url);
if (Sayt::isRegistered() && Sayt::isAdmin()) {
    $folder = 'admin/';
} elseif (Sayt::isRegistered() && !Sayt::isAdmin()) {
    $folder = 'users/';
} elseif (count($arUrl) > 0 && $arUrl[0] == 'registration') {
    $folder = '';
} elseif (count($arUrl) > 0 && $arUrl[0] == '') {
    startLogin();
} else {
    $folder = '';
    $url = 'LoginUser';
}

route($folder, $url, $query);

function route($folder, $uri, $query)
{
    $arrUri = explode('/', trim($uri, '/'));
    if (count($arrUri) == 1 && $arrUri[0] == '') {
        $controller = 'Index';
        $action = 'actionIndex';
    } elseif (count($arrUri) == 1 && $arrUri[0]) {
        $controller = ucfirst($arrUri[0]);
        $action = 'actionIndex';
    } elseif (count($arrUri) == 2 && $arrUri[0] && $arrUri[1]) {
        $controller = ucfirst($arrUri[0]);
        $action = 'action' . ucfirst($arrUri[1]);
    }
    echo __NAMESPACE__ . '------';
    $file = PUT_APP . '/Controllers/' . $folder . $controller . '.php';
    if (file_exists($file)) {

        $controller = new $controller;
        $controller->zapros = $query;
        if (method_exists($controller, $action)) {
            $action = $controller->$action();

            //if ($controller->viewOn) {

            print_r($controller->viewContent);
            exit();
        } else {
            pageNotFound();
        }

    } else {
        pageNotFound();
    }

    exit();
}

function startLogin()
{
    header("Location: /loginuser");
    exit;
}

function pageNotFound()
{
    //header("Location: /err/404.html");
    exit;
}

function d($var = '')
{
    if (is_array($var)) {
        foreach ($var as $v) {
            pr($v);
        }
    } else {
        pr($var);
    }
    exit;
}

function pr($var2)
{
    echo '<pre>' . var_dump($var2);
}
