<?
$commande_id = intval($_GET['id']);
$ok = false;
if ($commande_id > 0) {

	require("../lib/racine.php");
	//checkRef();
	
	// FETCH COMMANDE ///////////////////////////////////////////////////////////////////////////////////////
	$A = new SQL('mod_commandes');
	$A->LireSql(array('*')," id='$commande_id' LIMIT 1 ");
	$client_id = Aff($A->V[0]['cat_id']);
	
	$ref = Aff($A->V[0]['titre']);
	$mailok = Aff($A->V[0]['mailok']); 
	$mailok2 = clean($_GET['mailok']); // Prevenir si mailok mais essaye de renvoyer un nouveau ?
	if ($mailok2 == '1') $mailok = 0;
/*	$statut_c = $A->V[0]['statut_c'];
	$statut_p = $A->V[0]['statut_p'];
	$response_code = $A->V[0]['response_code'];*/
	
	// GET CLIENT
	$A = new SQL('mod_clients');
	$A->LireSql(array('*')," id='$client_id' LIMIT 1 ");
	$client_civilite = Aff($A->V[0]['civilite']);
	$nom = Aff($A->V[0]['nom']);
	$client_prenom = Aff($A->V[0]['prenom']);
	$client_client = $civilite.' '.$prenom.' '.$nom;
	$client_email = Aff($A->V[0]['email']);

	if ($mailok != '1') {

		
		// BUILD TICKET COMMANDE /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		$C =& new Q("SELECT * FROM mod_commandes WHERE id='$commande_id' LIMIT 1");
		
		$arrayStatutC = array(1=>'Non valide',2=>'A traiter',3=>'Traitée');
		$arrayStatutP = array(0=>'Non valide',1=>'Valide');
		$arrayModeP = array(1=>'CB',2=>'VIREMENT',3=>'CHEQUE');

		$COMMANDE = '
		<p><strong>Informations commandes :</strong><p>
		<ul>
			<strong>Statut de la commande :</strong> '.$arrayStatutC[$C->V[0]['statut_c']].'<br />
			<strong>Statut du paiement :</strong> '.$arrayStatutP[$C->V[0]['statut_p']].'<br />
			<strong>R&eacute;f&eacute;rence :</strong> '.html(aff($C->V[0]['titre'])).'<br />
			<strong>Date :</strong> '.printDateTime($C->V[0]['datecrea']).'<br />
		</ul>
		<p><strong>Informations paiement :</strong><p>
		<ul>
			<strong>Total transaction (€) :</strong> '.html(aff($C->V[0]['transaction_total'])).'<br />
			<strong>Sous-Total (€) :</strong> '.html(aff($C->V[0]['total'])).'<br />
			<strong>Frais de port (€) :</strong> '.html(aff($C->V[0]['fdp'])).'<br />
			<strong>Poids total (Kilo) :</strong> '.html(aff($C->V[0]['poids'])).'<br />
			<strong>Facture PDF :</strong> '.html(aff($C->V[0]['facture'])).'<br />
			<strong>Mode paiement :</strong> '.html(aff($arrayModeP[$C->V[0]['mode_p']])).'<br />
		</ul>
		<p><strong>Informations client :</strong><p>
		<ul>
			<strong>Civilite :</strong> '.html(aff($C->V[0]['civilite'])).'<br />
			<strong>Nom :</strong> '.html(aff($C->V[0]['nom'])).'<br />
			<strong>Pr&eacute;nom :</strong> '.html(aff($C->V[0]['prenom'])).'<br />
			<strong>Email :</strong> '.html(aff($C->V[0]['email'])).'m<br />
		</ul>
		<p><strong>Adresse de facturation :</strong><p>
		<ul>
			<strong>Adresse :</strong> '.html(aff($C->V[0]['adresse'])).'<br />
			<strong>Code postal :</strong> '.html(aff($C->V[0]['cp'])).'<br />
			<strong>Ville :</strong> '.html(aff($C->V[0]['ville'])).'<br />
			<strong>Pays :</strong> '.html(aff($C->V[0]['pays'])).'<br />
			<strong>Tel :</strong> '.html(aff($C->V[0]['tel'])).'<br />
		</ul>
		<p><strong>Adresse de livraison :</strong><p>
		<ul>
			<strong>Adresse :</strong> '.html(aff($C->V[0]['adresse2'])).'<br />
			<strong>Code postal :</strong> '.html(aff($C->V[0]['cp2'])).'<br />
			<strong>Ville :</strong> '.html(aff($C->V[0]['ville2'])).'<br />
			<strong>Pays :</strong> '.html(aff($C->V[0]['pays2'])).'<br />
			<strong>Zone :</strong> '.html(aff($arr_Zones[$C->V[0]['zone2']])).'<br />
			<strong>Tel :</strong> '.html(aff($C->V[0]['tel2'])).'<br />
		</ul>
		<p><strong>Produits command&eacute;s :</strong></p>
		<ul>';

		$C =& new Q("SELECT * FROM mod_commandes_produits WHERE commande_id='$commande_id' ");
		foreach($C->V as $V) {
			$produit_titre = fetchValues('titre', 'mod_catalogue_produits', 'id', $V['produit_id']);

			if ($V['format'] =='b' || $V['format'] == 'r' || $V['format'] == 'p') { // dur
				$echelle = ', &eacute;ch. '.html(aff($V['echelle']));
			}
			else { // 3D
				$format = ( !empty($V['echelle']) && $V['echelle'] != '-' ? ' - &eacute;ch. '.html(aff($V['echelle'])) : '');
				$echelle = '';
			}			
			
			$COMMANDE .= '<strong>'.$V['quantite'].' '.html(aff($produit_titre)).' :</strong> '.html(aff($arr_Formats[$V['format']])).$echelle.' - '.html(aff($V['prix_total'])).' &euro; TTC<br />';
		}
		$COMMANDE .= '</ul>';
		
		if ($C->V[0]['mode_p'] == 1) {// CB
			$COMMANDE .= '
			<p><strong>Paiement par CB :</strong><p>
			<ul>
				<strong>N° transaction :</strong> '.html(aff($C->V[0]['transaction_id'])).'<br />
				<strong>Mode transaction :</strong> '.html(aff($C->V[0]['transaction_mode'])).'<br />
				<strong>Heure transaction :</strong> '.html(aff($C->V[0]['transaction_heure'])).'<br />
				<strong>Date transaction :</strong> '.html(aff($C->V[0]['transaction_date'])).'<br />
				<strong>Code r&eacute;ponse :</strong> '.html(aff($C->V[0]['response_code'])).'<br />
				<strong>Autorisation ID :</strong> '.html(aff($C->V[0]['authorisation_id'])).'<br />
				<strong>Code devise :</strong> Euro<br />
			</ul>';
		}
		
		$COMMANDE .= '<p>&nbsp;<p>';
		
		// END BUILD TICKET COMMANDE /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		
		// FETCH ALERTE EMAIL
		$P =& new Q("SELECT * FROM dat_email_alertes WHERE id='4' LIMIT 1 ");

		$suject_email = aff($P->V[0]['sujet']);
		$texte_email = aff($P->V[0]['texte']);
		$admin_email = fetchValue('email');
		$email = $_SESSION[SITE_CONFIG]['CLIENT']['email'];
		
		// REPLACE DYN VALUES
		if (strpos($suject_email,'#REF') > -1) $suject_email = str_replace('#REF',$titre,$suject_email);
		if (strpos($texte_email,'#REF') > -1) $texte_email = str_replace('#REF',$titre,$texte_email);
		
		//if (strpos($suject_email,'#COMMANDE') > -1) $suject_email = str_replace('#COMMANDE',$COMMANDE,$suject_email);
		if (strpos($texte_email,'#COMMANDE') > -1) $texte_email = str_replace('#COMMANDE',$COMMANDE,$texte_email);

		// SEND MAIL
		mailto($admin_email,$email,$suject_email,$suject_email,$texte_email); // Email to client

		$Q =& new Q("UPDATE mod_commandes SET mailok='1' WHERE id='$commande_id' ");
		
		$ok = true;
		/////////////////////////////////////////////////////////////////////////////////////////
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr">
<head>
	<title>Administration : <?=$SITE;?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
	<meta name="author" content="molokoloco@gmail.com for Borntobeweb.fr 2007"/>
	<link rel="icon" href="<?=$WWW;?>admin/favicon.ico"/>
	<link rel="shortcut icon" type="image/icon" href="<?=$WWW;?>admin/favicon.ico"/>
	<link href="../style_admin.css.php" rel="stylesheet" type="text/css"/>
	<script type="text/javascript">
	function printIt() {
		if (window.print) window.print();
		else alert("Pour imprimer: Ctrl+P ou sélectionnez IMPRIMER dans le menu FICHIER");
	}
	</script>
	<style type="text/css">
	h1 { color : #CCC; }
	img { vertical-align:middle; margin:10px; }
	.texte { line-height:1.2em; }
	</style>
</head>
<body onload="self.focus();" style="background-color:#FFFFFF;">
<table width="100%"  border="0" cellpadding="15" cellspacing="0" class="arial11">
<tr>
<td align="center"><? if (!$ok) { ?>
	<b>L</b><b>'email a d&eacute;j&agrave; &eacute;t&eacute; envoy&eacute; voulez vous le <a href="send_mail.php?id=<?=$commande_id;?>&mailok=1">r&eacute;-envoyer</a> ? </b><? 
} else { ?>
	<b>Un email de validation viens d'&ecirc;tre envoy&eacute;.<br />
	<!--Le statut de la commande est maintenant &quot;trait&eacute;e&quot;.--></b><?
} ?></td>
</tr>
</table><script language="javascript">setTimeout("self.window.close();", 4000);</script>
</body>
</html>