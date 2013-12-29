<?php

if (empty($_SERVER['HTTPS'])) {
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    die();
}

include_once('php/tokens.php');
include_once('php/email.php');
// get the password and username
$csrf = $_POST['csrf'];
if (!verify_token($csrf)) {
	error_log("Failed to verify token");
	header('location: emailverify.php');
	die();
}

delete_token($csrf);
$username = $_POST['username'];
send_verification_email($username);
header('location: emailverify.php');
