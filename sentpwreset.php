<?php
include_once("php/tokens.php");
$csrf = generate_token(60);
?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/emailverify.css"/>
	</head>
	<body>
		<div style="width:100%">
			We have sent you a password reset email. Please check your email and click on the link inside to reset your password.
		</div>
	</body>
</html>
