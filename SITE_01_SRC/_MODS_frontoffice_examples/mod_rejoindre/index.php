<div id="index_rejoindre">
	<h1><?=aff(_NOUS_REJOINDRE_);?></h1>
	<h2><?=aff(_LISTE_DE_NOS_POSTES_DISPONIBLES_);?></h2>
	<div class="intro"><?=aff(_INTRO_REJOINDRE_);?></div>
    <br />
    
	<?
	$R =& new Q(" SELECT * FROM mod_nous_rejoindre WHERE actif='1' ORDER BY ordre DESC ");
	foreach ($R->V as $i=>$V) {
		?>
		<div class="poste">
			<h3><?=html(aff($V['titre_'.$lg]));?></h3>
			<div class="wg">
				<?=quote(aff($V['texte_'.$lg]));?>		
			</div>
			<a href="#form" class="postuler" onclick="$('candidature_programme').selectedIndex=<?=($i+1);?>"><?=aff(_POSTULER_);?></a>
		</div>
		<?
	}
	?>
	
	<a name="form"></a>
	<fieldset class="formulaire">
		<legend><?=aff(_FORMULAIRE_DE_CANDIDATURE_);?></legend>
		<h2><?=aff(_FORMULAIRE_DE_CANDIDATURE_);?></h2>
		<form onsubmit="return false;" id="candidature_frm" name="candidature_frm" enctype="multipart/form-data" method="post" action="<?=thisPage('action=CANDIDATURE', '', array('action'));?>">
			<script type="text/javascript">
			// <![CDATA[
			candidature_submit = function() {
				candidature_param = { mep:'alerte', autoScroll:true, action:'submit' };
				candidature_champs = {
					candidature_nom: {type:'', alerte:'<?=aff(_LE_NOM_EST_OBLIGATOIRE_);?>'},
					candidature_prenom: {type:'', alerte:'<?=aff(_LE_PRENOM_EST_OBLIGATOIRE_);?>'},
					candidature_programme: {type:'', alerte:'<?=aff(_LE_POSTE_EST_OBLIGATOIRE_);?>'},
					candidature_email: {type:'mel', alerte:'<?=aff(_LE_MAIL_EST_OBLIGATOIRE_ET_DOIT_ETRE_VALIDE_);?>'},
					candidature_message: {type:'', alerte:'<?=aff(_LE_MESSAGE_EST_OBLIGATOIRE_);?>'},
					candidature_cv: {type:'pdf|doc|txt', alerte:'<?=aff(_LE_CV_EST_OBLIGATOIRE_);?>'}
				};
				formVerif('candidature_frm', candidature_champs, candidature_param);
			};
			// ]]>
			</script>
			<p>
				<label for="candidature_nom"><?=aff(_NOM_);?> <span>*</span></label>
				<input type="text" name="candidature_nom" id="candidature_nom" />
			</p>			
			<p>
				<label for="candidature_prenom"><?=aff(_PRENOM_);?> <span>*</span></label>
				<input type="text" name="candidature_prenom" id="candidature_prenom" />
			</p>
			<p>
				<label for="candidature_programme"><?=aff(_POSTE_CONCERNE_);?> <span>*</span></label>
				<select name="candidature_programme" id="candidature_programme">
					<option value=""><?=aff(_SELECTIONNEZ_LE_POSTE_);?></option>
					<?
					foreach ($R->V as $V) {
						?><option value="<?=$V['id'];?>"><?=html(aff($V['titre_'.$lg]));?></option><?
					}
					?>
				</select>
			</p>

			<p>
				<label for="candidature_tel"><?=aff(_TELEPHONE_);?></label>
				<input type="text" name="candidature_tel" id="candidature_tel" />
			</p>
			<p>
				<label for="candidature_email"><?=aff(_E_MAIL_);?> <span>*</span></label>
				<input type="text" name="candidature_email" id="candidature_email" />
			</p>
			<p>
				<label for="candidature_message"><?=aff(_MOTIVATION_);?> <span>*</span></label>
				<textarea name="candidature_message" id="candidature_message" rows="4" cols="7"></textarea>
			</p>
			<p>
				<label for="candidature_cv"><?=aff(_CV_);?> <span>*</span></label>
				<input type="file" name="candidature_cv" id="candidature_cv" />
			</p>
			<p>
				<input type="image" src="./images/<?=$lg;?>/boutons/bt_envoyer.gif" class="submit" onclick="javascript:candidature_submit();" />
			</p>

		</form>
	</fieldset>

</div>