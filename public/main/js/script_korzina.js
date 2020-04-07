
window.onload=function ()		{
	
	ar_korzina=create_ar_korzina();
//		$('#rrr').append('ar_korzina: vibor-'+ar_korzina['vibor']+'-razmer-'+ar_korzina['razmer']+'-kolichestvo-'+ar_korzina['kolichestvo']+'-ostatok-'+ar_korzina['ostatok']+'-nalichie-'+ar_korzina['nalichie']);
	tovar_no(ar_korzina);
	tovar_cena_itog(ar_korzina);
//	$('#rrr').append('|||| ar_korzina: vibor-'+ar_korzina['vibor']+'-razmer-'+ar_korzina['razmer']+'-kolichestvo-'+ar_korzina['kolichestvo']+'-ostatok-'+ar_korzina['ostatok']+'-nalichie-'+ar_korzina['nalichie']);
			
	
	
	
	create_ar_vibor_razmera();
koplate();

//Добавление события изменения количества выбранных товаров
//$('.kol_vo').change(function(){alert('change');});


el2=$('.razmeri_li_yes').click(function(){
var str=this.id;
//str=str.substr(str.indexOf('ostatok')+7);str=str.substr(0,str.indexOf('"'));alert(str);alert(this.className);str='';for(i in el2){str=str+'\n'+i+' = '+el2[i];}alert(str);
nach=str.indexOf('li')+2;kon=str.indexOf('ostatok')-nach;
tovar_vibor_razmera(str.substr(nach,kon),str);
});



}





function plus(name){
	var id = name.substr(7),nom,kol,ost;
	el = document.getElementById(name + '_input');
	kol = Number(el.value);
	nom = ar_korzina['id'].indexOf(id);
	ost = ar_korzina['ostatok'][nom];

  if(ar_korzina['razmer'][nom]=='no'){
	if(ar_korzina['nalichie'][nom]==0){
		alert('В данный момент товар отсутствует!');}
	else if(ost==kol){
		alert('Данного товара осталось '+kol+' шт.');}
	else if(ost>kol){
		kol=kol+1;
		el.value = kol;
		ar_korzina['kolichestvo'][nom]=kol;
		tovar_cena_itog(ar_korzina);}
    }
  else{
	if(ar_korzina['nalichie'][nom]==0){
		alert('В данный момент товар отсутствует!');}
	else if(ar_korzina['vibor'][nom]==0){
		alert('Выберите размер товара!');}
	else {
		ost=ar_korzina['vibor'][nom].substr(ar_korzina['vibor'][nom].indexOf('ostatok')+7);
		if(ost==kol){
			alert('Данного товара осталось '+kol+' шт.');}
		if(ost>kol){
			kol=kol+1;
			el.value = kol;
			ar_korzina['kolichestvo'][nom]=kol;
			tovar_cena_itog(ar_korzina);}
	}
  }
}


function minus(name){
	var kol,el,nom,id=name.substr(7); el=$('#'+name+'_input');kol=el.val();
	if(kol==1){return;}else{kol=kol-1;el.val(kol);}
	ar_korzina['kolichestvo'][ar_korzina['id'].indexOf(id)]=kol;
	tovar_cena_itog(ar_korzina);}

function korzina_del(id,cena){//alert(id); alert(cena);
document.getElementById('korzina_'+id).className='tovar_bez_oplati';

document.getElementById('cena_itog').innerHTML = (document.getElementById('cena_itog').innerHTML - (cena * document.getElementById('kol_vo_' + id + '_input').value ));

if(document.getElementsByClassName('tovar_na_oplatu').length == 0){
	document.getElementById('stroka_itog').className = 'net';
}

}

