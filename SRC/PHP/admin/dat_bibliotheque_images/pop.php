<? include_once("../menu/menu_light.php"); ?><?

// REQUIRE / INDEX.PHP / INSER AND UPDATE TO DO

$input = clean(urldecode($_GET['input']));
$cat_id = intval($_GET['cat_id']);

if ($cat_id < 1) {
	$C = new SQL('dat_bibliotheque_images_cat'); 
	$C->LireSql(array('*'),"  id!='' ORDER BY ordre DESC LIMIT 1 ");
	$cat_id = $C->V[0]['id'];
}

require('../dat_bibliotheque_images/data.php');
$imageDir = $root.$R2['rep'];


?><script type="text/javascript">
<!--
var addImg = function(id, img) {
	var inputSelect = opener.document.getElementById('<?=$input;?>');
	inputSelect.value = id;
	var imgSrcSelect = opener.document.getElementById('<?=$input;?>_img');
	imgSrcSelect.innerHTML = '<a href="javascript:void(0);" onclick="popImg(\'<?=$imageDir;?>grand/'+img+'\');"><img src="<?=$imageDir;?>mini/'+img+'" alt="" title="Agrandir l\'image" border="0" class="bor1"></a>';
	self.window.close();
};
var makeInsert = function() {
	var cat_id = $('cat_id').options[$('cat_id').selectedIndex].value;
	$('actionFrame').src = 'index.php?mode=fiche&cat_id='+cat_id+'&child=0&id=0';
	Effect.toggle('insertProd');
};
//-->
</script><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="95%" valign="top"><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="20%" height="23" nowrap class="table-titre">Biblioth&egrave;que d'images </td>
<td class="table-titre2">&nbsp;&nbsp;Cat&eacute;gorie : <?
$m = new menuSelect('cat_id','dat_bibliotheque_images_cat');
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
<td colspan="2" align="center" class="table-bas" id="valider2" height="25">Cliquez sur une image pour la s&eacute;lectionner </td>
</tr>
<tr>
<td colspan="2" align="right" valign="top" nowrap class="table-entete1"><?

$C =& new Q("SELECT * FROM dat_bibliotheque_images WHERE cat_id='$cat_id' ORDER BY id DESC"); 

$i = 0;
$col = 5;
$pct = intval(100/$col).'%';
$table_images = '<table width="100%" border="0" cellpadding="4" cellspacing="1" class="table-sstitre">';
foreach($C->V as $V)  {
	
	$m =& new FILE();
	if (!$m->isMedia($imageDir.'mini/'.$V['image'])) continue;
	$m->css = 'bor1';

	if ($i == 0) $table_images .= '<tr>'; // FIRST LIGNE
	else if ($i % $col == 0) $table_images .= '</tr><tr>'; // INTER-LIGNE
	$table_images .= '<td width="'.$pct.'" align="center" valign="middle" bgcolor="#F4F4F4" onMouseOver="this.style.backgroundColor=\'#BBBBBB\';" onMouseOut="this.style.backgroundColor=\'\';" onClick="addImg(\''.$V['id'].'\',\''.$V['image'].'\');" style="cursor:pointer;" title="Ajouter cette image">'.$m->image(FALSE).'</td>';

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
<td height="25" colspan="2" align="center" nowrap class="table-bas">Cliquez sur une image pour la s&eacute;lectionner </td>
</tr>
</table>
<br /></td>
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
<? include_once("../menu/menu_bas.php"); ?>