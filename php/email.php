<?php
include_once('tokens.php');
include_once('dbconfig.php');
include_once('dbm.class.php');

# creating a one hour token
function generate_verification_token($username) {
	$data = array( "email" => "$username");
	$token = generate_token(3600,json_encode($data));
	return $token;
}

function verification_template($username) { 
	$ver = '<html>'.
	'<head>'.
		'<link rel="stylesheet" type="text/css" href="http://localhost/excel/css/verification_email.css"/>'.
	'</head>'.
	'<body>'.
		'<div class="email-header"><a href="http://localhost/excel/login.php">Laghava.com</a></div>'.
		'<div class="email-body"><b>Hi!</b>,<br> To verify your email on Laghava.com Just click <a href="http://localhost/excel/emailclicked.php?id='.generate_verification_token($username).
		'">Here</a></div>'.
	'</body>'.
	'</html>';
	return $ver;
}

function send_verification_email($username) {
	$to = $username;
	$subject = "Email Verification for laghava.com";
	$from = "param@laghava.com";
	$headers = "From:" . $from. "\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = verification_template($username);
	mail($to,$subject,$message,$headers);
}

function set_email_verified($username) {
	$dbh = new dbm(DBHOST,DBMAIN,DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("update users set verified = true where email=:username ;");
	if (!$stmt->execute(array(':username' => $username))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		return false;
	}	
	$stmt->closeCursor();
	return true;
}
