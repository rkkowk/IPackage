<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Scandir
{
    public function __construct(){
    
    }
    
    /**
     * 递归遍历目录
     *parma $path 路径； $gt 时间戳 大于 ； $arr 引用变量，获取结果
     * @return bool
     */
    public function scandirs($path,$gt,&$arr = array()){
        if ($gt) {
            $path = checkSlash($path);
            $gt = is_int($gt) ? $gt : strtotime($gt);
            
            $dir = scandir($path);
            foreach ($dir as $key => $value) {
                // 过滤 . ..
                if ($value == '.' || $value == '..') {
                    continue;
                }
                // 区分文件和文件夹
                if (is_file($path . $value)) {
                    if (filemtime($path . $value) >= $gt || filectime($path . $value) >= $gt) {
                        // 转码
                        $arr[$path . $value]['filename'] = iconv('GB2312', 'UTF-8', $value);
                        $arr[$path . $value]['path'] = iconv('GB2312', 'UTF-8', $path);
                        $arr[$path . $value]['filemtime'] = date('Y-m-d', filectime($path . $value));
                    }
                } elseif (is_dir($path . $value)) {
                    $newpath = checkSlash($path . $value);
                    $this->scandirs($newpath, $gt, $arr);
                }
            }
            if (empty($arr)) {
                $arr = array();
            } else {
                // 排序
                sort($arr);
            }
            if (empty($dir)) {
                return false;
            } else {
                return true;
            }
        }else{
            //自带函数遍历目录并处理
            $dir = scandir($path);
            if( !empty($dir) ){
                foreach ($dir as $key => $value){
                    if( is_dir($path.$value) && $value != '.' && $value != '..' ){
                        $dirarr [$key]['path'] = $path.$value;
                        $dirarr [$key]['filename'] = $value;
                    }
                }
                rsort($dirarr);
                return $dirarr;
            }else{
                return false;
            }
        }
    }
    /**
     * 移动文件到指定目录
     *parma str $oldpath str $newpath
     * @return bool
     */
    public function save($array,$oldpath,$newpath){
         
        $oldpath = iconv('UTF-8', 'GB2312', $oldpath);
        $newpath = iconv('UTF-8', 'GB2312', $newpath);
        $array = arrayEncode('UTF-8', 'GB2312', $array);
         
        foreach ($array as $key => $value) {
            $newvalue = $newpath.str_replace($oldpath,'',$value);
            $path = pathinfo($newvalue,PATHINFO_DIRNAME);
            if( !is_dir($path) ){
                $result = mkdir($path,0777,true);
                if( $result ){
                    $msg[] = date('Y-m-d H:i:s').' mkdir '.$path.' true';
                }else{
                    $msg[] = date('Y-m-d H:i:s').' mkdir '.$path.' false';
                    break;
                }
            }
            	
            $result = copy($value, $newvalue);
            if($result){
                $msg[] = date('Y-m-d H:i:s').' copy '.$newvalue.' true';
            }else{
                $msg[] = date('Y-m-d H:i:s').' copy '.$newvalue.' false';
                break;
            }
        }
        rsort($msg);
    
        return arrayEncode('GB2312','UTF-8', $msg);
    }
}

