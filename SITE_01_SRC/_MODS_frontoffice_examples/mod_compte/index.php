<?

if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) goto('index.php');

if (empty($_SESSION[SITE_CONFIG]['COMPTE'])) $_SESSION[SITE_CONFIG]['COMPTE'] = $_SESSION[SITE_CONFIG]['CLIENT'];

?><div id="compte">
	<h1 class="t_rub">Mon compte</h1>
    
    <div class="centre_double">
    	<!-- Centre Gauche -->
        <div class="centre_gauche" id="mes_coords">
        	<!-- Mes coordonnees -->
        	<div class="mes_coords">
                	<h2>Mes coordonn&eacute;es</h2>

                    <div class="formulaire" id="view_info">
                       	<p>Titre&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['civilite']));?></p>
                        <p>Nom&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['nom']));?></p>
                        <p>Pr&eacute;nom&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['prenom']));?></p>
                        <p>Email&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['email']));?></p>
						<p>Adresse&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['adresse']));?></p>
                        <p>Code postal&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['cp']));?></p>
                        <p>Ville&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['ville']));?></p>
                        <p>Pays&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['pays']));?></p>
						<p>Zone&nbsp;: <?=html(aff($arr_Zones[$_SESSION[SITE_CONFIG]['COMPTE']['zone']]));?></p>
                        <p>Tel.&nbsp;: <?=html(aff($_SESSION[SITE_CONFIG]['COMPTE']['tel']));?></p>

                        <div class="modifier"><a href="javascript:void(0);" onclick="compteEdit('on');"><img src="images/fr/bouton/bt_modifier.gif" alt="Modifier" class="rollover" /></a></div>
                    </div>
                    
                    <div class="formulaire" id="edit_info" style="display:none;">

					    <form action="<?=$compte_url;?>?action=COMPTE" method="post" enctype="multipart/form-data" name="compte_frm" id="compte_frm" onsubmit="return false;">
						<script type="text/javascript">
						// <![CDATA[
							var compte_submit = function() {
								var compte_param = { mep: 'alerte', autoScroll: false, action: 'submit'};
								var compte_champs = {
									compte_civilite: {alerte:'Le champ civilit&eacute; est obligatoire'},
									compte_nom: {alerte:'Le champ nom est obligatoire'},
									compte_prenom: {alerte:'Le champ pr&eacute;nom est obligatoire'},
									compte_email: {type:'mel',alerte:'Le champ e-mail est obligatoire et doit &ecirc;tre valide'},
									compte_adresse: {alerte:'Le champ adresse est obligatoire'},
									compte_cp: {alerte:'Le champ code postal est obligatoire'},
									compte_ville: {alerte:'Le champ ville est obligatoire'},
									compte_pays: {type:'',alerte:'Le champ pays est obligatoire et doit &ecirc;tre valide'},
									compte_zone: {alerte:'Le champ Zone est obligatoire'},
									compte_tel: {minchar:10, alerte:'Le champ t&eacute;l&eacute;phone est obligatoire'}
								};
								formVerif('compte_frm', compte_champs, compte_param);
							}
						// ]]>
						</script>

						<p class="diff">
							<label for="compte_civilite">Titre<span>*</span>&nbsp;: </label>
							<select name="compte_civilite" id="compte_civilite">
								<option value="Mr" <?=($_SESSION[SITE_CONFIG]['COMPTE']['civilite'] == 'Mr' ? 'selected="selected"' : '');?>>Mr.</option>
								<option value="Mme" <?=($_SESSION[SITE_CONFIG]['COMPTE']['civilite'] == 'Mme' ? 'selected="selected"' : '');?>>Mme</option>
								<option value="Mlle" <?=($_SESSION[SITE_CONFIG]['COMPTE']['civilite'] == 'Mme' ? 'selected="selected"' : '');?>>Mlle</option>
							</select>
						</p>
						<p><?=getFormRow('text', 'Nom<span>*</span>&nbsp;: ', 'compte_nom', $_SESSION[SITE_CONFIG]['COMPTE']['nom'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Pr&eacute;nom<span>*</span>&nbsp;: ', 'compte_prenom', $_SESSION[SITE_CONFIG]['COMPTE']['prenom'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Email<span>*</span>&nbsp;: ', 'compte_email', $_SESSION[SITE_CONFIG]['COMPTE']['email']);?></p>
						
						<p><?=getFormRow('text', 'Adresse<span>*</span>&nbsp;: ', 'compte_adresse', $_SESSION[SITE_CONFIG]['COMPTE']['adresse'], array('maxlength'=>'250','class'=>'long'));?></p>
						<p><?=getFormRow('text', 'Code postal<span>*</span>&nbsp;: ', 'compte_cp', $_SESSION[SITE_CONFIG]['COMPTE']['cp'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Ville<span>*</span>&nbsp;: ', 'compte_ville', $_SESSION[SITE_CONFIG]['COMPTE']['ville'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Pays<span>*</span>&nbsp;: ', 'compte_pays', $_SESSION[SITE_CONFIG]['COMPTE']['pays'], array('maxlength'=>'150'));?></p>
						<p id="compte_zoneR">
							<label for="compte_zone">Zone<span>*</span>&nbsp;: </label>
							<select name="compte_zone" id="compte_zone" style="width:210px;">
							<?
							foreach($arr_Zones as $k=>$zone) { ?><option value="<?=$k;?>" <?=($_SESSION[SITE_CONFIG]['COMPTE']['zone'] == $k ? 'selected="selected"' : '');?>><?=html(aff($zone));?></option><? } ?>
							</select>
						</p>
						<p><?=getFormRow('text', 'T&eacute;l.<span>*</span>&nbsp;: ', 'compte_tel', $_SESSION[SITE_CONFIG]['COMPTE']['tel'], array('maxlength'=>'150'));?></p>
						
						 <div class="modifier"><input type="image" src="images/common/bouton/bt_ok.gif" class="submit" value="OK" onclick="compte_submit();"/></div>  
						 
					</form>     
            	</div>
        	</div>
            
            <!-- Mot de passe -->
            <div class="mon_m2p">
            	 <form action="<?=$compte_url;?>?action=COMPTEPASS" method="post" enctype="multipart/form-data" name="comptepass_frm" id="comptepass_frm" onsubmit="return false;">
					<script type="text/javascript">
					// <![CDATA[
						var comptepass_submit = function() {
							var comptepass_param = { mep: 'alerte', autoScroll: false, action: 'submit'};
							var comptepass_champs = {
								old_m2p: {alerte:'Le champ ancien mot de passe est obligatoire'},
								new_m2p: {alerte:'Le champ nouveau mot de passe est obligatoire'},
							};
							formVerif('comptepass_frm', comptepass_champs, comptepass_param);
						}
					// ]]>
					</script>
                	<h2>Mon mot de passe</h2>
                	<div class="formulaire">
                        <p class="intro">Pour modifier votre mot de passe remplissez ce formulaire&nbsp;:</p>
                        <p>
                            <label for="old_m2p">Ancien mot de passe<span>*</span></label>
                            <input type="password" name="old_m2p" id="old_m2p" />
                        </p>
                        <p>
                            <label for="new_m2p">Nouveau mot de passe<span>*</span></label>
                            <input type="password" name="new_m2p" id="new_m2p" />
                        </p>
                        <p>
                            <label for="new_m2p_2">Nouveau mot de passe<span>*</span><span class="petit">(V&eacute;rification)</span></label>
                            <input type="password" name="new_m2p_2" id="new_m2p_2" />
                        </p>
                        <div class="modifier"><input type="image" src="images/common/bouton/bt_ok.gif" class="submit" value="OK" onclick="comptepass_submit();" /></div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Centre Droite -->
        <div class="centre_droite">
        	<!-- Commande en cours -->
        	<div class="cmd_cours">
            	<h2>Mes Commandes en cours</h2>
                <div class="cmd">
                	
					<?
					$unmoisavant = date("Y-m-d h:m:s", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
					
					$req = q("SELECT * FROM mod_commandes WHERE client_id='{$_SESSION[SITE_CONFIG]['CLIENT']['id']}' AND (dateexpe IS NULL OR dateexpe>'$unmoisavant') ORDER BY id DESC");
					if (count($req) > 0) {
						foreach((array)$req as $V) {
							?>
							<p>
								<a href="_commandes.php?commande_id=<?=$V['id'];?>" class="lightwindow">Commande <?=html(aff($V['titre']));?></a>
								<?
								if ($V['statut_p'] != 1) echo '<span class="rouge">Commande en attente de règlement</span>';
								elseif ($V['statut_c'] == 1) echo '<span class="rouge">Commande annulée</span>';
								elseif ($V['statut_c'] == 2) echo '<span class="orange">Commande en pr&eacute;paration</span>';
								elseif ($V['statut_c'] == 3) echo ' <span class="vert">Commande exp&eacute;di&eacute;e le '.printDateTime($V['dateexpe']).'</span>';
								?>
							</p>
							<?
						}
					}

					$req2 = q("SELECT * FROM mod_devis WHERE client_id='{$_SESSION[SITE_CONFIG]['CLIENT']['id']}' AND (dateexpe IS NULL OR dateexpe>'$unmoisavant') ORDER BY id DESC");
					if (count($req2) > 0) {
						foreach((array)$req2 as $V) {
							?>
							<p>
								<a href="_commandes.php?devis_id=<?=$V['id'];?>" class="lightwindow">Devis <?=html(aff($V['titre']));?></a>
								<?
								if ($V['statut_c'] == 1) echo '<span class="rouge">Devis annulée</span>';
								elseif ($V['statut_c'] == 2) echo '<span class="orange">Devis attente de fichier</span>';
									elseif ($V['statut_p'] != 1) echo '<span class="rouge">Devis en attente de règlement</span>';
								elseif ($V['statut_c'] == 3) echo '<span class="orange">Devis en pr&eacute;paration</span>';
								elseif ($V['statut_c'] == 4) echo ' <span class="vert">Devis exp&eacute;di&eacute;e le '.printDateTime($V['dateexpe']).'</span>';
								?>
							</p>
							<?
						}
					}

					if (count($req) < 1 && count($req2) < 1) echo ' <p class="center">Aucune demande en cours</p>'; // en cours
					
					?>
                </div>
            </div>
            
            <!-- Historique -->
            <div class="cmd_histo">
            	<h2>Historique de mes commandes</h2>
                <div class="cmd">
                    	<?
						$req = q("SELECT * FROM mod_commandes WHERE client_id='{$_SESSION[SITE_CONFIG]['CLIENT']['id']}' AND dateexpe<'$unmoisavant' ORDER BY id DESC");
						if (count($req) < 1) echo ' <p class="center">Aucune commande</p>';
						else {
							foreach ((array)$req as $V) {
								?>
								<p>
									<a href="_commandes.php?commande_id=<?=$V['id'];?>" class="lightwindow">Commande <?=html(aff($V['titre']));?></a>
								</p>
								<?
							}
						}
					?>
                </div>
            </div>
            
            <!-- Sur mesure -->
            <div class="cmd_histo">
            	<h2>Sur mesure</h2>
                <div class="cmd">
					<?
					$req = q("SELECT * FROM mod_devis WHERE client_id='{$_SESSION[SITE_CONFIG]['CLIENT']['id']}' AND dateexpe<'$unmoisavant' ORDER BY id DESC");
					if (count($req) < 1) echo ' <p class="center">Aucune demande personnalis&eacute;e</p>'; // en cours
					else {
						foreach((array)$req as $V) {
							?>
							<p>
								<a href="_commandes.php?devis_id=<?=$V['id'];?>" class="lightwindow">Devis <?=html(aff($V['titre']));?></a>
							</p>
							<?
						}
					}
					?>
                </div>
            </div>
        
        </div>
    </div>    
</div>