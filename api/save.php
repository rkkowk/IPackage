 <?php 
require '../config.php';
use lib\Scandir;
if( isAjax() === true ){
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

?>