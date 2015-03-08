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
        $sql = "INSERT INTO `" . _DB_PREFIX_ . "achats_clients_sync` "
                . "(`id`, `montant`, `date`, `choixPaiement`, `etat`, `erreurPaybox`, `erreurPaypal`, "
                . "`estAdhesion`, `recuFiscal`, `commentaire`, `URLInterface`, `adresseIP`, "
                . "`intitule`, `panier`, `id_client_boutique`, `nom`, `prenom`, `courriel`, "
                . "`telephone`, `adresse`, `adresseComplement`, `codePostal`, `ville`, `pays`, "
                . "`newsletter`, `pasDePapier`, `syncEtat`) "
                . "VALUES ("
                . "'" . $entity->getOrderId() . "', "
                . "'" . $entity->getMontant() . "', "
                . "'" . $entity->getDate() . "', "
                . "'" . $entity->getChoixPaiement() . "', "
                . "'" . $entity->getEtat() . "', "
                . "'" . $entity->getErreurPaybox() . "', "
                . "'" . $entity->getErreurPaypal() . "', "
                . "'" . $entity->getEstAdhesion() . "', "
                . "'" . $entity->getRecuFiscal() . "', "
                . "'" . addslashes($entity->getCommentaire()) . "', "
                . "'" . $entity->getURLInterface() . "', "
                . "'" . $entity->getAdresseIP() . "', "
                . "'" . $entity->getIntitule() . "', "
                . "'" . $entity->getPanier() . "', "
                . "'" . $entity->getIdClientBoutique() . "', "
                . "'" . addslashes($entity->getNom()) . "', "
                . "'" . addslashes($entity->getPrenom()) . "', "
                . "'" . $entity->getCourriel() . "', "
                . "'" . $entity->getTelephone() . "', "
                . "'" . addslashes($entity->getAdresse()) . "', "
                . "'" . addslashes($entity->getAdresseComplement()) . "', "
                . "'" . $entity->getCodePostal() . "', "
                . "'" . addslashes($entity->getVille()) . "', "
                . "'" . addslashes($entity->getPays()) . "', "
                . "'" . $entity->getNewsletter() . "', "
                . "'" . $entity->getPasDePapier() . "', "
                . "'" . $entity->getSyncEtat() . "'"
                . ");";

        if (!Db::getInstance()->Execute($sql)) {
            return false;
        }
    }

    public static function getAll() {
        return (Db::getInstance()->ExecuteS("SELECT * FROM `" . _DB_PREFIX_ . "achats_clients_sync`"));
    }

}
