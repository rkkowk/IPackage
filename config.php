<?php 
require __DIR__.'/include/function.php';
//config
	//默认路径
	$setSrc =( isset($_COOKIE['src']) && !empty($_COOKIE['src']) )? $_COOKIE['src'] : trim($_SERVER['DOCUMENT_ROOT'],'/').'/';
	$setTar =( isset($_COOKIE['tar']) && !empty($_COOKIE['tar']) )? $_COOKIE['tar'] :  'F:/RK/增量包/'.date('Ymd').'/';
	//默认日期
	$setGt = date('Y-m-d');
	$setGt = ( isset($_COOKIE['gt']) && !empty($_COOKIE['gt']) )? $_COOKIE['gt'] :  strtotime($setGt);
	
 ?>