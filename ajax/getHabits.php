<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$tz = $_POST['tzoffset'];
$id = $DREAMER_DATA['u'];


$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);
$stmt = $dbh->m_dbh->prepare("SELECT IF( DAY( x.touch + INTERVAL x.t_elapsed DAY ) != DAY( x.rtnow ) , ". 
	"x.t_elapsed +1, ".
	"x.t_elapsed ".
	") AS t_elapsed, ". 
"IF( DAY( x.created + INTERVAL x.d_elapsed DAY ) != DAY( x.rtnow ) , ".
	"x.d_elapsed +1, ".
	"x.d_elapsed ".
	") AS d_elapsed, ".
"x.habitid AS habitid, x.name AS name, x.score AS score, x.ease AS ease ".
"FROM ( ".
"SELECT TIMESTAMPDIFF( DAY , ( touch - INTERVAL $tz MINUTE), (UTC_TIMESTAMP( ) - INTERVAL $tz MINUTE )) AS t_elapsed, ".
"TIMESTAMPDIFF( DAY , ( created - INTERVAL $tz MINUTE), ".
	"( UTC_TIMESTAMP( ) - INTERVAL $tz MINUTE )) AS d_elapsed, ".
"touch - INTERVAL $tz MINUTE AS touch, created - INTERVAL $tz MINUTE AS created, ".
"UTC_TIMESTAMP( ) - INTERVAL $tz MINUTE AS rtnow, ".
"habitid, name, score, ease FROM habits WHERE uid =:id)x;", array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$stmt->execute(array(':id' => $id ));
$row = $stmt->fetchall(PDO::FETCH_ASSOC);
if ((!$row) || (count($row) == 0)) {
	return false;
}
$verified = json_encode($row);
echo $verified;

