<?php
/**
 * 2012-2015 ZL Development
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zakarialounes.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://www.zakarialounes.fr for more information.
 *
 *  @author    ZL Development <me@zakarialounes.fr>
 *  @copyright 2015 ZL Development
 *  @author    Raphaël Droz <raphael.droz+floss@gmail.com>
 *  @copyright 2015
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  @license   General Public Licence v3 or later
 */

require_once('SalesforceEntity.php');

class SyncFromValidationOrder extends SalesforceEntity {

  const paymentAddons = Array(
    'paybox' => 'CB',
    'worldpay' => 'CB',
    'sagepay' => 'CB',
    'kwixo' => 'CB',
    'cmcic' => 'CB',
    'paypal' => 'PA',
    'cheque' => 'CH',
    'bankwire' => 'VI'
  );

  const paymentStates = Array(
    'En attente du paiement par chèque' => 'attente',
    'En attente de paiement par chèque' => 'attente',
    'Paiement accepté' => 'valide',
    'Préparation en cours' => 'valide',
    'En cours de préparation' => 'valide',
    'En cours de livraison' => 'valide',
    'Expedié' => 'valide',
    'Livré' => 'valide',
    'Annulé' => 'erreur',
    'Remboursé' => 'erreur',
    'Erreur de paiement' => 'erreur',
    'En attente de réapprovisionnement' => 'attente',
    'En attente de réapprovisionnement (payé)' => 'attente',
    'En attente du paiement par virement bancaire' => 'attente',
    'En attente de virement bancaire' => 'attente',
    'En attente du paiement PayPal' => 'attente',
    'En attente de paiement PayPal' => 'attente',
    'Paiement à distance accepté' => 'valide',
    'En attente de réapprovisionnement (non payé)' => 'attente',
    'En attente de paiement à la livraison' => 'attente',
    'Autorisation accepté par PayPal' => 'valide',
  );

  public static function parseProduct($products) {
    return implode(';',
                   array_map(function($e) { return $e['name']; },
                             $products));
  }

  // $payment_method = 4th argument passed to validateOrder() by payment modules
  public static function parseChoixPaiement($addon, $module = NULL, $payment_method = NULL ) {
    if (is_object($addon)) {
      if (array_key_exists($addon->module_name, self::paymentAddons)) {
        return self::paymentAddons[$addon->module_name];
      }
    }
    if ($payment_method == 'CB avec Paybox') return 'CB';
    // may come other tests over $module AND/OR $payment_method
    return 'PR'; // prélèvement
  }
    
  public static function parseEtat($addon) {
    if (is_object($addon)) {
      if (array_key_exists($addon->name[1], self::paymentStates)) {
        return self::paymentStates[$addon->name[1]];
      } else {
        return 'erreur';
      }
    }
    return 'erreur';
  }

  public function setNewsletter($customer_data, $first_order = TRUE) {
    // see https://github.com/PrestaShop/PrestaShop/pull/3350
    $x1 = time() - strtotime($customer_data->newsletter_date_add);
    $x2 = time() - strtotime($customer_data->date_upd);
    // modification récente du profile: signifie soit que la newsletter
    // a été cochée il y a moins de 3 jours, soit tout autre modification du
    // profile de moins de 3 jours
    $recent_mod = ($x1 <= 3600 * 24 * 3 || $x2 <= 3600 * 24 * 3);

    if (!$customer_data->newsletter) {
      $this->newsletter = FALSE;
    } else {
      $this->newsletter = TRUE;
    }


    // premier achat et case "newsletter" cochée: synchro
    if($first_order && $this->newsletter) {
      $this->MCsyncEtat = "tosync";
    }

    // acheteur déjà connu ayant modifié son profile il y a moins
    // de 3 jours et ayant "newsletter" coché
    elseif(!$first_order && $this->newsletter && $recent_mod) {
      $this->MCsyncEtat = "tosync";
    }

    else {
      $this->MCsyncEtat = "synchronised"; // "torefresh" ?
    }

    return ($this);
  }

  public function setPasDePapier($pasDePapier) {
    if ($pasDePapier == 0 || empty($pasDePapier)) {
      $res = 'non';
    } else {
      $res = 'oui';
    }

    parent::setPasDePapier($res);

    return ($res);
  }

