<div id="index_demande">
	<h1><?=aff(_DEMANDE_D_INFORMATIONS_);?></h1>
	<div class="ensemble">
		<div class="coordonnees">
			<h2><?=aff(_NOS_COORDONNEES_);?></h2>
			<?=quote(aff(_DEMANDES_NOS_COORDONNEES_));?>
		</div>
	 	<div class="plan">
			<h2><?=aff(_PLAN_D_ACCES_);?></h2>
		    <a href="http://maps.google.com/maps?f=q&amp;hl=fr&amp;geocode=&amp;q=58+A+rue+du+Dessous+des+Berges,+75+013+Paris+/+France&amp;sll=48.893734,2.362437&amp;sspn=0.009932,0.017102&amp;ie=UTF8&amp;z=16&amp;iwloc=addr" target="_blank" title="Google Map"><img src="./images/common/plan.gif" alt="" width="276" height="207" border="0" /></a>
		</div>
	</div>
	
	<fieldset class="formulaire">
		<legend><?=aff(_PAR_FORMULAIRE_);?></legend>
		<h2><?=aff(_PAR_FORMULAIRE_);?></h2>

		<form onsubmit="return false;" id="demande_frm" name="demande_frm" enctype="multipart/form-data" method="post" action="<?=thisPage('action=DEMANDE', '', array('action'));?>">
			<script type="text/javascript">
			// <![CDATA[
			demande_submit = function() {
				demande_param = { mep:'alerte', autoScroll:true, action:'submit' };
				demande_champs = {
					demande_nom: {type:'', alerte:'<?=aff(_LE_NOM_EST_OBLIGATOIRE_);?>'},
					demande_prenom: {type:'', alerte:'<?=aff(_LE_PRENOM_EST_OBLIGATOIRE_);?>'},
					demande_email: {type:'mel', alerte:'<?=aff(_LE_MAIL_EST_OBLIGATOIRE_ET_DOIT_ETRE_VALIDE_);?>'},
					demande_message: {type:'', alerte:'<?=aff(_LE_MESSAGE_EST_OBLIGATOIRE_);?>'}
				};
				formVerif('demande_frm', demande_champs, demande_param);
			};
			// ]]>
			</script>
			<p>
				<label for="demande_nom"><?=aff(_NOM_);?> <span>*</span></label>
				<input type="text" name="demande_nom" id="demande_nom" />
			</p>			
			<p>
				<label for="demande_prenom"><?=aff(_PRENOM_);?> <span>*</span></label>
				<input type="text" name="demande_prenom" id="demande_prenom" />
			</p>
			<p>
				<label for="demande_programme"><?=aff(_PROGRAMME_CONCERNE_);?></label>
				<select name="demande_programme" id="demande_programme">
					<option value=""><?=aff(_SELECTIONNEZ_UN_PROGRAMME_);?></option>
					<?
					foreach($S->arbo[8]['childs'] as $$ssrid) {
						if (count($S->arbo[$$ssrid]['childs']) > 0) echo '<option value="'.aff($S->arbo[$$ssrid]['titre_'.$lg]).'">'.html(aff($S->arbo[$$ssrid]['titre_'.$lg])).'</option>';
					}
					?>
				</select>
			</p>
			<p>
				<label for="demande_societe"><?=aff(_SOCIETE_);?></label>
				<input type="text" name="demande_societe" id="demande_societe" />
			</p>
			<p>
				<label for="demande_tel"><?=aff(_TELEPHONE_);?></label>
				<input type="text" name="demande_tel" id="demande_tel" />
			</p>
			<p>
				<label for="demande_email"><?=aff(_E_MAIL_);?> <span>*</span></label>
				<input type="text" name="demande_email" id="demande_email" />
			</p>
			<p>
				<label for="demande_message"><?=aff(_MESSAGE_);?> <span>*</span></label>
				<textarea name="demande_message" id="demande_message" rows="4" cols="7"></textarea>
			</p>
			<p>
				<input type="image" src="./images/<?=$lg;?>/boutons/bt_envoyer.gif" class="submit" onclick="javascript:demande_submit();" />
			</p>
		</form>
	</fieldset>
</div>