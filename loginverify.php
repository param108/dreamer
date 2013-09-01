<?php
include_once('php/tokens.php');
include_once('php/dbconfig.php');
include_once('php/dbm.class.php');

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
$password = sha1($password);
error_log("$username => $password");
$dbh = new dbm(DBHOST,"excel",DBUSER,DBPASS);
$stmt = $dbh->m_dbh->prepare("select * from users where email =:username and password=:password;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute(array(':username' => $username, ':password' => $password));
$row = $stmt->fetchall(PDO::FETCH_ASSOC);
if (!$row || count($row) == 0) {
	error_log("Failed to find user");
	return redirect_to_login();	
} 

# found the user now update the session
$login_token = generate_token(600,'{"ip":"'.$_SERVER['REMOTE_ADDR'].'","port":"'.$_SERVER['REMOTE_PORT'].'"}');
setcookie('at',$login_token);
redirect_to_home();
