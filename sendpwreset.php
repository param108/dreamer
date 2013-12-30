<?php

if (empty($_SERVER['HTTPS'])) {
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    die();
}

include_once('php/tokens.php');
include_once('php/email.php');
include_once('php/users.php');
// get the password and username
$csrf = $_POST['csrf'];
if (!verify_token($csrf)) {
	error_log("Failed to verify token");
	header('location: forgotPassword.php');
	die();
}

delete_token($csrf);
$username = $_POST['username'];
if (!empty($username) && user_exists_nopasswd($username)) {
    send_pwreset_email($username);
}
header('location: sentpwreset.php');

