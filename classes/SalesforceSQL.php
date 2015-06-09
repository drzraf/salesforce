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
ini_set('display_errors', 1);
ini_set('errors_reporting', E_ALL);

class SalesforceSQL {

    public static function save(SalesforceEntity $entity) {
      $exist = Db::getInstance()->getRow('SELECT 1 FROM `'._DB_PREFIX_.'achats_clients_sync`'
                                         . ' WHERE `id` = ' . "'" . $entity->getOrderId() . "'");
     
      $data = array(
        'montant' => $entity->getMontant(),
        'date' => $entity->getDate(),
        'choixPaiement' => $entity->getChoixPaiement(),
        'etat' => $entity->getEtat(),
        'erreurPaybox' => $entity->getErreurPaybox(),
        'erreurPaypal' => $entity->getErreurPaypal(),
        'estAdhesion' => $entity->getEstAdhesion(),
        'recuFiscal' => $entity->getRecuFiscal(),
        'commentaire' => addslashes($entity->getCommentaire()),
        'URLInterface' => $entity->getURLInterface(),
        'adresseIP' => $entity->getAdresseIP(),
        'intitule' => $entity->getIntitule(),
        'panier' => Db::getInstance()->escape($entity->getPanier()),
        'id_client_boutique' => $entity->getIdClientBoutique(),
        'nom' => addslashes($entity->getNom()),
        'prenom' => addslashes($entity->getPrenom()),
        'courriel' => $entity->getCourriel(),
        'telephone' => $entity->getTelephone(),
        'adresse' => addslashes($entity->getAdresse()),
        'adresseComplement' => addslashes($entity->getAdresseComplement()),
        'codePostal' => $entity->getCodePostal(),
        'ville' => addslashes($entity->getVille()),
        'pays' => addslashes($entity->getPays()),
        'newsletter' => $entity->getNewsletter(),
        'pasDePapier' => $entity->getPasDePapier(),
        'syncEtat' => $entity->getSyncEtat(),
      );

      if(! $exist) {
        $data = ['id' => $entity->getOrderId()] + $data;
        return Db::getInstance()->insert('achats_clients_sync', $data);
      }
      else {
        return Db::getInstance()->update('achats_clients_sync', $data, '`id` = ' .  $entity->getOrderId());
      }
    }
  
  public static function getAll() {
    return (Db::getInstance()->ExecuteS("SELECT * FROM `" . _DB_PREFIX_ . "achats_clients_sync`"));
  }
}
