ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_achat` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total relatif aux seuls achats TTC' AFTER `pasDePapier`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_don` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total relatif aux seuls dons' AFTER `total_achat`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_ht_achat_no_tva` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total des produits non-soumis à TVA' AFTER `total_don`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_ht_achat_tva_5_5` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total HT des produits soumis à une TVA à 5,5%' AFTER `total_ht_achat_no_tva`;

ALTER TABLE `PREFIX_achats_clients_sync` ADD `total_ht_achat_tva_20` decimal(20,6) NOT NULL DEFAULT '0' COMMENT 'sous-total HT des produits soumis à TVA à 20%' AFTER `total_ht_achat_tva_5_5`;
