if(!ar_filtr){ var ar_filtr=[]; str_cookie=document.cookie; if(str_cookie.length>0){}}
if(!ar_korzina){var ar_korzina=[];}
win_h=screen.height;
win_w=screen.width;

kol_div=50;
if(document.cookie.indexOf('kol_div=')<0){document.cookie='kol_div='+kol_div+';';}


	window.onload=function(){
ar_korzina=korzina_status();


//Поиск значения в сервисном текстовом поле		
input_page_nom=document.getElementById('page_nom');
if(input_page_nom.value>=0){next_page(input_page_nom.value);}else{filtr_go();}

//Обработка корзины для покупок

el=$('ul > li').click(function(){r=this.id; $('#poisk').val($('#poisk').val()+','+r);});

//vibor22=document.getElementsByClassName('checkbox');kol_vibor=0;
//for(ee in Object.keys(vibor22)){if(vibor22[ee] instanceof Object && vibor22[ee].checked==true) { kol_vibor=kol_vibor+1;} }





//Закрытие скобки загрузки документа
}

//При изменении размеров окна изменяет порядок следования элементов
//window.onresize=function(){alert(win_h);alert(win_w);}



//Функции
function ch_click(th){
if(th.name!=th.id){document.getElementsByName(th.name)[0].checked=false;}else{
el_vse=document.getElementsByName(th.name);
for (nom in Object.keys(el_vse)){if(th.checked==true){el_vse[nom].checked=true;}else{el_vse[nom].checked=false;}}}
}

function a_click(th){
 cl=th.className;
if(th.name=='VSE'+cl){
  el_vse=document.getElementsByName('checkbox'+th.className);
  ch=document.getElementById('checkbox'+cl);
  if(ch.checked) {ch.checked=false;} else {ch.checked=true;}
  for (nom in Object.keys(el_vse))	{if(ch.checked==true){el_vse[nom].checked=true;}else{el_vse[nom].checked=false; }}}
	else{filtr=document.getElementById('checkbox'+th.name);
	 if(filtr.checked) {filtr.checked=false; document.getElementById('checkbox'+cl).checked=false;}  else {filtr.checked=true;}}
}
////////////////

function ch_click1(id,ar_tovar){
el=document.getElementById(id);
if(el.checked){add_ar_filtr(ar_tovar,el.value);}else{del_ar_filtr(ar_tovar,el.value);}
}

function a_click1(id,ar_tovar){
el=document.getElementById(id);
if(el.checked){el.checked=false;del_ar_filtr(ar_tovar,el.value);}else{el.checked=true;add_ar_filtr(ar_tovar,el.value);}
}

function ch_click2(id){
el=document.getElementById(id);
if(el.checked){add_ar_filtr(el.name,el.value);}else{del_ar_filtr(el.name,el.value);}
}

function a_click2(id){
el=document.getElementById(id);
if(el.checked){el.checked=false;del_ar_filtr(el.name,el.value);}else{el.checked=true;add_ar_filtr(el.name,el.value);}
}

function add_ar_filtr(param,param_val){
if(!ar_filtr){ar_filtr=[];}
if(!ar_filtr[param]){ar_filtr[param]=[];}
ar_filtr[param].push(param_val);
}

function del_ar_filtr(param,param_val){
if(!ar_filtr){ar_filtr=[];}
if(!ar_filtr[param]){ar_filtr[param]=[];}
for(nom in ar_filtr[param]){if(ar_filtr[param][nom]==param_val){delete ar_filtr[param][nom];}}
}

function clear_check(name,ar_tovar){
if(ar_tovar){clear_ar_filtr('ar_tovar');}else{clear_ar_filtr(name);}
 names=document.getElementsByName(name);
 for(nom in names){if(names[nom].checked){names[nom].checked=false;}}
}

function clear_ar_filtr(param){
if(ar_filtr[param]){ar_filtr[param]=[];}
}

function filtr_view(th){st=document.getElementsByName('filtr_submenu'+th.name)[0].style;
if(st.display=='none'){st.display='block';}else{st.display='none';}
//alert(th.name);
}





