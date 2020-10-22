<?php
namespace Library\DatabaseMysqli;
require_once( $_SERVER['DOCUMENT_ROOT']	.'admin/config/Config.php');

use Admin\Config\Config;




class DatabaseMysqli
{
    private $config;
    private $db;
    private $dbh;
    private $error;
    private $stmt;

    public function __construct()
    {
        $this->config = new Config();
        $this->db = $this->config->db();
        $conn = new \mysqli($this->db['host'], $this->db['username'], $this->db['password'], $this->db['database']);
        if ($conn->connect_errno) {
          echo "Failed to connect to MySQL: " . $conn->connect_errno;
          exit();
        }
        else {
            $this->dbh = $conn;
        }

    }

    // Prepare statement with query
    public function prepare($query) {
      return $this->dbh->prepare($query);
    }
    public function query($query) {
        $result = $this->dbh->query($query);
        return $result;
    }
//
//    // Execute the prepared statement
//    public function execute(){
//        return $this->stmt->execute();
//    }
//    // Get result set as array of objects
//    public function resultset(){
//    }
//    // Get single record as object
//    public function single(){
//
//    }
//
//    // Get record row count
//    public function rowCount(){
//
//    }
//
//    // Returns the last inserted ID
//    public function lastInsertId(){
//
//    }
}