<?
require_once('../lib/racine.php');
require_once('cms_fonctions.php');

setIsoHeader();

$element_id = gpc('element_id');
$E =& new Q("SELECT * FROM cms_pages_elements WHERE id='$element_id' LIMIT 1");

$elements_type = getElementsType();
### db($elements_type);

?><table width="100%"  border="0" cellpadding="4" cellspacing="0"  class="bgTableauTitre">
<tr>
<td height="20">&nbsp;Modifier un &eacute;l&eacute;ment&nbsp;<img src="../images/flech_show.png" width="14" height="14" align="absmiddle" /></td>
</tr>
</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr><td height="20">&nbsp;</td></tr>
</table>

<table width="650" border="0" cellpadding="2" cellspacing="1" class="bor1">
<tr>
<td align="center" nowrap class="table-bas"><table width="220"  border="0" cellpadding="0" cellspacing="0" id="VALIDER_FORM">
  <tr>
    <td class="menu" height="20"><table  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
        <td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:submitFormElement();" class="menu" onfocus="blur()">VALIDER</a></td>
        <td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
      </tr>
    </table></td>
    <td height="20" align="right" class="menu"><table  border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
        <td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:$('duplicate').value='1';submitFormElement();" class="menu" onfocus="blur()">DUPLIQUER</a></td>
        <td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
      </tr>
    </table></td>
  </tr>
