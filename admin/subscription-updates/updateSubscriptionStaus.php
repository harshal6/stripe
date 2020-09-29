<?php

require_once('../../vendor/autoload.php');
require_once('../../lib/pdo_db.php');
require_once('../../vendor/stripe/stripe-php/init.php');
require_once('../config/Config.php');


$stripe_object = new \Stripe\StripeClient(
    $stripe['secret_key']
);



//get all customers
$customer = new Customer();
$all = $customer->getCustomers();
//get subscription ids for each and eventually status
foreach($all as $key => $value) {
    $customer_id = $value->customer_id;
    $subscription = $stripe->subscriptions->all(
        'customer' => $customer_id,
        []
    );
    $status = $subscription->status;
    //update customers with the status
    $customer->updateCustomerStatus($status, $customer_id);
}

