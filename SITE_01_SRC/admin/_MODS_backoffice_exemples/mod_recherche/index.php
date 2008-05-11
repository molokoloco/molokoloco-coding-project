<?  include_once("../menu/menu.php"); ?><?

$q = $_POST['q']!='' ? Clean($_POST['q']) : Clean($_GET['q']);
$data = $_POST['data']!='' ? Clean($_POST['data']) : Clean($_GET['data']);

?><table width="100%" border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%" border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="23" nowrap class="table-titre">RECHERCHE</td>
<td width="67%" align="center" class="table-titre2"></td>
</tr>
</table>
</td>
</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="15">
<tr valign="top">
<td align="center"> 
<table  border="0" cellpadding="0" cellspacing="0">
<form action="index.php" method="GET" enctype="multipart/form-data" id="F1" name="F1" onKeyPress="kH();">
<script language="JavaScript" type="text/JavaScript">
<!--
function kH(e) { // Submit with Enter Key...
	var K = window.event ? window.event.keyCode : e.which;
	if (K == "13") { V1(document.F1); }
}
function V1() { // Verif formulaire
	whom = document.F1;
	var error = "";
 	if (whom.data.value == "") error += "- Choisissez une rubrique de recherche";
	
	var ok = 0;
	for (i=0; i<whom.elements.length; i++) { 
		if (whom.elements[i].name.indexOf('R_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R0_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R1_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R2_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R3_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R4_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R5_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R6_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R7_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
		if (whom.elements[i].name.indexOf('R8_name') != -1) { if (whom.elements[i].checked == true) ok = 1; }
	}
	if (ok != 1) error += "\n- Choisissez au moins un champs pour la recherche";
	
	if (whom.q.value == "" || whom.q.value == "Mot(s)-clé") error += "\n- Veuillez entrer un mot-clé";
	if (error != "") alert(error);
	else whom.submit();
}
//-->
</script>
<tr valign="top">
<td width="96" class="texte">Dans la rubrique</td>
</tr>
<tr valign="top">
<td class="texte"><img src="../images/spacer.gif" width="10" height="10"></td>
</tr>
<tr valign="top">
<td class="texte"><img src="../images/spacer.gif" width="160" height="10"><?

$dir = $wwwRoot.'admin/';
//$filesRep = getFile($dir,'rep');
$filesRep = array('mod_articles','dat_bibliotheque_fichiers','dat_bibliotheque_images');
$options = '';
foreach($filesRep as $repData) {
	if (is_file($dir.'/'.$repData.'/data.php')) {
		
		$R = $R0 = $R1 = $R2 = $R3 = $R4 = $R5 = $R6 = $R7 = $R8 = $R9 = NULL;
		include_once($dir.'/'.$repData.'/data.php');

		//if (isset($R['table'])) $options .= '<option value="'.$R['table'].':R" '.($data == $R['table'].':R' ? 'selected' : '').'>'.$R['titre'].'</option>';
		if (isset($R0['table']) && !empty($R0['titre'])) $options .= '<option value="'.$repData.':R0" '.($data == $R0['table'].':R0' ? 'selected' : '').'>'.$R0['titre'].'</option>';
		
		if (isset($R2['table']) && !empty($R2['titre'])) {
			$options .= '<option value="'.$repData.':R1:R2" '.($data == $R1['table'].':R1:R2' ? 'selected' : '').'>'.$R1['titre'].'</option>';
		}
		else if (isset($R1['table']) && !empty($R1['titre'])) $options .= '<option value="'.$repData.':R1" '.($data == $R1['table'].':R1' ? 'selected' : '').'>'.$R1['titre'].'</option>';
	}
}

?><select name="data" onChange="window.location='index.php?data='+this.options[this.selectedIndex].value+'&q=<?=$q;?>';" style="width:100%;">
	<option selected>Choisir --&gt;</option>
	<!--<option value="mod_ec_idees:R1" <?=($data=='mod_ec_idees:R1'?'selected':'');?>>Id&eacute;es</option>-->
	<?=$options;?>
</select></td>
</tr>
<tr valign="top">
<td class="texte"><img src="../images/spacer.gif" width="10" height="10"></td>
</tr>
<tr valign="top"><?

if ($data != '') {

	$data = explode(':',$data);
	include '../'.$data[0].'/data.php';
	
	?><td class="texte">sur les champs</td>
	</tr>
	<tr valign="top">
	<td class="texte"><img src="../images/spacer.gif" width="10" height="10"></td>
	</tr>
	<tr valign="top">
	<td class="texte"><table width="100%"  border="0" cellpadding="3" cellspacing="0" class="tablebor comment"><?
	/////////////////////////////////
	if (in_array('R', $data) && count($R) > 0) { // Normal
		$D = $R; $Ddata = $R_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R0', $data) && count($R0) > 0) { // Rubrique
		$D = $R0; $Ddata = $R0_data;
		?><tr>
		<td colspan="2" class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R0_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R0_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R0_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R0_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R0_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R0_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R1', $data) && count($R1) > 0) { // Normal
		$D = $R1; $Ddata = $R1_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R1_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R1_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R1_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R1_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R1_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R1_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R2', $data) && count($R2) > 0) { // Sous-cat
		$D = $R2; $Ddata = $R2_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R2_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R2_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R2_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R2_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R2_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R2_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R3', $data) && count($R3) > 0) { // Sous-cat
		$D = $R3; $Ddata = $R3_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R3_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R3_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R3_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R3_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R3_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R3_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R4', $data) && count($R4) > 0) { // Sous-cat
		$D = $R4; $Ddata = $R4_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R4_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R4_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R4_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R4_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R4_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R4_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R5', $data) && count($R5) > 0) { // Sous-cat
		$D = $R5; $Ddata = $R5_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R5_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R5_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R5_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R5_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R5_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R5_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R6', $data) && count($R6) > 0) { // Sous-cat
		$D = $R6; $Ddata = $R6_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R6_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R6_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R6_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R6_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R6_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R6_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R7', $data) && count($R7) > 0) { // Sous-cat
		$D = $R7; $Ddata = $R7_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R7_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R7_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R7_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R7_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R7_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R7_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	if (in_array('R8', $data) && count($R8) > 0) { // Sous-cat
		$D = $R8; $Ddata = $R8_data;
		?><tr>
		<td colspan="2" nowrap class="texte bgTableauPcP"><?=$D['titre'];?></td>
		</tr><?
		for ($i=0; $i<count($Ddata); $i++) { // Each champs
			if ($Ddata[$i]['sqlType'] == 'varchar'  || $Ddata[$i]['sqlType'] == 'text'|| $Ddata[$i]['sqlType'] == 'tinytext') {
				if ($Ddata[$i]['bilingue'] == '1') {
					foreach ($langues as $langue) { 
						?><tr valign="top">
						<td><input type="checkbox" name="R8_name[]" value="<?=$Ddata[$i]['name'].'_'.$langue;?>" style="border:none;" <? if (isset($_GET['R8_name'])) { if (in_array($Ddata[$i]['name'].'_'.$langue,$_GET['R0_name'])) echo 'checked'; } ?>></td>
						<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?> <?=$langue;?></td>
						</tr><?
					}
				}
				else {
					?><tr valign="top">
					<td><input type="checkbox" name="R8_name[]" value="<?=$Ddata[$i]['name'];?>" style="border:none;" <? if (isset($_GET['R8_name'])) { if (in_array($Ddata[$i]['name'],$_GET['R0_name'])) echo 'checked'; } ?>></td>
					<td width="90%"><?=($Ddata[$i]['titre']!=''?Aff($Ddata[$i]['titre']):ucfirst(Aff($Ddata[$i]['name'])));?></td>
					</tr><?
				}
			}
		}
	}
	/////////////////////////////////
	?><tr align="center">
	<td colspan="2"><script language="JavaScript">
	<!--
	function clicTous(booleen) {
		form = document.F1;
		for (i=0; i<form.elements.length; i++) { 
			if (form.elements[i].name.indexOf('R_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R0_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R1_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R2_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R3_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R4_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R5_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R6_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R7_name') != -1) form.elements[i].checked = booleen;
			else if (form.elements[i].name.indexOf('R8_name') != -1) form.elements[i].checked = booleen;
		}
	}
	//-->
	</script>[<a href="javascript:void(0);" onClick="clicTous(true);">tous</a>|<a href="javascript:void(0);" onClick="clicTous(false);">aucun</a>]</td>
	</tr>
	</table></td>
	</tr><?
}
?><tr valign="top">
<td class="texte"><img src="../images/spacer.gif" width="10" height="10"></td>
</tr>
<tr valign="top">
<td class="texte">rechercher</td>
</tr>
<tr valign="top">
<td class="texte"><img src="../images/spacer.gif" width="10" height="10"></td>
</tr>
<tr valign="top">
<td class="texte"><input name="q" type="text" onClick="if(this.value=='Mot(s)-cl&eacute;')this.value = '';" value="<?=($q!=''?$q:'Mot(s)-cl&eacute;');?>" maxlength="150" title="&quot;-&quot; entre 2 mots pour &quot;OU&quot; || &quot;+&quot; entre 2 mots pour &quot;ET&quot;" style="width:100%;"></td>
</tr>
<tr valign="top">
<td class="texte"><img src="../images/spacer.gif" width="10" height="10"></td>
</tr>
<tr valign="top">
<td class="texte"><table width="100%"  border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
<td nowrap background="../images/images/button_02.png"><a href="javascript:V1();" class="menu">RECHERCHER</a></td>
<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
</tr>
</table></td>
</tr>
</form>
</table></td>
<td width="98%" align="center"><table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="bgTableauTitre">
<tr>
<td height="20" align="center">&nbsp;R&eacute;sultat de la recherche &nbsp;<img src="../images/flech_show.png" width="14" height="14" align="absmiddle">&nbsp;</td>
</tr>
</table>
<br>
<table width="100%" border="0" cellpadding="3" cellspacing="1" class="tablebor" >
<tr align="center">
<td class="table-sstitre">#</td>
<td width="80%" class="table-sstitre">Titre</td>
<td class="table-sstitre">Actions</td>
</tr><?
if ($q != '' && $data != '') { ////////////////////////////// RECHERCHE
	
	if (strpos($q,'-') !== false) $bolean = 'OR';
	else $bolean = 'AND';
	$q = str_replace('-',' ',$q);
	$q = str_replace('+',' ',$q);
	$q = str_replace('  ',' ',$q);
	
	$K = explode(' ',$q); // on place les differents mots dans un tableau
	$nbWord = count($K);
	
	$result = false;
	$noresult = false;
	/////////////////////////////////////////////////////// DEBUT "R" ///////////////////////////////////////////////////////////////
	if (isset($_GET['R_name']) && $_GET['R_name'][0] != '') {
		$D = $R; $Ddata = $R_data;
		// Find titre and ID for this table
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' OR ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' OR ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " $bolean ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		$F->CustomSql($sql);
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN "R" /////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////// DEBUT R1 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R1_name']) && $_GET['R1_name'][0] != '') {
		$D = $R1; $Ddata = $R1_data;
		// Find titre and ID for this table
		$titre = '';

		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];

		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R1_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' OR ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		
		$sql = "SELECT $id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R1_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' OR ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " $bolean ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		$F->CustomSql($sql);
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R1 /////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////// DEBUT R0 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R0_name']) && $_GET['R0_name'][0] != '') {
		$D = $R0; $Ddata = $R0_data;
		// Find titre and ID for this table
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R0_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' OR ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R0_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' OR ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " $bolean ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R0 /////////////////////////////////////////////////////////
	
	
	
	/////////////////////////////////////////////////////// DEBUT R2 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R2_name']) && $_GET['R2_name'][0] != '') {
		$D = $R2; $Ddata = $R2_data;
		// Find titre and ID for this table // FIND CAT_ID FOR R2 ................. DIFFERENCE AVEC LES AUTRES
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R2_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' $bolean ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,cat_id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R2_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' $bolean ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " AND ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&cat_id=<?=$F->V[$i]['cat_id'];?>&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R2 /////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////// DEBUT R3 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R3_name']) && $_GET['R3_name'][0] != '') {
		$D = $R3; $Ddata = $R3_data;
		// Find titre and ID for this table // FIND CAT_ID FOR R3 ................. DIFFERENCE AVEC LES AUTRES
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R3_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' $bolean ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,cat_id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R3_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' $bolean ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " AND ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&cat_id=<?=$F->V[$i]['cat_id'];?>&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R3 /////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////// DEBUT R4 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R4_name']) && $_GET['R4_name'][0] != '') {
		$D = $R4; $Ddata = $R4_data;
		// Find titre and ID for this table // FIND CAT_ID FOR R4 ................. DIFFERENCE AVEC LES AUTRES
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R4_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' $bolean ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,cat_id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R4_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' $bolean ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " AND ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&cat_id=<?=$F->V[$i]['cat_id'];?>&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R4 /////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////// DEBUT R5 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R5_name']) && $_GET['R5_name'][0] != '') {
		$D = $R5; $Ddata = $R5_data;
		// Find titre and ID for this table // FIND CAT_ID FOR R5 ................. DIFFERENCE AVEC LES AUTRES
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R5_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' $bolean ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,cat_id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R5_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' $bolean ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " AND ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&cat_id=<?=$F->V[$i]['cat_id'];?>&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R5 /////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////// DEBUT R6 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R6_name']) && $_GET['R6_name'][0] != '') {
		$D = $R6; $Ddata = $R6_data;
		// Find titre and ID for this table // FIND CAT_ID FOR R6 ................. DIFFERENCE AVEC LES AUTRES
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R6_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' $bolean ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,cat_id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R6_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' $bolean ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " AND ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&cat_id=<?=$F->V[$i]['cat_id'];?>&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R6 /////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////// DEBUT R7 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R7_name']) && $_GET['R7_name'][0] != '') {
		$D = $R7; $Ddata = $R7_data;
		// Find titre and ID for this table // FIND CAT_ID FOR R7 ................. DIFFERENCE AVEC LES AUTRES
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R7_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' $bolean ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,cat_id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R7_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' $bolean ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " AND ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&cat_id=<?=$F->V[$i]['cat_id'];?>&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R7 /////////////////////////////////////////////////////////
		/////////////////////////////////////////////////////// DEBUT R8 ///////////////////////////////////////////////////////////////
	if (isset($_GET['R8_name']) && $_GET['R8_name'][0] != '') {
		$D = $R8; $Ddata = $R8_data;
		// Find titre and ID for this table // FIND CAT_ID FOR R8 ................. DIFFERENCE AVEC LES AUTRES
		$titre = '';
		for ($i=0; $i<count($Ddata); $i++) { 
			if ($Ddata[$i]['name'] == 'titre' || $Ddata[$i]['name'] == 'nom' || $Ddata[$i]['name'] == 'email' || $Ddata[$i]['name'] == 'texte') {
				$titre = $Ddata[$i]['name'];
				if ($Ddata[$i]['bilingue'] == '1') $titre .= '_'.$langues[0];
				break;
			}
		}
		if ($titre == '') { $titre = 'id'; $id = ''; }// ??? //
		else $id = $Ddata[0]['name'];
		
		$query = ''; // On prépare la requête SQL...
		foreach($_GET['R8_name'] as $champs) $query .= $champs." LIKE '%".$K[0]."%' $bolean ";
		$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
		$sql = "SELECT $id,cat_id,$titre FROM {$D['table']} WHERE ( $query ) ";

		if ($nbWord > 1) { // on boucle pour integrer tous les mots dans la requête -> AND
			for($y=1; $y<$nbWord; $y++) {
				$query = '';
				foreach($_GET['R8_name'] as $champs) $query .= $champs." LIKE '%".$K[$y]."%' $bolean ";
				$query = substr($query,0,($bolean=='OR'?'-3':'-4'));
				$sql .= " AND ( ".$query." ) ";
			}
		}
		$sql .= " ORDER BY $titre ASC LIMIT 0,300"; // Limite.....

		$F = new SQL($D);
		
		$F->CustomSql($sql);
	
		if ($F->nb > 0) {
			for ($i=0; $i<$F->nb; $i++) {
				?><tr class="<?=($i%2==0?'table-ligne1':'table-ligne2');?>" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
				<td align="center" class="texte"><?=($i+1);?></td>
				<td class="texte"><?=$D['titre'];?> : <b><a href="../<?=$data[0];?>/index.php?mode=fiche&cat_id=<?=$F->V[$i]['cat_id'];?>&id=<?=$F->V[$i][$id];?>" class="menu"><?=Aff($F->V[$i][$titre]);?></a></b></td>
				<td align="center" class="texte"><a href="../<?=$data[0];?>/index.php?mode=fiche&id=<?=$F->V[$i]['id'];?>" class="menu">Editer</a></td>
				</tr><?
			}
			$result = true;
		}
	}
	/////////////////////////////////////////////////////// FIN R8 /////////////////////////////////////////////////////////
	
	if (!$result) { // No Result
		?><tr class="table-ligne1" onMouseOver="this.style.backgroundColor='#FFFFFF';" onMouseOut="this.style.backgroundColor='';">
		<td colspan="3" align="center" class="texte">&nbsp;<br />
		Aucun r&eacute;sultat pour cette recherche...<br />
		&nbsp;</td>
		</tr><?
	}
}
?><tr>
<td colspan="4" class="table-bas">&nbsp;</td>
</tr>
</table></td>
</tr>
</table></td></tr>
</table>
<? include_once("../menu/menu_bas.php"); ?>