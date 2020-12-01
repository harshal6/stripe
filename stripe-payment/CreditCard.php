<?php
require_once('../vendor/autoload.php');
require_once('../admin/config/Config.php');
require_once('../stripe-payment/Customer.php');

use StripePayment\Customer\Customer;
use Stripe\Customer as StripeCustomer;
use Stripe\Token;
use Stripe\Subscription;


// @todo Jquery validation for fields.

$config = new Admin\Config\Config;
$config->setApiKey($config->stripeKeys()['stripe_secret_key']);
// sanitize Post array.
$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);

// Default Payment Type 
$paymentType = 'cc';

// Set initial values.
$plan_type = '';
$name = $email = $gname = $gemail = $bname = $bemail = $type= $card_number = $cvc = $expiry = $promo ='';

// Destructure Post Values.
if (isset($POST['name']) && isset($POST['email'])) {
  $name = $POST['name'];
  $email = $POST['email'];
} 

if (isset($POST['gname']) && isset($POST['gemail'])) {
  $gname = $POST['gname'];
  $gemail = $POST['gemail'];
}

if (isset($POST['bname']) && isset($POST['bemail'])) {
  $bname = $POST['bname'];
  $bemail = $POST['bemail'];
}

if (isset($POST['promo_code'])) {
  $promo = $POST['promo_code'];
}

// Setting email and name as per the selected type.
if (isset($POST['type'])) {
  $type = $POST['type'];
  if($type  == "girl") {
    $name = $gname;
    $email = $gemail;
  } 
  else if ($type  == "boy") {
    $name = $bname;
    $email = $bemail;
  }
}

// Card detials. 
if (isset($POST['creditNumber']) && isset($POST['cvc']) && isset($POST['expiry'])) {
  $card_number = $POST['creditNumber']; 
  $cvc = $POST['cvc'];
  $expiry = $POST['expiry'];
}

if (isset($POST['payment'])) {
  $paymentType = $POST['payment'];
}

// plan type
if (isset($POST['plan_type'])) {
  $plan_type = $POST['plan_type'];
  if ($plan_type == "Individual") {
    $plan_type = $config->productId()['individual'];
  } else {
    $plan_type = $config->productId()['combo'];
  }
}


/**
 * Execute If statement if payment type is credit card.
 * Execute else statement if payment type is invoice.
 */
if ($paymentType == 'cc') {
  try {

    $expiry = explode("/", $POST['expiry']);
    if(!isset($expiry[1])) {
      throw new Exception('Enter a valid Card Expiry date');
    }
  
    $expiry_month = $expiry[0];
    $expiry_year = $expiry[1];  
    
  
    $token =  generateToken($card_number, $expiry_month, $expiry_year, $cvc);

    // Create Customer In Stripe
    $customer = StripeCustomer::create([
      "email" => $email,
      "name" => $name,
      "source" => $token->id
    ]);
    
    // Create a manage-billing.
    $subscription =  createSubscriptionForAutoCollection($customer->id, $plan_type, $promo);
    $data = [
      'name' => $name,
      'email' => $email,
      'bname' => $bname,
      'gname' => $gname,
      'bemail' => $bemail,
      'gemail' => $gemail,
      'subscription' => $subscription,
      'paymentType' => $paymentType,
      'plan_type' => $_POST['plan_type'],

    ];
    addCustomerDataWrapper($data);


      /**
     * Return to success Page if payment is successfull. 
     * This where you can trigger the email.
    */
    if($subscription->status == "active") {
      $message = "Payment successful";
      onSuccess($message);
    } else {
      throw new Exception('Payment failed due to some internal Error');
    }

  }
  catch(Exception $e) {
    header("Location:../create-account?Message={$e->getMessage()}");
  }
} 
else {
  try {
    // Create Customer In Stripe
    $customer = createCustomer($email, $name);

    // Create a manage-billing invoice. This will be finalised and sent by stripe after an hour or so.
    $subscription_invoice =  createSubscriptionForInvoice($customer, $plan_type, $promo);
    $data = [
          'name' => $name,
          'email' => $email,
          'bname' => $bname,
          'gname' => $gname,
          'bemail' => $bemail,
          'gemail' => $gemail,
          'subscription' => $subscription_invoice,
          'paymentType' => $paymentType,
          'plan_type' => $_POST['plan_type'],

    ];
    addCustomerDataWrapper($data);
    if($subscription_invoice->status == "active") {
      $message = "Invoice has been sent";
      onSuccess($message);
    } else {
      throw new Exception('Payment failed due to some internal Error');
    }
  } catch (Exception $e) {
    header("Location:../create-account?Message={$e->getMessage()}");
  }
  
}

function addCustomerDataWrapper($data) {
  $payment_type = ($data['paymentType'] == 'cc') ? 0 : 1;
  $payment_status = ($data['paymentType'] == 'invoice') ? 'unpaid' : $data['subscription']->status;
  if ($data['plan_type'] == "Individual") {
    // Customer Data
    // 0 for individual
    $customerData = customerData($data['subscription']->customer, $data['name'], $data['email'], $payment_status, $payment_type, 0, $data['subscription']->created);
    // Instantiate Customer
    $customer = new Customer();
    // Add new customer
    $customer->addCustomer($customerData);
  }
  if ($data['plan_type'] == "Combined") {
    $customer = new Customer();
    //2 for boy
    $customerData = customerData($data['subscription']->customer, $data['bname'], $data['bemail'], $payment_status ,$payment_type, 2, $data['subscription']->created);
    $customer->addCustomer($customerData);
    $customer = new Customer();
    //1 for girl
    $customerData = customerData($data['subscription']->customer, $data['gname'], $data['gemail'], $payment_status ,$payment_type, 1, $data['subscription']->created);
    $customer->addCustomer($customerData);
  }
}

// Set customer data
function customerData ($customerID, $name, $email, $status, $paymentType, $type, $created) {
  $customerData = [
    'customer_id' => $customerID,
    'name' => $name,
    'email' => $email,
    'payment_status' => $status,
    'payment_type' => $paymentType,
    'account_type' => $type,
    'created' => $created,
  ];
  return $customerData;
}

// Create Customer In Stripe
function createCustomer($email, $name) {
  $customer = StripeCustomer::create([
    "email" => $email,
    "name" => $name,
  ]);
  return $customer;
}

// Generate Token with cardDetails.
function generateToken($card_number, $expiry_month, $expiry_year, $cvc) {
  $token = Token::create([
    'card' => [
      'number' => $card_number,
      'exp_month' => $expiry_month,
      'exp_year' => $expiry_year,
      'cvc' => $cvc,
    ],
  ]);

  return $token;
}

// Subscription to send the invoice.
function createSubscriptionForInvoice($customer, $subscriptionPlanId, $promo) {
  $subscription = Subscription::create([
    "customer" => $customer,
    "coupon" => $promo ? $promo : '',
    'collection_method' => 'send_invoice',
    'items' =>[
      ['price'=> $subscriptionPlanId]
    ],
    'days_until_due' => 1,
  ]);

  return $subscription;
}

// Subscription for automatically charging the user at the end of manage-billing.
function createSubscriptionForAutoCollection($customer, $subscriptionPlanId, $promo) {
  $subscription = Subscription::create([
    "customer" => $customer,
    "coupon" => $promo ? $promo : '',
    'collection_method' => 'charge_automatically',
    'items' =>[
      ['price'=> $subscriptionPlanId]
    ],
  ]);

  return $subscription;
}

// This is where you can trigger the Email. 
function onSuccess($message) {
  header("Location:Success.php?Message={$message}");
}