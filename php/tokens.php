<?php
include_once('dbconfig.php');
include_once('dbm.class.php');

function getToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[rand(0,(strlen($codeAlphabet)-1))];
    }
    return $token;
}
function generate_token($life=60,$data="{}") {
	$token =  getToken(30);
	$dbh = new dbm(DBHOST,'excel',DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("insert into user_session values (:token,:expiry,:data,FROM_UNIXTIME(:created));");
	$created = time();
	if (!$stmt->execute(array(':token'=>$token,':expiry'=>$life,':data'=>$data, ':created'=>$created))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		return null;
	}	
	$stmt->closeCursor();
	return $token;
}
function delete_token($token) {
	$dbh = new dbm(DBHOST,'excel',DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("delete from user_session where session_key=:token;");
	if (!$stmt->execute(array(':token'=>$token))) {
		print_r($stmt->errorInfo());
		$stmt.closeCursor();
		return false;
	}	
	$stmt->closeCursor();
	return true;
}
function verify_token($token) {
	$dbh = new dbm(DBHOST,'excel',DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("select UNIX_TIMESTAMP(created),expiry from user_session where session_key=:token;");
	if (!$stmt->execute(array(':token'=>$token))) {
		return null;
	}	
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($rows) == 0) {
		return false;
	}
	$presentTime = time();	
	if (abs($rows[0]["UNIX_TIMESTAMP(created)"]-$presentTime)>$rows[0]["expiry"]) {
		delete_token($token);
		return false;
	}

	return true;
}
