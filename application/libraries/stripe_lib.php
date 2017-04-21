<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('curl_init')) {
  throw new Exception('Stripe needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Stripe needs the JSON PHP extension.');
}
if (!function_exists('mb_detect_encoding')) {
  throw new Exception('Stripe needs the Multibyte String PHP extension.');
}
use stripe\Stripe as Stripe;
class Stripe_lib
{
    public function __construct()
    {

        // Stripe singleton
        require(APPPATH.'third_party/stripe/Stripe.php');

        // Utilities
        require(APPPATH.'third_party/stripe/Util/Util.php');
        require(APPPATH.'third_party/stripe/Util/Set.php');
        require(APPPATH.'third_party/stripe/Util/RequestOptions.php');

        // Errors
        /*require(APPPATH.'third_party/stripe/Error.php');*/
        require(APPPATH.'third_party/stripe/Error/Base.php');
        require(APPPATH.'third_party/stripe/Error/Api.php');
        require(APPPATH.'third_party/stripe/Error/ApiConnection.php');
        require(APPPATH.'third_party/stripe/Error/Authentication.php');
        require(APPPATH.'third_party/stripe/Error/Card.php');
        require(APPPATH.'third_party/stripe/Error/InvalidRequest.php');
        require(APPPATH.'third_party/stripe/Error/RateLimit.php');

        // Plumbing
        require(APPPATH.'third_party/stripe/Object.php');
        require(APPPATH.'third_party/stripe/ApiRequestor.php');
        require(APPPATH.'third_party/stripe/ApiResource.php');
        require(APPPATH.'third_party/stripe/SingletonApiResource.php');
        require(APPPATH.'third_party/stripe/AttachedObject.php');
        require(APPPATH.'third_party/stripe/Collection.php');

        // Stripe API Resources
        require(APPPATH.'third_party/stripe/Account.php');
        require(APPPATH.'third_party/stripe/Card.php');
        require(APPPATH.'third_party/stripe/Balance.php');
        require(APPPATH.'third_party/stripe/BalanceTransaction.php');
        require(APPPATH.'third_party/stripe/Charge.php');
        require(APPPATH.'third_party/stripe/Customer.php');
        require(APPPATH.'third_party/stripe/Invoice.php');
        require(APPPATH.'third_party/stripe/InvoiceItem.php');
        require(APPPATH.'third_party/stripe/Plan.php');
        require(APPPATH.'third_party/stripe/Subscription.php');
        require(APPPATH.'third_party/stripe/Token.php');
        require(APPPATH.'third_party/stripe/Coupon.php');
        require(APPPATH.'third_party/stripe/Event.php');
        require(APPPATH.'third_party/stripe/Transfer.php');
        require(APPPATH.'third_party/stripe/Recipient.php');
        require(APPPATH.'third_party/stripe/ApplicationFee.php');
        
    }

}


