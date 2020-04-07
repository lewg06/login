<?php
session_start();
if(!$_POST['filtr']){	header('Location: http://localhost:8080/index.php');exit;}
//print_r($_POST);
//setcookie('page_nom','0');
 
$ar_filtr=explode('_%%_',$_POST['filtr']);

$ar_q1=[];

foreach($ar_filtr as $nom=>$text){
$name=substr($text,0,stripos($text,'_===_')); $param=explode('_%%%_',substr($text,stripos($text,'_===_')+5,500));
$ar_q1[$name]=$param;}

if(empty($ar_q1['ar_tovar'])){$ar_q1['ar_tovar']=$ar_q1['ar_filtr'];}

$ar_select2=[];
//Формирование массива для запроса без пустых элементов
foreach($ar_q1 as $name=>$ar){foreach($ar as $ind=>$val){if($val){$ar_select2[$name][]=$val;}}}

//print_r($ar_select2);
$ar_select=[];

$nom=0;
foreach($ar_select2 as $name=>$ar){
	//Удаление checkbox_
 $name2=substr($name,9,strlen($name));
//echo $name2;
		$nom=$nom+1;
if($name==='ar_tovar'){
require_once('ar_menu2_unserialize.php');
$ar_menu2=create_ar_menu2();
 $str_id='';
 foreach($ar as $val){if(!$ar_menu2[$val]['ar']){continue;} foreach($ar_menu2[$val]['ar'] as $ind=>$id){if(!$id){continue;} $str_id=$str_id.$id.',';} }
  $str_id=substr($str_id,0,strlen($str_id)-1);
  //$ar_select[$nom]['on']=" t0.id=t$nom.id ";$ar_select[$nom]['where']=" t$nom.id in($str_id) ";
  $ar_select[$nom]['on']=""; $ar_select[$nom]['where']=" t0.id in($str_id) ";
}
elseif($name==='checkbox_options' ){
	$str_razmer='';
	foreach($ar as $razmer){if(!$razmer){continue;} $str_razmer=$str_razmer.$razmer.',';} 
  $str_razmer=substr($str_razmer,0,strlen($str_razmer)-1);
//  $ar_select[$nom]['on']=" t1.id=t$nom.id ";  $ar_select[$nom]['where']=" t$nom.razmer in($str_razmer) ";
  $ar_select[$nom]['on']="";  $ar_select[$nom]['where']=" t0.razmer in($str_razmer) ";
}
elseif($name==='ar_price'){}
else{
	//ar_filtr-это идентификатор подменю
	if($name==='ar_filtr'){continue;}
	$str_ower='';
	foreach($ar as $ower){if(!$ower){continue;} $str_ower=$str_ower."'".$ower."'".',';} 
  $str_ower=substr($str_ower,0,strlen($str_ower)-1);
  $ar_select[$nom]['on']=" join tovar t$nom on t0.id=t$nom.id ";
  $ar_select[$nom]['where']=" ( t$nom.param='$name2' and t$nom.param_value in($str_ower) ) ";
//  echo $str_ower;

}

}

$query='select t0.id from tovar t0 ';
foreach($ar_select as $ar=>$val){$query=$query.$val['on'];}
$query=$query.'where ';
foreach($ar_select as $ar=>$val){$query=$query.$val['where'].' and ';}

$query=substr($query,0,strlen($query)-4).' group by t0.id';
//echo '<br>'.$query;
//exit;
//echo '<br>';//.print_r($ar_select);

$put_connect=$_SERVER['DOCUMENT_ROOT'].'/php2/connect.php';
require_once($put_connect);
$pdo=connect_dailywear();

$zapros=$pdo->query($query);

$i=0;$count=0;$page=0;
$left=0;$top=0;$ar_session=[];
if (!$zapros){echo print_r($pdo->errorinfo());exit();}
while($tovar=$zapros->fetch(PDO::FETCH_ASSOC)){
$str='';
$i=$i+1;
$count=$count+1;
$ar_session[$page][]=$tovar['id'];
if($count>49){$count=0; $page=$page+1;}
}

$_SESSION['page']=$ar_session;
//echo '<br> session--  '.print_r($_SESSION['page'][22]);

//echo $page;
require_once($_SERVER['DOCUMENT_ROOT'].'/php2/page.php');
view_page(0);

//require_once($_SERVER['DOCUMENT_ROOT'].'/php2/page_next_a.php');

?>




