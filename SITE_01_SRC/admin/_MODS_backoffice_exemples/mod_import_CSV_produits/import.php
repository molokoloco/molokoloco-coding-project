<? require("../menu/menu_light.php"); ?><?
/**
 * Importation de fichier au format CSV
*/

// Some Params
$verboseDebug = false;
$backUpCsv = true; // Garde la copie du CSV et des images
$protectionTexte = '"'; // option � l'export du csv
$dossierImagePath = (isLocal() ? $wwwRoot.'_WORK_/_ALL_VISU/' : $wwwRoot.'temp/'); // Repertoire ou sont les images, avec le nom correspondant dans le CSV


// Enleve les " du CSV
function cleanString($string) { 
	global $protectionTexte;
	return str_replace($protectionTexte, '', stripslashes(stripslashes($string)));
}

// Get Action(s)
$action = isset($_GET['action']) ? clean($_GET['action']) : '';
if ($action != '') {
	switch($action) {
		case 'ImportCSV' : // IMPORTE FILE ////////////////////////////////////////////////////////////////////
			
			@ignore_user_abort(true);
			
			// UPLOAD FILE CSV.................
			$fichier_csv = '';
			$inputName = 'file_csv';
			
			if (!isset($_FILES[$inputName]['tmp_name'])) alert('Vous devez s�lectionner un fichier', 'back');
			elseif (!in_array(getExt($_FILES[$inputName]['name']), array('csv', 'txt', 'xls'))) {
				@unlink($_FILES[$inputName]['tmp_name']);
				alert('Format inconnu... : le fichier ne semble pas avoir une extension .CSV, .XLS ou .TXT...', 'back');
				exit();
			}
			
			$m =& new FILE();
			$m->uploadFile($inputName, './csv/');
			if ($m->error) {
				@unlink($_FILES[$inputName]['tmp_name']);
				alert($m->error, 'back');
				exit();
			}
			$fichier_csv = './csv/'.$m->name;
			
			// DOSSIER IMAGES LOCALES
			$dossierImage = getFile($dossierImagePath);
			if (count($dossierImage) < 1) {
				db('Attention, votre r�pertoire image semble vide : '.$dossierImagePath);
			}
			else {
				//> array('Cheval1.jpg' => 'cheval1.jpg',...);
				function mapLowercase($value) { return strtolower($value); }
				$dossierImage = array_combine($dossierImage, $dossierImage);
				$dossierImage = array_map('mapLowercase', $dossierImage);
			}
			if ($verboseDebug) db('Image(s) d�tect�e(s) : '.implode(', ', $dossierImage));
			
			// TRAITEMENT CSV
			$contenu = join('', @file($fichier_csv));
			if (!$backUpCsv) @unlink($fichier_csv);
			
			$lignes = explode(chr(13).chr(10), $contenu);
			if (count($lignes) < 2) alert('Votre fichier ne comporte pas 2 lignes minimum ou n\'est pas au format CSV', 'back'); //> Au moins 2 lignes
			
			// Field Separator.....
			switch($_POST['sep']) { 
				case '1' : $sep = ','; break;
				case '2' : $sep = ';'; break;
				case '3' : $sep = chr(9); break; // tab
				default : $sep = ';'; break;
			}
			if (!ereg($sep, $lignes[1])) alert('Il semble que le format de s�paration CSV indiqu� ne soit pas le bon : "'.$sep.'" introuvable', 'back');

			// SCAN DU CSV
			$ssrubrique_titreLikeEx = 0;
			$ssrubrique_id = 0;
			$produit_id = 0;
			
			for ($j=1; $j<count($lignes); $j++) { // $j=1 : 1er ligne = entetes....
				
				// 0=>Cat�gories, Sous-Cat�gories, Nouveaut�, R�f�rence, Titre, Description, Visuel, Animation, Format, Fichier (t�l�chargement), D�lai (en jours), Echelle, Dimmension (mm), Prix, Prix Promo, Poids (gramme)
				$champs = explode($sep, $lignes[$j]);
				if (count($champs) < 1) {
					echo 'La ligne '.$j.' ne comporte pas de champs et � �t� ignor�... (pb s�parateur ?)<br />';
					continue;
				}
				
				// RUBRIQUE & SOUS-RUBRIQUE
				$rubrique_titreLike = clean(cleanString($champs[0]));
				$ssrubrique_titreLike = clean(cleanString($champs[1]));
				
				if (!empty($ssrubrique_titreLike) && ($ssrubrique_titreLike != $ssrubrique_titreLikeEx || $ssrubrique_id < 1)) {
					
					// RUBRIQUE ?
					if (!empty($rubrique_titreLike)) {
						$Q =& q("SELECT id FROM mod_catalogue_rubriques WHERE titre LIKE '$rubrique_titreLike' LIMIT 1");
						if ($verboseDebug) db($Q);
						$rubrique_id = $Q['id'];
						if ($rubrique_id < 1) {
							$A = new Q();
							$A->insert('mod_catalogue_rubriques', array(
								'ordre'=> '999',
								'actif'=> '1',
								'titre'=> $rubrique_titreLike,
								'atouts'=> ''
							));
							if ($verboseDebug) db($A);
							$rubrique_id = $A->id;
						}
					}
					
					// SOUS-RUBRIQUE
					$Q =& q("SELECT id FROM mod_catalogue_ssrubriques WHERE titre LIKE '$ssrubrique_titreLike' AND (rubrique_id='$rubrique_id' OR rubrique_id='0') LIMIT 1");
					if ($verboseDebug) db($Q);
					$ssrubrique_id = $Q['id'];
					if ($ssrubrique_id < 1) {
						$A = new Q();
						$A->insert('mod_catalogue_ssrubriques', array(
							'ordre'=> '999',
							'actif'=> '1',
							'rubrique_id'=> $rubrique_id,
							'titre'=> $ssrubrique_titreLike
						));
						if ($verboseDebug) db($A);
						$ssrubrique_id = $A->id;
					}
					
				}
				$ssrubrique_titreLikeEx = $ssrubrique_titreLike;
				
				if ($ssrubrique_id < 1) {
					echo 'Pb de Sous-Cat�gories : '.$ssrubrique_titreLike.'<br />';
					continue;
				}
				
				// PRODUIT
				$titre = ucfirst(str_replace('_', ' ', clean(cleanString($champs[4]))));
				$reference = clean(cleanString($champs[3]));

				if (!empty($reference)) { // NEW PRODUIT
					
					if (empty($titre)) $titre = $reference; // Buggus client
					
					$Q =& q("SELECT MAX(ordre) AS ordre FROM mod_catalogue_produits LIMIT 1");
					$ordre = $Q['ordre'] + 10;
					
					$actif = 1;
					$nouveau = 0;
					$meilleur = 0;
					$texte = clean(cleanString($champs[5]));
					$pdf = '';
					$document = '';
					$visuel_1 = clean(cleanString($champs[6]));
					$visuel_2 = '';
					$visuel_3 = '';
					
					if (!empty($visuel_1) && in_array(getExt($visuel_1), $extensionsImg)) {
						$visuelNoLower = array_search(strtolower($visuel_1), $dossierImage);
						if ($visuelNoLower) {
							if ($verboseDebug) db($visuelNoLower);
							$file_image = $dossierImagePath.$visuelNoLower; // DOSSIER IMAGE !!!!!!!!!!!!!!!!!!!!!!!
							if (@file_exists($file_image)) {
								$m =& new FILE();
								$m->makeThumbs($file_image, $wwwRoot.'medias/catalogue/', $arr_produits_images);
								if ($m->error) {
									db('Pb image : '.$m->error.'<br />');
								}
								else $visuel_1 = $m->name;
								if (!$backUpCsv) @unlink($file_image);
								if ($verboseDebug) db($m);
							}
						}
					}
				
					$A = new Q();
					$A->insert('mod_catalogue_produits', array(
						'ordre'=> $ordre,
						'actif'=> $actif,
						'nouveau'=> $nouveau,
						'meilleur'=> $meilleur,
						'ssrubrique_id'=> $ssrubrique_id,
						'titre'=> $titre,
						'reference'=> $reference,
						'texte'=> $texte,
						'pdf'=> $pdf,
						'document'=> $document,
						'visuel_1'=> $visuel_1,
						'visuel_2'=> $visuel_2,
						'visuel_3'=> $visuel_3
					));
					$produit_id = $A->id;
					if ($verboseDebug) db($A);
				}
				
				if ($produit_id < 1) {
					db('Pb de produit : '.$titre.'<br />');
					continue;
				}
				
				$format = array_search(clean(cleanString($champs[8])), $arr_Formats); // "$arr_Formats" from racine.php
				
				if ($produit_id > 0 && !empty($format)) { // NEW MATIERE
					
					$actif = 1;
					//$produit_id = '';
					$titre = '';
					$texte = '';
					$delai = clean(cleanString($champs[10]));
					$echelle = clean(cleanString($champs[11]));
					$dimensions = clean(cleanString($champs[12]));
					$poids = '';
					$prix = floatval(str_replace(',', '.', clean(cleanString($champs[13]))));
					$prix_promo = '';
					$document = '';
					
					$A = new Q();
					$A->insert('mod_matieres', array(
						'actif'=> $actif,
						'produit_id'=> $produit_id,
						'format'=> $format,
						'titre'=> $titre,
						'texte'=> $texte,
						'delai'=> $delai,
						'echelle'=> $echelle,
						'dimensions'=> $dimensions,
						'poids'=> $poids,
						'prix'=> $prix,
						'prix_promo'=> $prix_promo,
						'document'=> $document
					));
					if ($verboseDebug) db($A);
				}

				if ($verboseDebug) db('------------------------------------- LIGNE '.$j.' -------------------------------------');
			}
			
			if ($verboseDebug) db('END');
			
			//$info = 'crea';
			$intitule = 'Cr&eacute;ation effectu&eacute;e, vous pouvez maintenant aller<br>
			v�rifier la liste des produits en <a href="../mod_catalogue_produits/" onClick="if(opener.document){opener.document.location=this.href;daddy=window.self;daddy.opener=window.self;daddy.close();return false; }">cliquant ici</a>';
		
		break;
	}
}
?><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="23" nowrap class="table-titre">IMPORTATION DES RAPPORTS, AU FORMAT .CSV </td>
</tr>
<tr align="center">
<td class="bgTableauPcP"><? if ($info != '' || $intitule != '') { include("../lib/actions_infos.php"); } ?></td>
</tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="15">
<tr>
<td><table width="100%"  border="0" cellpadding="3" cellspacing="1" class="tablebor">
<form method="POST" action="import.php?action=ImportCSV" name="F6" enctype="multipart/form-data">
<script language="JavaScript" type="text/JavaScript">
<!--
function V6() { // Verif de la saisie
	whom = document.F6;
	 if (whom.file_csv.value=='')  { 
		alert("Veuillez choisir un fichier");
	}
	else whom.submit();
}
//-->
</script>
<tr>
<td colspan="2" class="table-sstitre">Importer des  donn&eacute;es [Format .CSV, t&eacute;l&eacute;charger le <a href="csv/Liste-industrie_2008_10_15.xls" target="_blank">gabarit .XLS</a>]</td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">Fichier CSV :</td>
<td width="98%" nowrap class="table-ligne1"><input name="MAX_FILE_SIZE" type="hidden" value="2600000"><input name="file_csv" type="file" size="50" maxlength="50" style="width:100%;" class="table-ligne1"></td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">S&eacute;parateur CSV :</td>
<td class="table-ligne1"><input name="sep" type="radio" class="radio" value="1" checked> Virgules (,) <input name="sep" type="radio" class="radio" value="2"> 
Points-virgules (;) <input name="sep" type="radio" class="radio" value="3"> Tabulations (Grand espace blanc)</td>
</tr>
<tr align="center">
<td colspan="2" class="table-bas"><input type="button" name="B3" value="Importer et mettre &agrave; jour la BDD" onclick="javascript:V6();"></td>
</tr>
</form>
</table></td></tr>
</table></td>
</tr>
</table>
<? require("../menu/menu_bas.php"); ?>