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
<td width="20%" height="23" nowrap class="table-titre">COMMANDES</td>
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

$commande_id = gpc('commande_id');

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

echo $COMMANDE;

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