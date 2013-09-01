<?php
function _getDateString() {
	$date = new DateTime();
	$dateStr = '[' . date_format($date, 'Y-m-d H:i:s') . ',' . date_format($date, 'O') . ']';
	return $dateStr;
}

function mylog($str) {
	error_log(_getDateString() . ' ' . $str."\n",3,"logfile.txt");
}

