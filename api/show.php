<?php
require '../config.php';
use lib\Scandir;
if( isAjax() === true ){

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
 ?>