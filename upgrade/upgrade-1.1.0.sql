ALTER TABLE `PREFIX_achats_clients_sync` CHANGE `syncDate` `SFsyncDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec Salesforce';

ALTER TABLE `PREFIX_achats_clients_sync` CHANGE `syncEtat` `SFsyncEtat` set('synchronised','tosync','torefresh','error') NOT NULL COMMENT 'Mettre toujours "tosync". Sera modifié au moment de la synchro avec Salesforce';

ALTER TABLE `PREFIX_achats_clients_sync` CHANGE `syncErreur` `SFsyncErreur` text NOT NULL COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec Salesforce';

ALTER TABLE `PREFIX_achats_clients_sync` ADD `MCsyncDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec MailChimp' AFTER `SFsyncErreur`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `MCsyncEtat` set('synchronised','tosync','torefresh','error') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mettre toujours "tosync". Sera modifié au moment de la synchro avec MailChimp' AFTER `MCsyncDate`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `MCsyncErreur` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec MailChimp' AFTER `MCsyncEtat`;