function koplate(){var  koplate1=0; str='';
cena_itog=document.getElementById('cena_itog').innerHTML;

 }
 //Функция проверки отсутствия товара имеющего размеры
 function create_ar_korzina(){
var ar_korzina=[],el={},id='',ost=[],razm=[],ar_id=[];ar_cena=[];ar_ost=[];ar_razm=[];ar_vib=[];ar_kol=[];ar_nalichie=[];nalichie=0;ost2=0;
 str='';
	 var el=$('.razmeri_ul');
	 
	 for(var i=0;i<el.length;i=i+1){
	  id=el[i].id.substr(10);ost=[];razm=[];
	  cena=Number($('#cena_tovara_'+id).html());

	  if(el[i].childElementCount == 0){nalichie = Number($('#ostatok_'+id).html()); ost.push(nalichie);razm = ['no']; ar_vib.push(id);}
		 else{ar_vib.push(0); nalichie = 0;
			 for(var ii = 0;ii < el[i].childElementCount; ii = ii + 1) {
			  li = el[i].childNodes[ii].id;
			  nach = li.indexOf('li') + 2; kon = li.indexOf('ostatok');
			  razm.push(li.substr(nach, kon  -nach));//при наличии размеров
			  nach = li.indexOf('ostatok') + 7; kon = li.length;
			  ost2 = Number(li.substr(nach, kon - nach));
			  ost.push(ost2);
			  nalichie = nalichie + ost2;
			 }
		 }
	ar_id.push(id);	ar_cena.push(cena);	ar_ost.push(ost); ar_razm.push(razm); ar_kol.push(1); ar_nalichie.push(nalichie);
	}
ar_korzina['id']=ar_id;	ar_korzina['cena']=ar_cena;ar_korzina['ostatok']=ar_ost;ar_korzina['razmer']=ar_razm;
ar_korzina['vibor']=ar_vib;ar_korzina['nalichie']=ar_nalichie;ar_korzina['kolichestvo']=ar_kol;
return ar_korzina; }
 
function create_ar_vibor_razmera(){
	 var ar_vibor_razmera=[];			
return ar_vibor_razmera; }
 
  
 //Функция подсчета итоговой цены товаров
 function tovar_cena_itog(ar_korzina){var cena_itog=0;
	 for(i in ar_korzina['cena']){cena_itog=cena_itog+ar_korzina['cena'][i]*ar_korzina['kolichestvo'][i];}
	 $('#cena_itog').html(cena_itog);
 }
 //Функфия выделения товара, у которого нет цены и нет в наличии
 function tovar_no(ar_korzina){
var id=0;
  for(i in ar_korzina['nalichie']){
	  if(ar_korzina['cena'][i]==0){ar_korzina['nalichie'][i]=0;}//Удаляется товар не имеющий корректной цены
	  if(ar_korzina['nalichie'][i]==0){
		 id=ar_korzina['id'][i];
		 ar_korzina['cena'][i]=0;
		 $('#korzina_'+id).attr('class','tovara_net');
		 $('#cena_tovara_'+id).html('Товара нет в наличие');}
	 }
 }

 //Функция выделения и постановки выбора размера в массив ar_korzina
 function tovar_vibor_razmera(razmer,str_id){
	 var id=str_id.substr(str_id.indexOf('id')+2,str_id.indexOf('li')-2),str_id2,ind;
 ind=ar_korzina['id'].indexOf(id);
 str_id2=ar_korzina['vibor'][ind];

 if(str_id2==str_id){alert('tot  ge');return;}
 else{ar_korzina['vibor'][ind]=str_id; ar_korzina['kolichestvo'][ind]=1; $('#kol_vo_'+ar_korzina['id'][ind]+'_input').val(1);
	 $('#'+str_id2).attr('class','razmeri_li_yes');$('#'+str_id).attr('class','razmeri_li_yes_vibor');tovar_cena_itog(ar_korzina);}
 }
 
 
 //Нажата кнопка оформление заказа
 function oformlenie_zakaza(){
	 $('#myModal').modal('show');
	 
 }
 //Функция 
 function pokupka(){ }

function str55(el){
str='';
for (e in el){str = str + e + ' = ' + el[e];}
return str;	
}

