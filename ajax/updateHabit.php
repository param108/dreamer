<?php
include_once('../php/dbconfig.php');
include_once('../php/dbm.class.php');
include_once('../header.php');


$habitid = $_POST['habitid'];
$ease = $_POST['ease'];
$id = $DREAMER_DATA['u'];
$ret = array('e' => 0);
$dbh = new dbm(DBHOST,DBMAIN, DBUSER, DBPASS);

/* read score now, so we dont have to wait for  writes to go through */
$stmt = $dbh->m_dbh->prepare("select score from habits where uid =:id;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY)); 
$stmt->execute(array(':id' => $id));
$row = $stmt->fetchall(PDO::FETCH_ASSOC);
if ((!$row) || (count($row) == 0) || (count($row)>1)) {
                print_r($stmt->errorInfo());
                $stmt->closeCursor();
                $ret['e'] = 1;
                $out = json_encode($ret);
                die($out);
}

$score = $row[0]['score'];
$score = $score + 1;

$stmt = $dbh->m_dbh->prepare("update habits set score=score + 1, ease=:ease, touch=NOW() where uid=:uid and habitid=:habitid;",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':uid' => $id, ':ease' => $ease, ':habitid' => $habitid))) {
                print_r($stmt->errorInfo());
                $stmt->closeCursor();
                $ret['e'] = 1;
                $out = json_encode($ret);
                die($out);
}

$stmt = $dbh->m_dbh->prepare("insert into habit_clicks values(:habitid,NOW(),:score,:ease);",array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
if (!$stmt->execute(array(':habitid' => $id, ':ease' => $ease, ':score' => $score))) {
                print_r($stmt->errorInfo());
                $stmt->closeCursor();
                $ret['e'] = 1;
} else {
                $ret['e'] = 0;
}

$out = json_encode($ret);
echo $out;

