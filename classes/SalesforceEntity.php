<?php
/**
 *  @author    Raphaël Droz
 *  @copyright 2015, L214
 *  @license   General Public Licence v3 or later
 */

class SalesforceEntity extends ObjectModel {

  public $force_id = TRUE;

  public $id_order;
  public $montant;
  public $date;
  public $choixPaiement;
  public $etat;
  public $erreurPaybox;
  public $erreurPaypal;
  public $estAdhesion;
  public $recuFiscal;
  public $commentaire;
  public $URLInterface;
  public $adresseIP;
  public $intitule;
  public $panier;
  public $id_client_boutique;
  public $nom;
  public $prenom;
  public $courriel;
  public $telephone;
  public $adresse;
  public $adresseComplement;
  public $codePostal;
  public $ville;
  public $pays;
  public $newsletter;
  public $pasDePapier;
  public $totalVenteTTC_HP;
  public $totalDon;
  public $totalVenteHT_TVA_0;
  public $totalVenteHT_TVA_5_5;
  public $totalVenteHT_TVA_20;
  public $shipping_tax_excl;
  public $SFsyncDate;
  public $SFsyncEtat;
  public $SFsyncErreur;
  public $MCsyncDate;
  public $MCsyncEtat;
  public $MCsyncErreur;

  public static $definition = array(
    'table' => 'achats_clients_sync',
    'primary' => 'id_order',
    'fields' => array(
      // added here because even on insert it's not auto-determined by Prestashop
      // (attribute marked "primary" is by itself not part of updated columns)
      'id_order' => ['type' => self::TYPE_STRING, 'required' => true],
      'montant' => ['type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true],
      'date' => ['type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true],
      'choixPaiement' => ['type' => self::TYPE_STRING, 'size' => 2, 'required' => true],
      'etat' => ['type' => self::TYPE_STRING, 'size' => 16, 'required' => true],
      'erreurPaybox' => ['type' => self::TYPE_STRING, 'size' => 5],
      'erreurPaypal' => ['type' => self::TYPE_STRING, 'size' => 5],
      'estAdhesion' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
      'recuFiscal' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
      'commentaire' => ['type' => self::TYPE_STRING],
      'URLInterface' => ['type' => self::TYPE_STRING, 'validate' => 'isUrl', 'required' => true],
      'adresseIP' => ['type' => self::TYPE_STRING],
      'intitule' => ['type' => self::TYPE_STRING],
      'panier' => ['type' => self::TYPE_STRING],
      'id_client_boutique' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],

      'nom' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true],
      'prenom' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true],
      'courriel' => ['type' => self::TYPE_STRING, 'validate' => 'isEmail', 'required' => true],
      'telephone' => ['type' => self::TYPE_STRING, 'validate' => 'isPhoneNumber', 'required' => true],
      'adresse' => ['type' => self::TYPE_STRING, 'validate' => 'isAddress', 'required' => true],
      'adresseComplement' => ['type' => self::TYPE_STRING, 'required' => false],
      'codePostal' => ['type' => self::TYPE_STRING, 'validate' => 'isPostCode', 'required' => true],
      'ville' => ['type' => self::TYPE_STRING, 'validate' => 'isCityName', 'required' => true],
      'pays' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 64, 'required' => true],
      'newsletter' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true],
      'pasDePapier' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => false],

      'SFsyncDate' => ['type' => self::TYPE_DATE, 'required' => false],
      'SFsyncEtat' => ['type' => self::TYPE_STRING, 'size' => 16, 'required' => true],
      'SFsyncErreur' => ['type' => self::TYPE_STRING, 'required' => false],

      'MCsyncDate' => ['type' => self::TYPE_DATE, 'required' => false],
      'MCsyncEtat' => ['type' => self::TYPE_STRING, 'size' => 16, 'required' => true],
      'MCsyncErreur' => ['type' => self::TYPE_STRING, 'required' => false],
    )
  );

/*     
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
       'id_client_boutique' => $entity->idClientBoutique,
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
       'SFsyncEtat' => $entity->SFsyncEtat,
       'MCsyncEtat' => $entity->MCsyncEtat
       );

       if(! $exist) {
       $data = ['id' => $entity->orderId] + $data;
       return Db::getInstance()->insert('achats_clients_sync', $data);
       }

       else {
       return Db::getInstance()->update('achats_clients_sync', $data, '`id` = ' .  $entity->getOrderId());
       }
*/

  public static function getAllRecords() {
    return Db::getInstance()->ExecuteS("SELECT * FROM `" . _DB_PREFIX_ . "achats_clients_sync`");
  }

}
