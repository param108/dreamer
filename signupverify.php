<?php

if (empty($_SERVER['HTTPS'])) {
    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    header("Location: $redirect");
    die();
}

include_once('php/tokens.php');
include_once('php/dbconfig.php');
include_once('php/dbm.class.php');
include_once('php/email.php');
include_once('php/users.php');

$isAjax = false;
if (array_key_exists('ajax',$_GET)) {
	$isAjax = true;
}

# error codes
# t: token failure
# p: password regexp failure
# pu: password and username regexp failure
# uu: username is already used
# u: username regexp failure
function redirect_to_signup($error) {
	global $isAjax;
	if ($isAjax) {
		die('{"error":"'.$error.'"}');
	} else {
			header('location: signup.php?error='.$error);
			die();
	}
}

function redirect_to_emailverify() {
	global $isAjax;
	if ($isAjax) {
		die('{"error":""}');
	} else {
		header('location: emailverify.php');
		die();
	}
}

function validPassword($pass) {
	$isValid = false;
	if (preg_match('/^[A-Za-z0-9\\.,-\\/#!$%\\^&\\*;:{}=\\-_~()]+$/',$pass)) {
		if (preg_match('/[A-Z]/',$pass)) {
			if (preg_match('/[\\.,-\\/#!$%\\^&\\*;:{}=\\-_~()]/',$pass)) {
				return true;
			}
		}
	}
	return false;
}
/**
Validate an email address.
Provide email address (raw input)
Returns true if the email address has the email 
address format and the domain exists.
*/
function validEmail($email)
{
   $isValid = true;
   $atIndex = strrpos($email, "@");
   if (is_bool($atIndex) && !$atIndex)
   {
      $isValid = false;
   }
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);
      if ($localLen < 1 || $localLen > 64)
      {
         // local part length exceeded
         $isValid = false;
      }
      else if ($domainLen < 1 || $domainLen > 255)
      {
         // domain part length exceeded
         $isValid = false;
      }
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
      {
         // local part starts or ends with '.'
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $local))
      {
         // local part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
      {
         // character not valid in domain part
         $isValid = false;
      }
      else if (preg_match('/\\.\\./', $domain))
      {
         // domain part has two consecutive dots
         $isValid = false;
      }
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                 str_replace("\\\\","",$local)))
      {
         // character not valid in local part unless 
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/',
             str_replace("\\\\","",$local)))
         {
            $isValid = false;
         }
      }
      if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
      {
         // domain not found in DNS
         $isValid = false;
      }
   }
   return $isValid;
}

// get the password and username
$csrf = $_POST['csrf'];
if (!verify_token($csrf)) {
	error_log("Failed to verify token");
	redirect_to_signup('t');
}

delete_token($csrf);
$username = $_POST['username'];
$password = $_POST['password'];

$userValid = validEmail($username);
$passValid = validPassword($password);
if (!$userValid) {
	if (!$passValid) {
		redirect_to_signup('pu');
	} else {
		redirect_to_signup('u');
	}
}
# now check the db if the user exists
$dbh = new dbm(DBHOST,DBMAIN,DBUSER,DBPASS);
$stmt = $dbh->m_dbh->prepare("select * from users where email = :username ",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute(array(':username' => $username));
$row = $stmt->fetchall(PDO::FETCH_ASSOC);
error_log(var_export($stmt->queryString,true));
if (count($row) > 0) {
	return redirect_to_signup('uu');	
} 


if (!$passValid) {
	redirect_to_signup('p');
}

create_user($username, $password);
#send the verification mail
send_verification_email($username);
redirect_to_emailverify();
