#!/usr/bin/env php
<?php

if(PHP_SAPI !== 'cli') exit; // cli only

$options = getopt("d:");
if(isset($options['d']) && is_dir($options['d'])) {
  define('PRESTASHOP_BASEDIR', $options['d']);
} else {
  exit(basename(__FILE__) . " -d <DIR>"
       . "\n\tRun the MailChimp sync of customers having pending orders."
       . "\nParameters:\n\t-d <DIR>: The prestashop base directory"
       . "\nEnvironment:\n\tMAILCHIMP_API_KEY (eg: de7798....-us1)"
       . "\n\tMAILCHIMP_LIST_ID (eg: 2859...80\n");
}

if(!getenv("MAILCHIMP_API_KEY")) exit("MAILCHIMP_API_KEY environment variable no set\n");
if(!getenv("MAILCHIMP_LIST_ID")) exit("MAILCHIMP_LIST_ID environment variable no set\n");
define('MAILCHIMP_API_KEY', getenv('MAILCHIMP_API_KEY'));
define('MAILCHIMP_LIST_ID', getenv('MAILCHIMP_LIST_ID'));


require_once('vendor/autoload.php');
include('src/MailChimp.php');
require_once(PRESTASHOP_BASEDIR . '/config/config.inc.php');
require_once(_PS_MODULE_DIR_ . 'salesforce/classes/SalesforceEntity.php');

$mcApi = new \L214\MailChimp(['apiKey' => MAILCHIMP_API_KEY,
                              'listID' => MAILCHIMP_LIST_ID,
                              'campaignID' => '']);
$query = new DbQuery();
$query->select('*')
      ->from('achats_clients_sync')
      ->where('MCsyncEtat = \'tosync\' AND id_client_boutique = 4');

// whereas each user may have done multiple orders waiting a sync
// whereas MailChimp is keyed by email
// ... keep a trace of processed email just avoid multiple mailchimp sync for a given email.
$emails_synced = [];

foreach(Db::getInstance()->ExecuteS($query) as $o) {
  $order = (object)($o);
  $customer = new Customer($order->id_client_boutique);
  $address = new Address(Address::getFirstCustomerAddressId($customer->id));

  $email = $customer->email; // normally: same as $order->courriel
  $newsletter = $customer->newsletter; // normally: same as $order->newsletter
  $cp = $address->postcode;

  $userInfo = $mcApi->getUserByEmail($email);
  // TODO:
  var_dump($userInfo);

  /* useless until SalesforceEntity implements an ObjectModel
  // $order->set(...)
  $order->save(); */

  die;
}
