<?

if (empty($_SESSION[SITE_CONFIG]['CADDIE']['produits'])) goto($commandes_url.'?etape=etape1');

if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] > 0 && empty($_SESSION[SITE_CONFIG]['INSCRIPTION2'])) $_SESSION[SITE_CONFIG]['INSCRIPTION2'] = $_SESSION[SITE_CONFIG]['CLIENT'];

?><div id="commande" class="etape2">
	<h1 class="t_rub">Commander</h1>
    <div class="nav_cmd">
    	<ul>
        	<li id="nc1"><a href="<?=$commandes_url;?>?etape=etape1"><img src="images/fr/titre/t_nc1.gif" alt="Ma s&eacute;lection" /></a></li>
            <li id="nc2"><a href="<?=$commandes_url;?>?etape=etape2"><img src="images/fr/titre/t_nc2_on.gif" alt="Mes coordonn&eacute;es" /></a></li>
            <li id="nc3"><a href="<?=($_SESSION[SITE_CONFIG]['CLIENT']['id'] > 0 ? $commandes_url.'?etape=etape3':'javascript:void(0);');?>"><img src="images/fr/titre/t_nc3.gif" alt="Moyens de paiement" /></a></li>
            <li id="nc4"><a href="javascript:void(0);"><img src="images/fr/titre/t_nc4.gif" alt="Confirmation" /></a></li>
        </ul>
    </div>
    <div class="form_coord">
       <form action="<?=$commandes_url;?>?etape=etape2&amp;action=INSCRIPTION2" method="post" enctype="multipart/form-data" name="inscription2_frm" id="inscription2_frm" onsubmit="return false;">
        	<script type="text/javascript">
			// <![CDATA[
				var inscription2_submit = function() {
					var inscription2_param = { mep: 'alerte', autoScroll: false, action: 'submit' };
					var inscription2_champs = {
						<? if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) { ?>
						inscription2_civilite: {alerte:'Le champ civilit&eacute; est obligatoire'},
						inscription2_nom: {alerte:'Le champ nom est obligatoire'},
						inscription2_prenom: {alerte:'Le champ pr&eacute;nom est obligatoire'},
						inscription2_email: {type:'mel',alerte:'Le champ e-mail est obligatoire et doit &ecirc;tre valide'},
						inscription2_m2p: {minchar:5, alerte:'Le champ mot de passe est obligatoire et doit faire 5 charact&egrave;res min.'},
						<? } ?>
						inscription2_adresse: {alerte:'Le champ adresse est obligatoire'},
						inscription2_cp: {alerte:'Le champ code postal est obligatoire'},
						inscription2_ville: {alerte:'Le champ ville est obligatoire'},
						inscription2_pays: {type:'',alerte:'Le champ pays est obligatoire et doit &ecirc;tre valide'},
						inscription2_tel: {minchar:10, alerte:'Le champ t&eacute;l&eacute;phone est obligatoire'}
					};
					if ($('fac_diff').checked) {
						var inscription2_champs2 = {
							inscription2_adresse2: {alerte:'Le champ adresse est obligatoire'},
							inscription2_cp2: {alerte:'Le champ code postal est obligatoire'},
							inscription2_ville2: {alerte:'Le champ ville est obligatoire'},
							inscription2_pays2: {type:'',alerte:'Le champ pays est obligatoire et doit &ecirc;tre valide'},
							inscription2_zone2: {alerte:'Le champ Zone est obligatoire'},
							inscription2_tel2: {minchar:10, alerte:'Le champ t&eacute;l&eacute;phone est obligatoire'}
						};
						inscription2_champs = Object.extend(inscription2_champs, inscription2_champs2);
					}
					else inscription2_champs.inscription2_zone = {alerte:'Le champ Zone est obligatoire'};
					formVerif('inscription2_frm', inscription2_champs, inscription2_param);
				}
			// ]]>
			</script>

			<div class="fieldsets">
                <fieldset class="info_perso">
                    <legend>Mes informations personnelles</legend>
                    <h2>Mes informations personnelles</h2>
					<? if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) { ?>
						<p class="first">
							<label for="inscription2_civilite">Titre<span>*</span> : </label>
							<select name="inscription2_civilite" id="inscription2_civilite">
								<option value="Mr" <?=($_SESSION[SITE_CONFIG]['INSCRIPTION2']['civilite'] == 'Mr' ? 'selected="true"' : '');?>>Mr.</option>
								<option value="Mme" <?=($_SESSION[SITE_CONFIG]['INSCRIPTION2']['civilite'] == 'Mme' ? 'selected="true"' : '');?>>Mme</option>
								<option value="Mlle" <?=($_SESSION[SITE_CONFIG]['INSCRIPTION2']['civilite'] == 'Mme' ? 'selected="true"' : '');?>>Mlle</option>
							</select>
						</p>
						<p><?=getFormRow('text', 'Nom<span>*</span> : ', 'inscription2_nom', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['nom'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Pr&eacute;nom<span>*</span> : ', 'inscription2_prenom', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['prenom'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Email<span>*</span> : ', 'inscription2_email', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['email']);?></p>
						<p><?=getFormRow('password', 'Mot de passe<span>*</span> : ', 'inscription2_m2p', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['m2p']);?></p>
						<p><?=getFormRow('password', 'Confirmer mot de passe<span>*</span> : ', 'inscription2_m2p_2', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['m2p']);?></p>               
					
					<? } else { ?>
						<input type="hidden" name="inscription2_civilite" value="<?=aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['civilite']);?>" />
						<input type="hidden" name="inscription2_nom" value="<?=aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['nom']);?>" />
						<input type="hidden" name="inscription2_prenom" value="<?=aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['prenom']);?>" />
						<input type="hidden" name="inscription2_email" value="<?=aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['email']);?>" />
						<input type="hidden" name="inscription2_m2p" value="<?=aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['m2p']);?>" />
						<input type="hidden" name="inscription2_m2p_2" value="<?=aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['m2p']);?>" />
						<p class="first"><label for="">Titre : </label> <?=aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['civilite']);?></p>
						<p><label for="" id="">Nom :</label> <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['nom']));?></p>
						<p><label for="" id="">Pr&eacute;nom :</label> <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['prenom']));?></p>
						<p><label for="" id="">Email :</label> <?=html(aff($_SESSION[SITE_CONFIG]['INSCRIPTION2']['email']));?></p>
					<? } ?>
				</fieldset>
				
                <div class="fieldsets_droite">
                    <fieldset class="fac_adresse">
                        <legend>Votre adresse de facturation</legend>
                        <h2>Votre adresse de facturation</h2>
						<p class="first"><?=getFormRow('text', 'Adresse<span>*</span> : ', 'inscription2_adresse', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['adresse'], array('maxlength'=>'250','class'=>'long'));?></p>
						<p><?=getFormRow('text', 'Code postal<span>*</span> : ', 'inscription2_cp', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['cp'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Ville<span>*</span> : ', 'inscription2_ville', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['ville'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Pays<span>*</span> : ', 'inscription2_pays', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['pays'], array('maxlength'=>'150'));?></p>
						<p id="inscription2_zoneR">
							<label for="inscription2_zone">Zone<span>*</span> : </label>
							<select name="inscription2_zone" id="inscription2_zone" style="width:210px;">
							<?
							foreach($arr_Zones as $k=>$zone) { ?><option value="<?=$k;?>" <?=($_SESSION[SITE_CONFIG]['INSCRIPTION2']['zone'] == $k ? 'selected="true"' : '');?>><?=html(aff($zone));?></option><? } ?>
							</select>
						</p>
						<p><?=getFormRow('text', 'T&eacute;l.<span>*</span> : ', 'inscription2_tel', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['tel'], array('maxlength'=>'150'));?></p>
						<p class="diff">
						<input type="checkbox" name="fac_diff" id="fac_diff" value="1" onchange="if(this.checked) { Effect.BlindDown('liv_adresse',{duration:0.6}); $('inscription2_zoneR').hide(); } else  { Effect.BlindUp('liv_adresse',{duration:0.6}); $('inscription2_zoneR').show(); } " <?=($_SESSION[SITE_CONFIG]['INSCRIPTION2']['fac_diff'] ? 'checked="true"' : '');?>/>       
						<label for="fac_diff">Entrer une adresse de livraison diff&eacute;rente</label>
                        </p>
                    </fieldset>

                    <fieldset class="liv_adresse" id="liv_adresse" style="display:<?=($_SESSION[SITE_CONFIG]['INSCRIPTION2']['fac_diff'] ? "''" : 'none');?>;">
                        <legend>Votre adresse de livraison</legend>  
                        <h2>Votre adresse de livraison</h2>
                        <p class="first"><?=getFormRow('text', 'Adresse<span>*</span> : ', 'inscription2_adresse2', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['adresse2'], array('maxlength'=>'250','class'=>'long'));?></p>
                        <p><?=getFormRow('text', 'Code postal<span>*</span> : ', 'inscription2_cp2', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['cp2'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Ville<span>*</span> : ', 'inscription2_ville2', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['ville2'], array('maxlength'=>'150'));?></p>
						<p><?=getFormRow('text', 'Pays<span>*</span> : ', 'inscription2_pays2', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['pays2'], array('maxlength'=>'150'));?></p>
						<p>
							<label for="inscription2_zone">Zone<span>*</span> : </label>
							<select name="inscription2_zone2" id="inscription2_zone2" style="width:210px;">
							<?
							foreach($arr_Zones as $k=>$zone) { ?><option value="<?=$k;?>" <?=($_SESSION[SITE_CONFIG]['INSCRIPTION2']['zone2'] == $k ? 'selected="true"' : '');?>><?=html(aff($zone));?></option><? } ?>
							</select>
						</p>
						<p><?=getFormRow('text', 'T&eacute;l.<span>*</span> : ', 'inscription2_tel2', $_SESSION[SITE_CONFIG]['INSCRIPTION2']['tel2'], array('maxlength'=>'150'));?></p>
                    </fieldset>
                </div>
            </div>
            <div class="pied">
            	<p>Les champs suivis d'une * sont obligatoires
				<? if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1) { ?><br />Si vous &ecirc;tes d&eacute;j&agrave; inscrit : <a href="javascript:void(0);" onclick="new Effect.ScrollTo('TOP');$('log_email').focus();">Connectez-vous</a><? } ?></p>
            	<input type="image" src="images/fr/bouton/bt_etape_suivante.gif" class="submit" value="Etape suivante" onclick="inscription2_submit();"/>
           	</div>
        </form>

    </div>
</div>