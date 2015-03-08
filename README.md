# Salesforce

## Installation

1. Move this folder salesforce from this modules folder to your PrestaShop modules folder
  * Example: `cp modules/salesforce /var/www/prestashop-1.6.0.14/modules -rf`
2. For each payment Modules, edit the file `controllers/front/validation` like this :
  * Add `require(dirname(__FILE__).'/../../../../modules/salesforce/classes/SyncFromValidationOrder.php');` in the top of file, after `<?php`
  * Add this line `SyncFromValidationOrder::initFromContext($this->context);` after `$this->module->validateOrder(...)` or after `parent::validateOrder(....)` and `before Tools::redirect(...)`
  * if it is a Paybox or Paypal Module you must edit the previously pasted code by  `SyncFromValidationOrder::initFromContext($this->context, Array('paybox' => $variableErrorCodePaybox);` or by  `SyncFromValidationOrder::initFromContext($this->context, Array('paypal' => $variableErrorCodePaypal);`
3. Then, install it from your back office
4. Enjoy!
