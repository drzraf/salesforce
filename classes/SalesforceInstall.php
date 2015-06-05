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
class SalesforceInstall {

    /**
     * Create table
     */
    private function createTables() {
        if (!Db::getInstance()->Execute("
            CREATE TABLE IF NOT EXISTS `" . _DB_PREFIX_ . "achats_clients_sync` (
                `id` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
                `montant` decimal(17,2) unsigned NOT NULL, 
                `date` datetime NOT NULL,
                -- credit card, Paypal, Check, Transfer, PR?
                `choixPaiement` set('CB','PA','CH','VI','PR') COLLATE utf8_unicode_ci NOT NULL,
                `etat` set('attente','valide','erreur','test') COLLATE utf8_unicode_ci NOT NULL,
                `erreurPaybox` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
                `erreurPaypal` varchar(5) COLLATE utf8_unicode_ci DEFAULT NULL,
                `estAdhesion` set('oui','non') COLLATE utf8_unicode_ci DEFAULT NULL,
                `recuFiscal` set('oui','non') COLLATE utf8_unicode_ci DEFAULT NULL,
                `commentaire` text COLLATE utf8_unicode_ci, 
                `URLInterface` varchar(255) COLLATE utf8_unicode_ci NOT NULL, 
                `adresseIP` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `intitule` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL, 
                `panier` text COLLATE utf8_unicode_ci DEFAULT NULL, 
                `id_client_boutique` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
                `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `courriel` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `telephone` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
                `adresse` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `adresseComplement` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
                `codePostal` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
                `ville` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `pays` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                `newsletter` set('oui','non') COLLATE utf8_unicode_ci NOT NULL,
                `pasDePapier` set('oui','non') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'non',
                `syncDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
                `syncEtat` set('synchronised','tosync','torefresh','error') COLLATE utf8_unicode_ci NOT NULL,
                `syncErreur` text COLLATE utf8_unicode_ci NOT NULL,  PRIMARY KEY (`id`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;")) {
            return false;
        }
    }

    /**
     * Install Bitcoin
     */
    public static function install() {
        $install = new SalesforceInstall();
        $install->createTables();
    }

}
