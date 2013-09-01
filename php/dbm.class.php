<?php
include_once("debug.php");
class dbm {
  /**
   * The db handle
   */
  public $m_dbh;
  public function __construct($host, $dbname, $user, $pass) {
    $this->connect($host, $dbname, $user, $pass);
  }

  public function close() {
    
  }

  public function connect($host, $dbname, $user, $pass) {
    $dsn = "mysql:dbname=$dbname;host=$host"; 
    try {
      $this->m_dbh = new PDO($dsn, $user, $pass);
    } catch (PDOException $e) {
      mylog( 'Connection failed: ' . $e->getMessage());
      die();
    }
  }
}
