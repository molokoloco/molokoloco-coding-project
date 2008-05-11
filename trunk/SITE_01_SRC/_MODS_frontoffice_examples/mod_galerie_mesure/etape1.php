<div id="gal_mesure" class="etape1">
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
                    <li id="ng1"><a href="<?=$devis_url;?>"><img src="images/fr/titre/t_ng1_on.gif" alt="Mes souhaits" /></a></li>
                    <li id="ng2"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng2.gif" alt="Mon objet" /></a></li>
                    <li id="ng3"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng3.gif" alt="Mes coordonn&eacute;es" /></a></li>
                    <li id="ng4"><a href="javascript:void(0);"><img src="images/fr/titre/t_ng4.gif" alt="Confirmation" /></a></li>
                </ul>
            </div>
            
            <!-- Bloc RTE -->
            
            <!-- Formulaire -->
            <div class="form_souhaits">
            	 <form action="<?=$devis_url;?>?etape=etape1&amp;action=DEVIS1" name="devis_form" id="devis_form" method="post" onsubmit="return false;">
	                <script type="text/javascript">
					// <![CDATA[
						var devis_submit = function() {
							var devis_param = { mep: 'alerte', autoScroll: false, action: 'submit' };
							var devis_champs = {
								souhaits: {minchar:10, alerte:'Vous devez choisir votre souhait'}
							};
							formVerif('devis_form', devis_champs, devis_param);
						}
					// ]]>
					</script>
					<h2>Vos souhaits</h2>
                    <div class="formulaire">
                    	<p>
                        	<input type="radio" name="souhaits" id="souhaits1" value="1" <?=($_SESSION[SITE_CONFIG]['DEVIS']['souhaits'] == 1 ? 'checked="checked"' : '');?> />
                            <label for="souhaits1">Mat&eacute;rialiser un fichier 3D</label>
                        </p>
                        <p>
                        	<input type="radio" name="souhaits" id="souhaits2"  value="2" <?=($_SESSION[SITE_CONFIG]['DEVIS']['souhaits'] == 2 ? 'checked="checked"' : '');?> />
                            <label for="souhaits2">Nous confier vos objets pour cr&eacute;er une r&eacute;plique</label>
                        </p>
                        <p>
                        	<input type="radio" name="souhaits" id="souhaits3" value="3" <?=($_SESSION[SITE_CONFIG]['DEVIS']['souhaits'] == 3 ? 'checked="checked"' : '');?> />
                            <label for="souhaits3">Nous confier vos objets pour cr&eacute;er un fichier 3D</label>
                        </p>
                    </div>
                    <div class="pied">
                        <input type="image" src="images/fr/bouton/bt_etape_suivante.gif" class="submit" value="Etape suivante"  onclick="devis_submit();" />
                    </div>
                </form>
            </div>
        </div>
    </div>    
</div>