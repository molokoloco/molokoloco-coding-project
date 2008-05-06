<? require("lib/racine.php");

$redir = gpc('redir');
$redir = ( empty($redir) || $redir == 'index.php' ? $redir = './menu/index.php' : urldecode($redir) );

if (isLocal()) {
	$_SESSION[SITE_CONFIG]['ADMIN']['id'] = 1; // AUTO CONNECT IF LOCAL :) // !!!!!!!!!!!!!!!!!!!!!!!!!!
	$_SESSION[SITE_CONFIG]['ADMIN']['type'] = 3;
}
if (isset($_GET['action']) && $_GET['action'] == 'LOGIN') {
	
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	
	$R =& new Q("SELECT * FROM admin_utilisateurs WHERE login='$login' AND password='$password' AND actif = '1' LIMIT 1");

	if (count($R->V) < 1) $_SESSION[SITE_CONFIG]['info'] = 'Mauvais login et/ou mot de passe';
	else {
		$_SESSION[SITE_CONFIG]['ADMIN'] = $R->V[0];
		goto('./'.$redir);
	}
}
elseif (isset($_GET['action']) && $_GET['action'] == 'LOGOUT') {

	$_SESSION[SITE_CONFIG] = NULL; // Reset EveryThing

}
elseif (isset($_SESSION[SITE_CONFIG]['ADMIN']['id']) && $_SESSION[SITE_CONFIG]['ADMIN']['id'] > 0) { // Auto redir ?
	goto('./'.$redir);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="style_admin.css.php" rel="stylesheet" type="text/css" />
<title>Identification</title>
</head>

<body>
	<br />
	<br />
	<br />
	<form id="frmlog" name="frmlog" method="post" action="index.php?action=LOGIN&amp;redir=<?=urlencode($redir);?>" style="margin: 0px; padding: 0px;">
	<table width="390" border="0" align="center" cellpadding="2" cellspacing="1" class="tablebor text">
	<tr align="center">
	<td colspan="2" nowrap="nowrap" class="table-sstitre"><strong>IDENTIFICATION</strong></td>
	</tr>
	<? if ($_SESSION[SITE_CONFIG]['info']) {
		$intitule = $_SESSION[SITE_CONFIG]['info'];
		echo '<tr class="table-ligne1">
		<td colspan="2" nowrap="nowrap">';
		require ('lib/actions_infos.php');
		echo '</td>
		</tr>';
	}
	?><tr class="table-ligne1">
	<td align="right"><strong>Login :</strong> </td>
	<td><input name="login" id="login" type="text" size="30" maxlength="150"/></td>
	</tr>
	<tr class="table-ligne1">
	<td align="right"><strong>Mot de passe :</strong> </td>
	<td><input name="password" type="password" id="pass" size="30" maxlength="50"/></td>
	</tr>
	<tr class="table-ligne1">
	<td align="center">&nbsp;</td>
	<td><input type="submit" name="Submit" value="Connexion" /></td>
	</tr>
	</table>
	</form>
	<? js(" document.getElementById('login').focus(); "); ?>
</body>
</html><?
$_SESSION[SITE_CONFIG]['info'] = NULL;
?>