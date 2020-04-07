<?php
if (file_exists('/sdcard/Download/hh.php')){echo 'fail est';}
//copy('load_shop.php','/sdcard/Download/html_shop2/hh.php');
//copy('load_shop.php','hh.php');
$put_from='';
$put_to='/sdcard/Download/html_shop2/';

$ar_put=[];
$put_tovari='tovari';
$ar_put[]=$put_to.$put_tovari;
$put_opisanie='opisanie';
$ar_put[]=$put_to.$put_opisanie;
$put_otzivi='otzivi';
$ar_put[]=$put_to.$put_otzivi;
$put_korzina='korzina';
$ar_put[]=$put_to.$put_korzina;
$put_zakazi='zakazi';
$ar_put[]=$put_to.$put_zakazi;
$put_shabloni='shabloni';
$ar_put[]=$put_to.put_shabloni;


foreach($ar_put as $put_html){if (!file_exists($put_html)){mkdir($put_html);}}

$xml=simplexml_load_file('shop2.xml');

//Sozdanie menu
$categories=$xml->shop->categories;

$ar_menu=[];
$ar_tmp=[];

//Opredelenie korennogo menu
foreach($categories as $cat=>$attr){
foreach($attr as $at=>$val){
$id=(string) $val->attributes()['id'];
$parentId=(string) $val->attributes()['parentId'];
$name=(string) $val;
if(!$val->attributes()['parentId']){$ar_menu[]=['str'=>'id=MENU,'.$id,'id'=>$id,'name'=>'MENU,'.$name];}else {$ar_tmp[]=['id'=>$id,'parentId'=>$parentId,'name'=>$name];}
}}

$ii=1;
while ($ii>0){
$ii=0;
foreach($ar_menu as $ar=>$val_menu){
foreach($ar_tmp as $tmp=>&$val_tmp){
if($val_menu['id']==$val_tmp['parentId'] && $val_tmp['id']>0){
$str22=$val_menu['str'].','.$val_tmp['id'];
$name=$val_menu['name'].','.$val_tmp['name'];
$val_tmp['id']=$val_tmp['id'];
$ar_menu[]=['str'=>$str22,'id'=>$val_tmp['id'],'name'=>$name];
$val_tmp['id']=0;$val_tmp['parentId']=0;
}
$ii=$ii+$val_tmp['id'];
}}
}
$ar_tmp=[];

asort($ar_menu);

$offers=$xml->shop->offers->offer;	
//Добавление товара в массив с меню, здесь же можно посмотреть ошибки на ссылки товара)))
foreach($offers as $offer=>$p){
 $cat_id=$p->categoryId;
 $id=(string) $p->attributes()['id'];
foreach($ar_menu as $ar=>&$val){ if($cat_id==$val['id']){$val['tovar']=$val['tovar'].','.$id;}}}

$ar_menu1=[];
$ar_menu2=[];
$max_count=0;
//Убираем id= в str и разбираем её на массив, вычисляя макс длину массива и количество подменю
foreach($ar_menu as $ar=>&$p){$p['str']=substr($p['str'],3); 
$p['str']=explode(',',$p['str']);
$p['name']=explode(',',$p['name']);
$p['count']=count($p['str']);
if($max_count<count($p['str'])){$max_count=count($p['str']);}
}

//echo 'max='.$max_count;

///////// ar_menu1 start

$str_menu='';
$ar_roditel=[];
$ar_potomok=[];

$ar_menu1=[];

for($i=$max_count-1;$i>=0;$i=$i-1){
 $ar_tmp=[];
  foreach($ar_menu as $are=>$el){
	    if(($el['count']-1)==$i){
      $str_put=create_str_put($i-1,$el['name']);
      $str_roditel=create_str_roditel($i-1,$el['name']);

      if (!$ar_tmp[$str_put]) {$ar_tmp[$str_put]['ar']=[];}
       if(!in_array($el['name'][$i],$ar_tmp[$str_put]['ar'])){
//	echo '<br>'.$el['str'][$i];    Без добавления ID в качестве номера в массиве      $ar_tmp[$str_put]['ar'][]=$el['name'][$i];
           $ar_tmp[$str_put]['ar'][$el['str'][$i]]=$el['name'][$i];
           $ar_tmp[$str_put]['roditel']=$str_roditel;
           $ar_tmp[$str_put]['roditel_id']=$el['str'][$i-2];
           $ar_tmp[$str_put]['id']=$el['str'][$i-1];
           $ar_tmp[$str_put]['uroven']=$i;
           $ar_tmp[$str_put]['name']=$el['name'][$i-1];
      }}
   }
$ar_menu1[]=$ar_tmp;
}

