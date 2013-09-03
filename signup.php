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
		<script src="js/signup.js"></script>
		<link rel="stylesheet" type="text/css" href="css/signup.css"/>
	</head>
	<body>
		<form id='signup-form' action="/excel/signupverify.php<?=(($redirectUrl == '')?'':'?redirect='.$redirectUrl)?>">
			<div><input type='hidden' name='csrf' value='<?=$csrf?>'</div>
			<div style="100%">
			Username <input type="text" name='username'>
			</div>
			<div style="100%" class="username-invalid-msg" style="display:none;">
				Username must be a valid email address
			</div>
			<div style="100%">
			Password <input type="password" name='password'>
			</div>
			<div style="100%" class="password-invalid-msg" style="display:none;">
				Password must use only characters, numbers and punctuation<br>and should have atleast one capital letter and punctuation mark		
			</div>
			<div style="100%">
			<input type="submit" value="Sign up">
			</div>
		</form>
	</body>
</html>
