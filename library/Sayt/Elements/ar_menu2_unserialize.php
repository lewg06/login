<?php

function create_ar_menu2(){
$file_ar_menu2=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dannie/ar_menu2.txt');
if($file_ar_menu2===false){echo 'Не могу открыть файл с ar_menu2'; exit;}
$ar_menu2_tmp=unserialize($file_ar_menu2);
$ar_menu2=[];
foreach($ar_menu2_tmp as $tmp=>$val){$ar_menu2[$tmp]['name']=$val['name'];$ar_menu2[$tmp]['ar']=explode(',',$val['ar']);
//echo '<br>'.$tmp.'='.$val['name'];
}
return $ar_menu2;}

?>