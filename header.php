<?php

if (empty($_SERVER['HTTPS'])) {
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    die();
}
include_once('php/tokens.php');
if (!array_key_exists('at',$_COOKIE)) {
	error_log("No at token");
	header('location: login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}
$data='junk';
if(!verify_token($_COOKIE['at'],$data)) {
	error_log("unverified token");
	header('location: login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}

if ($data == 'junk') {
	error_log('no data in token');
	header('location: login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}
$DREAMER_DATA = json_decode($data,true);
if ($DREAMER_DATA['ip'] != $_SERVER['REMOTE_ADDR']) {
	error_log("ip/port mismatch in token:".$DREAMER_DATA['ip'].":".$DREAMER_DATA['port'].">".$_SERVER['REMOTE_ADDR'].":".$_SERVER['REMOTE_PORT']);
	header('location: login.php?redirect='.$_SERVER['REQUEST_URI']);
	die();
}

reset_token($_COOKIE['at']);
// fall through into the destination url
