<? require '../lib/racine.php'; ?>
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

<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="95%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="20%" height="23" nowrap class="table-titre">DEVIS</td>
<td class="table-titre2">&nbsp;&nbsp;<?=$WWW;?> - <?=getDateTime();?></td>
<td width="20%" align="center" class="table-titre2"><a href="javascript:void(0);" onclick="printIt();" class="whiteLink">Imprimer</a></td>
</tr>
<tr align="center">
<td colspan="3" class="bgTableauPcP"></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td class="texte"><?

$devis_id = gpc('devis_id');

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

echo $DEVIS;

?></td>
</tr>
</table><table width="100%" border="0" cellspacing="0" cellpadding="0" id="insertProd" style="display:none;">
<tr>
<td><iframe src="javascript:void(0)" id="actionFrame" name="actionFrame" width="100%" height="100" frameborder="0" allowtransparency="1" scrolling="no"></iframe></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
</table>
</body>
</html>