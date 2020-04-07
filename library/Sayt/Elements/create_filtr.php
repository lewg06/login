<?php
require_once('/storage/emulated/0/htdocs/orenpuh.ru/php2/ar_menu1_unserialize.php');
require_once('/storage/emulated/0/htdocs/orenpuh.ru/php2/ar_menu2_unserialize.php');
require_once('/storage/emulated/0/htdocs/orenpuh.ru/php2/ar_tovar_unserialize.php');
$ar_menu1=create_ar_menu1();
$ar_tovar=create_ar_tovar();
$ar_menu2=create_ar_menu2();
//print_r($ar_menu2);
//exit;

/////////////
function filtr_menu1($id,$ar_menu1,$title){
	$tovar='"ar_tovar"';
	$str_menu1='';
foreach($ar_menu1 as $menu=>$param){	foreach($param as $arr=>$val){
if($val['id']==$id){
foreach($val['ar'] as $kod=>$text){
$str_menu1=$str_menu1."<li class='filtr' ><input type='checkbox' id='checkbox_$kod' name='checkbox_$id' class='checkbox' 
 onchange='ch_click1(this.id,$tovar)' value='$kod'><a href='#' name='checkbox_$kod' onclick='a_click1(this.name,$tovar);' >$text</a></li>";
}}}}
//$str_menu1="<li  class='filtr' ><input type='checkbox' id='checkbox_$id' name='checkbox_$id' class='checkbox'
// onchange='clear_check(this.name,$tovar)' value='VSE' ><a href='#' name='checkbox_$id' onclick='clear_check(this.name,$tovar);' >ОЧИСТИТЬ</a></li>".$str_menu1;
$str_menu1="<a href='#' name='checkbox_$id' onclick='clear_check(this.name,$tovar);' >Снять выделение</a></li>".$str_menu1;

$str_menu1="<li  class='filtr' ><a href='#' name='$id' onclick='filtr_go()' >ПОКАЗАТЬ</a></li>".$str_menu1;
$str_menu1="<ul class='filtr_submenu' name='filtr_submenu$id' >".$str_menu1.'</ul>';
//$str_menu1="<ul class='filtr_main' name='filtr_main$id' ><li class='filtr' ><a href='#'  onclick='filtr_view(this);' name= '$id' >$title</a>".$str_menu1.'</li></ul>';
$str_menu1="<li class='filtr' ><a href='#'  onclick='filtr_view(this);' name= '$id' >$title</a>".$str_menu1.'</li>';
//onmouseover='filtr_over("."'filtr_submenu$id'".");' onmouseout='filtr_out("."'filtr_submenu$id'".");'

 return $str_menu1; }


//////////////
function ar_filtr($punkt_menu,$ar_menu2,$ar_tovar){
$ar_filtr=[];
foreach($ar_menu2[$punkt_menu]['ar'] as $ar=>$tovar){

if(!$tovar){continue;}
	foreach($ar_tovar[$tovar] as $param=>$val){
//echo '<br>'.$param.' --- '.$val;
if($param=='id'|| $param=='price' || $param=='picture'){continue;}
if(!$ar_filtr[$param]){$ar_filtr[$param]=[];}
if(in_array($val,$ar_filtr[$param])){continue;}
if($param=='param'){  foreach($val as $name=>$v){if(!$ar_filtr[$name]){$ar_filtr[$name]=[];}if(in_array($v,$ar_filtr[$name])){continue;}
 $ar_filtr[$name][$v][]=$ar_tovar[$tovar]['categoryId'];} continue;}
elseif($param=='options'){foreach($val as $razmer=>$ostatok){$ar_filtr[$param][$razmer][]=$ar_tovar[$tovar]['categoryId'];continue;}}
else {$ar_filtr[$param][$val][]=$ar_tovar[$tovar]['categoryId'];}
}}

$ar_filtr2=[];
foreach($ar_filtr as $ar=>$val){$ar_filtr2[$ar]=asort($val );}
return $ar_filtr;}

