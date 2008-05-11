<?

if (empty($_SESSION[SITE_CONFIG]['CADDIE']['produits']) || $_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) goto($commandes_url.'?etape=etape1');


?><div id="commande" class="etape3">
	<h1 class="t_rub">Commander</h1>
    <!-- Nav -->
    <div class="nav_cmd">
    	<ul>
        	<li id="nc1"><a href="<?=$commandes_url;?>?etape=etape1"><img src="images/fr/titre/t_nc1.gif" alt="Ma s&eacute;lection" /></a></li>
            <li id="nc2"><a href="<?=$commandes_url;?>?etape=etape2"><img src="images/fr/titre/t_nc2.gif" alt="Mes coordonn&eacute;es" /></a></li>
            <li id="nc3"><a href="<?=$commandes_url;?>?etape=etape3"><img src="images/fr/titre/t_nc3_on.gif" alt="Moyens de paiement" /></a></li>
            <li id="nc4"><a href="javascript:void(0);"><img src="images/fr/titre/t_nc4.gif" alt="Confirmation" /></a></li>
        </ul>
    </div>
    
    <!-- Formulaire -->
    <div class="form_paiement">
       <form action="<?=$commandes_url;?>?etape=etape3&amp;action=VALIDATION" method="post" enctype="multipart/form-data" name="inscription3_frm" id="inscription3_frm" onsubmit="return false;">
        	<div class="ensemble">
            	<fieldset class="recap_cmd">
	                <legend>R&eacute;capitulatif de votre commande</legend>
                    <h2>R&eacute;capitulatif de votre commande</h2>
                	<div class="modifier"><a href="<?=$commandes_url;?>?etape=etape1"><img src="images/fr/bouton/bt_modifier.gif" alt="Modifier" /></a></div>
                	
					<?
					$poids = 0;
					$prod_durOnce = false; // Produit en dur ou 3D
					foreach($_SESSION[SITE_CONFIG]['CADDIE']['produits'] as $prod_id_sel=>$formatEchelle_sel) {
						if (empty($formatEchelle_sel)) continue;
						
						$C =& new Q("SELECT * FROM mod_catalogue_produits WHERE id='$prod_id_sel' AND actif='1' LIMIT 1");
						foreach($formatEchelle_sel as $formatEchelle => $formatEchelleArr) {
							
							if ($formatEchelleArr['format'] =='b' || $formatEchelleArr['format'] == 'r' || $formatEchelleArr['format'] == 'p') {
								$prod_durOnce = true;
								$whereEch = " AND echelle='".$formatEchelleArr['echelle']."'";
							}
							else $whereEch = '';
							
							$M =& new Q("SELECT prix, prix_promo, poids FROM mod_matieres WHERE produit_id='$prod_id_sel' AND format='".$formatEchelleArr['format']."' $whereEch LIMIT 1");
							$title = $formatEchelleArr['quantite'].' '.$arr_Formats[$formatEchelleArr['format']].' (éch. '.$formatEchelleArr['echelle'].', poids '.intval($M->V['0']['poids']).'gr)';
							$poids += ( intval($M->V['0']['poids']) * $formatEchelleArr['quantite']);
							?>
							<p title="<?=html(aff($title));?>">
								<? if ($M->V['0']['prix_promo'] > 0 && $M->V['0']['prix_promo'] < $M->V['0']['prix']) { ?>
								<strong><?=html(aff($C->V[0]['titre']));?></strong> <span class="barre"><?=pad($M->V['0']['prix'] * $formatEchelleArr['quantite'], 2);?>&nbsp;&euro;</span> <span class="promo"><?=pad($M->V['0']['prix_promo'] * $formatEchelleArr['quantite'], 2);?> &euro; promo</span>
								<? } else { ?>
								<strong><?=html(aff($C->V[0]['titre']));?></strong> <span><?=pad($M->V['0']['prix'] * $formatEchelleArr['quantite'], 2);?>&nbsp;&euro;</span>
								<? } ?>
							</p>
							<?
						}
					}
					?>

					<div class="stot">
						<strong>Sous-total : </strong> <span><?=pad($_SESSION[SITE_CONFIG]['CADDIE']['total'], 2);?>&nbsp;&euro;&nbsp;TTC</span>
					</div>
                    <p class="tva">
                    	<strong>Dont TVA : </strong> <span><?=pad($_SESSION[SITE_CONFIG]['CADDIE']['total'] * $TVA, 2);?>&nbsp;&euro;</span>
                    </p>
                    <?
					
					if ($prod_durOnce) {

						require_once('_tarif_laposte.php');
						$zone = $_SESSION[SITE_CONFIG]['INSCRIPTION2']['fac_diff'] ? $_SESSION[SITE_CONFIG]['INSCRIPTION2']['zone2'] : $_SESSION[SITE_CONFIG]['INSCRIPTION2']['zone'];
						$arr_tar = 'zone_'.$zone;
						$tarifArray = $$arr_tar;

						$poidsTotalKilo = floatval($poids / 1000);
						$tarif_fdp = 0.00;
						$tarifBak = 0.00;
						###db($poidsTotalKilo.'kg - '.$tarifArray.'€');
						foreach($tarifArray as $kilo => $tarif) {
							###db($kilo.' >= '.$poidsTotalKilo);
							if ($kilo >= $poidsTotalKilo) {
								if ($tarifBak > 0 && $kilo > $poidsTotalKilo) $tarif_fdp = pad($tarifBak, 2);
								else $tarif_fdp = pad($tarif, 2);
								break;
							}
							$tarifBak = $tarif;
						}
						if ($tarif_fdp <= 0) {
							$kiloMax = 0;
							$tarifMax = 0.00;
							foreach($tarifArray as $kilo => $tarif) {
								$kiloMax = $kilo;
								$tarifMax = $tarif;
							}
							$tarif_fdp = ($poidsTotalKilo / $kiloMax) * $tarifMax;
						}
						$tarif_fdp = pad($tarif_fdp, 2);
					}
					else $tarif_fdp = 0;
					
					$_SESSION[SITE_CONFIG]['CADDIE']['poids'] = $poidsTotalKilo;
					$_SESSION[SITE_CONFIG]['CADDIE']['fdp'] = $tarif_fdp;
					
					?>
					<p class="tva" title="Estimation - Poids total : <?=$poidsTotalKilo;?>Kg">
                    	<strong>Frais de port<?=($tarif_fdp > 0 ? '*' : '');?> : </strong> <span><?=($tarif_fdp > 0 ? $tarif_fdp.'&nbsp;&euro;' : '-&nbsp;&nbsp;');?></span> 
                    </p>
                    <div class="tot">
						<strong>Total : </strong> <span><?=pad($_SESSION[SITE_CONFIG]['CADDIE']['total'] + $_SESSION[SITE_CONFIG]['CADDIE']['fdp'], 2);?>&nbsp;&euro;&nbsp;TTC</span>
					</div>
					<p class="tva">
                    	<small><?=($tarif_fdp > 0 ? '* Estimation selon le poids total de la commande ('.$poidsTotalKilo.' Kg)' : '');?></small>
                    </p>
                </fieldset>
                
                <div class="recap_droite">
	                <fieldset class="recap_coord">
                        <legend>R&eacute;capitulatif de votre commande</legend>
                        <h2>R&eacute;capitulatif de votre commande</h2>
                    	<div class="modifier"><a href="<?=$commandes_url;?>?etape=etape2"><img src="images/fr/bouton/bt_modifier.gif" alt="Modifier" /></a></div>                
                        <p><strong>Vos informations</strong></p>
					    <p>Titre : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['civilite']));?></p>
                        <p>Nom : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['nom']));?></p>
                        <p>Pr&eacute;nom : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['prenom']));?></p>
                        <p>Email : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['email']));?></p>
                        <br />
						<p><strong>Adresse de facturation</strong></p>
						<p>Adresse : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['adresse']));?></p>
                        <p>Code postal : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['cp']));?></p>
                        <p>Ville : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['ville']));?></p>
                        <p>Pays : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['pays']));?></p>
						<? if (!$_SESSION[SITE_CONFIG]['INSCRIPTION2']['fac_diff']) { ?>
							<p>Zone : <?=html(aff($arr_Zones[$_SESSION[SITE_CONFIG]['INSCRIPTION2']['zone']]));?></p>
						<? } ?>
                        <p>Tel. : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['tel']));?></p>
						<? if ($_SESSION[SITE_CONFIG]['INSCRIPTION2']['fac_diff']) { ?>
							<br />
							<p><strong>Adresse de livraison</strong></p>
							<p>Adresse : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['adresse2']));?></p>
							<p>Code postal : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['cp2']));?></p>
							<p>Ville : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['ville2']));?></p>
							<p>Pays : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['pays2']));?></p>
							<p>Zone : <?=html(aff($arr_Zones[$_SESSION[SITE_CONFIG]['INSCRIPTION2']['zone2']]));?></p>
							<p>Tel. : <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['tel2']));?></p>
						<? } ?>
					</fieldset>
                    <fieldset class="paiement">
                        <legend>Votre moyen de paiement</legend>
                        <h2>Votre moyen de paiement</h2>
                        <p class="first">
                            <input type="radio" name="inscription3_paiement" id="inscription3_paiement1" value="1" /> <label for="inscription3_paiement1">Par carte bancaire : </label>
                        </p>
                        <p class="img">
                        	<img src="images/common/picto_cb1.gif" alt="" /><img src="images/common/picto_cb2.gif" alt="" /><img src="images/common/picto_cb3.gif" alt="" /><img src="images/common/picto_cb4.gif" alt="" />
                        </p>
                        <p>
                            <input type="radio" name="inscription3_paiement" id="inscription3_paiement2" value="2" /> <label for="inscription3_paiement2">Par virement bancaire</label>
                        </p>
                        <p>
                            <input type="radio" name="inscription3_paiement" id="inscription3_paiement3" value="3" /> <label for="inscription3_paiement3">Par ch&egrave;que</label>
                        </p>
						
					</fieldset>
                </div>
            </div>
            <div class="pied">
            	<input type="image" src="images/fr/bouton/bt_etape_suivante.gif" class="submit" value="Etape suivante" onclick="inscription3_submit();" />
           	</div>
        </form>

    </div>
</div>