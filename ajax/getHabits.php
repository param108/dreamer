<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$tz = $_POST['tzoffset'];
$id = $DREAMER_DATA['u'];


$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("select (DAY(UTC_TIMESTAMP() - interval :tz MINUTE) - DAY(touch - interval :tz MINUTE)) as t_elapsed, (DAY(UTC_TIMESTAMP() - interval :tz MINUTE) - DAY(created - interval :tz MINUTE)) as d_elapsed, habitid, name, score, ease from habits where uid =:id;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
$stmt->execute(array(':id' => $id, ':tz' => $tz));
$row = $stmt->fetchall(PDO::FETCH_ASSOC);
if ((!$row) || (count($row) == 0)) {
	return false;
}
$verified = json_encode($row);
echo $verified;

