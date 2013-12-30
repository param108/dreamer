<?php
include_once('php/tokens.php');
include_once('php/email.php');
include_once('php/users.php');
$data = "junk";
$id = $_GET['id'];
$tokenexpired = false;
$user_does_not_exist = false;
if (!verify_token($id, $data)) {
	$tokenexpired = true;
} else {
	delete_token($id);
}
function pwreset_button_disabled($t) {
	if ($t) {
		print("-disabled");
	}
}
function show_user_not_found($t) {
        if ($t) {
                print("user-not-found-show");
        } else {
                print("user-not-found-hide");
        }
}

function show_token_expired($t) {
        if ($t) {
                print("token-expired-show");
        } else {
                print("token-expired-hide");
        }
}

function show_pwreset($t) {
        if ($t) {
                print("pwreset-show");
        } else {
                print("pwreset-hide");
        }
}
if (!$tokenexpired) {
	$data = json_decode($data,true);
	$email = $data['email'];

	if (!empty($email)) {
		$uid = user_exists_nopasswd($email);
		if ($uid === false) {
			error_log("Failed to find user");
			$user_does_not_exist = true;
		} 
	} else {
		$user_does_not_exist = true;
	}

	if (!$user_does_not_exit) {
		$login_token = generate_token(600,'{"u":"'.$id.'","ip":"'.$_SERVER['REMOTE_ADDR'].'","port":"'.$_SERVER['REMOTE_PORT'].'","email":"'.$email.'"}');
		setcookie('at',$login_token);
	}
}
?> 
<html>
	<head>
        <script src="js/jquery.js"></script>
        <script src="js/pwreset.js"></script>
	<link rel="stylesheet" type="text/css" href="css/pwreset.css"/>
	</head>
	<body>
		<div class="<?show_token_expired($tokenexpired);?>" style="width:100%">
			Your Link Expired ! (its only valid for an hour!). To get a new link please go to <a href="forgotPassword.php">forgot password page</a>
		</div>
		<div class="<?show_user_not_found($user_does_not_exist);?>" style="width:100%">
			I am sorry this user could not be found. To get a new link please go to <a href="forgotPassword.php">forgot password page</a>
		</div>
		<div id="pwreset-input" class="<?show_pwreset(!($tokenexpired||$user_does_not_exist));?>" style="width:100%;">
	<div id="pwreset-unmatched-msg"> Passwords do not match, please try again</div>
	<div id="pwreset-invalid-msg">Your new password is invalid. Passwords must be a minimum of 8 characters long and must contain, small and capital letters and atleast one punctuation mark</div>
	<div id="pwreset-update-failed-msg">Password update failed, please try again</div>
	<div id="pwreset-update-success-msg">Password successfully updated, please go to the <a href="login.php">login page</a> to login.</div>
        <div>
        New Password <input type="password" name="new-password" id="pwreset-input-newpwd"></input>
        </div>
        <div>
        New Password (again)<input type="password" name="new-password-again" id="pwreset-input-newpwdagn"></input>
        </div>
        <div>
        <button id="pwreset-button<?pwreset_button_disabled($tokenexpired || $user_does_not_exist);?>">Reset Password</button>
        </div>
        </div>
	</body>
</html>
