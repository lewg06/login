<?php

//$put_connect=$_SERVER['DOCUMENT_ROOT'].'/orenpuh.ru/php2/connect.php';
$put_connect='/storage/emulated/0/htdocs/orenpuh.ru/php2/connect.php';
require_once($put_connect);
$pdo=connect_dailywear();

echo 'start  ';

//$query="select nom,id,param,param_value from tovar where param='picture' and param_value<>'' and id>5192";
$query="select nom,id,param,param_value from tovar where param='picture' and param_value<>'' group by id";
$zapros=$pdo->query($query);

echo 'zapros  ';
//$zapros=$pdo->prepare($query);
//$zapros->execute(["email=>$email,pass=>$pass"]);

if (!$zapros){echo print_r($pdo->errorinfo());exit();}

echo 'result  ';

$ar_pic=[];
$i=0;
while($tovar=$zapros->fetch(PDO::FETCH_ASSOC)){
$str='';
$i=$i+1;
//echo '<br>'.$i.$tovar['nom'].' -- '.$tovar['id'];
//$ar_pic[$tovar['nom']]['id']=$tovar['id'];
//echo print_r($tovar);
//if($i>5){exit;}
$pic= $tovar['param_value'];
//echo $pic;
//print_r(getimagesize($pic));
//copy($pic,"/sdcard/Download/html_shop2/pic/".$tovar['id'].".jpeg");
//$str=$tovar['nom'].'_%_'.getimagesize($pic)[0].'_%%_'.getimagesize($pic)[1].PHP_EOL;
//file_put_contents('/storage/emulated/0/htdocs/orenpuh.ru/dannie/param_pic.txt',$str,FILE_APPEND);
}

//if (!$user || $user==''){echo 'Проверьте введеное ИМЯ ПОЛЬЗОВАТЕЛЯ или ПАРОЛЬ';exit();}

//@$_SESSION['user']=$user;
//@$_SESSION['user_id']=$user_id;

//setcookie("user",$user,"0","/");
//setcookie('user_id',$user_id,0,'/');

$f=fopen('/storage/emulated/0/htdocs/orenpuh.ru/dannie/param_pic.txt','r');
while($text=fgets($f)){
$nom=substr($text,0,stripos($text,'_%_'));
$width=substr($text,stripos($text,'_%_')+3,stripos($text,'_%%_')-stripos($text,'_%_')-3);
$height=substr($text,stripos($text,'_%%_')+4,strlen($text));
$ar_pic[$nom]['width']=$width;$ar_pic[$nom]['height']=$height;
}

$ar_pic2=[]; foreach($ar_pic as $ar=>$id){$ar_pic2[$ar]['width']=$id['width']; $ar_pic2[$ar]['height']=$id['height'];}
//file_put_contents('/storage/emulated/0/htdocs/orenpuh.ru/dannie/param_pic_unserialize.txt',serialize($ar_pic2),FILE_APPEND);


?>




