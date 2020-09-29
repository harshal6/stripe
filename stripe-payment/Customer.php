<?php
  class Customer {
    private $db;

    public function __construct() {
      $this->db = new Database;
    }

    public function addCustomer($data) {
      // Prepare Query
      $this->db->query('INSERT INTO tempaccounts (name, email, customer_id, payment_status, payment_type, account_type, created) VALUES(:name, :email, :customer_id, :payment_status, :payment_type, :account_type, :created)');

      // Bind Values
      $this->db->bind(':name', $data['name']);
      $this->db->bind(':email', $data['email']);
      $this->db->bind(':customer_id', $data['customer_id']);
      $this->db->bind(':payment_status', $data['payment_status']);
      $this->db->bind(':payment_type', $data['payment_type']);
      $this->db->bind(':account_type', $data['account_type']);
      $this->db->bind(':created', $data['created']);

      // Execute
      if($this->db->execute()) {
        return true;
      } else {
        return false;
      }
    }

    public function getCustomers() {
      $this->db->query('SELECT * FROM tempaccounts ORDER BY created_at DESC');

      $results = $this->db->resultset();

      return $results;
    }

    public function updateCustomerStatus( $status, $customer_id) {
      $this->db->query('UPDATE tempaccounts SET payment_status = :status WHERE customer_id = :customer_id');
      $this->db->bind(':status', $status);
      $this->db->bind(':customer_id', $customer_id);

      if ($this->db->execute()) {
        return true;
      }
      else {
        return false;
      }
    }
  }