<?
require_once('cms_fonctions.php');

$action = gpc('action');
$rubrique_id = gpc('id');
$element_id = gpc('element_id');
$element_type_id = gpc('element_type_id');

// Stock langue at each change (Pas besoin de ballader la variable partout)
if (empty($_SESSION[SITE_CONFIG]['element_langue'])) $_SESSION[SITE_CONFIG]['element_langue'] = $langues[0]; // 1ere langue par defaut
$element_langue = gpc('element_langue');
if (!empty($element_langue)) $_SESSION[SITE_CONFIG]['element_langue'] = $element_langue;
else $element_langue = $_SESSION[SITE_CONFIG]['element_langue'];

// ---------- Fetch all Types of elements -------------------------//
$elements_type = getElementsType(); ### db($elements_type);
$total_element_type = count($elements_type);

$arr_type = array(
	'0'=> 'Titre',
	'1'=> 'Texte',
	'2'=> 'Liste',
	'3'=> 'Média',
	'4'=> 'Divers',
);

/*
	// Build table for bouttons elements types	
	$col = 11;
	$pct = intval(100/$col).'%';
	$i = 0;
	$table_elements_type = '<table width="100%" border="0" cellpadding="0" cellspacing="1" class="table-sstitre">';
	foreach($elements_type as $element_type_idSel=>$V)  { 
		if ($V['actif'] == 0) {
			$total_element_type--;
			continue;
		}
		if ($i == 0) $table_elements_type .= '<tr>'; // FIRST LIGNE
		else if ($i % $col == 0) $table_elements_type .= '</tr><tr>'; // INTER-LIGNE
		
		$table_elements_type .= '<td width="'.$pct.'" align="center" bgcolor="#F4F4F4" onMouseOver="this.style.backgroundColor=\'#BBBBBB\';" onMouseOut="this.style.backgroundColor=\'\';"><a href="javascript:addElement(\''.addslashes($V['titre']).'\',\''.$element_type_idSel.'\',\''.$rubrique_id.'\',\''.$element_langue.'\');" class="elementLink">'.aff($V['titre']).'</a></td>';
		$i++;
		if ($i == $total_element_type-2) {
			while($i % $col != 0) { $table_elements_type .= '<td bgcolor="#F4F4F4">&nbsp;</td>'; $i++; }
			$table_elements_type .= '</tr>';
		}
	}
	$table_elements_type .= '</table>';
*/


// ---------- Make some action ? -------------------------//
if (!empty($action)) require('cms_element_action.php');


// ---------- Fetch all elements of this rubrique -------------------------//
$E =& new Q("SELECT * FROM cms_pages_elements WHERE pid='$rubrique_id' AND langue='$element_langue' ORDER BY ordre ASC");
$total_element = count($E->V);


?><style>
ul#element_list, ul#form_list {
	margin:0px;
	padding:0;
	overflow:hidden;
	zoom:1;
}	
ul#element_list li, ul#form_list li {
	margin:0px;
	padding:0;
	list-style:none;
}
.move {
	cursor:n-resize;
}

.effelementcheck {
	height:18px;
	border: none;
	background:none;
}
.wastebin_active {
	background:#BBBBBB;
}


/* TABS */
#tabs {
	margin:0px;
	padding:0;
	overflow:hidden;
	zoom:1;
}
#tabs li {
	margin:0px;
	padding:0;
	list-style:none;
	float:left;
}
#tabs a.active-tab {
	background-color:#BBBBBB;
	color:#FFF;
}
#tabs a {
	height:20px;
	background-color:#CCCCCC;
	color:#666666;
	float:left;
	text-decoration:none;
	padding:2px 10px;
	line-height:20px;
	border-right:1px solid #666666;
	border-top:1px solid #666666;
	border-bottom:1px solid #666666;
}
#tabs li.first {
	border-left:1px solid #666666;
}
#tabs li.last {
}
.panel {
	clear:both;
	display:none;
	margin-top:-1px;
}
.active-tab-body {
	display:block;
}
#container a.elementLink {
	vertical-align:middle;
	color:#666666;
	font:11px Arial;
	height:25px;
	padding:4px 10px;
	line-height:25px;
	border-right:1px solid #999999;
	border-bottom:1px solid #999999;
	border-top:1px solid #666666;
}
#container a.first {
	border-left:1px solid #999999;
}
#container a.elementLink:hover {
	color:#FFF;
	background-color:#BBBBBB;
}

