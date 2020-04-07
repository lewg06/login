<?php
class Create {

	public static createAhref ( array $ar = [], $zapros = '', $select_page = '' ) {
	$str_a = '';
	foreach($ar as $id) {
		if( $select_page == $id ){
			$str_a = $str_a . "<span class='this_page'>$id</span>";
		} else {
			$str_a = $str_a . "<a href='$zapros&page=$select_page' class='other_page'>$id</a>";
		}
	}
	return $ahref;
	}
	
	public static createOptions (array $options = []) {
	$str_options = '';
	foreach($ar as $razmer=>$ostatok) {
		if( $ostatok > 0 ){
			$str_options = $str_options . "<li class='options_yes' >$razmer</li>"
		} else {
			$str_options = $str_options . "<li class='options_no' >$razmer</li>"
		}
	}
	
	return "<ul class='options'>" . $options . "</ul>";
	}
}