function filtr_go(){
//str_ar_filtr='';for(e in ar_filtr){str_ar_filtr=str_ar_filtr+'_%%_'+e+'==='+ar_filtr[e];   alert(str_ar_filtr);
str_ar_filtr='';for(e in ar_filtr){str_ar_filtr=str_ar_filtr+'_%%_'+e+'_===_'; for(ee in ar_filtr[e]){str_ar_filtr=str_ar_filtr+"_%%%_"+ar_filtr[e][ee];     }  // alert(str_ar_filtr);
	//str_ar_filtr=str_ar_filtr+'_%%_'+e+'==='+ar_filtr[e];
	}
var str='';var nom=0;
ch_query=document.getElementsByClassName('checkbox');
for (nom in Object.keys(ch_query)) {
 if(ch_query[nom] instanceof Object && ch_query[nom].checked==true){str=str+','+ch_query[nom].name+'='+ch_query[nom].value;}
}
//if((str.length)>0){alert(str);}
input_page_nom.value=0;

obj={};
//for(e in ar_filtr){obj[e]=ar_filtr[e];}
obj.filtr=str_ar_filtr;
//alert(obj.filtr);

$.ajax({
type: 'POST',
url: 'php2/zapros.php',
data: obj,
success: function(data){$('#rezultat').html(data);}
});

//error: alert('Повторите запрос к даным!'),
}
function next_page(page){
document.cookie='page_nom='+page+';';
input_page_nom.value=page;
$.ajax({type:'POST',url:'php2/page_next.php',data: 'next_page='+page,	success: function(data){$('#rezultat').html(data);}});
//$.ajax({type:'POST',url:'php2/page_next_a.php',data:'next_page='+page,success: function(data){$('#next_page').html(data+'tttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttt');}});

}

function add_korzina(id,cena){
ar_korzina[id]=id;
str_cookie='';
for(i in ar_korzina){str_cookie=str_cookie+i+'&';}
document.cookie='id='+str_cookie;
//document.getElementById('korzina_yes').style.display='block';
//document.getElementById('korzina_no').style.display='none';
//document.getElementById('korzina_clear').style.display='block';
//document.getElementById('korzina_tovar').style.display='block';
//document.getElementById('korzina_pusto').style.display='none';
}

function korzina_clear(){clear_cookie();}

function korzina(){alert(document.cookie);alert('Вы еще не выбрали товар. Хороших покупок!');}

function korzina_li(el){alert(el.id);}

function korzina_div_over(){document.getElementsByClassName('korzina_ul')[0].style.display='block';}

function korzina_ul_out(){document.getElementsByClassName('korzina_ul')[0].style.display='none';}

function korzina_status(){str_id='';
str_id=get_cookie_id();
var ar_tmp=[],ar_tmp2=[];
if (str_id.length>0){ ar_tmp=str_id.split('&'); for(el in Object.keys(ar_tmp)){if(!ar_tmp[el]) {;continue;} ar_tmp2[ar_tmp[el]]='id';}}
return ar_tmp2;}

function get_cookie_id(){kuki=document.cookie.indexOf('id=');str_id='';
if(kuki>=0){str_id=document.cookie.substr(kuki+3); str_id=str_id.substr(0,str_id.indexOf('; '));
if(str_id.length>0){
//document.getElementById('korzina_yes').style.display='block';
//document.getElementById('korzina_no').style.display='none';
//document.getElementById('korzina_clear').style.display='block';
//document.getElementById('korzina_tovar').style.display='block';
//document.getElementById('korzina_pusto').style.display='none';
}

}else{clear_cookie();}
return str_id;}

function clear_cookie(){document.cookie='id='; ar_korzina=[];
//document.getElementById('korzina_yes').style.display='none';
//document.getElementById('korzina_no').style.display='block';
//document.getElementById('korzina_clear').style.display='none';
//document.getElementById('korzina_tovar').style.display='none';
//document.getElementById('korzina_pusto').style.display='block';
}
//function filtr_over(submenu){alert(submenu);}
//function filtr_out(submenu){alert(submenu);}




