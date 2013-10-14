<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$roleid = $_POST['roleid'];
$id = $DREAMER_DATA['u'];

$ret = array('e' => 0);
$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("delete from roles where uid=:uid and roleid=:roleid;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':uid' => $id, ':roleid' => $roleid))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		$ret['e'] = 1;
		return false;
} else {
		$ret['e'] = 0;
}	
$out = json_encode($ret);
echo $out;
