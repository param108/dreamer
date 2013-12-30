<?php
include_once("php/tokens.php");
$csrf = generate_token(60);
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/forgotPassword.css"/>
	</head>
	<body>
		<div style="width:100%;">
		Forgot your password? fill in your email address below and we will send you a reset email!
		<form id='send-email-form' action="sendpwreset.php">
			<div><input type='hidden' name='csrf' value='<?=$csrf?>'</div>
			<div style="width:100%;">
			email: <input type="text" name='username'>
			<input type="submit" value="Send Reset Email">
			</div>
		</div>
	</body>
</html>
