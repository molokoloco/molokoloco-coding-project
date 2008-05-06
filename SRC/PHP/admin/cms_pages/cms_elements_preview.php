<?

if (!isset($WWW)) { // Direct call ?
	require('../lib/racine.php');
	require_once('cms_fonctions.php');
	
	setIsoHeader();
	
	$rubrique_id = gpc('id');
	$element_id = gpc('element_id');
}

?><table width="100%"  border="0" cellpadding="4" cellspacing="0"  class="bgTableauTitre">
<tr>
<td height="20">&nbsp;Pr&eacute;visualisation&nbsp;<img src="../images/flech_show.png" width="14" height="14" align="absmiddle" /></td>
</tr>
</table>

<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="texte1">
<tr><td height="20">&nbsp;</td></tr>
</table>

<table width="100%" border="0" cellpadding="2" cellspacing="1" class="bor1">
<tr>
<td align="center" nowrap class="table-sstitre"><?

if ($element_id > 0) {
	$element_type_id = fetchValues('type_id', 'cms_pages_elements', 'id', $element_id); 
	echo 'El&eacute;ment &quot;'.fetchValues('titre', 'cms_elements_types', 'id', $element_type_id).'&quot;'; 
}
else { 
	?>Pages &quot;<?=aff(fetchValues('titre_fr','cms_pages','id', $rubrique_id));?>&quot;<!-- [<a href="<?=$root;?>page-r<?=$rubrique_id;?>.html" target="_blank">Afficher dans le site</a>]--><?
}
?></td>
</tr>
<tr>
<td align="right" valign="top" nowrap class="table-entete2"><table width="100%" border="0" cellspacing="0" cellpadding="6">
<tr>
<td width="100%" align="center"><iframe name="iframe_element" id="iframe_element" src="cms_iframe_element.php?id=<?=$rubrique_id;?>&element_id=<?=$element_id;?>" allowtransparency="1" frameborder="0" scrolling="auto" width="100%" height="30" class="bor1"></iframe></td>
</tr>
</table></td>
</tr>
<tr>
<td height="25" align="center" nowrap class="table-bas legende">Largeur de page dans le site : <strong><?=$frontPageWidth;?></strong> pixels</td>
</tr>
</table>