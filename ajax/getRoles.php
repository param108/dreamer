<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$id = $DREAMER_DATA['u'];


$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("select TIMESTAMPDIFF(DAY, UTC_TIMESTAMP(), touch) as t_elapsed, roleid, name from roles where uid =:id;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute(array(':id' => $id));
$row = $stmt->fetchall(PDO::FETCH_ASSOC);
if ((!$row) || (count($row) == 0)) {
	return false;
}
$verified = json_encode($row);
echo $verified;