  public function setTotaux($cart, $order, $products, $context) {
    /* cf:
       SELECT GROUP_CONCAT(id_product), t.name, p.id_tax_rules_group from ps1_product_shop p LEFT JOIN ps1_tax_rules_group t ON t.id_tax_rules_group = p.id_tax_rules_group

       Investigate the alternative (overhead but maybe more exact):
       new Product(id_product)->getTaxesRate() ?

       // howto re-run hook for Cart ID:
       include('config/config.inc.php');
       Order::getOrdersWithInformations();
       $x=new Order(Order::getOrderByCartId(45));
       $x->context = Context::getContext();
       $x->context->cart = new Cart(45);
       $x->setCurrentState(1);
    */

    $this->total_vente_ttc_hp
      = $this->total_don
      = $this->total_vente_ht_tva_0
      = $this->total_vente_ht_tva_5_5
      = $this->total_vente_ht_tva_20
      = $this->shipping_tax_excl
      = 0;

    foreach($products as $product) {
      $id_product = $product['id_product'];
      $rateInfo = Product::getTaxesInformations(array('id_product' => $id_product),
                                                $context);
      $tags = Tag::getProductTags($product['id_product']);
      $tags = $tags[$context->cart->id_lang];
      $is_don = ($tags && array_search("don", $tags) !== FALSE);
						
      $rate = $rateInfo['rate'];
      $price = $product['price'];
      if(isset($product['pwyw_price']) && $product['pwyw_price']) {
        // TODO: si price exist, pwyw_price est toujours un prix libre
        // (mais un prix a pu être suggéré).
        // Cela ne concerne que des produits en TVA 0
        $rate = 0;
        $price = $product['pwyw_price'];
      }

      if($is_don) {
        $this->total_don += $price;
        continue; // le montant des dons ne s'ajoute point aux achats
      }
      else {
        // total_vente_ttc_hp: cumul des achats TVA comprise indépendemment de leur taux de TVA
        $this->total_vente_ttc_hp += ($rate == 0) ? $price : $product['price_wt'];
        if($rate == 0) $this->total_vente_ht_tva_0         += $price;
        elseif($rate == 5.5) $this->total_vente_ht_tva_5_5 += $price;
        elseif($rate == 20) $this->total_vente_ht_tva_20   += $price;
      }
    }

    /* at the time of the order, it was $order->total_shipping_tax_excl
    // $this->shipping_tax_excl = $order->total_shipping_tax_excl;
    but it may have changed since. The dynamically computed value is: */
    $this->shipping_tax_excl = $cart->getOrderTotal(false, Cart::ONLY_SHIPPING);
    // Note: tax affecting the carrier are available through $order->carrier_tax_rate

    return $this;
  }

  public function setErrorsFromContext($options) {
    if (isset($options['paybox'])) {
      $this->erreurPaybox = $options['paybox'];
    } else if (isset($options['paypal'])) {
      $this->erreurPaypal = $options['paypal'];
    }

    return ($this);
  }

  public static function initFromContext($context, $options = NULL) {

    $customer = $context->customer;

    $shop = new ShopURL($context->cart->id_shop);
    $cart = new Cart($context->cart->id);

    // set customer from the ID registered in the cart
    // useful for hooks run from command line on existing carts
    if(is_null($customer->id) && $context->cart->id_customer) {
      $customer = new Customer($context->cart->id_customer);
    }
    $first_customer_order = (int)Db::getInstance()->getValue('SELECT COUNT(1) FROM `'
                                                             . _DB_PREFIX_ . 'achats_clients_sync`'
                                                             . ' WHERE id_client_boutique = '
                                                             . (int)$customer->id) == 0;

    $order = new Order(Order::getOrderByCartId($context->cart->id));
    if(! $order->id) {
      error_log("order->id is null/empty!");
      return; // avoid storing/synchronizing obviously corrupted data
    }

    $address = new Address(Address::getFirstCustomerAddressId($customer->id));
    $products = $cart->getProducts();

    $sync = new SyncFromValidationOrder($order->id);
    $sync->id_order = $order->id;
    $sync->date = $order->date_upd;

    // customer
    $sync->id_client_boutique = $customer->id;
    $sync->nom = $customer->lastname;
    $sync->prenom = $customer->firstname;
    $sync->courriel = $customer->email;
    $sync->setNewsletter($customer, $first_customer_order);
    // $sync->estAdhesion = ;
    // $sync->recuFiscal = ;
    // $sync->pasDePapier = ; setPasDePapier(xxx),

    // customer address
    $sync->telephone = ($address->phone ? : $address->phone_mobile);
    $sync->adresse = $address->address1;
    $sync->adresseComplement = $address->address2;
    $sync->codePostal = $address->postcode;
    $sync->ville = $address->city;
    $sync->pays = $address->country;

    // cart
    $sync->montant = $cart->getOrderTotal();
    $sync->panier = self::parseProduct($products);
    $sync->setTotaux($cart, $order, $products, $context);
    $sync->choixPaiement = self::parseChoixPaiement($order->getCurrentOrderState(),
                                                    $order->module,
                                                    $order->payment);
    $sync->etat = self::parseEtat($order->getCurrentOrderState());

    // misc
    $sync->commentaire = $order->getFirstMessage();
    $sync->URLInterface = $shop->getURL();
    $sync->adresseIP = $_SERVER['REMOTE_ADDR'];
    $sync->intitule = 'Achats';
    $sync->setErrorsFromContext($options);

    $sync->SFsyncEtat = "tosync";

    $sync->save();
  }
}
