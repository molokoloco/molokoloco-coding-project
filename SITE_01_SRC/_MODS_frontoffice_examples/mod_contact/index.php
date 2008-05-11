<?
$D =& new Q("SELECT coordonnees, plan FROM mod_accueil WHERE id='1' LIMIT 1 ");	
?>
<div id="index_demande">
	<h1 class="t_rub">Contacts</h1>
	<div class="ensemble">
		<div class="coordonnees">
			<h2>Nos coordonn&eacute;es</h2>
			<?=quote(aff($D->V[0][coordonnees]));?>
		</div>
	 	<div class="plan">
			<h2>Plan d'acc&egrave;s</h2>
		    <?	
			$m =& new FILE();
			if ($m->isMedia('medias/mods/medium/'.$D->V[0]['plan'])) {
				$m->media();
			}
			else {
				$m =& new FILE();
				if ($m->isMedia('medias/mods/'.$D->V[0]['plan'])) {
					$m->media();
				}
			}
			?>
		</div>
	</div>
	
	<fieldset class="formulaire">
		<legend>Par formulaire</legend>
		<h2>Par formulaire</h2>

		<form onsubmit="return false;" id="contact_frm" name="contact_frm" enctype="multipart/form-data" method="post" action="<?=thisPage('action=CONTACT', '', array('action'));?>">
			<script type="text/javascript">
			// <![CDATA[
			contact_submit = function() {
				contact_param = { mep:'alerte', autoScroll:true, action:'submit' };
				contact_champs = {
					contact_nom: {type:'', alerte:'Le nom est obligatoire'},
					contact_prenom: {type:'', alerte:'Le pr&eacute;nom est obligatoire'},
					contact_email: {type:'mel', alerte:'Le mail est obligatoire et doit &ecirc;tre valide'},
					contact_message: {type:'', alerte:'Le message est obligatoire'}
				};
				formVerif('contact_frm', contact_champs, contact_param);
			};
			// ]]>
			</script>
			<p>
				<label for="contact_nom">Nom <span>*</span></label>
				<input type="text" name="contact_nom" id="contact_nom" />
			</p>			
			<p>
				<label for="contact_prenom">Prenom <span>*</span></label>
				<input type="text" name="contact_prenom" id="contact_prenom" />
			</p>
			<p>
				<label for="contact_programme">Programme</label>
				<select name="contact_programme" id="contact_programme">
					<option value="">S&eacute;lectionner</option>
				</select>
			</p>
			<p>
				<label for="contact_societe">Soci&eacute;t&eacute;</label>
				<input type="text" name="contact_societe" id="contact_societe" />
			</p>
			<p>
				<label for="contact_tel">Tel</label>
				<input type="text" name="contact_tel" id="contact_tel" />
			</p>
			<p>
				<label for="contact_email">Email <span>*</span></label>
				<input type="text" name="contact_email" id="contact_email" />
			</p>
			<p>
				<label for="contact_message">Message <span>*</span></label>
				<textarea name="contact_message" id="contact_message" rows="4" cols="7"></textarea>
			</p>
			<p>
				<input type="image" src="./images/fr/bouton/bt_envoyer.gif" class="submit" onclick="javascript:contact_submit();" />
			</p>
		</form>
	</fieldset>
</div>