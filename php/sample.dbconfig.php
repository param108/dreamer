<?php
define('DBHOST','');
define('DBUSER','');
define('DBPASS','');
# the main DB name
define('DBMAIN','');
if (!empty($_SERVER['HTTPS'])) {
        define('PROTOCOL','https://');
} else {
        define('PROTOCOL','http://');
}
define('MAINURL',PROTOCOL.'laghava.com');
