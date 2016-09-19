<?php
//function 
	/**
	 * 数组转码
	 */
	function arrayEncode( $in_charset,$out_charset,array $arr){
	    return eval('return ' . iconv($in_charset, $out_charset, var_export($arr, 1)) . ';');
	}
	/**
	 * isset + !empty
	 */
	function checkPnG($key,$expect = true){
	    if( ( array_key_exists( $key,$_POST ) || array_key_exists( $key,$_GET ) ) && $_POST[$key] == $expect ){
	        return true;
	    }else{
	        return false;
	    }
	}
	/**
	 * 保证斜杠
	 */
	function checkSlash($str){
	    return trim($str,'/').'/';
	}