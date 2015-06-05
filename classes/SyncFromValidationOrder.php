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
 *  @copyright 2012-2015 ZL Development
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once('SalesforceEntity.php');
require_once('SalesforceSQL.php');

class SyncFromValidationOrder extends SalesforceEntity {

    public $context;

    public function setContext($context) {
        $this->context = $context;

        return ($this);
    }

    public function getContext() {
        return ($this->context);
    }

    public function parseProduct($products) {
        $product_list = "";
        $product_size = count($products);
        if ($product_size > 0) {
            for ($i = 0; $i < $product_size; $i++) {
                $product_list .= $products[$i]['name'] . ";";
            }
        }

        return ($product_list);
    }

    // $payment_method = 4th argument passed to validateOrder() by payment modules
    public function parseChoixPaiement($addon, $module = NULL, $payment_method = NULL ) {
        $payment = 'PR';
        if (is_object($addon)) {
            $paymentAddons = Array(
                'paybox' => 'CB',
                'worldpay' => 'CB',
                'sagepay' => 'CB',
                'kwixo' => 'CB',
                'cmcic' => 'CB',
                'paypal' => 'PA',
                'cheque' => 'CH',
                'bankwire' => 'VI'
            );
            if (isset($paymentAddons[$addon->module_name])) {
                return $paymentAddons[$addon->module_name];
            }
        }
        if ($payment_method == 'CB avec Paybox') return 'CB';
        // may come other tests over $module AND/OR $payment_method
        return 'PR'; // default
    }
    
    public function parseEtat($addon) {
        $etat = 'erreur';
        if (is_object($addon)) {
            $paymentAddons = Array(
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
            if (isset($paymentAddons[$addon->name[1]])) {
                $etat = $paymentAddons[$addon->name[1]];
            } else {
                $etat = 'erreur';
            }
        }

        return ($etat);
    }

    public function setNewsletter($newsletter) {
        if ($newsletter == 0 || empty($newsletter)) {
            $res = 'non';
        } else {
            $res = 'oui';
        }

        parent::setNewsletter($res);

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

    public function setCustomerAddressFromContext($customer) {
        $address = new Address(Address::getFirstCustomerAddressId($customer->id));
        $this
                ->setTelephone(($address->phone ? $address->phone : $address->phone_mobile))
                ->setAdresse($address->address1)
                ->setAdresseComplement($address->address2)
                ->setCodePostal($address->postcode)
                ->setVille($address->city)
                ->setPays($address->country)
        ;

        return ($this);
    }

    public function setCustomerFromContext() {
        $customer = $this->context->customer;
        $this
                ->setIdClientBoutique($customer->id)
                ->setNom($customer->lastname)
                ->setPrenom($customer->firstname)
                ->setCourriel($customer->email)
                ->setNewsletter($customer->newsletter)
                ->setCustomerAddressFromContext($customer)
        ;

        return ($this);
    }

    public function setCartFromContext() {
        $cart = new Cart($this->context->cart->id);

        $this
                ->setPanier($this->parseProduct($cart->getProducts()))
                ->setMontant($cart->getOrderTotal())
        ;

        return ($this);
    }

    public function setOrderFromContext() {
        $orderId = Order::getOrderByCartId($this->context->cart->id);
        $order = new Order($orderId);

        $this
                ->setOrderId($orderId)
                ->setDate($order->date_upd)
                ->setChoixPaiement($this->parseChoixPaiement($order->getCurrentOrderState(),
                                                             $order->module,
                                                             $order->payment
                ))
                ->setEtat($this->parseEtat($order->getCurrentOrderState()))
                ->setCommentaire($order->getFirstMessage())
        ;

        return ($this);
    }

    public function setErrorsFromContext($options) {
        if (isset($options['paybox'])) {
            $this->erreurPaybox = $options['paybox'];
        } else if (isset($options['paypal'])) {
            $this->erreurPaypal = $options['paypal'];
        }

        return ($this);
    }

    public function setExtras($options) {
        $shop = new ShopURL($this->context->cart->id_shop);
        $this
                ->setURLInterface($shop->getURL())
                ->setAdresseIP(preg_replace("#\\000#", '', $_SERVER['REMOTE_ADDR']))
                ->setIntitule('Achats')
                ->setErrorsFromContext($options)
        ;

        return ($this);
    }

    public static function initFromContext($context, $options = NULL) {
        $sync = new SyncFromValidationOrder();
        $sync
                ->setContext($context)
                ->setCustomerFromContext()
                ->setCartFromContext()
                ->setOrderFromContext()
                ->setExtras($options)
                ->setSyncEtat("tosync")
        ;
        SalesforceSQL::save($sync);
    }

}
