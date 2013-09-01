<?php
include_once("../tokens.php");

function testVerifyTokenList($newToken,$tokenList) {
	foreach($tokenList as $token) {
		if ($token == $newToken) {
			die("Regenerated Token ".var_export($token,true));
		}
	}
}
$test_array = array();
for ($i=0;$i<100;$i++) {
	$newToken=generate_token();
	testVerifyTokenList($newToken,$test_array);
	$test_array[]=$newToken;
}

foreach($test_array as $token) {
	delete_token($token);
}

#check if the token expires properly

$token = generate_token(10);
sleep(2);
if(!verify_token($token)) {
	die("Token expired early");
}
sleep(15);
if (verify_token($token)) {
	die("Token did not expire");
}

$token = generate_token(10);
sleep(5);
reset_token($token);
sleep(5);
reset_token($token);
sleep(5);
reset_token($token);
if(!verify_token($token)) {
	die("Token expired early after reset");
}
