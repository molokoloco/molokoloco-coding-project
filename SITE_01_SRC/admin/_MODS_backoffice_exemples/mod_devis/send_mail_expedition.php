<?
$devis_id = intval($_GET['id']);
$ok = false;
$noExp = false;
if ($devis_id > 0) {

	require("../lib/racine.php");
	//checkRef();
	
	// FETCH DEVIS ///////////////////////////////////////////////////////////////////////////////////////
	$A = new SQL('mod_devis');
	$A->LireSql(array('*')," id='$devis_id' LIMIT 1 ");
	$client_id = Aff($A->V[0]['cat_id']);
	
	$ref = Aff($A->V[0]['titre']);
	$mailok = Aff($A->V[0]['mailok']); 
	$mailok2 = clean($_GET['mailok']); // Prevenir si mailok mais essaye de renvoyer un nouveau ?
	if ($mailok2 == '2') $mailok = 1;


	$EXPEDITION = '';
	if (!empty($A->V[0]['dateexpe'])) $EXPEDITION = printDateTime($A->V[0]['dateexpe']);
	else $noExp = true;
	
	// GET CLIENT
	$A = new SQL('mod_clients');
	$A->LireSql(array('*')," id='$client_id' LIMIT 1 ");
	$client_civilite = Aff($A->V[0]['civilite']);
	$nom = Aff($A->V[0]['nom']);
	$client_prenom = Aff($A->V[0]['prenom']);
	$client_client = $civilite.' '.$prenom.' '.$nom;
	$client_email = Aff($A->V[0]['email']);

	if ($mailok != 2 && !$noExp) {

		// BUILD TICKET DEVIS /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		$C =& new Q("SELECT * FROM mod_devis WHERE id='$devis_id' LIMIT 1");

		$arrayStatutC = array(1=>'Non valide', 2=>'Attente du fichier', 3=>'A traiter', 4=>'Traitée');
		$arrayStatutP = array(0=>'Non valide', 1=>'Valide');
		$arrayModeP = array(1=>'CB', 2=>'VIREMENT', 3=>'CHEQUE');
		$arraySouhaits = array(1=>'Mat&eacute;rialiser un fichier 3D', 2=>'Cr&eacute;er une r&eacute;plique', 3=>'Cr&eacute;er un fichier 3D');
		
		$DEVIS = '
		<p><strong>Informations devis :</strong><p>
		<ul>
			<strong>Statut du devis :</strong> '.$arrayStatutC[$C->V[0]['statut_c']].'<br />
			<strong>Statut du paiement :</strong> '.$arrayStatutP[$C->V[0]['statut_p']].'<br />
			<strong>R&eacute;f&eacute;rence :</strong> '.html(aff($C->V[0]['titre'])).'<br />
			<strong>Date :</strong> '.printDateTime($C->V[0]['datecrea']).'<br />
		</ul>
		<p><strong>Informations demande :</strong><p>
		<ul>
			<strong>Souhaits :</strong> '.aff($arraySouhaits[$C->V[0]['souhaits']]).'<br />
			<strong>Nom fichier :</strong> '.html(aff($C->V[0]['nom_fichier'])).'<br />
			<strong>Format du fichier :</strong> '.html(aff($arr_Fichier_Formats[$C->V[0]['format_fichier']])).'<br />
			<strong>Fichier :</strong> '.( $C->V[0]['fichier'] ? affCleanName(aff($C->V[0]['fichier'])) : '-' ).'<br />
		</ul>
		<p><strong>Informations client :</strong><p>
		<ul>
			<strong>Civilite :</strong> '.html(aff($C->V[0]['civilite'])).'<br />
			<strong>Nom :</strong> '.html(aff($C->V[0]['nom'])).'<br />
			<strong>Pr&eacute;nom :</strong> '.html(aff($C->V[0]['prenom'])).'<br />
			<strong>Email :</strong> '.html(aff($C->V[0]['email'])).'m<br />
		</ul>
		<p><strong>Adresse :</strong><p>
		<ul>
			<strong>Adresse :</strong> '.html(aff($C->V[0]['adresse'])).'<br />
			<strong>Code postal :</strong> '.html(aff($C->V[0]['cp'])).'<br />
			<strong>Ville :</strong> '.html(aff($C->V[0]['ville'])).'<br />
			<strong>Pays :</strong> '.html(aff($C->V[0]['pays'])).'<br />
			<strong>Tel :</strong> '.html(aff($C->V[0]['tel'])).'<br />
		</ul>
		';
		
		$DEVIS .= '<p>&nbsp;<p>';
		
		// END BUILD TICKET DEVIS /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

		// FETCH ALERTE EMAIL
		$P =& new Q("SELECT * FROM dat_email_alertes WHERE id='7' LIMIT 1 ");

		$suject_email = aff($P->V[0]['sujet']);
		$texte_email = aff($P->V[0]['texte']);
		$admin_email = fetchValue('email');
		$email = $_SESSION[SITE_CONFIG]['CLIENT']['email'];
		
		// REPLACE DYN VALUES
		if (strpos($suject_email,'#REF') > -1) $suject_email = str_replace('#REF',$titre,$suject_email);
		if (strpos($texte_email,'#REF') > -1) $texte_email = str_replace('#REF',$titre,$texte_email);
		
		//if (strpos($suject_email,'#DEVIS') > -1) $suject_email = str_replace('#DEVIS',$DEVIS,$suject_email);
		if (strpos($texte_email,'#DEVIS') > -1) $texte_email = str_replace('#DEVIS',$DEVIS,$texte_email);

		if (strpos($texte_email,'#EXPEDITION') > -1) $texte_email = str_replace('#EXPEDITION',$EXPEDITION,$texte_email);

		// SEND MAIL
		mailto($admin_email,$email,$suject_email,$suject_email,$texte_email); // Email to client

		$Q =& new Q("UPDATE mod_devis SET mailok='2', statut_c='4' WHERE id='$devis_id' ");
		
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
<td align="center"><?
if ($noExp){ ?>
	<b>Avant de faire cet action, il faut préciser la date de l'expédition</b><? 
} elseif (!$ok) { ?>
	<b>L'email a d&eacute;j&agrave; &eacute;t&eacute; envoy&eacute; voulez vous le <a href="send_mail.php?id=<?=$devis_id;?>&mailok=2">r&eacute;-envoyer</a> ? </b><? 
} else { ?>
	<b>Un email de validation d'expédition viens d'&ecirc;tre envoy&eacute;.<br />
	Le statut de la devis est maintenant &quot;trait&eacute;e&quot;.</b><?
} ?></td>
</tr>
</table><script language="javascript">setTimeout("self.window.close();", 4000);</script>
</body>
</html>