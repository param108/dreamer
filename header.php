<?php
include_once('php/tokens.php');
if (!array_key_exists('at',$_COOKIE)) {
	error_log("No at token");
	header('location: /excel/login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}
$data='junk';
error_log("header: ".$_COOKIE['at']);
if(!verify_token($_COOKIE['at'],$data)) {
	error_log("unverified token");
	header('location: /excel/login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}

if ($data == 'junk') {
	error_log('no data in token');
	header('location: /excel/login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}
error_log($data);
$pdata = json_decode($data,true);
if (!($pdata['ip'] == $_SERVER['REMOTE_ADDR'] && $pdata['port'] == $_SERVER['REMOTE_PORT'])) {
	error_log("ip/port mismatch in token:".$pdata['ip'].":".$pdata['port'].">".$_SERVER['REMOTE_ADDR'].":".$_SERVER['REMOTE_PORT']);
	header('location: /excel/login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}

// fall through into the destination url