</table></td>
</tr>
<tr>
<td align="right" valign="top" class="table-entete2">
	<table width="100%" border="0" cellspacing="0" cellpadding="6">
	<tr>
		<td width="100%" align="center">
		
			<?=form('frm_edit_element', 'cms_element_action.php?action=update_element&element_id='.$element_id, false, 'post', 'actionFrame');?>
			<input type="hidden" name="duplicate" id="duplicate" value="0" />

				<table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="bor1">
				<tr>
				<td width="130" align="right" valign="top" class="table-entete1">Type d'&eacute;l&eacute;ment :</td>
				<td class="table-ligne1 comment"><?
				echo aff($elements_type[$E->V[0]['type_id']]['titre']);
				/*$inputs = array();
				foreach($elements_type as $idSel=>$eType) $inputs[] = array($idSel, $eType['titre']);
				$m = new menuSelect('type_id', $inputs);
				$m->selected = $E->V[0]['type_id'];
				$m->printMenuSelect();*/
				?></td>
				</tr>
				<tr>
				<td align="right" class="table-entete2" valign="top">Langue :</td>
				<td class="table-ligne2 comment"><?
				echo ucfirst($E->V[0]['langue']);
				/*$inputs = array();
				foreach($langues as $langue) $inputs[] = array($langue, $langue);
				$m = new menuSelect('langue', $inputs);
				$m->selected = $E->V[0]['langue'];
				$m->printMenuSelect();*/
				?></td>
				</tr>
				<tr>
				<td align="right" class="table-entete1" valign="top">Actif :</td>
				<td class="table-ligne1 comment"><input name="actif" id="actif" class="radio" value="1" type="radio" <?=($E->V[0]['actif']!=0?'checked="checked"':'');?>>&nbsp;Oui <input name="actif" id="actif" class="radio" value="0" type="radio" <?=($E->V[0]['actif']==0?'checked="checked"':'');?>>&nbsp;Non</td>
				</tr>
				</table>
				
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td height="20">&nbsp;</td></tr>	
				</table>
				
				<? // ADD NEW FORM

				if (!empty($elements_type[$E->V[0]['type_id']]['valeurs'])) { // L'element comporte des variables dyn sinon pas de formulaire (<br /> ...)
				
					$array_ensembles = parseAbstractString($elements_type[$E->V[0]['type_id']]['valeurs']);
					### db($array_ensembles ,'$array_ensemble');

					$array_valeurs = cleanUnserial($E->V[0]['valeurs']);
					### db($array_valeurs ,'$array_valeurs');

					$inputTr = '';
					$jsVerif = '';
					$jsSubmitAdd = '';
					$jsAdd = '';

					foreach((array)$array_ensembles as $eCount=>$array_ensemble) { // Pour chaque ensemble de valeur
						
						if ($array_ensemble['ensemble'] == 'item') { // Ensemble with simple instance ///////// 
							
							foreach((array)$array_ensemble['valeurs'] as $i=>$element_item) { // Pour chaque valeur
								$inputs = 		getInputTr($i, $element_item, $array_valeurs, '');
								$inputTr .= 	$inputs['tr'];
								$jsVerif .= 	$inputs['jsVerif'];
								$jsSubmitAdd .= $inputs['jsSubmitAdd'];
								$jsAdd .= 		$inputs['jsAdd'];
							}

							$myFormEditHtm = '
							<li>
								<table width="100%" cellspacing="1" cellpadding="2" border="0" align="center" class="bor1">
								'.$inputTr.'
								</table>
							</li>';
							
						}
						else { // Ensemble with multiple instance ///////// 
							
							if (!isset($instance_count)) $instance_count = 0;
							if (!isset($array_valeurs['instance_count'][$instance_count])) $array_valeurs['instance_count'][$instance_count] = 0;

							for ($key=0; $key<=$array_valeurs['instance_count'][$instance_count]; $key++) {
								
								$inputTr = '';
								foreach((array)$array_ensemble['valeurs'] as $i=>$element_item) { // Pour chaque valeur
									$inputs = 		getInputTr($i, $element_item, $array_valeurs, $key); // $key : '' = no instance // >= 0 = instance
									$inputTr .= 	$inputs['tr'];
									$jsVerif .= 	$inputs['jsVerif'];
									$jsSubmitAdd .= $inputs['jsSubmitAdd'];
									$jsAdd .= 		$inputs['jsAdd'];
								}
								
								// 1 Ensemble element valeurs
								$myFormEditHtm .= '
								<li>
									<table width="100%" cellspacing="1" cellpadding="2" border="0" align="center" class="bor1" style="border-top:none;">
									'.$inputTr.'
									</table>
								</li>';
							}
							
							$instance_count++;
						}
						
						// Button Add Ensemble multiple instance ?
						if ($array_ensemble['ensemble'] == 'items' && !empty($jsAdd)) { 
							$tpl_js_var = 'tpl_add'; //.($eCount+1);
							
							js("
								$tpl_js_var = '<table width=\"100%\" cellspacing=\"1\" cellpadding=\"2\" border=\"0\" align=\"center\" class=\"bor1\" style=\"border-top:none;\">';
								$jsAdd
								$tpl_js_var += '</table>';
							");
														
							?><table width="100%" cellspacing="1" cellpadding="2" border="0" align="center" class="bor1" bgcolor="#f4f4f4">
							<tr>
							<td align="right" height="25"><a href="javascript:addFormElement(<?=$tpl_js_var;?>);" class="texte">Ajouter un &eacute;l&eacute;ment</a>&nbsp;<img src="../images/ajout.gif" width="13" height="13" border="0" align="absmiddle" />&nbsp;</td>
							</tr>
							</table><?
							
							echo $myFormAddTpl;	
						}
					
					} // FIN CHAQUE ENSEMBLE


// FORMULAIRE COMPLET
echo '<ul id="form_list">
	'.$myFormEditHtm.'
</ul>';


js("
	submitFormElement = function() {
		param_edit_element = {mep: 'message', autoScroll: true, action: 'submit'};
		champs_edit_element = { ".substr($jsVerif, 0, -1)." };
		$jsSubmitAdd
		formVerif('frm_edit_element', champs_edit_element, param_edit_element);
	};
");

					// Button Del Ensemble multiple instance ?
					if ($array_ensemble['ensemble'] == 'items' && !empty($jsAdd)) { 
						?><table width="100%" cellspacing="1" cellpadding="2" border="0" align="center" class="bor1" bgcolor="#f4f4f4" style="border-top:none;" id="eraseBin">
						<tr>
						<td align="right" class="texte" height="25">Glisser ici pour effacer&nbsp;<img width="13" height="13" border="0" align="absmiddle" src="../images/delete.gif" title="Effacer" class="delete"/>&nbsp;</td>
						</tr>
						</table><?
					}
	
				}

				?>
				<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr><td height="20">&nbsp;</td></tr>
				</table>
				<iframe name="iframe_element" id="iframe_element" src="cms_iframe_element.php?element_id=<?=$element_id;?>" allowtransparency="1" frameborder="0" scrolling="auto" width="100%" height="30" class="bor1"></iframe>
				<?=formE();?>
			</td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td align="center" nowrap class="table-bas"><table width="220"  border="0" cellpadding="0" cellspacing="0" id="VALIDER_FORM_2">
	<tr>
	<td class="menu" height="20">
		<table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
		<td nowrap background="../images/images/button_02.png"><a href="javascript:submitFormElement();" class="menu" onfocus="blur()">VALIDER</a></td>
		<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
		</tr>
		</table>
	</td>
	<td height="20" align="right" class="menu">
		<table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
		<td nowrap background="../images/images/button_02.png"><a href="javascript:$('duplicate').value='1';submitFormElement();" class="menu" onfocus="blur()">DUPLIQUER</a></td>
		<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
		</tr>
		</table>
	</td>
	</tr>
	</table></td>
</tr>
</table>