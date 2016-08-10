<?php
require '../config.php';
use lib\Scandir;
if( isAjax() === true ){
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
?>