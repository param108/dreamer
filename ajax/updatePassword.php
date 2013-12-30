<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');
$ret = array('e' => 0);

$id = $DREAMER_DATA['u'];
$email = $DREAMER_DATA['email'];

$pwd = $_POST['newpwd'];

$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("update users set password=SHA1(:pwd) where id=:id email=:em",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':id' => $id, ':em' => $email, ':pwd' => $pwd))){
	$stmt->closeCursor();
	$ret['e'] = 1;
	echo json_encode($ret);
	die();
}


delete_token($_COOKIE['at']);
echo json_encode($ret);
die();
