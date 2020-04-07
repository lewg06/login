<?php
session_start();

function view_page($page){

	if($_SESSION['page']==false){echo 'По данному запросу нет данных!'; exit;}
$ar_session=$_SESSION['page'];
$str_div='';
$i=0;
$left=0;$top=0;
foreach($ar_session[$page] as $id){
$i=$i+1;
$str_div=str_replace('%%left%%',$left,file_get_contents($_SERVER['DOCUMENT_ROOT'].'/tovari/id'.$id.'/div'.$id.'.php'));
$str_div=str_replace('%%top%%',$top,$str_div);
$str_div=str_replace('%%_cena_%%',file_get_contents($_SERVER['DOCUMENT_ROOT'].'/_param/cena/cena'.$id.'.php'),$str_div);
$str_div=str_replace('%%_razmeri_%%',file_get_contents($_SERVER['DOCUMENT_ROOT'].'/_param/razmeri/razmeri'.$id.'.php'),$str_div);
echo $str_div;

$left=$left+250;$top=$top;
 if (($i%5)==0){$top=$top+310;$left=0;}
}
//return $top+300;
$top=$top+50;
if(count($_SESSION['page'])==1){exit;}
$str_a="<div class='next_page_div' style='position: absolute; top:$top; width: 100%; text-align:center; '>";
$i=0;
foreach($_SESSION['page'] as $pages=>$ar){
$i=$i+1;
if($page==$pages){
$str_a=$str_a.'<a href="#" name="this_page" class="this_page_a" >' .$i. '</a>';
	}else{//style="padding: 3px ;"
$str_a=$str_a.'<a href="#" name="next_page" class="next_page_a" onclick="next_page('.$pages.');"  >' .$i. '</a>';
//$str_a=$str_a.'<li name="next_page" class="next_page" onclick="next_page('.$pages.');" style="padding: 3px;position:absolute; top:'.$top.' ;" >' .$i. '</li>';
}
}
echo $str_a.'</div>';
$top=$top+40;
require_once($_SERVER['DOCUMENT_ROOT'].'/floor.php');
}

?>




