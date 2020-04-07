<?php
ini_set('max_execution_time', 900);
//if (!($_POST) and !(stristr($_SERVER["HTTP_REFERER"],"dailywear.ru/index.php"))) {
//	header('Location: http://localhost:8080/orenpuh.ru/index.php');
//	exit();	
//	}

$put_connect=$_SERVER['DOCUMENT_ROOT'].'/php2/connect.php';
require_once($put_connect);

require_once($_SERVER['DOCUMENT_ROOT'].'/php2/ar_tovar_unserialize.php');

$ar_tovar=create_ar_tovar();
//print_r($ar_tovar['2672']); echo '<br><br>';
//exit;


$pdo=connect_dailywear();
//exit;echo 'stop';
$i=0;
$ar_insert=[];
foreach($ar_tovar as $id=>$attr){
	$i=$i+1;
//	if($i>50){break;}
  $str_insert='';
foreach($attr as $param=>$val){
//echo '<br>  id='.$id.'  '.$param.'='.$val . '  ---   ';
//echo '<br>';
//if($id<>2672){continue;}
//if($id==2672){print_r($val);}
if ($param=='id'){continue;}
	elseif ($param=='price'){$ar_insert[]="insert into tovar (id, param, price) values ('$id' , '$param' , '$val' )";}
	elseif ($param=='options'){
	 foreach($val as $razmer=>$ostatok){$ar_insert[]="insert into tovar (id, param, razmer, ostatok) values ('$id', '$param' , '$razmer' , '$ostatok' )";}}
	elseif ($param=='param'){
		foreach($val as $p=>$v){$ar_insert[]="insert into tovar (id, param, param_value) values ('$id' , '$p' , '$v' )";}}
	else {$ar_insert[]="insert into tovar (id, param, param_value) values ('$id' , '$param' , '$val' )";}
}}
//print_r($ar_insert);
$count=count($ar_insert);

//exit;

$i=0;
//Запись данных в таблицу TOVAR
foreach($ar_insert as $insert_tovar){$i=$i+1; echo '<br>'.$count.' --- '.$i; $pdo->exec($insert_tovar);}
echo 'Товары обновлены!!!';
?>


