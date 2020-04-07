<?php
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/php2/page.php');
$next_page=$_POST['next_page'];
view_page($next_page);
?>