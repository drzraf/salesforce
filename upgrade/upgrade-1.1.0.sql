ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_achat` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total relatif aux seuls achats TTC' AFTER `pasDePapier`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_don` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total relatif aux seuls dons' AFTER `total_achat`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_ht_achat_no_tva` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total des produits non-soumis à TVA' AFTER `total_don`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_ht_achat_tva_5_5` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total HT des produits soumis à une TVA à 5,5%' AFTER `total_ht_achat_no_tva`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_ht_achat_tva_20` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total HT des produits soumis à TVA à 20%' AFTER `total_ht_achat_tva_5_5`;

ALTER TABLE `PREFIX_achats_clients_sync` CHANGE `syncDate` `SFsyncDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec Salesforce';

ALTER TABLE `PREFIX_achats_clients_sync` CHANGE `syncEtat` `SFsyncEtat` set('synchronised','tosync','torefresh','error') NOT NULL COMMENT 'Mettre toujours "tosync". Sera modifié au moment de la synchro avec Salesforce';

ALTER TABLE `PREFIX_achats_clients_sync` CHANGE `syncErreur` `SFsyncErreur` text NOT NULL COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec Salesforce';

ALTER TABLE `PREFIX_achats_clients_sync` ADD `MCsyncDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec MailChimp' AFTER `SFsyncErreur`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `MCsyncEtat` set('synchronised','tosync','torefresh','error') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mettre toujours "tosync". Sera modifié au moment de la synchro avec MailChimp' AFTER `MCsyncDate`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `MCsyncErreur` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec MailChimp' AFTER `MCsyncEtat`;
