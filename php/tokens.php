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
function verify_token($token,&$data = null) {
	$dbh = new dbm(DBHOST,'excel',DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("select UNIX_TIMESTAMP(created),expiry,data from user_session where session_key=:token;");
	if (!$stmt->execute(array(':token'=>$token))) {
		error_log("verify_token sql failed");
		return null;
	}	
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($rows) == 0) {
		error_log("verify_token sql failed: no rows in output");
		return false;
	}
	$presentTime = time();	
	if (abs($rows[0]['UNIX_TIMESTAMP(created)']-$presentTime)>$rows[0]['expiry']) {
		error_log("verify_token failed: token expired");
		delete_token($token);
		return false;
	}
	if (!($data === null)) {
		$data = $rows[0]['data'];
	}

	return true;
}

function reset_token($token) {
	$dbh = new dbm(DBHOST,'excel',DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("update user_session set created=FROM_UNIXTIME(:created) where session_key=:token;");
	$created = time();
	if (!$stmt->execute(array(':token'=>"$token",':created'=>"$created"))) {
		$stmt->closeCursor();
		return false;
	}	
	$stmt->closeCursor();
	return true;
}
function dump_token($token) {
	$dbh = new dbm(DBHOST,'excel',DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("select * from user_session where session_key=:token;");
	if (!$stmt->execute(array(':token'=>$token))) {
		error_log("verify_token sql failed");
		return null;
	}	
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if (count($rows) == 0) {
		error_log("verify_token sql failed: no rows in output");
		return false;
	}
	print_r($rows);

	return true;
}