</style>
<script language="javascript" type="text/javascript" src="../lib/tinymce/tiny_mce.js"></script>

<table width="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top">

	<table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="24" nowrap class="table-titre"><a href="index.php?mode=cms&id=<?=$rubrique_id;?>" class="whiteLink">EDITION DE PAGE</a></td>
	<td width="67%" class="table-titre2" align="center"></td>
	</tr>
	<tr align="center">
	<td colspan="2" class="bgTableauPcP"></td>
	</tr>
	</table>

<table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr>
<td class="texte">

	<table width="100%"  border="0" cellpadding="2" cellspacing="0" class="texte">
	<tr>
	<td align="right" nowrap><b>Pages du site&nbsp;:</b></td>
	<td width="1" nowrap="nowrap"><?

	// FETCH ARRAY ALL ARBO
	require_once('../lib/class/class_arbo.php');
	$S =& new ARBO();
	$S->fields = array('id', 'pid', 'type_id', 'titre_fr');
	$S->buildArbo();
	
	$menu = ''; // GLOBAL
	function getMenuChild($arrayChilds, $selectedValue) {
		global $S;
		global $menu, $linkcoloron, $cmsPageTypeId, $accueilPageTypeId;

	$minNiveau = 0;
		$maxNiveau = 4;
	$gris = '#808080';
	
		if (empty($arrayChilds) || !is_array($arrayChilds)) return;

		foreach($arrayChilds as $rid) {
			$V = $S->arbo[$rid];
			
			if ($V['type_id'] == $accueilPageTypeId) continue;

		$niveau = count($V['parents']);
		$CoulTranche = 1/($maxNiveau+1);
		$rgb = html2rgb($gris); // Gris 50 % 
		$rgb[0] = $rgb[0]*(1+($niveau*$CoulTranche));
		$rgb[1] = $rgb[1]*(1+($niveau*$CoulTranche));
		$rgb[2] = $rgb[2]*(1+($niveau*$CoulTranche));
		$rgb = rgb2html($rgb);
			$esp = str_repeat('-', $niveau);

			$style = 'style="background:'.($rid==$selectedValue ? $linkcoloron : $rgb).';"';
			if ($V['type_id'] == $cmsPageTypeId) // || $V['type_id'] == $accueilPageTypeId
				$menu .= '<option value="'.$V['id'].'" '.($rid==$selectedValue?'selected':'').' '.$style.'>'.$esp.' '.aff($V['titre_fr']).'</option>';
			else
				$menu .= '<optgroup label="&nbsp;'.$esp.' '.aff($V['titre_fr']).'" '.$style.'>'.$esp.' '.aff($V['titre_fr']).'</optgroup>';
		
			if ($V['type_id'] != $accueilPageTypeId) $menu .= getMenuChild($S->arbo[$rid]['childs'], $selectedValue);
		}
	}
	$arbo = array_merge(array($S->arid), $S->arbo[$S->arid]['childs']);
		
	getMenuChild($arbo, $rubrique_id);
	
	?><select onchange="window.location='index.php?mode=cms&id='+this.options[this.selectedIndex].value;" size="1" id="rubrique_id" name="rubrique_id">
	<option>Choisir une page -&gt;</option>
	<?=$menu;?>
	</select></td>
	<td nowrap="nowrap"><a href="../cms_pages/">Retour</a></td>
	
	<? if (count($langues) > 1) { ?>
	<td width="20" nowrap="nowrap">&nbsp;</td>
	<td align="right" nowrap="nowrap"><strong>Version</strong>&nbsp;</td>
	<td nowrap="nowrap"><?
	
	$select = array();
	foreach($langues as $langue) $select[] = array($langue, $langue);
	
	$m = new menuSelect('element_langue', $select);
	$m->first = '';
	$m->selected = $element_langue;
	$m->url = 'index.php?mode=cms&id='.$rubrique_id.'&element_langue=';
	$m->printMenuSelect();
	
	?></td>
	<? } ?>
	
	<td width="20" nowrap="nowrap">&nbsp;</td>

	<td width="75%" align="right" nowrap="nowrap"><table border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td align="right"><table  border="0" cellspacing="0" cellpadding="0" id="ENREGISTRER" style="display:none;">
	<tr>
	<td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
	<td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:saveElementListOrder('<?=$rubrique_id;?>');" class="menu" onfocus="blur()" title="Enregistrer le nouvel ordre des éléments">ENREGISTRER</a></td>
	<td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
	</tr>
	</table></td>
	<td width="20">&nbsp;</td>
	<td height="20"><table  border="0" cellspacing="0" cellpadding="0" id="PREVISUALISER">
	<tr>
	<td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
	<td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:getCmsPreview(<?=$rubrique_id;?>, 0);" class="menu" onfocus="blur()" title="Prévisualiser  cette page dans la frame">PREVISUALISER</a></td>
	<td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
	</tr>
	</table></td>
	<td width="20">&nbsp;</td>
	<td height="20"><table  border="0" cellspacing="0" cellpadding="0" id="PUBLIER">
	<tr>
	<td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
	<td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:buildElementPage('<?=$rubrique_id;?>');" class="menu" id="ENREGISTRER_texte" onfocus="blur()" title="Tant que la page n'est pas publiée une fois, les changements sur le site sont immédiat">PUBLIER</a></td>
	<td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
	</tr>
	</table></td>
	<td width="20">&nbsp;</td>
	<td height="20"><table  border="0" cellspacing="0" cellpadding="0" id="AFFICHER">
	<tr>
	<td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
	<td nowrap="nowrap" background="../images/images/button_02.png"><a href="<?=$root;?>page-r<?=$rubrique_id;?>.html" class="menu" id="AFFICHER_texte" onfocus="blur()" target="_blank" title="Ouvrir la page du site dans une nouvelle fenêtre">VOIR</a></td>
	<td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
	</tr>
	</table></td>
	</tr>
	</table></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr><td height="20"><div id="div" style="display:none;"><img src="../images/load_w.gif" width="36" height="36" /></div></td></tr>
    </table><?
	
	if ($rubrique_id > 0) {
	
			?><table width="100%"  border="0" cellpadding="4" cellspacing="0"  class="bgTableauTitre">
			<tr>
			<td height="20">&nbsp;Ajouter un &eacute;l&eacute;ment&nbsp;<img src="../images/flech_show.png" width="14" height="14" align="absmiddle" /></td>
			</tr>
			</table>
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td height="20"><div id="inprogress" style="display:none;"><img src="../images/load_w.gif" width="36" height="36" /></div></td>
			</tr>
			</table>

			<div id="container">
				<ul id="tabs">
					<? foreach($arr_type as $i=>$type) { 
						?><li class="<?=($i ==0 ? 'first' : ($i==count($arr_type)-1 ? 'last' : ''));?>"><a href="#tab<?=($i+1);?>"><strong><?=aff($type);?></strong></a></li><?
					 } ?>
				</ul>
				<div class="panel" id="tab1">
					<? 
					$Q =& new Q("SELECT * FROM cms_elements_types WHERE actif='1' ORDER BY type ASC, titre ASC");
					$typeEx = ''; $i = 2; $class = ' first';
					foreach($Q->V as $V)  { 
						if ($typeEx != '' && $typeEx != $V['type']) {
							echo '</div><div class="panel" id="tab'.$i.'">';
							$i++; $class = ' first';
						}
						else if ($typeEx != '') $class = '';
						echo '<a href="javascript:addElement(\''.addslashes($V['titre']).'\',\''.$V['id'].'\',\''.$rubrique_id.'\',\''.$element_langue.'\');" class="elementLink'.$class.'">'.aff($V['titre']).'</a>';	
						$typeEx = $V['type'];
					} 
					?>
				</div>
			</div>
			
			<script>
				 new divTabs('tabs');
				 //Event.observe(window,'load',function() { new divTabs('tabs'); },false);
			</script>


			<?=$table_elements_type;?>
		
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr><td height="20">&nbsp;</td></tr>
			</table>
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td align="center" valign="top" id="element_list_view" <?=($total_element < 1 ? 'style="display:none;"' : '');?>><table width="100%"  border="0" cellpadding="4" cellspacing="0"  class="bgTableauTitre">
			<tr>
			<td height="20" nowrap="nowrap">&nbsp;El&eacute;ments de page <img src="../images/flech_show.png" width="14" height="14" align="absmiddle"></td>
			</tr>
			</table>
			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			<tr>
			<td height="20"><img src="../images/spacer.gif" width="260" height="1" /></td>
			</tr>
			</table>
			
			<?=form('frm_eff_element', '_actions.php?action=delete_element&rubrique_id='.$rubrique_id, false);?>
			
			<table width="100%" border="0" cellpadding="0" cellspacing="1" class="tablebor">
			<tr class="table-sstitre">
			<td height="25" >El&eacute;ments</td>
			<td style="width:70px;_width:8%;">Actif</td>
			<td style="width:70px;_width:8%;">Effacer</td>
			</tr>
			<tr align="center">
			<td colspan="3">
			<ul id="element_list">
			<?
			$oneElementAtleast = false; // Show preview ?
			foreach($E->V as $V) {
		
					if ($V['actif'] == 1) $oneElementAtleast = true;
					$element_type_id_titre = $elements_type[$V['type_id']]['titre'];
		
					?><li id="element_<?=$V['id'];?>">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #fff;">
					<tr class="table-ligne1" onmouseover="this.style.backgroundColor='#FFFFFF';" onmouseout="this.style.backgroundColor='';" id="tr_element_<?=$V['id'];?>">
					<td class="texte" style="border-left:1px solid #fff;"><img src="../images/drag.gif" width="30" height="22" align="absmiddle" border="0" title="D&eacute;placer" class="move" /><? if ($elements_type[$V['type_id']]['valeurs'] != '') { ?><a href="javascript:void(0);" onclick="editElement(<?=$V['id'];?>);" id="link_element_<?=$V['id'];?>" title="Editer l'&eacute;l&eacute;ment"><?=aff($element_type_id_titre);?></a><? } else { ?><?=aff($element_type_id_titre);?><? } ?></td>
					<td align="center" class="texte" style="width:70px;_width:66px;border-left:1px solid #fff;"><input type="checkbox" name="actif[]" id="actif_<?=$V['id'];?>" onchange="setActif(this.checked,'<?=$V['id'];?>');" class="radio" <?=($V['actif']==1 ? 'checked="checked"' : '' );?> /></td>
					<td align="center" style="width:70px;_width:66px;border-left:1px solid #fff;"><input name="eff[]" type="checkbox" class="effelementcheck" value="<?=$V['id'];?>" /></td>
					</tr>
					</table></li><?
			}
			?>
			</ul>
			</td>
			</tr>
			<tr class="table-sstitre">
			<td height="25" colspan="3">
				<table width="100%" border="0" cellpadding="2" cellspacing="0" class="texte">
				<tr>
				<td><div style="display:inline; float:right;">Tout s&eacute;lectionner</div></td>
				<td width="1%"><input name="checkall" id="checkall" type="checkbox" class="radio" value="1" onclick="javascript:effClicTous('frm_eff_element',this.checked);" /><input name="element_serialize" type="hidden" id="element_serialize" value=""/></td>
				<td width="1%">
					<table  border="0" cellspacing="0" cellpadding="0">
					<tr>
					<td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
					<td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:effElement();" class="menu">EFFACER</a></td>
					<td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
					</tr>
					</table></td>
				</tr>
				</table></td>
			</tr>
			</table>
			<?=formE();?>
		
		<? if (count($langues) > 1) { ?>
		<table border="0" cellpadding="0" cellspacing="0" class="texte">
		<tr>
		<td><table  border="0" cellspacing="0" cellpadding="0" id="AFFICHER">
		<tr>
		<td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
		<td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:void(0);" onclick="redir('index.php?mode=cms&id=<?=$rubrique_id;?>&amp;action=duplicate_element_langue&langue_dupli='+ $('element_langue_duplicate').value);" class="menu" onfocus="blur()">Dupliquer cette page en</a></td>
		<td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
		</tr>
		</table></td>
		<td>&nbsp;</td>
		<td><?
		$select = array();
		foreach($langues as $langue) if ($langue != $element_langue) $select[] = array($langue, $langue);
		$m = new menuSelect('element_langue_duplicate', $select);
		$m->first = '';
		$m->selected = $element_langue;
		$m->url = 'index.php?mode=cms&id='.$rubrique_id.'&element_langue=';
		$m->printMenuSelect();
		?></td>
		</tr>
		</table>
		<? } ?>
			
		<br />
		<br /></td>
		<td width="20">&nbsp;</td>
		<td align="center" valign="top" class="texte" width="<?=($frontPageWidth<600?($frontPageWidth+22):'600');?>"><img src="../images/spacer.gif" width="<?=($frontPageWidth<600?($frontPageWidth+22):'600');?>" height="1" />
		<div id="cms_element_pane"><?
		
		if ($oneElementAtleast) require('cms_elements_preview.php');
		
		?></div></td>
		</tr>
		</table><?
		
	}
	?></td>
</tr>
</table></td>
</tr>
</table><iframe src="javascript:void(0)" id="actionFrame" name="actionFrame" style="display:none"> width="700" height="500"</iframe>