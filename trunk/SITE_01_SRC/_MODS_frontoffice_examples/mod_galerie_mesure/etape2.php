<?

if ($_SESSION[SITE_CONFIG]['DEVIS']['souhaits'] < 1) goto($devis_url.'?etape=etape1');

?><div id="gal_mesure" class="etape2">
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
                    <li id="ng2"><a href="<?=$devis2_url;?>?etape=etape2"><img src="images/fr/titre/t_ng2_on.gif" alt="Mon objet" /></a></li>
                    <li id="ng3"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng3.gif" alt="Mes coordonn&eacute;es" /></a></li>
                    <li id="ng4"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng4.gif" alt="Confirmation" /></a></li>
                </ul>
            </div>
            
            <div class="intro">
            	<p>Vous souhaitez <?
				switch($_SESSION[SITE_CONFIG]['DEVIS']['souhaits']) {
					case '1' : echo 'mat&eacute;rialiser un fichier 3D'; break;
					case '2' : echo 'nous confier vos objets pour cr&eacute;er une r&eacute;plique'; break;
					case '3' : echo 'nous confier vos objets pour cr&eacute;er un fichier 3D'; break;
				}
				?>, merci de remplir les champs ci-dessous :</p>
            </div>

            <div class="form_objet">
            	<form action="<?=$devis2_url;?>?etape=etape2&amp;action=DEVIS2" name="devis2_form" id="devis2_form" method="post" enctype="multipart/form-data" onsubmit="return false;">
	                <script type="text/javascript">
					// <![CDATA[
						var devis2_submit = function() {
							var devis2_param = { mep: 'alerte', autoScroll: false, action: 'submit' };
							var devis2_champs = {
								devis2_nom: {alerte:'Le champs nom est obligatoire'},
								devis2_format: {alerte:'Le champs format est obligatoire'}
							};
							<? if (empty($_SESSION[SITE_CONFIG]['DEVIS']['fichier'])) { ?>
							if (!$('devis2_cd').checked) devis2_champs.devis2_fichier = {alerte:'Le champs fichier est obligatoire'};
							<? } ?>	
							formVerif('devis2_form', devis2_champs, devis2_param);
						}
					// ]]>
					</script>
	                <h2>Information concernant votre fichier 3D</h2>
                    <div class="formulaire">
                    	<p>
	                        <label for="devis2_nom">Donner un nom &agrave; votre fichier<span>*</span></label>
                            <input type="text" name="devis2_nom" id="devis2_nom" value="<?=html(aff($_SESSION[SITE_CONFIG]['DEVIS']['nom']));?>" />                        
                        </p>
                        <p>
	                        <label for="devis2_format">Format de votre fichier<span>*</span></label>
							<select name="devis2_format" id="devis2_format">
								<option value="">Format --&gt;</option>
								<? foreach ($arr_Fichier_Formats as $k=>$v) echo '<option value="'.$k.'" '.($_SESSION[SITE_CONFIG]['DEVIS']['format'] == $k ? 'selected="selected"' : '').'>'.html(aff($v)).'</option>'; ?>
							</select>
                        </p>
                        <p id="devis_cd" <?=($_SESSION[SITE_CONFIG]['DEVIS']['cd'] ? 'style="display:none;"' : '');?>>
	                        <label for="devis2_fichier">Uploader votre fichier<span>*</span></label>
                            <input type="file" name="devis2_fichier" id="devis2_fichier" />
							<?
							$m =& new FILE();
							if ($m->isMedia('medias/clients_devis/mini/'.$_SESSION[SITE_CONFIG]['DEVIS']['fichier'])) {
								$m->style = 'margin:10px 0 0 190px;display:block;';
								$m->popImage();
							}
							elseif ($m->isMedia('medias/clients_devis/'.$_SESSION[SITE_CONFIG]['DEVIS']['fichier'])) {
								$m->style = 'margin:10px 0 0 190px;display:block;';
								$m->media();
							}
							?>
                        </p>
						<p>
							<label for="devis2_cd">Envoyer le fichier par CD<span>**</span></label>
							<input type="checkbox" name="devis2_cd" id="devis2_cd" value="1" onchange="if(this.checked) { $('devis_cd').hide(); } else  { $('devis_cd').show(); } " <?=($_SESSION[SITE_CONFIG]['DEVIS']['cd'] ? 'checked="true"' : '');?> style="width:12px;"/>
                        </p>
                    </div>
                    <div class="info">
                    	<p>Les champs suivis d'une * sont obligatoires</p>
                        <p>**<em>Si votre fichier d&eacute;passe les <strong>10 mo</strong>, le transfert sera impossible, poursuivez et continuer &agrave; remplir les diff&eacute;rentes &eacute;tapes. Nous vous communiquerons nos coordonn&eacute;es &agrave; l'&eacute;tape 4 pour l'envoi par voie postal de votre fichier sur CD.</em></p>
                    </div>
                    <div class="pied">
                        <input type="image" src="images/fr/bouton/bt_etape_suivante.gif" class="submit" value="Etape suivante" onclick="devis2_submit();" />
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>