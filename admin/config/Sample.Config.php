<?php
require_once('../vendor/stripe/stripe-php/init.php');
require_once('../vendor/autoload.php');

$stripe = [
  "secret_key"      => "sk_test_l6yur5CeuWLl7aoh49ML1hrZ",
  "publishable_key" => "pk_test_PFo64Kh6KvhzVZEmfqqMxJk1",
];

$product_price_ids = [
  "combo" => "price_1HWEi2Fqb01h4tABT8isH96D",
  "individual" => "price_1HWEg0Fqb01h4tABxqbTKRJI"
];

$stripeKey = \Stripe\Stripe::setApiKey($stripe['secret_key']);

$return_url = 'http://stripe-project.docksal/manage-billing';

$database = [
  'username' => 'user',
  'password' => 'user',
  'database' => 'default',
  'host' => 'db',
];