//file_put_contents('/storage/emulated/0/htdocs/orenpuh.ru/dannie/ar_menu1.txt',serialize($ar_menu1));
//echo 'ar_menu1 ok';   exit;
//echo '<br><br>';
//foreach($ar_menu1 as $ar=>$val){if($val['count']==1){echo '<br><br>'.print_r($val);}}

function create_str_put($kol,$ar){$str=''; foreach($ar as $a=>$ell){if($kol<0){break;} $str=$str.$ell.',';$kol=$kol-1;} if($str==''){$str='menu';} return ($str);}
function create_str_roditel($kol,$ar){$str=''; foreach($ar as $a=>$ell){if($kol<=0){break;} $str=$str.$ell.',';$kol=$kol-1;} if($str==''){$str='menu';} return ($str);}
//print_r($ar_menu1);
/////////// ar_menu1  end
///////////// menu new start
$i=0;
if($i>0){
	//
$str_menu='';
$str_zamena='%%';
$ar_poisk=[];
$ar_poisk2=[];
for($i=0;$i<count($ar_menu1)-1;$i=$i+1){
	$nom=$ar_menu1[$i];
	foreach($nom as $potomok=>$el){
		$roditel=$el['roditel'];
	  $str_podmenu='';
	  $uroven='uroven'.$el['uroven'];
    $str_menu='';

   foreach($el['ar'] as $rr=>$li){
    $pp=$potomok.$li.',';					
	   if($ar_poisk[$pp]){$ar_poisk2[$potomok]=$ar_poisk2[$potomok]."<li id='$rr' >".$li."<ul name='uroven$i' class='submenu'>".$ar_poisk[$pp].'</ul></li>';}
     else {$ar_poisk2[$potomok]=$ar_poisk2[$potomok]."<li id='$rr' >".$li."</li>";}
   }
 }
$ar_poisk=[];foreach($ar_poisk2 as $ap=>$app){$ar_poisk[$ap]=$app;}$ar_poisk2=[];
}

$str_menu='';
foreach($ar_poisk as $ar=>$val){$str_menu=$str_menu.'<ul class="menu">'.$val.'</ul>';}
$put_menu='/storage/emulated/0/htdocs/orenpuh.ru/php2/menu.php';
echo $str_menu;
//file_put_contents($put_menu,$str_menu);
//
}
///////////// menu new end