///////////////////
function filtr_menu2($id,$ar_filtr,$ar_title){
	$str_menu2_itog='';
foreach($ar_filtr as $ar=>$param){
if(is_array($ar_title)){if(!array_key_exists($ar,$ar_title)){continue;}else{$title=$ar_title[$ar];}}else{$title=$ar;}
$str_menu2='';
//echo $ar;
foreach($param as $name=>$val){
$str_menu2=$str_menu2."<li class='filtr' ><input type='checkbox' id='checkbox_$ar"."_"."$name' name='checkbox_$ar' class='checkbox' 
 onchange='ch_click2(this.id);' value='$name'><a href='#' name='checkbox_$ar"."_"."$name' onclick='a_click2(this.name);' >$name</a></li>";
}
//$str_menu2="<li  class='filtr' ><input type='checkbox' id='checkbox_$ar' name='checkbox_$ar' class='checkbox' 
// onchange='clear_check(this.name);' value='VSE' ><a href='#' name='checkbox_$ar' onclick='clear_check(this.name);' >ОЧИСТИТЬ</a></li>".$str_menu2;
$str_menu2="<a href='#' name='checkbox_$ar' onclick='clear_check(this.name);' >Снять выделение</a></li>".$str_menu2;

$str_menu2="<li  class='filtr' ><a href='#' onclick='filtr_go()' >ПОКАЗАТЬ</a></li>".$str_menu2;
$str_menu2="<ul class='filtr_submenu' name='filtr_submenu$ar' >".$str_menu2.'</ul>';
$str_menu2="<li class='filtr' ><a href='#'  onclick='filtr_view(this);' name= '$ar' >$title</a>".$str_menu2.'</li>';
//$str_menu2="<ul class='filtr_main' name='filtr_main$ar' ><li class='filtr' ><a href='#'  onclick='filtr_view(this);' name= '$ar' >$title</a>".$str_menu2.'</li></ul>';
//$str_menu2='<div class="div_filtr_submenu" style="float:left;">'.$str_menu2.'</div>';

//file_put_contents("/storage/emulated/0/htdocs/orenpuh.ru/menu/menu2_$id"."_"."$ar.php",$str_menu2); //echo '<br>DANNIESOHRANENI';
//echo $str_menu2;
	$str_menu2_itog=	$str_menu2_itog.$str_menu2;
}

return $str_menu2_itog;
}

//////////////


$punkt_menu=3;
$menu_filtr="<ul class='filtr_main' name='filtr_main$punkt_menu' >";
//Создает массив для подготовки к созданию меню фильтра
$ar_filtr=ar_filtr($punkt_menu,$ar_menu2,$ar_tovar);

$tt=1 ;
if($tt>0){
$ar_title=[];

//$ar_title['Модель']='Модель';
$ar_title['Цвет']='Цвет';
//$ar_title['Сезон']='Сезон';
//$ar_title['Материал подошвы']='Материал подошвы';
//$ar_title['Материал верха']='Материал верхней части';
//$ar_title['options']='Размер обуви';
//$ar_title['options']='Размер одежды';
$ar_title['options']='Размер';
//$ar_title['Категория']='Категория';
//$ar_title['Предмет одежды']='Предмет одежды';
//$ar_title["Материал"]='Материал';
$ar_title["vendor"]='Производитель';
$ar_title["name"]='Вид ксессуара';
//$ar_title["Страна бренда"]='Страна бренда';

//$ar_title[]='';
}else{$ar_title=false;}
//$ar_title - массив содержащий имена заголовков и их исправления

//$menu_filtr=$menu_filtr.filtr_menu1($punkt_menu,$ar_menu1,'БРЕНД');//Кросс м-1 ж-97
//$menu_filtr=$menu_filtr.filtr_menu1($punkt_menu,$ar_menu1,'Вид одежды');//Одежда м-1597 ж-1596
//Аксессуары без filtr_menu1

//Чтобы вывести все возможности меню FALSE
//$menu_filtr=$menu_filtr.filtr_menu2(1,$ar_filtr,false).'</ul>';
$menu_filtr=$menu_filtr.filtr_menu2($punkt_menu,$ar_filtr,$ar_title).'</ul>';

echo $menu_filtr;
file_put_contents("/storage/emulated/0/htdocs/orenpuh.ru/menu/menu_filtr_$punkt_menu.php",$menu_filtr); //echo '<br>DANNIESOHRANENI';

?>



