<?php
class AdminMag extends Model {
	
	private function insertTovar () {
		ini_set('max_execution_time', 900);
		//if (!($_POST) and !(stristr($_SERVER["HTTP_REFERER"],"dailywear.ru/index.php"))) {
		//	header('Location: http://localhost:8080/orenpuh.ru/index.php');
		//	exit();	
		//	}

		$put_connect=$_SERVER['DOCUMENT_ROOT'].'/php2/connect.php';
		require_once($put_connect);

		require_once($_SERVER['DOCUMENT_ROOT'].'/php2/ar_tovar_unserialize.php');

		$ar_tovar=create_ar_tovar();
		//print_r($ar_tovar['2672']); echo '<br><br>';
		//exit;

		$pdo=connect_dailywear();
	//exit;echo 'stop';
	$i=0;
	$ar_insert=[];
	foreach($ar_tovar as $id=>$attr){
		$i=$i+1;
		//	if($i>50){break;}
		$str_insert='';
		foreach($attr as $param=>$val){
			//echo '<br>  id='.$id.'  '.$param.'='.$val . '  ---   ';
			//echo '<br>';
			//if($id<>2672){continue;}
			//if($id==2672){print_r($val);}
		if ($param=='id'){continue;}
			elseif ($param=='price'){$ar_insert[]="insert into tovar (id, param, price) values ('$id' , '$param' , '$val' )";}
			elseif ($param=='options'){
			 foreach($val as $razmer=>$ostatok){$ar_insert[]="insert into tovar (id, param, razmer, ostatok) values ('$id', '$param' , '$razmer' , '$ostatok' )";}}
			elseif ($param=='param'){
				foreach($val as $p=>$v){$ar_insert[]="insert into tovar (id, param, param_value) values ('$id' , '$p' , '$v' )";}}
			else {$ar_insert[]="insert into tovar (id, param, param_value) values ('$id' , '$param' , '$val' )";}
		}
	}
		//print_r($ar_insert);
	$count=count($ar_insert);

	//exit;

	$i=0;
	//Запись данных в таблицу TOVAR
	foreach($ar_insert as $insert_tovar){$i=$i+1; echo '<br>'.$count.' --- '.$i; $pdo->exec($insert_tovar);}
	echo 'Товары обновлены!!!';
	}
	
	private function loadShop () {
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
		$ar_put[]=$put_to.$put_shabloni;


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

	}
	
	private function createFiltr () {
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



	}
	
	private function createDiv () {
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
			
	}
	
	private function zapros() {
		session_start();
		if(!$_POST['filtr']){	header('Location: http://localhost:8080/index.php');exit;}
		//print_r($_POST);
		//setcookie('page_nom','0');
		 
		$ar_filtr=explode('_%%_',$_POST['filtr']);

		$ar_q1=[];

		foreach($ar_filtr as $nom=>$text){
		$name=substr($text,0,stripos($text,'_===_')); $param=explode('_%%%_',substr($text,stripos($text,'_===_')+5,500));
		$ar_q1[$name]=$param;}

		if(empty($ar_q1['ar_tovar'])){$ar_q1['ar_tovar']=$ar_q1['ar_filtr'];}

		$ar_select2=[];
		//Формирование массива для запроса без пустых элементов
		foreach($ar_q1 as $name=>$ar){foreach($ar as $ind=>$val){if($val){$ar_select2[$name][]=$val;}}}

		//print_r($ar_select2);
		$ar_select=[];

		$nom=0;
		foreach($ar_select2 as $name=>$ar){
			//Удаление checkbox_
		 $name2=substr($name,9,strlen($name));
		//echo $name2;
				$nom=$nom+1;
		if($name==='ar_tovar'){
		require_once('ar_menu2_unserialize.php');
		$ar_menu2=create_ar_menu2();
		 $str_id='';
		 foreach($ar as $val){if(!$ar_menu2[$val]['ar']){continue;} foreach($ar_menu2[$val]['ar'] as $ind=>$id){if(!$id){continue;} $str_id=$str_id.$id.',';} }
		  $str_id=substr($str_id,0,strlen($str_id)-1);
		  //$ar_select[$nom]['on']=" t0.id=t$nom.id ";$ar_select[$nom]['where']=" t$nom.id in($str_id) ";
		  $ar_select[$nom]['on']=""; $ar_select[$nom]['where']=" t0.id in($str_id) ";
		}
		elseif($name==='checkbox_options' ){
			$str_razmer='';
			foreach($ar as $razmer){if(!$razmer){continue;} $str_razmer=$str_razmer.$razmer.',';} 
		  $str_razmer=substr($str_razmer,0,strlen($str_razmer)-1);
		//  $ar_select[$nom]['on']=" t1.id=t$nom.id ";  $ar_select[$nom]['where']=" t$nom.razmer in($str_razmer) ";
		  $ar_select[$nom]['on']="";  $ar_select[$nom]['where']=" t0.razmer in($str_razmer) ";
		}
		elseif($name==='ar_price'){}
		else{
			//ar_filtr-это идентификатор подменю
			if($name==='ar_filtr'){continue;}
			$str_ower='';
			foreach($ar as $ower){if(!$ower){continue;} $str_ower=$str_ower."'".$ower."'".',';} 
		  $str_ower=substr($str_ower,0,strlen($str_ower)-1);
		  $ar_select[$nom]['on']=" join tovar t$nom on t0.id=t$nom.id ";
		  $ar_select[$nom]['where']=" ( t$nom.param='$name2' and t$nom.param_value in($str_ower) ) ";
		//  echo $str_ower;

		}

		}

		$query='select t0.id from tovar t0 ';
		foreach($ar_select as $ar=>$val){$query=$query.$val['on'];}
		$query=$query.'where ';
		foreach($ar_select as $ar=>$val){$query=$query.$val['where'].' and ';}

		$query=substr($query,0,strlen($query)-4).' group by t0.id';
		//echo '<br>'.$query;
		//exit;
		//echo '<br>';//.print_r($ar_select);

		$put_connect=$_SERVER['DOCUMENT_ROOT'].'/php2/connect.php';
		require_once($put_connect);
		$pdo=connect_dailywear();

		$zapros=$pdo->query($query);

		$i=0;$count=0;$page=0;
		$left=0;$top=0;$ar_session=[];
		if (!$zapros){echo print_r($pdo->errorinfo());exit();}
		while($tovar=$zapros->fetch(PDO::FETCH_ASSOC)){
		$str='';
		$i=$i+1;
		$count=$count+1;
		$ar_session[$page][]=$tovar['id'];
		if($count>49){$count=0; $page=$page+1;}
		}

		$_SESSION['page']=$ar_session;
		//echo '<br> session--  '.print_r($_SESSION['page'][22]);

		//echo $page;
		require_once($_SERVER['DOCUMENT_ROOT'].'/php2/page.php');
		view_page(0);

		//require_once($_SERVER['DOCUMENT_ROOT'].'/php2/page_next_a.php');
		
	}

	
	
	
	
}