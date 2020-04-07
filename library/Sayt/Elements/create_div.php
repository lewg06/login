<?php
////////////
ini_set('max_execution_time', 900);
$xml=simplexml_load_file('shop2.xml');
//перезапись таблицы с данными
//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dannie/dannie_kross_men.php','');
//print_r($xml);
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/_param')){mkdir($_SERVER['DOCUMENT_ROOT'].'/_param');}
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/_param/cena')){mkdir($_SERVER['DOCUMENT_ROOT'].'/_param/cena');}
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/_param/razmeri')){mkdir($_SERVER['DOCUMENT_ROOT'].'/_param/razmeri');}

if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/kross')){mkdir($_SERVER['DOCUMENT_ROOT'].'/kross');}
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/kross/men')){mkdir($_SERVER['DOCUMENT_ROOT'].'/kross/men');}
if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/kross/women')){mkdir($_SERVER['DOCUMENT_ROOT'].'/kross/women');}

$put_tovari='tovari';
$put_from='';
//$put_to='/sdcard/Download/html_shop2/';
$put_to=$_SERVER['DOCUMENT_ROOT'].'/';
$put_shablon=$_SERVER['DOCUMENT_ROOT'].'/shabloni/shablon_div1.php';

require_once($_SERVER['DOCUMENT_ROOT'].'/php2/ar_menu2_unserialize.php');
$ar_menu2=create_ar_menu2();
//print_r($ar_menu2);
//exit;

$file_ar_pic=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dannie/param_pic_unserialize.txt');
if($file_ar_pic===false){echo 'Не могу открыть файл с param_pic_unserialize'; exit;}
$ar_pic=unserialize($file_ar_pic);
//print_r($ar_pic);
//exit;


