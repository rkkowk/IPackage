<?php
set_time_limit(0);
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('PRC');
require './config.php';
use lib\FTP;
$host = '127.0.0.1';
$user = 'test';
$pwd = 'test';      

$chdir = '/step1';
$files = 'D:/wamp/www/IPackage/step2/step3/2.txt';
$obj = new FTP();
$obj->loginFTP($host, $user, $pwd);
$obj->upload2FTP($chdir,$files);
if (isset($_GET['ftp']) && ! empty($_GET['ftp'])) {
    $src = $_GET['ftp'];
    require './view/ftp.htm';
} else {
    // controller
    $src = $setSrc;
    $gt = $setGt;
    $tar = $setTar;
    require './view/index.htm';
}

?>
