<?php
//function 
	/**
	 * 数组转码
	 */
	function arrayEncode( $in_charset,$out_charset,array $arr){
	    return eval('return ' . iconv($in_charset, $out_charset, var_export($arr, 1)) . ';');
	}