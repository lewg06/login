<?php
function create_ar_menu1(){
$file_ar_menu1=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dannie/ar_menu1.txt');
if($file_ar_menu1===false){echo 'Не могу открыть файл с ar_menu1'; exit;}
$ar_menu1=unserialize($file_ar_menu1);
return $ar_menu1;}

?>