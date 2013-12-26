<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$name = $_POST['name'];
$id = $DREAMER_DATA['u'];

$ret = array('e' => 0);
$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("select name from habits where uid=:uid;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':uid' => $id))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		$ret['e'] = 1;
		$out = json_encode($ret);
		die($out);
}
$row = $stmt->fetchall(PDO::FETCH_NUM);
if ($row && count($row) > 0) {
	foreach($row as $k => $v) {
		if (strtolower($v[0]) == strtolower($name)) {
			$ret['e'] = 2;
			$out = json_encode($ret);
			die($out);
		}
	}
}
$stmt = $dbh->m_dbh->prepare("insert into habits values(:uid,DEFAULT,:name,NOW(),NOW(),0,'hard');",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':uid' => $id, ':name' => $name))) {
		print_r($stmt->errorInfo());
		$stmt->closeCursor();
		$ret['e'] = 1;
} else {
		$ret['e'] = 0;
}

$out = json_encode($ret);
echo $out;