$i=0;
$c=0;$top=0;$left=0;
$offers=$xml->shop->offers->offer;
$ar_itog=[];$ar_dannie=[];$ar_tovar=[];
foreach($offers as $offer=>$p){
   foreach( $p->attributes() as $attr=>$attr_val){	if($attr=='id'){$id= $attr_val;}	if($attr=='available'){$nalichie=$attr_val;}	}
//Поиск id в массиве
//			if(!in_array($id,$ar_menu2['1']['ar'])){continue;}
	
//	echo '<br>'.$i.'---'.count($ar_menu2['MENU']['ar']);
	$i=$i+1;
	$c=$c+1;
//	if ($i>50){break;}
$str_shablon='';

$put_id=$put_to.$put_tovari.'/id'.$id;
//echo $put_id.'<br>';continue;
if(!file_exists($put_id)){mkdir($put_id);}
$file=$put_to.$put_tovari.'/id'.$id.'/div'.$id.'.php';
//$file=$put_to.$put_tovari.'/id'.$id.'/div'.$id.'.php';
if(!file_exists($put_shablon)){echo 'NETFAILASHABLONA'; return;}else{$str_shablon=file_get_contents($put_shablon);}

$id=(string) $id;
$ar_tovar[$id]['id']=(int) $id;
$ar_tovar[$id]['available']= (string) $nalichie;

$ar_dannie[$id]=[];
$ar_dannie[$id]['%%id%%']=$id;

foreach($p as $param=>$val){
 $val2=(string) $val;
if ($param==='param') {$val3=(string) $val['name']; $ar_dannie[$id]["%%$val3%%"][$param]=$val2;   $ar_tovar[$id]['param'][$val3]=$val2;}
else
 {if ($param=='options') {
	 $ar_options=explode(';',$val2);
    //foreach($ar_options as $opt){ $ar_dannie[$id]['options']=$ar_dannie[$id]['options'].'<div style="width:10px border:2px solid black; background:grey;">'.$opt.'</div>';       }
    foreach($ar_options as $opt){
    if($opt) {
      $opt2=substr($opt,0,stripos($opt,'-'));
      $kol=0;
      $kol=substr($opt,stripos($opt,'-')+1,10);
      $cvet='grey';
      $name='id'.$id.'li'.$opt2.'ostatok'.$kol;
$class_options='class="razmeri_li_no" ';
      if($kol>0){$cvet='yellow'; $class_options='class="razmeri_li_yes" ';}
      //echo '<br>'.$kol;
      //$ar_dannie[$id]['options']=$ar_dannie['options']."<a href='#' style='border-radius: 3px 3px 3px 3px;width:20px;float:left;margin:3px; border:2px solid black; display:block;padding:1px; background: $cvet;'>".$opt2.'</a>'; }}
//      $ar_dannie[$id]['options']=$ar_dannie[$id]['options']."<li name='$name' style='position:relative;display: inline-block; float:left; border-radius: 3px 3px 3px 3px;width:18px;margin:2px; border:2px solid black; display:block;padding:1px; background: $cvet;'>".$opt2.'</li>'; 
//      $ar_dannie[$id]['options']=$ar_dannie[$id]['options']."<li name='$name' class='razmeri_li' style='position:relative;display: inline-block; float:left; border-radius: 3px 3px 3px 3px;width:18px;margin:2px; border:2px solid black; display:block;padding:1px; background: $cvet;'>".$opt2.'</li>'; 
//      $ar_dannie[$id]['options']=$ar_dannie[$id]['options']."<li name='$name' $class_options style='position:relative;display: inline-block; float:left; border-radius: 3px 3px 3px 3px;width:18px;margin:2px; border:2px solid black; display:block;padding:1px; background: $cvet;'>".$opt2.'</li>'; 
      $ar_dannie[$id]['options']=$ar_dannie[$id]['options']."<li id='$name' $class_options >".$opt2.'</li>'; 
      $ar_tovar[$id]['options'][$opt2]=$kol;}}}
   else{$ar_dannie[$id]['%%'.$param.'%%']=$val2; $ar_tovar[$id][$param]= (string) $val;}   }
}
//Размер картинки по умолчанию
if($ar_dannie[$id]['%%picture%%']){$ar_dannie[$id]['%%picture%%']='http://www.dailywear.ru/pic_div/'.$id.'.jpeg';}
//Изменил путь откуда брать картинки
$ar_dannie[$id]['%%img_size%%']=' width:100%;height:150px; ';
$f=1;
if($f>0){
//Добавление параметров картинок в массив
//if($ar_dannie[$id]['%%picture%%']){$ar_img_size=getimagesize($ar_dannie[$id]['%%picture%%']);
if($ar_dannie[$id]['%%picture%%']){$ar_img_size=getimagesize($_SERVER['DOCUMENT_ROOT']."/pic_div/$id.jpeg");
//echo $ar_img_size[0].'--'.$ar_img_size[1];
if($ar_img_size[0]<$ar_img_size[1]){$h=150;$w=$ar_img_size[0]/($ar_img_size[1]/150); $w=round($w,0,PHP_ROUND_HALF_DOWN);}
else{$w=178;(int) $h=$ar_img_size[1]/($ar_img_size[0]/178);$h=round($h,0,PHP_ROUND_HALF_UP);}

$ar_dannie[$id]['%%img_size%%']='height:'.$h.'px;width:'.$w.'px;';
if ($h>150){$ar_dannie[$id]['%%img_size%%']='height:150px;';}
}

echo '<br>'.$ar_dannie[$id]['%%picture%%'] .'==='. $ar_dannie[$id]['%%img_size%%'];;
}

if(!$ar_dannie[$id]['options']){//$ar_dannie[$id]['options']='Уточните у продавца';
 $ar_dannie[$id]['options']='';	}
//$ar_dannie[$id]['options']='<ul class="razmeri_ul" style="width:100%;position:relative;text-align:center;display:block;width:100%;">'.$ar_dannie[$id]['options'].'</ul>';
$ar_dannie[$id]['options']="<ul id='razeri_ul_$id' class='razmeri_ul' style='margin:0; padding:0;' >".$ar_dannie[$id]['options'].'</ul>';
$ar_dannie[$id]['options']=$ar_dannie[$id]['options'];
//$ar_dannie[$id]['options']="<div class='razmeri_div' >Размеры:</div>".$ar_dannie[$id]['options'];
//$ar_dannie[$id]['%%left%%']=$left;
//$ar_dannie[$id]['%%top%%']=$top;

foreach($ar_dannie[$id] as $ind=>$v){$str_shablon=str_replace($ind,$v,$str_shablon);}

$left=$left+200;
$top=$top;
if (($i%5)==0){$top=$top+350;$left=0;}

//Заполнение файлов с ценами и размерами
file_put_contents($_SERVER['DOCUMENT_ROOT']."/_param/cena/cena$id".".php",$ar_dannie[$id]['%%price%%']);
file_put_contents($_SERVER['DOCUMENT_ROOT']."/_param/razmeri/razmeri$id".".php",$ar_dannie[$id]['options']);
//echo $str_shablon;

//////////////
safe_kross_dannie($str_shablon);

//Запись id в файл
file_put_contents($file,$str_shablon);
//exit;
}
//print_r($ar_tovar);
///////////
//create_kross_men();

//Запись массива для сохранения в базе данных
//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dannie/ar_tovar.txt',serialize($ar_tovar));
echo 'ok';


///////////////////////////////
function safe_kross_dannie($str_shablon){
//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dannie/dannie_kross_men.php',$str_shablon,FILE_APPEND);

//echo $str_shablon;
//$put_kross_dannie=$_SERVER['DOCUMENT_ROOT'].'/dannie/dannie_kross_men.php';
//file_put_contents($put_kross_dannie,$str_shablon,FILE_APPEND);
}
function create_kross_men(){
$put_kross_shablon=$_SERVER['DOCUMENT_ROOT'].'/shabloni/shablon_kross.php';
$put_kross_men=$_SERVER['DOCUMENT_ROOT'].'/kross/men/kross_men.php';
if(!copy($put_kross_shablon,$put_kross_men)){echo 'Не удалось скопировать SHABLON_KROSS в KROSS_MEN'; exit;}
else{header('Location: http://localhost:8080/kross/men/kross_men.php');}
}

//ECHO ($_SERVER['DOCUMENT_ROOT']);
//print_r(getimagesize('http://outmaxshop.ru/image/data/products-drop/139/1.jpg'));

//print_r($ar_dannie);

//	header('Location: http://localhost:8080/kross/men/kross_men.php');
	

?>




