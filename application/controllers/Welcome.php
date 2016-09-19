<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Welcome extends CI_Controller {

	public function index()
	{
	    //config
	    //默认路径
	    $setSrc =( isset($_COOKIE['src']) && !empty($_COOKIE['src']) )? $_COOKIE['src'] : trim($_SERVER['DOCUMENT_ROOT'],'/').'/';
	    $setTar =( isset($_COOKIE['tar']) && !empty($_COOKIE['tar']) )? $_COOKIE['tar'] :  'F:/RK/增量包/'.date('Ymd').'/';
	    //默认日期
	    $setGt = date('Y-m-d');
	    
	    
	    
	    
	    $setGt = ( isset($_COOKIE['gt']) && !empty($_COOKIE['gt']) )? $_COOKIE['gt'] :  strtotime($setGt);

	    
        $data['src'] = $setSrc;
        $data['gt'] = $setGt;
        $data['tar'] = $setTar;
        
		$this->load->view('IPackage',$data);
	}
	
	
	
	public function search(){
	    if( $this->input->is_ajax_request() ){
    	    $this->load->library('Scandir');
	        $obj = new Scandir();
	        if( checkPnG('search') ){
	            $src = empty($_POST['src']) ?  $setSrc : $_POST['src'];
	            $gt = empty($_POST['gt']) ? $setGt : $_POST['gt'];
	            
	            setcookie('gt',$gt,time()+3600*12,'/');
	            $obj->scandirs($src ,$gt,$filesarr);
	        }
	        echo  json_encode($filesarr);
	    }
	}
	
	
	public function show(){
	    if( $this->input->is_ajax_request() ){
	        $this->load->library('Scandir');
	        $obj = new Scandir();
	        if( checkPnG('show','dir') ){
	            $src = empty($_POST['src']) ?  $setSrc : checkSlash($_POST['src']);
	            if( !is_dir($src) ){
	                exit ('0');
	            }
	            setcookie('src',$src,time()+3600*12,'/');
	            $dir =   $obj->scandirs($src,false);
	            echo json_encode(arrayEncode('GB2312','UTF-8',$dir));
	        }
	    }
	}
	

	//保存到本地
	public function save(){
	    if($this->input->is_ajax_request()){
	        $this->load->library('Scandir');
	        if( checkPnG('save','save') ){
	            
	            $src = !empty($_POST['src']) ? checkSlash($_POST['src']) : $setSrc;
	            $gt = !empty($_POST['gt']) ? strtotime($_POST['gt']) : $setGt;
	            $tar = !empty($_POST['tar']) ? checkSlash($_POST['tar']) : $setTar;
	    
	            setcookie('tar',$tar,time()+3600*12,'/');
	            $obj = new Scandir();
	            $array = explode( ',',rtrim($_POST['array'],','));
	            $result = $obj->save($array,$src ,$tar);
	            echo json_encode($result);
	            
	        }
	    }
	}
	
	//上传文件到FTP
	public function upload(){
	    if( $this->input->is_ajax_request() ){
	        if( isset($_POST['ftp']) && $_POST['ftp'] == 'ftp' ){
	            $files = explode(',',rtrim( $_POST['array'],','));
	            $local = $_POST['local'];
	            $config = json_decode(file_get_contents('./application/config/ftpConfig.json'),true);
	            $config['debug']    = TRUE;
	            $this->load->library('ftp');

	            $result = $this->ftp->connect($config);

	            foreach ($files as $key => $value){
	                $localhost = $value;
	                $ftpPath = dirname(str_replace($local, '/', $value));
	                var_dump($localhost );
	                var_dump($ftpPath);
                    $result = $this->ftp->upload($localhost, $ftpPath, 'ascii', 0775);
                    var_dump($result);exit;
	            }
                $this->ftp->close();
	        }
	    }
	}
	
}
