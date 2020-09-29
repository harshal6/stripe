<?php
require_once('../vendor/autoload.php');
require_once('../lib/pdo_db.php');
require_once('../vendor/stripe/stripe-php/init.php');
require_once('./Customer.php');
require_once('../admin/config/Config.php');



// @todo Jquery validation for fields.


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
    $plan_type = $product_price_ids['individual'];
  } else {
    $plan_type = $product_price_ids['combo'];
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
    $customer = \Stripe\Customer::create([
      "email" => $email,
      "name" => $name,
      "source" => $token->id
    ]);
    
    // Create a manage-billing.
    $subscription =  createSubscriptionForAutoCollection($customer->id, $plan_type, $promo);

    // Customer Data
    $customerData = customerData ($subscription->customer, $name, $email, $subscription->status ,$paymentType, $type, $subscription->created);

    // Instantiate Customer
    $customer = new Customer();

    // Add new customer
    $customer->addCustomer($customerData);
    if ($POST['plan_type'] == "Combined") {
      if ($type == 'girl') {
          $customerData = customerData ($subscription->customer, $bname, $bemail, $subscription->status ,$paymentType, $type, $subscription->created);
          $customer->addCustomer($customerData);
      }
      if ($type == 'boy') {
          $customerData = customerData ($subscription->customer, $gname, $gemail, $subscription->status ,$paymentType, $type, $subscription->created);
          $customer->addCustomer($customerData);
      }
    }

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

    $customerData = customerData ($subscription_invoice->customer, $name, $email, $subscription_invoice->status,$paymentType, $type, $subscription_invoice->created);
      // Instantiate Customer
    $customer = new Customer();
    
    // Add new customer
    $customer->addCustomer($customerData);

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

// Set customer data
function customerData ($customerID, $name, $email, $status, $paymentType, $type, $created){
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
  $customer = \Stripe\Customer::create([
    "email" => $email,
    "name" => $name,
  ]);
  return $customer;
}

// Generate Token with cardDetails.
function generateToken($card_number, $expiry_month, $expiry_year, $cvc) {
  $token = \Stripe\Token::create([
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
  $subscription = \Stripe\Subscription::create([
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
  $subscription = \Stripe\Subscription::create([
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