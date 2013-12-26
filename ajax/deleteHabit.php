<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$habitid = $_POST['habitid'];
$id = $DREAMER_DATA['u'];

$ret = array('e' => 0);
$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("delete from habits where uid=:uid and habitid=:habitid;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':uid' => $id, ':habitid' => $habitid))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		$ret['e'] = 1;
		return false;
} else {
		$ret['e'] = 0;
}	
$stmt = $dbh->m_dbh->prepare("delete from habit_clicks where uid=:uid and habitid=:habitid;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':uid' => $id, ':habitid' => $habitid))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		$ret['e'] = 1;
		return false;
} else {
		$ret['e'] = 0;
}	
$out = json_encode($ret);
echo $out;
