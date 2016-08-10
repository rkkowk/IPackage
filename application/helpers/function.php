<?php
//function 
	/**
	 * 是否是AJAx提交的
	 * @return bool
	 */
	function isAjax(){
	    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	        return true;
	    }else{
	        return false;
	    }
	}
	/**
	 * 自动加载类
	 */
	function __autoload($classname) {
	    $classpath = dirname(__FILE__).'/'.$classname.'.class.php';
	    if( file_exists($classpath) ){
	        require $classpath;
	    }else{
	        echo $classpath.'error';
	    }
	}
	/**
	 * 数组转码
	 */
	function arrayEncode( $in_charset,$out_charset,array $arr){
	    return eval('return ' . iconv($in_charset, $out_charset, var_export($arr, 1)) . ';');
	}