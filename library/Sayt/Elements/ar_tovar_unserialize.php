<?php
function create_ar_tovar(){$file_ar_tovar=file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dannie/ar_tovar.txt');
if($file_ar_tovar===false){echo 'Не могу открыть файл с ar_tovar'; exit;}
$ar_tovar=unserialize($file_ar_tovar);
return $ar_tovar;}
?>