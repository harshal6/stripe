<?php
namespace Admin\Config;

use Stripe\Stripe;

$database = [
    'username' => 'user',
    'password' => 'user',
    'database' => 'default',
    'host' => 'db',
];

define('DB_NAME', $database['database']);
define('DB_USER', $database['username']);
define('DB_PASS', $database['password']);
define('DB_HOST', $database['host']);
define('STRIPE_SECRET_KEY', 'sk_test_l6yur5CeuWLl7aoh49ML1hrZ');
define('STRIPE_PUB_KEY', 'pk_test_PFo64Kh6KvhzVZEmfqqMxJk1');
define('PRODUCT_INDIVIDUAL_PRICE_ID', 'price_1HWEg0Fqb01h4tABxqbTKRJI');
define('PRODUCT_COMBO_PRICE_ID', 'price_1HWEg0Fqb01h4tABxqbTKRJI');
define('STRIPE_RETURN_URL','http://stripe-project-new.docksal/manage-billing');

class Config {
    public function __construct() {
    }

    public function productId() {
      return [
        'individual' => 'price_1HWEg0Fqb01h4tABxqbTKRJI',
        'combo' => 'price_1HWEg0Fqb01h4tABxqbTKRJI'
      ];
    }

    public function db() {
      return [
        'username' => 'user',
        'password' => 'user',
        'database' => 'default',
        'host' => 'db'
      ];
    }

    public function stripeKeys(){
      return [
        'stripe_secret_key' => 'sk_test_l6yur5CeuWLl7aoh49ML1hrZ',
        'stripe_publishable_key' => 'pk_test_PFo64Kh6KvhzVZEmfqqMxJk1',
      ];
    }

    public function setApiKey($stripe_secret_key) {
        Stripe::setApiKey($stripe_secret_key);

    }

    public function returnUrl() {
        return 'http://stripe-project-new.docksal/manage-billing';
    }
}

