<?php
include_once('php/tokens.php');
include_once('php/dbconfig.php');
include_once('php/dbm.class.php');
include_once('php/users.php');

$isAjax = false;
$redirectUrl = '';
if (array_key_exists('ajax',$_GET)) {
	$isAjax = true;
}

if (array_key_exists('redirect',$_GET)) {
	$redirectUrl = $_GET['redirect'];
}

function redirect_to_login() {
	global $isAjax;
	if ($isAjax) {
		die('{"error":1}');
	} else {
		header('location: login.php?error=true');
		die();
	}
}

function redirect_to_home() {
	global $isAjax,$redirectUrl;
	if ($isAjax) {
		if ($redirectUrl != '') {
			die('{"error":0,"redirect":"'.$redirectUrl.'"}');
		} else {
			die('{"error":0,"redirect": ""}');
		}
	} else {
		if ($redirectUrl != '') {
			header('location: '.$redirectUrl);
		} else {
			header('location: home.php');
		}
		die();
	}
}
// get the password and username
$csrf = $_POST['csrf'];
if (!verify_token($csrf)) {
	error_log("Failed to verify token");
	return redirect_to_login();
}

delete_token($csrf);
$username = $_POST['username'];
$password = $_POST['password'];
$verified = false;
$id = user_exists($username, $password, $verified);
if ($id === false) {
	error_log("Failed to find user");
	return redirect_to_login();	
} 

if (!$verified) {
	error_log("User email not verified");
	$redirectUrl = 'emailverify.php';
	return redirect_to_home();	
}
# found the user now update the session
$login_token = generate_token(600,'{"u":"'.$id.'","ip":"'.$_SERVER['REMOTE_ADDR'].'","port":"'.$_SERVER['REMOTE_PORT'].'"}');
setcookie('at',$login_token);
redirect_to_home();
