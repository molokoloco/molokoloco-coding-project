<form id="frm_contact" name="frm_contact" enctype="multipart/form-data" method="post" action="<?=thisPage('action=CONTACT', '', array('action'));?>" onsubmit="return false;">
	<div style="display:inline"><a name="CONTACT"></a></div>
	<h1>Le contact, c'est maintenant :)</h1>
	<div style="padding:10px;width:168px;float:left;">
		<label for="contact_nom">Nom* :</label><br />
		<input type="text" id="contact_nom" name="contact_nom" value="<?=html(aff($_SESSION[SITE_CONFIG]['CONTACT']['nom']));?>"/>
		<label for="contact_prenom">Pr&eacute;nom* :</label><br />
		<input type="text" id="contact_prenom" name="contact_prenom" value="<?=html(aff($_SESSION[SITE_CONFIG]['CONTACT']['prenom']));?>"/>
		<label for="contact_tel">T&eacute;l&eacute;phone :</label><br />
		<input type="text" id="contact_tel" name="contact_tel" value="<?=html(aff($_SESSION[SITE_CONFIG]['CONTACT']['tel']));?>"/>
		<label for="contact_email">Email* :</label><br />
		<input type="text" id="contact_email" name="contact_email" value="<?=html(aff($_SESSION[SITE_CONFIG]['CONTACT']['email']));?>"/>
		<label for="contact_societe">Soci&eacute;t&eacute; :</label><br />
		<input type="text" id="contact_societe" name="contact_societe" value="<?=html(aff($_SESSION[SITE_CONFIG]['CONTACT']['societe']));?>"/>
	</div>
	<div style="padding:10px;width:336px;float:left;">
		<label for="contact_sujet">Sujet :</label><br />
		<input type="text" id="contact_sujet" name="contact_sujet" style="width:100%;" value="<?=html(aff($_SESSION[SITE_CONFIG]['CONTACT']['sujet']));?>"/>
		<label for="contact_message">Message* :</label><br />
		<textarea name="contact_message" cols="40" rows="6" id="contact_message" style="width:100%;height:149px;"><?=html(aff($_SESSION[SITE_CONFIG]['CONTACT']['message']));?></textarea>
		<div class="breaker">&nbsp;</div>
		<div style="margin-top:14px;">
			<a onfocus="blur();" href="javascript:contactSubmit();" class="button"><strong>Valider le formulaire</strong></a>
		</div>
	</div>
	<div class="breaker">&nbsp;</div>
</form>