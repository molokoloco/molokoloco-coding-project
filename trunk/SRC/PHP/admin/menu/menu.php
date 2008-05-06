<?

require_once("../lib/racine.php");

if (!isLocal() && (!isset($_SESSION[SITE_CONFIG]['ADMIN']) || intval($_SESSION[SITE_CONFIG]['ADMIN']['id']) < 1))
	goto('../index.php?redir='.urlencode(thisPage('', '', array('action','info'))));

$menuFlot = FALSE;

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta name="author" content="molokoloco@gmail.com for Borntobeweb.fr 2007">
	<title>Administration : <?=$SITE;?></title>
	<link rel="icon" href="<?=$WWW;?>admin/favicon.ico" />
	<link rel="shortcut icon" type="image/icon" href="<?=$WWW;?>admin/favicon.ico" />
	<link href="../lib/calendar/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css">
	<link href="../style_admin.css.php" rel="stylesheet" type="text/css">
	<link href="../../js/proto/lightboximages/overlay.css" rel="stylesheet" type="text/css">
	<script language="javascript" src="../init.js"></script>
	<script language="javascript" src="../lib/calendar/dhtmlgoodies_calendar.js"></script>
</head>
<body><div id="divNode" style="display:none;"></div>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	<td colspan="2" rowspan="2" valign="top" height="100%">
	<div id="navMenu">
		<? 
		if ($menuFlot) {
			 ?><script language="javascript">
			document.write('<img src="../images/spacer.gif" width="'+layerwidth+'" height="1">');
			if (NS4) {document.write('<LAYER NAME="floatlayer" LEFT="'+floatX+'" TOP="'+floatY+'">');}
			else if ((IE4) || (NS6)) {document.write('<DIV id="floatlayer" style="position:absolute; left:'+floatX+'; top:'+floatY+';">');}
			</script><?
		}
		?><table width="160" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
		<tr>
		<td valign="top"><table width="100%"  border="0" cellpadding="6" cellspacing="0">
		<tr>
		<td height="90" align="center" style="padding:0"><a href="<?=$root;?>" target="_blank" class="titre"><?=$logoc;?></a></td>
		</tr>
		</table></td>
		</tr>
		<tr>
		<td height="24" class="table-titre"><a href="javascript:Effect.toggle('navMenu', 'appear', {afterFinish:function(){Effect.toggle('showmenu');}});" title="Afficher/Masquer le menu" style="color:#FFFFFF;">MENU</a></td>
		</tr><?

		if ($_SESSION[SITE_CONFIG]['ADMIN']['type'] > 0) {
			?><tr>
			<td valign="top" style="border-right:4px solid <?=$bgcolor2;?>;"><?
			$i = 0;
			echo '<table width="100%" border="0" cellpadding="4" cellspacing="0" >';
			while ($i<count($adminMenu)) { 
				echo '<tr>
				<td nowrap class="bgTableauPcP" onMouseOver="this.style.backgroundColor=\''.$bgcolor1.'\';" onMouseOut="this.style.backgroundColor=\'\';" style="border-bottom:1px solid '.$bgcolor1.';">';
				if (strpos($selfDir,'/'.$adminMenu[$i]) !== false) { $b='<b>'; $bf='</b>'; } else { $b = $bf = ''; }
				if (strpos($adminMenu[$i+1],'-') !== false) echo '&nbsp;&nbsp;&nbsp;-&nbsp;'; 
				if ($adminMenu[$i] != '') echo '&nbsp;<a href="../'.$adminMenu[$i].'">';
				else echo '<span class="sstitre"><img src="../images/flech_menu.png" width="12" height="12" border="0" align="absmiddle">'; 
				echo $b.str_replace('-','',$adminMenu[$i+1]).$bf;
				if ($adminMenu[$i] != '') echo '</a>'; else echo '</span>';
				echo '</td>
				</tr>';
				$i += 2;
			}
			echo '</table>'; 
			?></td>
			</tr><?
		}
		
		?><tr>
		<td style="border-top:4px solid <?=$bgcolor2;?>;">&nbsp;</td>
		</tr>
		<tr>
		<td height="25" nowrap>&nbsp;&nbsp;<img src="../images/navigation/flech_dr.png" width="14" height="14" align="absmiddle">&nbsp;<a href="<?=$root;?>" target="_blank" class="sstitre">Acc&egrave;s au site</a></td>
		</tr>
		<tr>
		<td height="25" nowrap>&nbsp;&nbsp;<img src="../images/navigation/flech_dr.png" width="14" height="14" align="absmiddle">&nbsp;<a href="../index.php?action=LOGOUT" class="sstitre">D&eacute;connexion</a></td>
		</tr>
		<tr>
		<td height="60" nowrap  >&nbsp;</td>
		</tr>
		</table>
		<?
		if ($menuFlot) {
			?><script language="javascript">
			if (NS4) { document.write('<layer style="clear:both"></layer></LAYER>'); }
			else if ((IE4) || (NS6)) { document.write('<div style="clear:both"></div></DIV>'); }
			ifloatX=floatX;ifloatY=floatY;
			define();
			window.onresize=define;
			lastX=-1;lastY=-1;
			adjust();
			</script><?
		}
		?>
	</div>
	
	<div id="showmenu" style="display:none; width:24px; height:100%; cursor:pointer;" class="table-titre" onClick="Effect.toggle('showmenu', 'appear', {afterFinish:function(){Effect.toggle('navMenu');}});" title="Afficher/Masquer le menu"><br />
		M<br />
		E<br />
		N<br />
		U
	</div></td>
<td width="100%" height="500" valign="top"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<td height="90" align="right" valign="bottom" class="texte"><a href="mailto:julien.guezennec@gmail.com">Contact Technique</a><br />
<a href="http://ns24436.ovh.net/phpmyadmin/" target="_blank">Interface MySQL</a><br />
&nbsp;</td>
<td width="90" align="right"><a href="mailto:julien.guezennec@gmail.com" style="padding-right:10px;" title="Virtual Admin 2008"><?=$logoa;?></a></td>
</tr>
</table>