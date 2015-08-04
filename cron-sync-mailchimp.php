#!/usr/bin/env php
<?php

if(PHP_SAPI !== 'cli') exit; // cli only

$options = getopt("d:");
if(isset($options['d']) && is_dir($options['d'])) {
  define('PRESTASHOP_BASEDIR', $options['d']);
} else {
  exit(basename(__FILE__) .
       <<<EOF
 -d <DIR>
\trun the MailChimp sync of customers having pending orders.

Parameters:
\t-d <DIR>: the prestashop base directory

Environment:
\tMAILCHIMP_API_KEY (eg: de7798....-us1)
\tMAILCHIMP_LIST_ID (eg: 2859...80
\tMAILCHIMP_TEST_EMAIL: if set, restrict processing to the specified email

EOF
  );
}

if(!getenv("MAILCHIMP_API_KEY")) exit("MAILCHIMP_API_KEY environment variable no set\n");
if(!getenv("MAILCHIMP_LIST_ID")) exit("MAILCHIMP_LIST_ID environment variable no set\n");
define('MAILCHIMP_API_KEY', getenv('MAILCHIMP_API_KEY'));
define('MAILCHIMP_LIST_ID', getenv('MAILCHIMP_LIST_ID'));


require __DIR__ . '/vendor/autoload.php';
require_once(PRESTASHOP_BASEDIR . '/config/config.inc.php');
require_once(_PS_MODULE_DIR_ . 'salesforce/classes/SalesforceEntity.php');

$mcApi = new L214\MailChimp(['apiKey' => MAILCHIMP_API_KEY,
                              'listID' => MAILCHIMP_LIST_ID,
                              'campaignID' => '']);
$query = new DbQuery();
$query->select('*')
      ->from('achats_clients_sync')
  ;//->where('MCsyncEtat = "tosync"');

// debugging, restrict processing to this email only
if(getenv('MAILCHIMP_TEST_EMAIL')) {
  $query->where('courriel = "' . getenv('MAILCHIMP_TEST_EMAIL') . '"');
}

// whereas each user may have done multiple orders waiting a sync
// whereas MailChimp is keyed by email
// ... keep a trace of processed email just avoid multiple mailchimp sync for a given email.
$emails_synced = [];

foreach(Db::getInstance()->ExecuteS($query) as $o) {
  $order = (object)($o);
  $customer = new Customer($order->id_client_boutique);
  $address = new Address(Address::getFirstCustomerAddressId($customer->id));
  $country = new Country($address->id_country);
  $language = new Language($customer->id_lang);
  
  $email = $customer->email; // normally: same as $order->courriel
  if(isset($emails_synced[$email])) continue;

  $newsletter = $customer->newsletter; // normally: same as $order->newsletter

  if(! $newsletter) {
    $emails_synced[$email] = 1;
    continue; // no more processing (no unsubscription);
  }

  $cp = $address->postcode;
  $cc = $country->iso_code;

  $userInfo = $mcApi->getUserByEmail($email);

  // var_dump($userInfo); die;
  /* on if SalesforceEntity implements the ObjectModel
  // $order->set(...)
  $order->save(); */

  $ret = $mcApi->subscribeNewsletter($email, $customer->firstname, $customer->lastname,
                              $cp, $cc, $language->iso_code,
                              'PrestaShop', 'html', FALSE, TRUE);
  if($ret) {
    $emails_synced[$email] = 1;
    $msg = "mailchimp/prestashop: $email: newsletter=$newsletter";
    if(isset($userInfo['status'])) $msg.= sprintf(' (previous status was "%s")',
                                                  $userInfo["status"]);
    error_log($msg);
  }
  else {
    die("error during synchronisation: email=$email, id_order={$order->id_order}");
  }
}

if($emails_synced) {
  Db::getInstance()->update(
    'achats_clients_sync',
    ['MCsyncEtat' => 'synchronized'],
    'MCsyncEtat = "tosync"'
    . ' AND courriel IN ("' . implode('","', array_flip($emails_synced)) . '")');
}
