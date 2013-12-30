<?php
include_once('php/tokens.php');
$errorSeen = false;
$email = false;
if (array_key_exists('email',$_GET)) {
	$email = true;
}
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
		<span id='emailverified' <?=($email?"":"class='hidemotd'")?> >Congratulations! you have successfully verified your email! Please login to continue</span>
		<form id='login-form' action="loginverify.php<?=(($redirectUrl == '')?'':'?redirect='.$redirectUrl)?>">
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
		New User? <a href="signup.php">Sign Up</a>
		Forgot your password? <a href="forgotPassword.php">Reset Password</a>
	</body>
</html>
