<?php
namespace StripePayment\Customer;

require_once('../vendor/autoload.php');
require_once('../lib/mysqli_db.php');

//use Library\Database\Database;
use Library\DatabaseMysqli\DatabaseMysqli;

class Customer {
    private $db;

    public function __construct() {
      //$this->db = new Database;
        $this->db = new DatabaseMysqli();
    }

    public function addCustomer($data) {
      // Prepare Query
      $stmt = $this->db->prepare('INSERT INTO tempaccounts (name, email, customer_id, payment_status, payment_type, account_type, created) VALUES(?, ?, ?, ?, ?, ?, ?)');
      $stmt->bind_param("sssssii",$data['name'], $data['email'], $data['customer_id'], $data['payment_status'], $data['payment_type'], $data['account_type'], $data['created']);
      $stmt->execute();
      $stmt->close();
//      $this->db->close();

        // Bind Values
//      $this->db->bind(':name', $data['name']);
//      $this->db->bind(':email', $data['email']);
//      $this->db->bind(':customer_id', $data['customer_id']);
//      $this->db->bind(':payment_status', $data['payment_status']);
//      $this->db->bind(':payment_type', $data['payment_type']);
//      $this->db->bind(':account_type', $data['account_type']);
//      $this->db->bind(':created', $data['created']);
      // Execute
//      if($this->db->execute()) {
//        return true;
//      } else {
//        return false;
//      }
    }

    public function getCustomers() {
      $results = [];
      $result = $this->db->query('SELECT * FROM tempaccounts ORDER BY created_at DESC');
      if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
          $results[] = $row;
        }
      }

      return $results;
    }

    public function updateCustomerStatus( $status, $customer_id) {
      $sql = 'UPDATE tempaccounts SET payment_status = ' . $status . ' WHERE customer_id = '. $customer_id;
      if($this->db->query($sql) === TRUE ) {
        return true;
      }
      else {
        return false;
      }

//      $this->db->close();
    }
  }