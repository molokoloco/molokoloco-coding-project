<?

// $rencontre_id ?

?><div id="contact">
	<h1 class="t_page">Contact</h1>
	<div class="page">
		<div class="h_form">
			<div class="b_form">
				<form onsubmit="return false;" id="contact_frm" name="contact_frm" enctype="multipart/form-data" method="post" action="<?=thisPage('action=CONTACT', '', array('action'));?>">
					<script type="text/javascript">
					contact_submit = function() {
						contact_param = { mep: 'alerte', autoScroll: false, action: 'submit' };
						contact_champs = {
							contact_nom: {type:'', alerte:'Le nom est obligatoire'},
							contact_prenom: {type:'', alerte:'Le pr&eacute;nom est obligatoire'},
							contact_email: {type:'', alerte:'Email est obligatoire'},
							contact_societe: {type:'', alerte:'La soci&eacute;t&eacute; est obligatoire'}
						};
						formVerif('contact_frm', contact_champs, contact_param);
					}
					</script>
					<p class="intro"><strong>Vous souhaitez obtenir plus d’information sur la Charte Qualité Fleurs.</strong><br />
					Merci de remplir le formulaire ci-dessous :</p>

					<p>
						<label for="contact_nom">Nom<sup>*</sup> :</label>
						<input type="text" name="contact_nom" id="contact_nom" />
					</p>
					<p>
						<label for="contact_prenom">Prénom<sup>*</sup> :</label>
						<input type="text" name="contact_prenom" id="contact_prenom" />
					</p>
					<p>
						<label for="contact_email">Email* :</label>
						<input type="text" name="contact_email" id="contact_email" />
					</p>
					<p>
						<label for="contact_tel">Tél. :</label>
						<input type="text" name="contact_tel" id="contact_tel" />
					</p>
					<p>
						<label for="contact_societe">Société<sup>*</sup> :</label>
						<input type="text" name="contact_societe" id="contact_societe" />
					</p>
					<p>
						<label for="contact_message">Message :</label>
						<textarea name="contact_message" id="contact_message" rows="10" cols="20"></textarea>
					</p>
					<p>
						<span class="obligatoire">Les champs suivis d’une * sont obligatoires</span>
						<input type="image" src="images/fr/bt_envoyer2.gif" alt="Envoyer" class="bouton" onclick="javascript:contact_submit();"/>
					</p>
				</form>
			</div>
		</div>
		
		<div class="h_form texte">
			<div class="b_form">
				<div class="f_form">
					<p><strong>Le site internet Charte Qualité Fleurs est édité par Astredhor, avec le soutien de Val’hor et Viniflhor.</strong></p>
					<h2>Charte Qualité Fleurs chez Astredhor</h2>
					<p class="adresse">44 rue d’Alésia<br />
					75682 Paris Cedex 14<br />
					Fax : 01 45 38 56 72</p>
					
					<p class="visuel"><img src="images/fr/visuel_contact.gif" alt="" /></p>
				</div>
			</div>
		</div>
						
	</div>
</div>