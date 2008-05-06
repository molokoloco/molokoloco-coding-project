<? require_once("../lib/racine.php");

$table = gpc('table');

if (!empty($action)) {

	switch($action) {
		
		case 'CSV_IMPORT':
			
			$table = clean($_POST['table']);
			$champs = clean($_POST['champs']);
			$sep = clean($_POST['sep']);
			
			$fileContent = file_get_contents($_FILES['file_csv']['tmp_name']);
			if (empty($fileContent)) die('Empty file');
			
			$champsToInsert = explode(';', $champs);
			$contentRow = explode("\n", $fileContent);
			
			foreach($contentRow as $row) {
				$champs = array(
					0=>$champsToInsert,
					1=>addslashes_array(explode($sep, $row))
				);
				$G =& new SQL($table);
				$G->insertSql($champs,'1');
			}
			$intitule = 'Insertion effectuée';
		
		break;
		
		case 'CSV_EXPORT':
			
			$table = clean($_POST['table']);
			$where = clean($_POST['where']);
			$champs = clean($_POST['champs']);
			$sep = clean($_POST['sep']);
			
			$champsToExport = explode(';', $champs);
			
			$Q = new Q("SELECT ".implode(',', $champsToExport)." FROM $table ".(!empty($where) ? 'WHERE '.where : '')); // WHERE trad_uk=''
			$champs_csv = '';
			foreach($Q->V as $V) {
				foreach($champsToExport as $chp) $champs_csv .= '"'.str_replace(';', '";"', str_replace("\n", "#BR#", $V[$chp])).'"'.$sep;
				$champs_csv .= "\n";
			}
			
			$nom = 'export_'.$table.'-'.date("Ymd").'.csv';
			$mime = 'text/comma-separated-values';

			header('Content-Type: '.$mime);
			header('Content-Disposition: attachment; filename="'.$nom.'"');
			
			if (navDetect() == 'msie') {
			  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			  header('Pragma: public');
			}
			else header('Pragma: no-cache');
			
			$maintenant = gmdate('D, d M Y H:i:s').' GMT';
			
			header('Last-Modified: '.$maintenant);
			header('Expires: '.$maintenant); 
			header('Content-Length: '.strlen($champs_csv));
			die($champs_csv);
			
		break;
	}
}

?><? include_once("../menu/menu.php"); ?><table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
<tr>
<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td height="23" nowrap class="table-titre">IMPORTATION DE FICHIER AU FORMAT .CSV </td>
</tr>
<tr align="center">
<td class="bgTableauPcP"><? if ($info != '' || $intitule != '') { include("../lib/actions_infos.php"); } ?></td>
</tr>
</table>
<table width="100%"  border="0" cellspacing="0" cellpadding="15">
<tr>
<td><table width="100%"  border="0" cellpadding="3" cellspacing="1" class="tablebor">
<form method="POST" action="import_csv.php?action=CSV" name="F6" enctype="multipart/form-data">
<script language="JavaScript" type="text/JavaScript">
<!--
function V6() { // Verif de la saisie
	whom = document.F6;
	if (confirm("Etes-vous sur d'insérer dans la table\n\t"+whom.table.options[whom.table.selectedIndex].value+"\nles champs\n\t"+whom.champs.value+"\ndu fichier csv ?"))
		whom.submit();
}
//-->
</script>
<tr>
<td colspan="2" class="table-sstitre">Importer des  donn&eacute;es [Excel, format .CSV]</td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">Table :</td>
<td nowrap class="table-ligne1"><?

$T = new SQL('');
$tabls = $T->GetTableList();
$select = array();
foreach($tabls as $tabl) $select[] = array($tabl, $tabl);
$m = new menuSelect('table', $select);
$m->first = 'Choisir->';
$m->selected = $table;
$m->url = 'import_csv.php?table=';
$m->printMenuSelect();

?></td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">Champs CSV  :</td>
<td nowrap class="table-ligne1"><?
if (!empty($table)) {
	$T = new SQL('');
	$champs = $T->GetFieldList($table);
	$champs = implode(';',$champs);
}
?>Ne gardez que les champs présents dans votre CSV<br />
<input name="champs" type="text" size="50" style="width:100%" value="<?=$champs;?>"><br />
<?=$champs;?></td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">Fichier :</td>
<td width="98%" nowrap class="table-ligne1"><input name="MAX_FILE_SIZE" type="hidden" value="2600000">
<input name="file_csv" type="file" size="50" maxlength="50" style="width:100%;" ></td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">S&eacute;parateur :</td>
<td nowrap class="table-ligne1"><input name="sep" type="radio" class="radio" value=";" checked>Points-virgules (;) <input name="sep" type="radio" class="radio" value=","> virgules (,) <input name="sep" type="radio" class="radio" value="\t"> Tabulations (Grand espace blanc)</td>
</tr>
<tr align="center">
<td colspan="2" class="table-bas"><input type="button" value="Importer et mettre &agrave; jour la BDD" onClick="javascript:V6();"></td>
</tr>
</form>
</table>
<br />
<br /><table width="100%"  border="0" cellpadding="3" cellspacing="1" class="tablebor">
<form method="POST" action="import_csv.php?action=CSV_EXPORT" name="F7" enctype="multipart/form-data" target="actionFrame">
<script language="JavaScript" type="text/JavaScript">
<!--
function V7() { // Export
	whom = document.F7;
	whom.submit();
}
//-->
</script>
<tr>
<td colspan="2" class="table-sstitre">Exporter des  donn&eacute;es [Excel, format .CSV]</td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">Table :</td>
<td width="98%" nowrap class="table-ligne1"><?

$T = new SQL('');
$tabls = $T->GetTableList();
$select = array();
foreach($tabls as $tabl) $select[] = array($tabl, $tabl);
$m = new menuSelect('table', $select);
$m->first = 'Choisir->';
$m->selected = $table;
$m->url = 'import_csv.php?table=';
$m->printMenuSelect();

?></td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">Where : </td>
<td nowrap class="table-ligne1"><input name="where" type="text" size="50" style="width:100%" value=""></td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">Champs CSV  :</td>
<td nowrap class="table-ligne1"><?
if (!empty($table)) {
	$T = new SQL('');
	$champs = $T->GetFieldList($table);
	$champs = implode(';',$champs);
}
?>Ne gardez que les champs que vous voulez dans le CSV<br />
<input name="champs" type="text" size="50" style="width:100%" value="<?=$champs;?>"><br />
<?=$champs;?></td>
</tr>
<tr>
<td align="right" nowrap class="table-entete1">S&eacute;parateur :</td>
<td nowrap class="table-ligne1"><input name="sep" type="radio" class="radio" value=";" checked>Points-virgules (;) <input name="sep" type="radio" class="radio" value=","> virgules (,) <input name="sep" type="radio" class="radio" value="\t"> Tabulations (Grand espace blanc)</td>
</tr>
<tr align="center">
<td colspan="2" class="table-bas"><input type="button" value="Exporter au format CSV" onClick="javascript:V7();"></td>
</tr>
</form>
</table></td>
</tr>
</table></td>
</tr>
</table><iframe src="javascript:void(0)" id="actionFrame" name="actionFrame" style="display:none; visibility:hidden;"></iframe>
<? include_once("../menu/menu_bas.php"); ?>