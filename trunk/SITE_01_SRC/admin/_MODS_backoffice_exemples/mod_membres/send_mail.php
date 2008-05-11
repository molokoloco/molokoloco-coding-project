<?
require("../lib/racine.php");

$id = intval(gpc('id'));

$erreur = '';
if ($id > 0) {

	$R =& new Q("SELECT * FROM mod_membres WHERE id='$id' LIMIT 1");
	$V = $R->V[0];
	
	if (empty($V['email'])) $erreur .= 'Il manque l\'adresse email<br />';
	if (empty($V['login'])) $erreur .= 'Il manque le login<br />';
	if (empty($V['password'])) $erreur .= 'Il manque le password<br />';
	
	if (!$erreur) {
		list($suject_email, $texte_email) = fetchAlerte(1, array('#LOGIN'=>$V['login'], '#MDP'=>$V['password']));
		$email_admin = fetchValue('email');
		mailto($email_admin, $V['email'], $suject_email, '', aff($texte_email), $email_admin);
		
		// Set valide
		$champs = array(
			array('actif', '2')
		);
		$G =& new SQL('mod_membres');
		$G->updateSql($champs," id='$id' LIMIT 1 ");
	}
}
else $erreur .= 'Il manque l\'ID<br />';
?>
<html>
<head>
<title>Email</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="../../css/styles.css" rel="stylesheet" type="text/css"></head>
<body onLoad="self.focus();">
<table width="100%"  border="0" cellpadding="15" cellspacing="0" class="arial11">
<tr>
<td align="center"><? if ($erreur) { ?>
	<b>Erreur :<br />
	<?=aff($erreur);?></b><? 
} else { ?>
	<b>Un email avec les informations personnelles de &quot;<?=aff($V['login']);?>&quot;<br />
	viens d'&ecirc;tre envoy&eacute;.</b><?
} ?></td>
</tr>
</table><script language="javascript">setTimeout("window.opener.location.reload(); self.window.close();", 4000);</script>
</body>
</html>