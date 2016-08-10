<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
	    //config
	    //默认路径
	    $setSrc =( isset($_COOKIE['src']) && !empty($_COOKIE['src']) )? $_COOKIE['src'] : trim($_SERVER['DOCUMENT_ROOT'],'/').'/';
	    $setTar =( isset($_COOKIE['tar']) && !empty($_COOKIE['tar']) )? $_COOKIE['tar'] :  'F:/RK/增量包/'.date('Ymd').'/';
	    //默认日期
	    $setGt = date('Y-m-d');
	    $setGt = ( isset($_COOKIE['gt']) && !empty($_COOKIE['gt']) )? $_COOKIE['gt'] :  strtotime($setGt);
	    
        $src = $setSrc;
        $gt = $setGt;
        $tar = $setTar;
		$this->load->view('IPackage');
	}
	
	public function upload(){
	    if( is_ajax_request() ){
	        if( isset($_POST['ftp']) && $_POST['ftp'] == 'ftp' ){
	            $files = explode(',',rtrim( $_POST['array'],','));
	            $local = $_POST['local'];
	    
	            $config['hostname'] = '127.0.0.1';
	            $config['username'] = 'test';
	            $config['password'] = 'test';
	            $config['debug']    = TRUE;
	    
	            $FTP = new CI_FTP();
	            $FTP->connect($config);
	    
	            foreach ($files as $key => $value){
	                $value = dirname(str_replace($local, '/', $value));
	                var_dump($value);
	                $FTP->mkdir($value);
	            }
	        }
	    }
	}
	
	public function show(){
	    if( is_ajax_request() ){
	    
	        if(isset($_POST['show']) && $_POST['show'] == 'dir'){
	            $src = ( isset($_POST['src']) && !empty($_POST['src']) ) ? trim($_POST['src'],'/').'/' : $setSrc;
	            if( !is_dir($src) ){
	                exit ('0');
	            }
	            setcookie('src',$src,time()+3600*12,'/');
	            $dir =  scandir($src);
	            foreach ($dir as $key => $value){
	                if( is_dir($src.$value) && $value != '.' && $value != '..' ){
	                    $dirarr [$key]['path'] = $src.$value;
	                    $dirarr [$key]['filename'] = $value;
	                }
	            }
	            rsort($dirarr);
	            echo json_encode(arrayEncode('GB2312','UTF-8',$dirarr));
	        }
	    }
	}
	
	public function search(){
	    if( is_ajax_request()){
	        $obj = new Scandir();
	        if(isset($_POST['search']) && $_POST['search'] == 'true' ){
	            $src = ( isset($_POST['src']) && !empty($_POST['src']) ) ? trim($_POST['src'],'/').'/' : $setSrc;
	            $gt = ( isset($_POST['gt']) && !empty($_POST['gt']) ) ? strtotime($_POST['gt']) : $setGt;
	            setcookie('gt',$gt,time()+3600*12,'/');
	            $obj->scandirs($src ,$gt,$filesarr);
	        }
	        if ( empty($filesarr) || !isset($filesarr)){
	            $filesarr = array();
	        }else{
	            sort($filesarr);
	        }
	        echo  json_encode($filesarr);
	    }
	}
	
	public function save(){
	    if(is_ajax_request()){
	        if( isset($_POST['save']) && $_POST['save'] == 'save' ){
	            $src = ( isset($_POST['src']) && !empty($_POST['src']) ) ? trim($_POST['src'],'/').'/' : $setSrc;
	            $gt = ( isset($_POST['gt']) && !empty($_POST['gt']) ) ? strtotime($_POST['gt']) : $setGt;
	            $tar = ( isset($_POST['tar']) && !empty($_POST['tar']) ) ? trim($_POST['tar'],'/').'/' : $setTar;
	    
	            setcookie('tar',$tar,time()+3600*12,'/');
	            $obj = new Scandir();
	            $array = explode( ',',rtrim($_POST['array'],','));
	            $result = $obj->save($array,$src ,$tar);
	            echo json_encode($result);
	        }
	    }
	}
}
