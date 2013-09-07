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
			Please verify your email address by clicking on the link that we have emailed you!
		</div>
		<div style="width:100%;">
		Didnt get the email? fill below to send again!
		<form id='send-email-form' action="/excel/sendemailagain.php">
			<div><input type='hidden' name='csrf' value='<?=$csrf?>'</div>
			<div style="width:100%;">
			email: <input type="text" name='username'>
			<input type="submit" value="Send email again">
			</div>
		</div>
	</body>
</html>
