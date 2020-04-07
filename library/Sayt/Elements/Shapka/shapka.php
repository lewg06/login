<?php
session_start();
?>


<div style='position:relative;;width:100%;height:10%; margin:1%;'>
<span class='tel' style='position:absolute; left:5%;' >тел.8-800-555-555-55</span>
<input id='poisk'  type='text' value='Введите текст для поиска' style='width:60%;position:absolute;top:0%; left:20%;' onchange='alert(window.event);'></input>
<img class='korzina' src='pic/bag.png' ></img>
</div>

<div class='sdvig'>
<img class='shapka_pic' src='pic/customized.png' ></img>
</div>


<div id='menu' style='position:relative; width:90%; left:25%; margin:1%;' ><?php  require_once('menu/menu.php');  ?></div>
<input type='text' id='page_nom' value='_' style='display:none;width:100%;height:20px;'></inpit>

<br>



