<?php
//Нужно проверить в случае неправильного подключения, что выводит
function connect_dailywear(){
try{ $pdo= new PDO('mysql:host=localhost;dbname=dailywear','root','');}
catch(EPDOException $er){echo print_r($er);exit;}
return $pdo;
}
?>