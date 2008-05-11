<?

if ($_SESSION[SITE_CONFIG]['DEVIS']['souhaits'] < 1) goto($devis_url.'?etape=etape1');

if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] > 0 && empty($_SESSION[SITE_CONFIG]['DEVIS3'])) $_SESSION[SITE_CONFIG]['DEVIS3'] = $_SESSION[SITE_CONFIG]['CLIENT'];

?><div id="gal_mesure" class="etape3">
	<div class="titre_rub">
    	<h1 class="t_rub">Les Galeries</h1>
        <a href="javascript:void(0);">Retour à la liste</a>
    </div>
    
    <div class="centre_double">
    	<!-- Centre Gauche -->
        <div class="centre_gauche"><?php include_once('./gauche.php');?></div>
        
        <!-- Centre Droite -->
        <div class="centre_droite">
            <!-- Nav -->
            <div class="nav_gal">
                <ul>
                    <li id="ng1"><a href="<?=$devis2_url;?>?etape=etape1"><img src="images/fr/titre/t_ng1.gif" alt="Mes souhaits" /></a></li>
                    <li id="ng2"><a href="<?=$devis2_url;?>?etape=etape2"><img src="images/fr/titre/t_ng2.gif" alt="Mon objet" /></a></li>
                    <li id="ng3"><a href="<?=$devis2_url;?>?etape=etape3"><img src="images/fr/titre/t_ng3_on.gif" alt="Mes coordonn&eacute;es" /></a></li>
                    <li id="ng4"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng4.gif" alt="Confirmation" /></a></li>
                </ul>
            </div>
            
            <!-- Formulaire -->
            <div class="form_coords">
            	<form action="<?=$devis_url;?>?etape=etape3&amp;action=DEVIS3" method="post" enctype="multipart/form-data" name="devis3_frm" id="devis3_frm" onsubmit="return false;">
					<script type="text/javascript">
					// <![CDATA[
						var devis3_submit = function() {
							var devis3_param = { mep: 'alerte', autoScroll: false, action: 'submit' };
							var devis3_champs = {
								<? if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) { ?>
								devis3_civilite: {alerte:'Le champ civilit&eacute; est obligatoire'},
								devis3_nom: {alerte:'Le champ nom est obligatoire'},
								devis3_prenom: {alerte:'Le champ pr&eacute;nom est obligatoire'},
								devis3_email: {type:'mel',alerte:'Le champ e-mail est obligatoire et doit &ecirc;tre valide'},
								devis3_m2p: {minchar:5, alerte:'Le champ mot de passe est obligatoire et doit faire 5 charact&egrave;res min.'},
								devis3_adresse: {alerte:'Le champ adresse est obligatoire'},
								devis3_cp: {alerte:'Le champ code postal est obligatoire'},
								devis3_ville: {alerte:'Le champ ville est obligatoire'},
								devis3_pays: {type:'',alerte:'Le champ pays est obligatoire et doit &ecirc;tre valide'},
								devis3_tel: {minchar:10, alerte:'Le champ t&eacute;l&eacute;phone est obligatoire'}
								<? } ?>
							};
							formVerif('devis3_frm', devis3_champs, devis3_param);
						}
					// ]]>
					</script>
					
	                <h2>Mes informations personnelles</h2>
                    <div class="formulaire">
                    	
						<? if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) { ?>
							<p>
								<label for="devis3_civilite">Titre<span>*</span> : </label>
								<select name="devis3_civilite" id="devis3_civilite">
									<option value="Mr" <?=($_SESSION[SITE_CONFIG]['DEVIS3']['civilite'] == 'Mr' ? 'selected="true"' : '');?>>Mr.</option>
									<option value="Mme" <?=($_SESSION[SITE_CONFIG]['DEVIS3']['civilite'] == 'Mme' ? 'selected="true"' : '');?>>Mme</option>
									<option value="Mlle" <?=($_SESSION[SITE_CONFIG]['DEVIS3']['civilite'] == 'Mme' ? 'selected="true"' : '');?>>Mlle</option>
								</select>
							</p>
							<p><?=getFormRow('text', 'Nom<span>*</span> : ', 'devis3_nom', $_SESSION[SITE_CONFIG]['DEVIS3']['nom'], array('maxlength'=>'150'));?></p>
							<p><?=getFormRow('text', 'Pr&eacute;nom<span>*</span> : ', 'devis3_prenom', $_SESSION[SITE_CONFIG]['DEVIS3']['prenom'], array('maxlength'=>'150'));?></p>
							<p><?=getFormRow('text', 'Email<span>*</span> : ', 'devis3_email', $_SESSION[SITE_CONFIG]['DEVIS3']['email']);?></p>
							<p><?=getFormRow('text', 'Tel<span>*</span> : ', 'devis3_tel', $_SESSION[SITE_CONFIG]['DEVIS3']['tel']);?></p>
							
							<p><?=getFormRow('text', 'Adresse<span>*</span> : ', 'devis3_adresse', $_SESSION[SITE_CONFIG]['DEVIS3']['adresse'], array('maxlength'=>'250','class'=>'long'));?></p>
							<p><?=getFormRow('text', 'Code postal<span>*</span> : ', 'devis3_cp', $_SESSION[SITE_CONFIG]['DEVIS3']['cp'], array('maxlength'=>'150'));?></p>
							<p><?=getFormRow('text', 'Ville<span>*</span> : ', 'devis3_ville', $_SESSION[SITE_CONFIG]['DEVIS3']['ville'], array('maxlength'=>'150'));?></p>
							<p><?=getFormRow('text', 'Pays<span>*</span> : ', 'devis3_pays', $_SESSION[SITE_CONFIG]['DEVIS3']['pays'], array('maxlength'=>'150'));?></p>
							
							<p><?=getFormRow('password', 'Mot de passe<span>*</span> : ', 'devis3_m2p', $_SESSION[SITE_CONFIG]['DEVIS3']['m2p']);?></p>
							<p><?=getFormRow('password', 'Confirmer mot de passe<span>*</span> : ', 'devis3_m2p_2', $_SESSION[SITE_CONFIG]['DEVIS3']['m2p']);?></p>
						
						<? } else { ?>
						
							<input type="hidden" name="devis3_civilite" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['civilite']);?>" />
							<input type="hidden" name="devis3_nom" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['nom']);?>" />
							<input type="hidden" name="devis3_prenom" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['prenom']);?>" />
							<input type="hidden" name="devis3_email" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['email']);?>" />
							<input type="hidden" name="devis3_tel" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['tel']);?>" />
							<input type="hidden" name="devis3_adresse" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['adresse']);?>" />
							<input type="hidden" name="devis3_cp" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['cp']);?>" />
							<input type="hidden" name="devis3_ville" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['ville']);?>" />
							<input type="hidden" name="devis3_pays" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['pays']);?>" />
							<input type="hidden" name="devis3_m2p" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['m2p']);?>" />
							<input type="hidden" name="devis3_m2p_2" value="<?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['m2p']);?>" />
							
							<p class="first"><label>Titre : </label> <?=aff($_SESSION[SITE_CONFIG]['DEVIS3']['civilite']);?></p>
							<p><label>Nom :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['nom']));?></p>
							<p><label>Pr&eacute;nom :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['prenom']));?></p>
							<p><label>Adresse :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['adresse']));?></p>
							<p><label>Code postal :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['cp']));?></p>
							<p><label>Ville :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['ville']));?></p>
							<p><label>Pays :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['pays']));?></p>
							<p><label>Email :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['email']));?></p>
							<p><label>T&eacute;l :</label> <?=html(aff($_SESSION[SITE_CONFIG]['DEVIS3']['tel']));?></p>
						<? } ?>

                    </div>
                    
                    <div class="pied">
                    	<p>Les champs suivis d'une * sont obligatoires</p>
                        <input type="image" src="images/fr/bouton/bt_etape_suivante.gif" class="submit" value="Etape suivante" onclick="devis3_submit();" />
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>