<?php
namespace lib;

class FTP
{
    private $res = null;//资源句柄

    /**
     * 连接ftp
     *parma $host,$user,$pwd,$port ,$timeout (str)
     * @return bool
     */
    public function loginFTP ($host,$user,$pwd,$port = 21,$timeout = 90,&$msg = ''){
        $this->res = ftp_connect($host,$port = 21,$timeout = 90);
        if(!$this->res){
            $msg = __FILE__.__LINE__.'16行,连接失败';
            return false;
        }
        return ftp_login( $this->res,$user,$pwd);
    }
    
    /**
     * 关闭ftp
     * @return bool
     */
    public function closeFTP (&$msg){
        return  ftp_close($this->res);
    }
    
    /**
     * 上传文件
     * $chdir ftp目录
     * $files 文件名
     * @return bool
     */
    public  function  upload2FTP($chdir,$filename,&$msg = ''){
        $basename  = pathinfo ( $filename , PATHINFO_BASENAME );
        try {
            ftp_chdir($this->res,$chdir);
        } catch (Exception $e) {
            $msg =  __FILE__.__LINE__.$chdir.'更改目录错误'.$e->getMessage();
            return false;
        }
        
        $ret  =  ftp_nb_put ( $this->res , $basename ,  $filename ,  FTP_BINARY );
        while ( $ret  ==  FTP_MOREDATA ) {
        
            // 在这里可以加入其它代码
            echo  "." ;
        
            // 继续传送...
            $ret  =  ftp_nb_continue  ( $my_connection );
        }
        if ( $ret  !=  FTP_FINISHED ) {
            echo  "上传文件中发生错误..." ;
            exit( 1 );
        }
        
        
    }
}

