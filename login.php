<?php
include_once('php/tokens.php');
$errorSeen = false;
if (array_key_exists('error',$_GET)) {
	$errorSeen = true;
}
$redirectUrl = '';
if (array_key_exists('redirect',$_GET)) {
	$redirectUrl = $_GET['redirect'];
}
$csrf = generate_token(60);
?>
<html>
	<head>
		<script src="js/jquery.js"></script>
		<script src="js/login.js"></script>
		<link rel="stylesheet" type="text/css" href="css/login.css"/>
	</head>
	<body>
		<span id='loginmotd' <?=($errorSeen?"":"class='hidemotd'")?> >Error in username or password</span>
		<form id='login-form' action="/excel/loginverify.php<?=(($redirectUrl == '')?'':'?redirect='.$redirectUrl)?>">
			<div><input type='hidden' name='csrf' value='<?=$csrf?>'</div>
			<div style="100%">
			Username <input type="text" name='username'>
			</div>
			<div style="100%">
			Password <input type="password" name='password'>
			</div>
			<div style="100%">
			<input type="submit" value="Submit">
			</div>
		</form>
	</body>
</html>
