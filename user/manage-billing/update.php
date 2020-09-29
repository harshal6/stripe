<?php

require_once('../../admin/config/Config.php');

//fetch customer id from logged in status
$customer_id = 'cus_I6qZ1xTVH9RlBe';

$session = \Stripe\BillingPortal\Session::create([
    'customer' => $customer_id,
    'return_url' => $return_url,
]);
if ($session) {
  header('Location: '. $session->url );
}

