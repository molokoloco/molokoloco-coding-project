<div id="details_galerie">
	<div class="titre_rub">
    	<h1 class="t_rub"><?=html(aff($S->arbo[$S->rrid]['titre_'.$lg]));?></h1>
        <a href="javascript:history.back();">Retour &agrave; la liste</a>
    </div>
    
    <div class="centre_double">
    	<!-- Centre Gauche -->
        <div class="centre_gauche"><?php include_once('./gauche.php');?></div>
        
		<?
		
		$G =& new Q("SELECT * FROM mod_catalogue_produits WHERE id='$prod_id' and actif='1' LIMIT 1");
		###db($G);
		$V = $G->V[0];
		if ($V['id'] < 1) alert('D&eacute;sol&eacute; ce produit n\'est pas disponible', 'back', 'alert');
		
		$produit_id = $V['id'];
		
		$noProdDur = false;
		$noProd3D = false;

		$P =& new Q("
			SELECT MIN(prix) AS prix, MIN(prix_promo) AS prix_promo
			FROM mod_matieres
			WHERE actif='1' AND produit_id='$produit_id' AND ( format='b' OR format='r' OR format='p' )
			LIMIT 1
		");
		$prix_min_dur = ( $P->V['0']['prix_promo'] > 0 && $P->V['0']['prix_promo'] < $P->V['0']['prix'] ? $P->V['0']['prix_promo'] : $P->V['0']['prix'] );
		if ($prix_min_dur < 1) $noProdDur = true;
		
		$P =& new Q("
			SELECT MIN(prix) AS prix, MIN(prix_promo) AS prix_promo
			FROM mod_matieres
			WHERE actif='1' AND produit_id='$produit_id' AND format!='b' AND format!='r' AND format!='p'
			LIMIT 1
		");
		$prix_min_3d = ( $P->V['0']['prix_promo'] > 0 && $P->V['0']['prix_promo'] < $P->V['0']['prix'] ? $P->V['0']['prix_promo'] : $P->V['0']['prix'] );
		if ($prix_min_3d < 1) $noProd3D = true;
		
		?>
        <div class="centre_droite">
			<h2><?=html(aff($V['titre']));?></h2>
            <div class="ensemble">
            	<div class="img">
                	<div class="tableau">
                        <table>
                            <tr>
                                <td>&nbsp;</td>
                                <td><a href="javascript:void(0);"><img src="images/common/bouton/bt_haut.gif" alt="" /></a></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td><a href="javascript:void(0);"><img src="images/common/bouton/bt_gauche.gif" alt="" /></a></td>
                                <td height="190"><?
								$m =& new FILE();
								if ($m->isMedia('medias/catalogue/grand/'.$V['visuel_1'])) {
									$m->_pathzoom = 'medias/catalogue/pop/'.$V['visuel_1'];
									$m->id = 'prod_visuel_1';
									$m->galRel = 'Produit';
									$m->catRel ='Produit';
									$m->popImage();
								}
								?></td>
                                <td><a href="javascript:void(0);"><img src="images/common/bouton/bt_droite.gif" alt="" /></a></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><a href="javascript:void(0);"><img src="images/common/bouton/bt_bas.gif" alt="" /></a></td>
                                <td>&nbsp;</td>
                            </tr>
                        </table>
                    </div>  
					<?
					$one = false;
					for($j=2; $j<=4; $j++) {
						$m =& new FILE();
						if ($m->isMedia('medias/catalogue/mini/'.$V['visuel_'.$j])) {
							if (!$one) {
								?>
								<div class="liste" id="produit_galerie">
								<div class="groupe">
								<?
								if (!empty($V['document'])) { 
									?>
									<div class="element"><a href="_get_model.php?id=<?=$V['id']?>" class="lightwindow" id="pop3D"><img src="images/common/mini/picto_3d2.gif" alt="Vue 3D disponible" /></a></div>
									<?
									js("if (client.isIe()) { // IE make mistake with lightwindow
										$('pop3D').onclick = function() { return myPop('_get_model.php?id=".$V['id']."&IE=1', 'Modele_".$V['id']."', '680', '520'); };
										$('pop3D').className = '';
									}");
								}
							}
							$one = true;
							$m->id = 'prod_visuel_'.$j;
							//$m->title = html(aff($A['visuel_leg_'.$j]));
							$m->_pathzoom = 'medias/catalogue/pop/'.$V['visuel_'.$j];
							$m->galRel = 'Produit';
							$m->catRel ='Produit';
							$m->css = 'zoomIn bor';
							echo '<div class="element">';
							$m->popImage();
							echo '</div>';
						}
					}
					if ($one) {
						?>
						</div>
						</div><?
					}
					?>
                </div>
                <div class="txt">
                	<div class="wg">
                    	<?=quote(aff($V['texte']));?>
						<?
						$m =& new FILE();
						if ($m->isMedia('medias/catalogue/'.$V['pdf'])) {
							echo '<p><small>Fichier 3D <a href="http://www.adobe.com/fr/products/acrobat3d/3dpdf_samples/" title="Téléchargez Adobe Reader 8.1" target"_blank">PDF</a> :</small><br />';
							$m->info();
							echo '</p>';
						}
						?>
                    </div>			
<? js("

var matiere = function(e) {
	if (e.options[e.selectedIndex].value != '') {
		updateIdAjax('echelle', '_actions.php', 'action=GETECHELLE&produit_id=".$produit_id."&format='+e.options[e.selectedIndex].value);
		setTimeout('echelle($(\"echelle\"));', 600);
	}
};
var echelle = function(e) {
	if (e.options[e.selectedIndex].value != '')
		updateIdAjax('prixdur', '_actions.php', 'action=GETPRIX&produit_id=".$produit_id."&format='+$('matiere').options[$('matiere').selectedIndex].value+'&echelle='+e.options[e.selectedIndex].value);
};
var format = function(e) {
	if(e.options[e.selectedIndex].value != '')
		updateIdAjax('prix3d', '_actions.php', 'action=GETPRIX&produit_id=".$produit_id."&format='+e.options[e.selectedIndex].value);
};

");
?>
                    <div class="formulaire">
                        	<h3>Nos options</h3>
							
							<? if ($noProdDur && $noProd3D) { ?>
								
								<p>Ce produit n'est pas disponible &agrave; la vente</p>
                           
						    <? } if (!$noProdDur) { ?>
							
							<fieldset class="cmd">
                            	<legend>Commander cet objet</legend>
                                <h4><img src="images/fr/txt_commander.gif" alt="Commander cet objet" /></h4>
								<p><select name="matiere" id="matiere" onchange="matiere(this);">
								<option value="">Mati&egrave;re</option>
								<?
								$P =& new Q("SELECT DISTINCT format FROM mod_matieres WHERE actif='1' AND produit_id='$produit_id' AND ( format='b' OR format='r' OR format='p' ) ");
								foreach($P->V as $P) {
									echo '<option value="'.$P['format'].'">'.aff($arr_Formats[$P['format']]).'</option>';
								}
								?>
								</select></p>
                                <p><select name="echelle" id="echelle" onchange="echelle(this);">
								<option value="">Echelle</option>
								<option>[Choisir une mati&egrave;re]</option>
								</select></p>
                                <p class="lien"><a href="javascript:void(0);" onclick="addCaddie('dur', <?=$produit_id;?>);">Ajouter &agrave; ma s&eacute;lection</a></p>
                                <div class="prix"><h5><strong id="prixdur"><?=$prix_min_dur;?> &euro;</strong></h5></div>
                            </fieldset>
							
							<? } if (!$noProd3D) { ?>
							
                            <fieldset class="ach">
                            	<legend>Acheter le fichier 3D de l'objet</legend>
                                <h4><img src="images/fr/txt_acheter.gif" alt="Acheter le fichier 3D de l'objet" /></h4>
                                <p><select name="format" id="format" onchange="format(this);">
									<option value="">Format</option>
									<?
									$P =& new Q("SELECT DISTINCT format FROM mod_matieres WHERE actif='1' AND produit_id='$produit_id' AND format!='b' AND format!='r' AND format!='p' ");
									foreach($P->V as $P) {
										echo '<option value="'.$P['format'].'">'.aff($arr_Formats[$P['format']]).'</option>';
									}
									?>
								</select></p>
                                 <p class="lien"><a href="javascript:void(0);" onclick="addCaddie('3d', <?=$produit_id;?>);">Ajouter &agrave; ma s&eacute;lection</a></p>
                                <div class="prix"><h5><strong id="prix3d"><?=$prix_min_3d;?> &euro;</strong></h5></div>
                            </fieldset>
							
							<? } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</div>