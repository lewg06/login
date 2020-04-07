<?php
namespace Lib\Sayt;
class Sayt {
    public static $error = false;
    public static $message = '';
	static function isRegistered(){
		if ( isset($_SESSION['auth']) && !empty($_SESSION['auth']) ){
			return true;}
		elseif (isset($_REQUEST['login']) && isset($_REQUEST['pass']) && Sayt::userRegistered($_REQUEST['login'],$_REQUEST['pass'])){
			return true;
		}
		else {
			return false;
		}
	}

	private function getHasPass($pass){
	    return $pass;
    }

    private function errorMsg($msg){
        self::$error = true;
        self::$message = $msg;
    }
	
	static function userRegistered($login,$pass){
	    $pass = self::getHasPass($pass);
		$pdo = Sayt::connectDb();
		if($pdo){
			$query = 'SELECT * FROM users WHERE  login = "' . $login . '"';
			$zapros = $pdo->query($query);
			if (!$zapros){
			    self::errorMsg($pdo->errorinfo());
			}
			if (empty($zapros->fetch(PDO::FETCH_ASSOC))){
                self::errorMsg('Такой пользователь не зарегистрирован!');
			}
			else {
				$query = 'SELECT * FROM users WHERE login ="' . $login . '" AND password ="'. $pass . '"';
				$zapros = $pdo->query($query);
				if (!$zapros){
				    self::errorMsg($pdo->errorinfo());
				}
				if (empty($userId = $zapros->fetch(PDO::FETCH_ASSOC))){
                    self::errorMsg('Вы ввели неверный пароль, повторите еще раз!');
				}
				else {
					$_SESSION['auth'] = $userId['login'] === 'admin' ? 'admin' : 'user';
					$_SESSION['id'] = $userId['id'];
					return true;
				}
			}
		} else {
		    self::errorMsg('Не возможно подключтиться к базе данных!');
		}
		$_SESSION = [];
        return false;
	}
	
	static function isAdmin(){
		if(Sayt::isRegistered() && Sayt::getUser() === 'admin')  return true;
		return false;
	}
	
	static function getUser(){
		if(Sayt::isRegistered()) return $_SESSION['auth'];
		return false;
	}
	
	static function getUserId(){
		if(Sayt::isRegistered()) return $_SESSION['id'];
		return false;
	}
	
	static function getReqUser(){
		if (!empty($_REQUEST['login'])){
			return $_REQUEST['login'];
		}
		return False;
	}
	
	static function clearUser(){
		$_SESSION['auth'] = '';
		$_SESSION['id'] = '';
		$_SESSION = [];
		header("Location: /login");
	}
	
	static function createToGeneral($href = '',$text = ''){
		if(empty($href)) {
			return '';
		}
		$str = '';
		//foreach ($ar as $href=>$text) {
			$str = $str . '<a href="' . $href . '" >' . $text . '</a>';
		//}
		return $str;
	}
	
	static function connectDb(){
	    $config = require_once(PUT_APP . '/Config.cnfg');
	    $dbName = $config['dbName'];
        $host = $config['dbServer'];
        $user = $config['dbUser'];
        $pass = $config['dbPass'];
		try{ $pdo= new PDO("mysql:host=$host;dbname=$dbName;charset=utf8;",$user,$pass);}
		catch(EPDOException $er){echo print_r($er);}
		return $pdo;
	}
}
