<?php
require_once('../../vendor/autoload.php');
require_once('../../admin/config/Config.php');
require_once('../../admin/config/Sample.Config.php');

use \Stripe\BillingPortal\Session;

require_once('../../admin/config/Config.php');

//fetch customer id from logged in status
$customer_id = 'cus_I6qZ1xTVH9RlBe';

$session = Session::create([
    'customer' => $customer_id,
    'return_url' => $return_url,
]);
if ($session) {
  header('Location: '. $session->url );
}

