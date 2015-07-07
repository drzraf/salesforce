CREATE TABLE IF NOT EXISTS `PREFIX_achats_clients_sync` (
`id_order` varchar(128) NOT NULL,
`montant` decimal(17,2) unsigned NOT NULL, 
`date` datetime NOT NULL,
`choixPaiement` set('CB','PA','CH','VI','PR') NOT NULL COMMENT 'credit card, Paypal, Check, Transfer, PR?',
`etat` set('attente','valide','erreur','test') NOT NULL,
`erreurPaybox` varchar(5) DEFAULT NULL,
`erreurPaypal` varchar(5) DEFAULT NULL,
`estAdhesion` set('oui','non') DEFAULT NULL,
`recuFiscal` set('oui','non') DEFAULT NULL,
`commentaire` text,
`URLInterface` varchar(255) NOT NULL, 
`adresseIP` varchar(255) DEFAULT NULL COMMENT "IP du client au moment de l’achat",
`intitule` varchar(255) DEFAULT NULL, 
`panier` text DEFAULT NULL, 
`id_client_boutique` varchar(128) NOT NULL,

`nom` varchar(255) NOT NULL,
`prenom` varchar(255) NOT NULL,
`courriel` varchar(255) NOT NULL,
`telephone` varchar(30) DEFAULT NULL,
`adresse` varchar(255) NOT NULL,
`adresseComplement` varchar(255) DEFAULT NULL,
`codePostal` varchar(16) NOT NULL COMMENT 'utile à Mailchimp',
`ville` varchar(255) NOT NULL,
`pays` varchar(255) NOT NULL,
`newsletter` set('oui','non') NOT NULL,
`pasDePapier` set('oui','non') NOT NULL DEFAULT 'non',

`SFsyncDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec Salesforce',
`SFsyncEtat` set('synchronised','tosync','torefresh','error') NOT NULL COMMENT 'Mettre toujours "tosync". Sera modifié au moment de la synchro avec Salesforce',
`SFsyncErreur` text NOT NULL COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec Salesforce',

`MCsyncDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec MailChimp',
`MCsyncEtat` set('synchronised','tosync','torefresh','error') COLLATE utf8_unicode_ci NOT NULL COMMENT 'Mettre toujours "tosync". Sera modifié au moment de la synchro avec MailChimp',
`MCsyncErreur` text COLLATE utf8_unicode_ci NOT NULL COMMENT 'Laisser à NULL, sera rempli au moment de la synchro avec MailChimp',

PRIMARY KEY (`id`)
) ENGINE=ENGINE_TYPE DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