//exit;
///////////////////////////// ar_menu2 start
$i=0;
if($i>0){
//////////////////
//print_r($ar_menu);
$str_menu='menu';
$str_menu2='podmenu';

for ($nom=0;$nom<$max_count;$nom=$nom+1){
foreach($ar_menu as $ar=>$pp){
if($pp['name'][$nom] && $pp['tovar']){
	if($pp['name'][$nom]==$str_menu){}
else{$str_menu=$pp['name'][$nom];}
//if ($pp['name'][$nom+1] && $pp['name'][$nom+1]!=$str_menu2){$ar_menu1[$nom][$str_menu][]=$pp['name'][$nom+1];$str_menu2=$pp['name'][$nom+1]; }
if ($pp['name'][$nom+1]!=$str_menu2){$ar_menu1[$nom][$str_menu][]=$pp['name'][$nom+1];$str_menu2=$pp['name'][$nom+1];}
}}}

$str_menu3='menu';
foreach($ar_menu as $ar=>$ppp){
foreach($ppp['str'] as $arr=>$pppp){
$ar_menu2[$pppp]['name']=$ppp['name'][$arr];
$ar_menu2[$pppp]['ar']=$ar_menu2[$pppp]['ar'].$ppp['tovar'];
}}
//print_r($ar_menu2);
//file_put_contents('/storage/emulated/0/htdocs/orenpuh.ru/dannie/ar_menu2.txt',serialize($ar_menu2));
echo 'ar_menu2 ok';
exit;
////////////////////
}
///////////////////////// ar_menu2 end
///////////////////////// menu old start
$i=0;
if($i>0){
///////////////

//foreach($ar_menu1 as $ar=>$val){echo '<br><br>'.print_r($val);}

//echo 'end menu';
//echo '<br>';

$str_menu='';
$str_zamena='%%';
$ar_poisk=[];
$ar_poisk2=[];
for($i=count($ar_menu1);$i>0;$i=$i-1){
	$nom=$ar_menu1[$i-1];
	foreach($nom as $arr=>$el){
	$str_podmenu='';
	foreach($el as $arrr=>$podmenu){
		if($podmenu){
       $kol=0;
		   foreach($ar_poisk as $ar_p=>$poisk){if($podmenu==$ar_p){$str_podmenu=$str_podmenu.'<li>'.$podmenu.'<ul>'.$poisk.'</ul></li>';
		   $kol=$kol+1;}}
	   if($kol==0){		$str_podmenu=$str_podmenu.'<li>'.$podmenu.'</li>';}
	 }
	$ar_poisk2[$arr]=$str_podmenu;
	}
  
}
$ar_poisk=[];
foreach($ar_poisk2 as $ap=>$app){$ar_poisk[$ap]=$app;}
$ar_poisk2=[];
}


echo '<br><br>'.print_r($ar_menu);
//foreach($ar_poisk as $ar=>$val){echo '<br><br>'.$ar.'='.print_r($val);}
$str_menu='<ul id="menu">';
foreach($ar_poisk as $ar=>$val){$str_menu=$str_menu.'<li>'.$ar.'<ul>'.$val.'</ul><li>';}
$str_menu=$str_menu.'</ul>';
//echo $str_menu;
$put_menu='/storage/emulated/0/htdocs/orenpuh.ru/php2/menu.php';
//file_put_contents($put_menu,$str_menu);

///////////
}
////////////// menu old end
////////////// shablon start
$i=0;
if($i>0){
////////////

$i=0;

$put_tovari='/storage/emulated/0/htdocs/orenpuh.ru/tovari';

foreach($offers as $offer=>$p){
	$i=$i+1;
//	if ($i>0){break;}
foreach( $p->attributes() as $attr=>$attr_val){	if($attr=='id'){$id=$attr_val;}	if($attr=='available'){$nalichie=$attr_val;}	}

$put_shablon='/storage/emulated/0/htdocs/orenpuh.ru/shabloni/shablon_shop2.php';
$put_id=$put_tovari.'/id'.$id;
if(!file_exists($put_id)){mkdir($put_id);}
$file=$put_tovari.'/id'.$id.'/id'.$id.'.php';
if(!file_exists($put_shablon)){echo 'NETFAILASHABLONA'; return;}else{$str_shablon=file_get_contents($put_shablon);}

$ar_dannie=[];
$ar_dannie['id']=(string) $id;
$ar_dannie['options']='';
foreach($p as $param=>$val){//$ar[$aa]=0;;
$val2=(string) $val;
if($param==='param'){
$ar_dannie['%%param_val%%']=$ar_dannie['%%param_val%%'].'<div style="background:cyan;border:2px;">'. $val['name'].' : '.$val2.'</div>'; }
else{
if($param=='options'){$ar_options=explode(';',$val2);
foreach($ar_options as $opt){ $ar_dannie['options']=$ar_dannie['options'].'<div style="position:relative; border:2px; background:yellow;">'.$opt.'</div>';       }
}
else{$ar_dannie['%%'.$param.'%%']=$val2;}}
}
$ar_dannie['%%param_val%%']=$ar_dannie['%%param_val%%'].'Размеры : '.$ar_dannie['options'];
$ar_dannie['%%picture%%']='http://localhost:8080/orenpuh.ru/pic_div/'.$id.'.jpeg';
foreach($ar_dannie as $ind=>$v){$str_shablon=str_replace($ind,$v,$str_shablon);}

//Запись id в файл
file_put_contents($file,$str_shablon);
}

echo '<br>';
echo 'END';
/////////
}

////////////////shablon end



?>



 


