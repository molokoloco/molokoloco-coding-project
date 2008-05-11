<div id="adherer">
	<h1 class="t_page">Devenir adhérent</h1>
	<div class="page">
		<p><strong>La Charte Qualité Fleurs a pour objectif de mettre en avant la qualité et l'excellence de la tenue en vase des fleurs coupées françaises, grâce à une série d'engagements sérieux certifiés et contrôlés. 
Vous êtes producteur et souhaitez promouvoir les qualités de vos fleurs ? Rejoignez les 
adhérents de la Charte Qualité Fleurs déjà engagés dans cette démarche. Pour cela, 
remplissez le formulaire ci-dessous : nous  vous contacterons dans les plus 
brefs délais.</strong></p>
		
		<div class="adherent">
			<div class="b_adherent">
				<div class="f_adherent">
					<div class="visuel">
						<img src="images/fr/visuel_adherer.gif" alt="" />
					</div>
					<div class="texte">
						<h2>Demande d’informations</h2>
						<div class="h_form">
							<div class="b_form">
							<form onsubmit="return false;" id="adherer_frm" name="adherer_frm" enctype="multipart/form-data" method="post" action="<?=thisPage('action=ADHERENT', '', array('action'));?>">
								<script type="text/javascript">
								adherer_submit = function() {
									adherer_param = { mep: 'alerte', autoScroll: false, action: 'submit', divErrorCss: 'toto' };
									adherer_champs = {
										adherer_nom: {type:'', alerte:'Le nom est obligatoire'},
										adherer_prenom: {type:'', alerte:'Le pr&eacute;nom est obligatoire'},
										adherer_societe: {type:'', alerte:'La soci&eacute;t&eacute; est obligatoire'},
										adherer_fonction: {type:'', alerte:'La fonction est obligatoire'},
										adherer_adresse: {type:'', alerte:'L\'adresse est obligatoire'},
										adherer_cp: {type:'', alerte:'Le code postal est obligatoire'},
										adherer_ville: {type:'', alerte:'La ville est obligatoire'}
										
									};
									formVerif('adherer_frm', adherer_champs, adherer_param);
								}
								</script>
									<p>
										<label for="adherer_nom">Nom<sup>*</sup> :</label>
										<input type="text" name="adherer_nom" id="adherer_nom" />
									</p>
									<p>
										<label for="adherer_prenom">Prénom<sup>*</sup> :</label>
										<input type="text" name="adherer_prenom" id="adherer_prenom" />
									</p>
									<p>
										<label for="adherer_email">Email :</label>
										<input type="text" name="adherer_email" id="adherer_email" />
									</p>
									<p>
										<label for="adherer_societe">Société<sup>*</sup> :</label>
										<input type="text" name="adherer_societe" id="adherer_societe" />
									</p>
									<p>
										<label for="adherer_fonction">Fonction<sup>*</sup> :</label>
										<input type="text" name="adherer_fonction" id="adherer_fonction" />
									</p>
									<p>
										<label for="adherer_tel">Tél. :</label>
										<input type="text" name="adherer_tel" id="adherer_tel" />
									</p>
									<p>
										<label for="adherer_fax">Fax :</label>
										<input type="text" name="adherer_fax" id="adherer_fax" />
									</p>
									<p>
										<label for="adherer_adresse">Adresse<sup>*</sup> :</label>
										<input type="text" name="adherer_adresse" id="adherer_adresse" />
									</p>
									<p>
										<label for="adherer_cp">Code postal<sup>*</sup> :</label>
										<input type="text" name="adherer_cp" id="adherer_cp" />
									</p>
									<p>
										<label for="adherer_ville">Ville<sup>*</sup> :</label>
										<input type="text" name="adherer_ville" id="adherer_ville" />
									</p>
									<p>
										<label for="adherer_message">Message :</label>
										<textarea name="adherer_message" id="adherer_message" rows="10" cols="20"></textarea>
									</p>
									<p>
										<span class="obligatoire">Les champs suivis d’une * sont obligatoires</span>
										<input type="image" src="images/fr/bt_envoyer2.gif" alt="Envoyer" class="bouton" onclick="javascript:adherer_submit();"/>
									</p>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
</div>