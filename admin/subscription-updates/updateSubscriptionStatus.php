<?php
require_once('../../vendor/autoload.php');

use Stripe\StripeClient;
use StripePayment\Customer\Customer;

$stripe_object = new StripeClient(
    STRIPE_SECRET_KEY
);



//get all customers
$customer = new Customer();
$all = $customer->getCustomers();
//get subscription ids for each and eventually status
foreach($all as $key => $value) {
    $customer_id = $value->customer_id;
    $subscription = $stripe_object->subscriptions->all(
        'customer' => $customer_id
    );
    $status = $subscription->status;
    //update customers with the status
    $customer->updateCustomerStatus($status, $customer_id);
}

