<?php
session_start();
?>


<div id='shapka' style='position:relative;width:90%; height:25%; left:5%; margin:1%; box-shadow: 0 0 10px rgba(0,0,0,0.5); border:1px solid green;'>
<div name='leybl' style='width:10%;height:100%; padding:1%; '>
<a href='#' style='position: absolute; top:0%;'><img src='pic/logotip3.png' style='height:100%;'></img></a>
</div>
<div style='position:absolute;top:0%;width:80%;left:10%;'><img src='pic/customized.png' style='height:100%;width:100%' ></img></div>


<div class='korzina_div' onmouseover='korzina_div_over();' >
<ul name='korzina_ul' class='korzina_ul' onmouseout='korzina_ul_out();'>
<li id='korzina_no' class='korzina_no_li'><a href='#' class='korzina_no_a' onclick='korzina();'><img src='pic/korzina2.png' style='height:100%;'></img></a></li>
<li id='korzina_yes' class='korzina_yes_li' onclick='korzina_li(this);'><a href='korzina.php'><img src='pic/korzina_pokupok.png' style='height:100%;'></img></a></li>
<li id='korzina_clear' class='korzina_clear_li' onclick='korzina_clear();'>Очистить корзину</li>
<li id='korzina_pusto' class='korzina_pusto_li' onclick='korzina_li(this);'>Вы ещё не добавляли товар в корзину</li>
<li id='korzina_tovar' class='korzina_tovar_li' onclick='korzina_li(this);'></li>
</ul>
</div>
</div>
<div style='position:relative;;width:100%;height:5%; margin:1%;'>
<input id='poisk'  type='text' value='Введите текст для поиска' style='width:60%;position:absolute;top:0%; left:20%;' onchange='alert(window.event);'></input>
</div>
<div id='menu' style='position:relative; width:90%; left:25%; margin:1%;' ><?php  require_once('menu/menu.php');  ?></div>
<input type='text' id='page_nom' value='_' style='display:none;width:100%;height:20px;'></inpit>

<br>



