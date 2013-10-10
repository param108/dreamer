<?php
include_once('php/tokens.php');
include_once('php/email.php');
include_once('php/users.php');
$data = "junk";
$id = $_GET['id'];
if (verify_token($id, $data)) {
	$data = json_decode($data,true);
	set_email_verified($data['email']);
	delete_token($id);
	header('location: login.php?email=true');
	die();
} else {
	$csrf = generate_token(60);
}?> 
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/emailclicked.css"/>
	</head>
	<body>
		<div style="width:100%">
			Your token Expired ! (its only valid for an hour!)
		</div>
		<div style="width:100%;">
		Fill below to send email again! If you have already verified your email just click<a href="login.php">here</a> to login
		<form id='send-email-form' action="sendemailagain.php">
			<div><input type='hidden' name='csrf' value='<?=$csrf?>'</div>
			<div style="width:100%;">
			email: <input type="text" name='username'>
			<input type="submit" value="Send email again">
			</div>
		</div>
	</body>
</html>
