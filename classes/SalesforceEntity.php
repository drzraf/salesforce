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

class SalesforceEntity {

    public $orderId;
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
    public $idClientBoutique;
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
    // below: no getter/setter
    public $SFsyncDate;
    public $SFsyncEtat;
    public $SFsyncErreur;
    public $MCsyncDate;
    public $MCsyncEtat;
    public $MCsyncErreur;

    public function setOrderId($orderId) {
        $this->orderId = $orderId;

        return ($this);
    }

    public function setMontant($montant) {
        $this->montant = $montant;

        return ($this);
    }

    public function setDate($date) {
        $this->date = $date;

        return ($this);
    }

    public function setChoixPaiement($choixPaiement) {
        $this->choixPaiement = $choixPaiement;

        return ($this);
    }

    public function setEtat($etat) {
        $this->etat = $etat;

        return ($this);
    }

    public function setErreurPaybox($erreurPaybox) {
        $this->erreurPaybox = $erreurPaybox;

        return ($this);
    }

    public function setErreurPaypal($erreurPaypal) {
        $this->erreurPaypal = $erreurPaypal;

        return ($this);
    }

    public function setEstAdhesion($estAdhesion) {
        $this->estAdhesion = $estAdhesion;

        return ($this);
    }

    public function setRecuFiscal($recuFiscal) {
        $this->recuFiscal = $recuFiscal;

        return ($this);
    }

    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;

        return ($this);
    }

    public function setURLInterface($URLInterface) {
        $this->URLInterface = $URLInterface;

        return ($this);
    }

    public function setAdresseIP($adresseIP) {
        $this->adresseIP = $adresseIP;

        return ($this);
    }

    public function setIntitule($intitule) {
        $this->intitule = $intitule;

        return ($this);
    }

    public function setPanier($panier) {
        $this->panier = $panier;

        return ($this);
    }

    public function setIdClientBoutique($idClientBoutique) {
        $this->idClientBoutique = $idClientBoutique;

        return ($this);
    }

    public function setNom($nom) {
        $this->nom = $nom;

        return ($this);
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;

        return ($this);
    }

    public function setCourriel($courriel) {
        $this->courriel = $courriel;

        return ($this);
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;

        return ($this);
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return ($this);
    }

    public function setAdresseComplement($adresseComplement) {
        $this->adresseComplement = $adresseComplement;

        return ($this);
    }

    public function setCodePostal($codePostal) {
        $this->codePostal = $codePostal;

        return ($this);
    }

    public function setVille($ville) {
        $this->ville = $ville;

        return ($this);
    }

    public function setPays($pays) {
        $this->pays = $pays;

        return ($this);
    }

    public function setPasDePapier($pasDePapier) {
        $this->pasDePapier = $pasDePapier;

        return ($this);
    }

    public function getOrderId() {
        return ($this->orderId);
    }

    public function getMontant() {
        return ($this->montant);
    }

    public function getDate() {
        return ($this->date);
    }

    public function getChoixPaiement() {
        return ($this->choixPaiement);
    }

    public function getEtat() {
        return ($this->etat);
    }

    public function getErreurPaybox() {
        return ($this->erreurPaybox);
    }

    public function getErreurPaypal() {
        return ($this->erreurPaypal);
    }

    public function getEstAdhesion() {
        return ($this->estAdhesion);
    }

    public function getRecuFiscal() {
        return ($this->recuFiscal);
    }

    public function getCommentaire() {
        return ($this->commentaire);
    }

    public function getURLInterface() {
        return ($this->URLInterface);
    }

    public function getAdresseIP() {
        return ($this->adresseIP);
    }

    public function getIntitule() {
        return ($this->intitule);
    }

    public function getPanier() {
        return ($this->panier);
    }

    public function getIdClientBoutique() {
        return ($this->idClientBoutique);
    }

    public function getNom() {
        return ($this->nom);
    }

    public function getPrenom() {
        return ($this->prenom);
    }

    public function getCourriel() {
        return ($this->courriel);
    }

    public function getTelephone() {
        return ($this->telephone);
    }

    public function getAdresse() {
        return ($this->adresse);
    }

    public function getAdresseComplement() {
        return ($this->adresseComplement);
    }

    public function getCodePostal() {
        return ($this->codePostal);
    }

    public function getVille() {
        return ($this->ville);
    }

    public function getPays() {
        return ($this->pays);
    }

    public function getNewsletter() {
        return ($this->newsletter);
    }

    public function getPasDePapier() {
        return ($this->pasDePapier);
    }
}
