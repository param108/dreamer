<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$name = $_POST['name'];
$id = $DREAMER_DATA['u'];

$ret = array('e' => 0);
$dbh = new dbm(DBHOST,'excel', DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("insert into roles values(:uid,DEFAULT,:name,NOW());",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':uid' => $id, ':name' => $name))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		$ret['e'] = 1;
		return false;
} else {
		$ret['e'] = 0;
}	
$out = json_encode($ret);
echo $out;
