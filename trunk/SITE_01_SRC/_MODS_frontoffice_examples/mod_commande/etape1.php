<div id="commande" class="etape1">
	<h1 class="t_rub">Commander</h1>
    <!-- Nav -->
    <div class="nav_cmd">
    	<ul>
        	<li id="nc1"><a href="<?=$commandes_url;?>?etape=etape1"><img src="images/fr/titre/t_nc1_on.gif" alt="Ma s&eacute;lection" /></a></li>
            <li id="nc2"><a href="<?=($_SESSION[SITE_CONFIG]['CLIENT']['id'] > 0 ? $commandes_url.'?etape=etape2':'javascript:void(0);');?>"><img src="images/fr/titre/t_nc2.gif" alt="Mes coordonn&eacute;es" /></a></li>
            <li id="nc3"><a href="<?=($_SESSION[SITE_CONFIG]['CLIENT']['id'] > 0 ? $commandes_url.'?etape=etape3':'javascript:void(0);');?>"><img src="images/fr/titre/t_nc3.gif" alt="Moyens de paiement" /></a></li>
            <li id="nc4"><a href="javascript:void(0);"><img src="images/fr/titre/t_nc4.gif" alt="Confirmation" /></a></li>
        </ul>
    </div>
    
    <!-- Formulaire -->
    <div class="tab_cmd">
        <form action="<?=$commandes_url;?>?etape=etape2" name="caddie_form" id="caddie_form" method="post" onsubmit="return false;">
            <table>
                <tr>
                    <th class="objet">Objet</th>
                    <th class="ref">R&eacute;f&eacute;rence</th>
                    <th class="qt">Quantit&eacute;</th>
                    <th class="format">Format / mati&egrave;re</th>
                    <th class="echelle">Echelle</th>
                    <th class="tarif">Tarif TTC</th>
                    <th class="supp">&nbsp;</th>
                </tr>
               
			   <?
				if (empty($_SESSION[SITE_CONFIG]['CADDIE']['produits'])) {
					?>
					<tr class="type1">
						<td class="objet" colspan="7">Votre panier est vide</td>
					</tr>
					<?
				}
				else {
					### $_SESSION[SITE_CONFIG]['CADDIE']['produits'] = NULL;
					### db($_SESSION[SITE_CONFIG]['CADDIE']['produits']);
					
					$total = 0;
					
					foreach($_SESSION[SITE_CONFIG]['CADDIE']['produits'] as $prod_id_sel=>$formatEchelle_sel) {
						if (empty($formatEchelle_sel)) continue;
		
						$C =& new Q("SELECT * FROM mod_catalogue_produits WHERE id='$prod_id_sel' AND actif='1' LIMIT 1");

						$ssrub_id_Sel = $C->V['0']['ssrubrique_id'];
						$R =& new Q("SELECT rubrique_id, titre FROM mod_catalogue_ssrubriques WHERE id='$ssrub_id_Sel' AND actif='1' LIMIT 1 ");
						$ssrub_titre_Sel = $R->V[0]['titre'];
		
						$produit_url = urlRewrite($ssrub_titre_Sel .'-_-'.$C->V['0']['titre'], 'r'.$S->getRidByType(6).'sr'.$ssrub_id_Sel.'p'.$C->V['0']['id']);
		
						foreach($formatEchelle_sel as $formatEchelle => $formatEchelleArr) {
							if (empty($formatEchelleArr)) continue;
							
							$produit_unique_id = cleanName($prod_id_sel.$formatEchelleArr['format'].$formatEchelleArr['echelle']);
							
							$whereEch = (!empty($formatEchelleArr['echelle']) && $formatEchelleArr['echelle'] != '-' ? " AND echelle='".$formatEchelleArr['echelle']."'" : '' );
							$M =& new Q("SELECT MIN(prix) AS prix, MIN(prix_promo) AS prix_promo FROM mod_matieres WHERE produit_id='$prod_id_sel' AND format='".$formatEchelleArr['format']."' $whereEch LIMIT 1");
							$prix_min = ( $M->V['0']['prix_promo'] > 0 && $M->V['0']['prix_promo'] < $M->V['0']['prix'] ? $M->V['0']['prix_promo'] : $M->V['0']['prix'] );
							$prix_min_min = ( $prix_min_min == 0 || $prix_min_min > $prix_min ? $prix_min : $prix_min_min );
							
							if ($prix_min_min < 0.01) {
								unset($_SESSION[SITE_CONFIG]['CADDIE']['produits'][$prod_id_sel][$formatEchelle]);
								continue;
							}
							else $total++;
						
							?>
							<tr class="type<?=($total%2==0 ? '2' : '1');?>" id="caddie_prod_<?=$C->V[0]['id'];?>">
								<td class="objet">
									<input type="hidden" name="produit[]" id="produit_<?=$produit_unique_id;?>" value="<?=$prod_id_sel;?>">
									<div class="ensemble">
										<?
										$m =& new FILE();
										if ($m->isMedia('medias/catalogue/micro/'.$V['visuel_1'])) {
											echo '<a href="'.$produit_url.'">';
											$m->image();
											echo '</a>';
										}
										?>
										<a href="<?=$produit_url;?>" class="titre"><?=html(aff($C->V[0]['titre']));?></a>
									</div>
								</td>
								<td class="ref"><?=html(aff($C->V[0]['reference']));?></td>
								<td class="qt"><select name="quantite[]" id="quantite_<?=$produit_unique_id;?>" onchange="echelle('<?=$produit_unique_id;?>', <?=$prod_id_sel;?>);">
									<? for($i=1; $i<=10; $i++) echo '<option value="'.$i.'" '.($i == $formatEchelleArr['quantite'] ? 'selected="true"' : '').'>'.$i.'</option>'; ?>
								</select></td>
								<td class="format"><select name="format[]" id="format_<?=$produit_unique_id;?>" onchange="format('<?=$produit_unique_id;?>', <?=$prod_id_sel;?>);">
									<?
									$P =& new Q("SELECT DISTINCT format FROM mod_matieres WHERE actif='1' AND produit_id='$prod_id_sel' ");
									foreach($P->V as $P) {
										echo '<option value="'.$P['format'].'" '.($P['format'] == $formatEchelleArr['format'] ? 'selected="true"' : '').'>'.aff($arr_Formats[$P['format']]).'</option>';
									}
									?>
								</select></td>
								<td class="echelle"><select name="echelle[]" id="echelle_<?=$produit_unique_id;?>" onchange="echelle('<?=$produit_unique_id;?>', <?=$prod_id_sel;?>);">
									<option>[Choisir une mati&egrave;re]</option>
								</select><?
								js("
								updateIdAjax('echelle_".$produit_unique_id."', '_actions.php', 'action=GETECHELLE&produit_id=".$prod_id_sel."&format=".$formatEchelleArr['format']."&echelle=".$formatEchelleArr['echelle']."');
								updateIdAjax('prix_".$produit_unique_id."', '_actions.php', 'action=GETPRIX&produit_id=".$prod_id_sel."&format=".$formatEchelleArr['format']."&echelle=".$formatEchelleArr['echelle']."&quantite=".$formatEchelleArr['quantite']."');
								");
								
								?></td>
								<td class="tarif" id="prix_<?=$produit_unique_id;?>"><?=$prix_min_min;?> &euro;</td>
								<td class="supp"><a href="javascript:void(0);" onclick="delProduit(<?=$prod_id_sel;?>, '<?=$formatEchelle;?>');"><img src="images/common/picto_supp.gif" alt="Supprimer" /></a></td>
							</tr>
							<?
						}
					}
					if ($total <1) {
						?>
						<tr class="type1">
							<td class="objet" colspan="7">Votre panier est vide</td>
						</tr>
						<?
					}
				}
				?>
            </table>
            <input type="image" src="images/fr/bouton/bt_etape_suivante.gif" class="submit" value="Etape suivante" onclick="validate();"/>
        </form>
    </div>
</div>

<? js("

	var format = function(e, produit_id) {
		updateIdAjax('echelle_'+e, '_actions.php', 'action=GETECHELLE&produit_id='+produit_id+'&format='+$('format_'+e).options[$('format_'+e).selectedIndex].value);
		setTimeout('echelle(\"'+e+'\", '+produit_id+');', 600);
	};
	
	var echelle = function(e, produit_id) {
		updateIdAjax('prix_'+e, '_actions.php', 'action=GETPRIX&produit_id='+produit_id+'&format='+$('format_'+e).options[$('format_'+e).selectedIndex].value+'&echelle='+$('echelle_'+e).options[$('echelle_'+e).selectedIndex].value+'&quantite='+$('quantite_'+e).options[$('quantite_'+e).selectedIndex].value);
	};
	
	var validate = function() {
		inputCollection = Form.serialize('caddie_form', false);
		var params = 'action=CADDIE&'+inputCollection;
		new Ajax.Request('_actions.php', {
			method: 'get',
			parameters:params ,
			onSuccess: function(requete) {
				redir('".$commandes_url."?etape=etape2');
			}
		});
	};

");
?>