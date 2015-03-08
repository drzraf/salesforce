{*
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
*}

<style type="text/css">
    .table-responsive {
        display: -moz-box;
        overflow: scroll;
    }
</style>

<div class="panel">
    <h3><i class="icon icon-credit-card"></i> {l s='Salesforce' mod='salesforce'}</h3>

    <table class="table table-stripped table-hover table-responsive">
        <thead>
            <tr>
                <th colspan="2">Actions</th>
                <th>id</th>
                <th>montant</th>
                <th>date</th>
                <th>choixPaiement</th>
                <th>etat</th>
                <th>erreurPaybox</th>
                <th>erreurPaypal</th>
                <th>estAdhesion</th>
                <th>recuFiscal</th>
                <th>commentaire</th>
                <th>URLInterface</th>
                <th>adresseIP</th>
                <th>intitule</th>
                <th>panier</th>
                <th>id_client_boutique</th>
                <th>nom</th>
                <th>prenom</th>
                <th>courriel</th>
                <th>telephone</th>
                <th>adresse</th>
                <th>adresseComplement</th>
                <th>codePostal</th>
                <th>ville</th>
                <th>pays</th>
                <th>newsletter</th>
                <th>pasDePapier</th>
                <th>syncDate</th>
                <th>SyncEtat</th>
                <th>SyncErreur</th>
            </tr>
        </thead>
        
        <tbody>
        {foreach from=$entry item=foo}
            <tr>
                <td colspan="2">
                    <button class="btn btn-default" title="Marqué toSync">toSync</button>
                    <button class="btn btn-success" title="Marqué synchronisé">synchronised</button>
                </td>
                <td>{$foo['id']}</td>
                <td>{$foo['montant']} €</td>
                <td>{$foo['date']}</td>
                <td>{$foo['choixPaiement']}</td>
                <td>{$foo['etat']}</td>
                <td>{$foo['erreurPaybox']}</td>
                <td>{$foo['erreurPaypal']}</td>
                <td>{$foo['estAdhesion']}</td>
                <td>{$foo['recuFiscal']}</td>
                <td>{$foo['commentaire']}</td>
                <td>{$foo['URLInterface']}</td>
                <td>{$foo['adresseIP']}</td>
                <td>{$foo['intitule']}</td>
                <td>{$foo['panier']}</td>
                <td>{$foo['id_client_boutique']}</td>
                <td>{$foo['nom']}</td>
                <td>{$foo['prenom']}</td>
                <td>{$foo['courriel']}</td>
                <td>{$foo['telephone']}</td>
                <td>{$foo['adresse']}</td>
                <td>{$foo['adresseComplement']}</td>
                <td>{$foo['codePostal']}</td>
                <td>{$foo['ville']}</td>
                <td>{$foo['pays']}</td>
                <td>{$foo['newsletter']}</td>
                <td>{$foo['pasDePapier']}</td>
                <td>{$foo['syncDate']}</td>
                <td>{$foo['syncEtat']}</td>
                <td>{$foo['syncErreur']}</td>
            </tr>
        {/foreach}
        </tbody>
    </table>
</div>