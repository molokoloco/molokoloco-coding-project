<? include_once("../menu/menu_light.php"); ?><?

// REQUIRE / INDEX.PHP / INSER AND UPDATE TO DO

$input = clean(urldecode($_GET['input']));
$cat_id = intval($_GET['cat_id']);

if ($cat_id < 1) {
	$C =& new Q("SELECT id FROM dat_bibliotheque_fichiers_cat ORDER BY ordre DESC LIMIT 1");
	$cat_id = $C->V[0]['id'];
}

require('../dat_bibliotheque_fichiers/data.php');
$fileDir = $root.$R2['rep'];

?><script type="text/javascript">
<!--
var addFile = function(id, file) {
	var inputSelect = opener.document.getElementById('<?=$input;?>');
	inputSelect.value = id;
	var fileSrcSelect = opener.document.getElementById('<?=$input;?>_doc');
	fileSrcSelect.innerHTML = '<a href="<?=$fileDir;?>'+file+'" target="_blank">'+file+'</a>';
	self.window.close();
};
var makeInsert = function() {
	var cat_id = $('cat_id').options[$('cat_id').selectedIndex].value;
	$('actionFrame').src = 'index.php?mode=fiche&cat_id='+cat_id+'&child=0&id=0';
	Effect.toggle('insertProd');
};
//-->
</script><div id="divNode" style="display:none;"></div>
<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="95%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="20%" height="23" nowrap class="table-titre">Biblioth&egrave;que DE FICHIERs </td>
<td class="table-titre2">&nbsp;&nbsp;Cat&eacute;gorie : <?
$m = new menuSelect('cat_id','dat_bibliotheque_fichiers_cat');
$m->where = " 1 ORDER BY ordre DESC ";
$m->valeur = 'id';
$m->titre = 'titre';
$m->selected = $cat_id;
$m->url = 'pop.php?&input='.$input.'&cat_id=';
$m->printMenuSelect();
?></td>
<td width="20%" align="center" class="table-titre2"><a href="index.php" onclick="window.open('index.php');self.window.close();" target="_blank"><strong>Ajouter un fichier</strong></a></td>
</tr>
<tr align="center">
<td colspan="3" class="bgTableauPcP"></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td class="texte"><br />
<table width="100%" border="0" cellpadding="2" cellspacing="1" class="bor1">
<tr>
<td colspan="2" align="center" class="table-bas" id="valider2" height="25">Cliquez sur un fichier pour le s&eacute;lectionner </td>
</tr>
<tr>
<td colspan="2" align="right" valign="top" nowrap class="table-entete1"><?

$C =& new Q("SELECT * FROM dat_bibliotheque_fichiers WHERE cat_id='$cat_id' ORDER BY date DESC"); 

$i = 0;
$col = 5;
$pct = intval(100/$col).'%';
$table_images = '<table width="100%" border="0" cellpadding="4" cellspacing="1" class="table-sstitre">';
foreach($C->V as $V)  {
	
	$m =& new FILE();
	if (!$m->isMedia($fileDir.$V['fichier'])) continue;
	//$m->css = 'bor1';

	if ($i == 0) $table_images .= '<tr>'; // FIRST LIGNE
	else if ($i % $col == 0) $table_images .= '</tr><tr>'; // INTER-LIGNE
	
	$table_images .= '<td width="'.$pct.'" valign="top" bgcolor="#F4F4F4" onMouseOver="this.style.backgroundColor=\'#BBBBBB\';" onMouseOut="this.style.backgroundColor=\'\';" onClick="addFile(\''.$V['id'].'\',\''.$V['fichier'].'\');" style="cursor:pointer; font-weight:normal; text-align:left;" title="Ajouter ce fichier : '.$V['titre_fr'].'"><img border="0" align="absmiddle" src="../images/icons/'.$m->ext.'.gif"/> '.$m->cname.' (.'.$m->ext.', '.$m->size.')</td>';
	
	if (($i+1) == $total_element_type) {
		$i++; while($i % $col != 0) { $table_images .= '<td bgcolor="#F4F4F4">&nbsp;</td>'; $i++; }
		$table_images .= '</tr>';
	}
	$i++;
}
$table_images .= '</table>';

echo $table_images;

?></td>
</tr>
<tr>
<td height="25" colspan="2" align="center" nowrap class="table-bas">Cliquez sur un fichier pour le s&eacute;lectionner </td>
</tr>
</table>
<br /></td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" id="insertProd" style="display:none;">
<tr>
<td><iframe src="javascript:void(0)" id="actionFrame" name="actionFrame" width="100%" height="100" frameborder="0" allowtransparency="1" scrolling="no"></iframe></td>
</tr>
</table></td>
</tr>
</table></td>
</tr>
</table>
<? include_once("../menu/menu_bas.php"); ?>