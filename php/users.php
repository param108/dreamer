<?php
include_once('dbconfig.php');
include_once('dbm.class.php');

# $username a valid email id
# $password a sha1'd password
function create_user($username,$password)
{
	$dbpassword = sha1($password);
	$dbh = new dbm(DBHOST,'excel',DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("insert into users values (:username,:password, DEFAULT, false);");
	$created = time();
	if (!$stmt->execute(array(':username'=>$username,':password'=>$dbpassword))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		return false;
	}	
	$stmt->closeCursor();
	return true;
}

function user_exists($username, $password, &$verified = null) {
	$dbpassword = sha1($password);
	$dbh = new dbm(DBHOST,"excel",DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("select * from users where email =:username and password=:password;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$stmt->execute(array(':username' => $username, ':password' => $dbpassword));
	$row = $stmt->fetchall(PDO::FETCH_ASSOC);
	if ((!$row) || (count($row) == 0)) {
		return false;
	}
	$verified = $row[0]['verified'];
	return $row[0]['id'];
}

function user_exists_nopasswd($username, &$verified = null) {
	$dbh = new dbm(DBHOST,"excel",DBUSER,DBPASS);
	$stmt = $dbh->m_dbh->prepare("select * from users where email =:username;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
	$stmt->execute(array(':username' => $username));
	$row = $stmt->fetchall(PDO::FETCH_ASSOC);
	if ((!$row) || (count($row) == 0)) {
		return false;
	}
	$verified = $row[0]['verified'];
	return $row[0]['id'];

}
