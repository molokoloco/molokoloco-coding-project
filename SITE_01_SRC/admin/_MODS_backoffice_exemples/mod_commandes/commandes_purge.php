<?
require("../lib/racine.php");

if ($_GET['statut_c'] == 'non_valide') {
	$A = new Q("DELETE FROM commandes WHERE statut_c=1 OR statut_p=0 ");
	$cmd_sup = mysql_affected_rows();
}
else {
	$A = new Q("DELETE FROM commandes WHERE statut_c=0 ");
	$cmd_sup = mysql_affected_rows();
}

?><html>
<head>
<title>Administration : <?=$SITE;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<? require("../style.php"); ?>
</head>
<body onLoad="self.focus();">
<table  border="0" align="center" cellpadding="15" cellspacing="0" class="texte">
<tr>
<td height="40"><?
if ($_GET['statut_c'] == 'non_valide') {
	?><b><?=$cmd_sup;?> commandes non valides</b>  ont &eacute;t&eacute; effac&eacute;<br><?
}
else { 
	?><b><?=$cmd_sup;?> commandes temporaires</b> ont &eacute;t&eacute; effac&eacute;<br><br>
	Vous pouvez aussi effacer toutes les commandes <a href="commandes_purge.php?statut_c=non_valide"><b>non valides</b></a><?
}
?></td>
</tr>
</table><br>
<div align="center"><a href="javascript:self.window.close();" class="verdana11 lienvert">Fermer la fen&ecirc;tre</a></div>
</body>
</html>