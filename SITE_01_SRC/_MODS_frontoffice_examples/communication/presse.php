<?
$A =& new Q(" SELECT * FROM mod_page_intro WHERE id='4' LIMIT 1 ");
$A = $A->V[0];

?>
<div id="presse">
	<h1 class="t_page">Presse</h1>
	<? include('communication/smenu.php');?>
	<div class="page">
		<div class="h_presse">
			<div class="b_presse">
				<div class="f_presse">
					<?
                    $m =& new FILE();
                    if ($m->isMedia('imgs/pages/medium/'.$A['visuel'])) {
                        ?>
                        <div class="visuel"><?
                        $m->_pathzoom = 'imgs/pages/pop/'.$A['visuel'];
                        $m->popImage();
                        ?></div>
						<?
                    }
                    ?>
					<div class="description">
						<h2><?=aff($A['titre']);?></h2>
						<p><strong><?=aff($A['intro']);?></strong></p>
						<?=quote(aff($A['texte']));?>
						
						<?
						$m =& new FILE();
						if ($m->isMedia('imgs/pages/'.$A['document'])) {
							$m->css = 'telecharger';
							$m->texte = ( !empty($A['titre_document']) ? '<span>'.$A['titre_document'].' ('.$m->ext.', '.$m->size.')</span>' : '' );
							$m->lien();
						}
						?>
						
						<a href="javascript:affPresse();" class="t_contact" id="t_contact">Vous &ecirc;tes journaliste ? Contactez-nous</a>
						<div class="contact" id="contact" style="display:none;">
							<form onsubmit="return false;" id="contact_frm" name="contact_frm" enctype="multipart/form-data" method="post" action="<?=thisPage('action=CONTACTPRESSE', '', array('action'));?>">
								<script type="text/javascript">
								contact_submit = function() {
									contact_param = { mep: 'message', autoScroll: false, action: 'submit' };
									contact_champs = {
										contact_nom: {type:'', alerte:'Le nom est obligatoire'},
										contact_prenom: {type:'', alerte:'Le pr&eacute;nom est obligatoire'},
										contact_email: {type:'mel', alerte:'L\'email est obligatoire et doit &ecirc;tre valide'},
										contact_tel: {type:'', alerte:'Le t&eacute;l&eacute;phone est obligatoire'},
										contact_societe: {type:'', alerte:'La soci&eacute;t&eacute; est obligatoire'}
									};
									formVerif('contact_frm', contact_champs, contact_param);
								}
								</script>
								<p class="intro">Vous êtes journaliste ?<br />
								Vous souhaitez obtenir plus d'information sur la Charte Qualité Fleurs.<br />
								Merci de remplir le formulaire ci-dessous :</p>
								
								<p>
									<label for="contact_nom">Nom<sup>*</sup> :</label>
									<input type="text" name="contact_nom" id="contact_nom" value="" />
								</p>
								<p>
									<label for="contact_prenom">Prénom<sup>*</sup> :</label>
									<input type="text" name="contact_prenom" id="contact_prenom" value="" />
								</p>
								<p>
									<label for="contact_email">Email<sup>*</sup> :</label>
									<input type="text" name="contact_email" id="contact_email" value="" />
								</p>
								<p>
									<label for="contact_tel">Téléphone<sup>*</sup> :</label>
									<input type="text" name="contact_tel" id="contact_tel" value="" />
								</p>
								<p>
									<label for="contact_societe">Société<sup>*</sup> :</label>
									<input type="text" name="contact_societe" id="contact_societe" value="" />
								</p>
								<p>
									<label for="contact_message">Message :</label>
									<textarea name="contact_message" id="contact_message" rows="10" cols="30"></textarea>
								</p>
								<p class="envoi">
									<span>Les champs suivis d'une * sont obligatoires</span>
									<input type="image" src="images/fr/bt_envoyer.gif" alt="Envoyer" class="bouton" onclick="javascript:contact_submit();" />
								</p>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<h2>La presse en parle</h2>
		<?
		$G =& new Q(" SELECT * FROM mod_presses WHERE actif='1' ORDER BY date DESC ");
		foreach ($G->V as $V) {
		
			?><div class="presse">
				<?
                $m =& new FILE();
                if ($m->isMedia('imgs/presses/mini/'.$V['visuel'])) {
                    ?>
                    <div class="visuel"><?
                    $m->popImage();
                    ?></div>
					<?
                }
				?>
				<div class="description">
					<h3><?=printDateTime($V['date'], 5);?> – <span><?=aff($V['titre']);?></span></h3>
					<?=quote(aff($V['description']));?>
					<p class="liens">
						<?
						$m =& new FILE();
						if ($m->isUrl($V['lien'])) {
							$m->target = $V['cible'];
							$m->css = 'info';
							$m->texte = '<span>+ d\'info</span>';
							$m->title = html(aff($V['titre_lien']));
							$m->lien();
						}
						$m =& new FILE();
						if ($m->isMedia('imgs/presses/'.$V['document'])) {
								$m->css = 'telecharger';
								$m->texte = ( !empty($V['titre_document']) ? '<span>'.$V['titre_document'].' ('.$m->ext.', '.$m->size.')</span>' : '' );
								$m->lien();
							}
						?>
					</p>
				</div>
			</div>
			<?
		}
		?>
		
	</div>
</div>