<?php
require_once('../../vendor/autoload.php');
require_once('../../lib/mysqli_db.php');

use Library\DatabaseMysqli\DatabaseMysqli;

$postdata = file_get_contents("php://input");
$data = json_decode($postdata);

$customer = $data->customer;
$status = $data->status;

$database = new DatabaseMysqli();
$results = [];
$query = 'SELECT id FROM tempaccounts WHERE customer_id = "'.$customer.'"';
$result = $database->query($query);
while($rows = $result->fetch_assoc()) {
    $results[] = $rows;
    $id = $rows['id'];
    $stmt = $database->prepare('UPDATE tempaccounts set payment_status = ? WHERE customer_id=?');
    $stmt->bind_param("ss",$status, $customer);
    $result2 = $stmt->execute();
}
echo "Successfully updated subscription status";


