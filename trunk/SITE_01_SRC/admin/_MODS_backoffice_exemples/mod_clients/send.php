<?
require("../lib/racine.php");

$client_id = intval($_GET['id']);

if ($client_id > 0) {

	
	//checkRef();
	
	// GET CLIENT
	$A = new SQL('mod_clients');
	$A->LireSql(array('*')," id='$client_id' LIMIT 1 ");
	$civilite = Aff($A->V[0]['civilite']);
	$nom = Aff($A->V[0]['nom']);
	$prenom = Aff($A->V[0]['prenom']);
	$email = Aff($A->V[0]['email']);
	$m2p = Aff($A->V[0]['m2p']);


	$P =& new Q("SELECT * FROM dat_email_alertes WHERE id='1' LIMIT 1 "); // Envois des informations de connexion
	
	$suject_email = aff($P->V[0]['sujet']);
	$texte_email = aff($P->V[0]['texte']);
	$admin_email = fetchValue('email');

	// REPLACE DYN VALUES
	if (strpos($suject_email,'#civilite') > -1) $suject_email = str_replace('#civilite',$civilite,$suject_email);
	if (strpos($texte_email,'#civilite') > -1) $texte_email = str_replace('#civilite',$civilite,$texte_email);
	
	if (strpos($suject_email,'#nom') > -1) $suject_email = str_replace('#nom',$nom,$suject_email);
	if (strpos($texte_email,'#nom') > -1) $texte_email = str_replace('#nom',$nom,$texte_email);
	
	if (strpos($suject_email,'#prenom') > -1) $suject_email = str_replace('#prenom',$prenom,$suject_email);
	if (strpos($texte_email,'#prenom') > -1) $texte_email = str_replace('#prenom',$prenom,$texte_email);
	
	if (strpos($suject_email,'#email') > -1) $suject_email = str_replace('#email','<a href="mailto:'.$email.'">'.$email.'</a>',$suject_email);
	if (strpos($texte_email,'#email') > -1) $texte_email = str_replace('#email','<a href="mailto:'.$email.'">'.$email.'</a>',$texte_email);
	
	if (strpos($suject_email,'#m2p') > -1) $suject_email = str_replace('#m2p', $m2p, $suject_email);
	if (strpos($texte_email,'#m2p') > -1) $texte_email = str_replace('#m2p', $m2p, $texte_email);

	//if (strpos($texte_email,'#confirme') > -1) $texte_email = str_replace('#confirme','<a href="'.$WWW.'client.php?menu=email&amp;action=CONFIRME&email='.$email.'&ids='.$ids.'">'.$WWW.'client.php?menu=email&amp;action=CONFIRME&email='.$email.'&ids='.$ids.'</a>',$texte_email);
	//if (strpos($texte_email,'#adminEmail') > -1) $texte_email = str_replace('#adminEmail','<a href="mailto:'.$admin_email.'">'.$admin_email.'</a>',$texte_email);
	
	// SEND MAIL
	mailto($admin_email,$email,$suject_email,$suject_email,$texte_email);

	$ok = true;

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
<td align="center"><b>Un email de rappel viens d'&ecirc;tre envoy&eacute;.</b></td>
</tr>
</table><script language="javascript">setTimeout("self.window.close();", 4000);</script>
</body>
</html>