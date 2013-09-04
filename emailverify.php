<?php
include_once('php/tokens.php');
?>
<html>
	<head>
		<script src="js/jquery.js"></script>
		<link rel="stylesheet" type="text/css" href="css/signup.css"/>
	</head>
	<body>
		<div style="width:100%">
			<span class="signup-invalid-token-msg <?=(($badToken)?'':'hideit')?>" >I am sorry your token expired</span>
			<span class="signup-username-used-msg <?=(($userUsed)?'':'hideit')?>" >I am sorry that username is already used</span>
		</div>
		<form id='signup-form' action="/excel/signupverify.php">
			<div><input type='hidden' name='csrf' value='<?=$csrf?>'</div>
			<div style="width:100%;">
			Username <input type="text" name='username'>
			</div>
			<div style="width:100%;" class="username-invalid-msg <?=(($badUser)?'':'hideit')?>">
				Username must be a valid email address
			</div>
			<div style="width:100%;">
			Password <input type="password" name='password'>
			</div>
			<div style="width:100%;" class="password-invalid-msg <?=(($badPass)?'':'hideit')?>">
				Password must use only characters, numbers and punctuation<br>and should have atleast one capital letter and punctuation mark		
			</div>
			<div style="width:100%;">
			<input type="submit" value="Sign up">
			</div>
		</form>
	</body>
</html>
