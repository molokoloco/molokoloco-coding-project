<?
if ( !defined('MLKLC') ) die('Lucky Duck');

// ------------------------------------------------- CLASS FICHE -------------------------------------------------//

/* ----------------
$A = new FICHE($R3,$R3_data,$id);
$A->formName = 'F1'; 
$A->createFICHE();


onfocus="blur();"  /// readonly ?


------------------ */

class FICHE {
	var $table,$data,$rub_id,$cat_id,$id;
	// - - - - - - - - - - - - - - - - - - - PAGE - - - - - - - - - - - - - - - - - - - //
	function FICHE($table,$data,$id=0) { // CONSTRUCTEUR
		$this->table		= $table;
		$this->data			= $data;
		$this->rub_id		= 0;
		$this->cat_id		= 0; // $cat_id;
		$this->child		= 0; // Child n°2 == R3 data...
		$this->id			= $id;
		// Par defaut...
		$this->pageBdd		= 'index.php?mode=bdd';
		$this->pageListe	= 'index.php?mode=liste'; // Insert
		$this->pageFiche	= 'index.php?mode=fiche'; // Insert
		
		$this->formName		= 'F1';
		$this->scriptName	= 'V1';
		$this->methode		= 'POST';
		$this->html			= ''; // Var stock HTML
		$this->hidden		= ''; // Hidden fields
		$this->r			= 1; // Ligne 1 sur 2 (1-2-1-2-1-..)
		$this->level 		= 0;
		$this->champRelValue = ''; // reload / Insert
	}
	function createFICHE() { // METHODE
		$this->isrub		= $this->table['rubrelation'] != '' ? '1' : '0';
		$this->iscat		= substr($this->table['relation'],0,6) == 'parent' ? '1' : '0';
		$this->urlrub		= $this->rub_id > 0 ? '&rub_id='.$this->rub_id : '';
		$this->urlcat		= $this->cat_id > 0 ? '&cat_id='.$this->cat_id : '';
		$this->urlchild		= $this->cat_id > 0 ? '&child='.$this->child : '';
		$this->numChild		= substr($this->table['relation'],0,6) == 'parent' ? substr($this->table['relation'],7,1) : '0';
		
		$this->getLevel(); // Fetch current Rub level....
		$this->createFORMINPUT();
	}
	// - - - - - - - - - - - - - - - - - - - SCRIPT - - - - - - - - - - - - - - - - - - - //
	function getLevel() {
		global $R0;
		$this->level = 0;
		if ($this->isrub) {
			list($table,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);
			$this->champRelValue = '';
			// Page reload with menu parent_id ou Insert
			if (!empty($_POST[$champRel])) $this->champRelValue = intval($_POST[$champRel]);
			elseif (!empty($_GET[$champRel])) $this->champRelValue = intval($_GET[$champRel]);
			
			if ($this->id > 0 || $this->champRelValue != '') {
				if (!empty($this->champRelValue)) $parent_id = $this->champRelValue;
				else {
					$H = new SQL($table);
					$H->LireSql(array($champRel)," $idName='{$this->id}' LIMIT 1 ");
					$parent_id = $H->V[0][$champRel];
				}
				if ($parent_id != 0) {
					while ($parent_id != 0) {
						$H = new SQL($table);
						$H->LireSql(array($champRel)," $idName='$parent_id' LIMIT 1 ");
						$parent_id = $H->V[0][$champRel];
						$this->level++;
					}
				}
			}
		}
		return $this->level;
	}
	
	// - - - - - - - - - - - - - - - - - - - SCRIPT - - - - - - - - - - - - - - - - - - - //
	function createSCRIPT() {
		global $langues;
		
		$checkFieldArr = '';
		$multiselect = '';
		
		for ($i=0; $i<count($this->data); $i++) { // Each champs
		
			if ($this->data[$i]['oblige'] == '1') {

				if ($this->id < 1 && (empty($this->champRelValue) && $this->champRelValue != '0') ) $this->data[$i]['level']['0'] = 0; // Tout s'affiche a l'insertion si pas de post sur parent_id
				if (!isset($this->data[$i]['level'])) $this->data[$i]['level']['0'] = $this->level; // Defaut data level array : s'affiche
	
				if (in_array($this->level,$this->data[$i]['level'])) { // D1

					//if ($this->data[$i]['input'] == 'textarea' || $this->data[$i]['input'] == 'text' || $this->data[$i]['input'] == 'password' || $this->data[$i]['input'] == 'select')
					//elseif ($this->data[$i]['input'] == 'radio')
					//elseif ($this->data[$i]['input'] == 'checkbox')
					
					$checkFieldArr .= $this->data[$i]['name'];
					if ($this->data[$i]['bilingue'] == 1) $checkFieldArr .= '_'.$langues[0];
					$checkFieldArr .= ': {type:"", alerte:"Ce champs est obligatoire"},';
				}
			}
			if ($this->data[$i]['input'] == 'multiselect') {
				$multiselect = '
					var objSel = '.$this->formName.'.select_'.$this->data[$i]['name'].';
					if (objSel) {
						for (var i=0; i<objSel.options.length; i++) objSel.options[i].selected = true;
						//if (objSel.options.length != 3) error += "\\n- Veuillez choisir au moins 3 options";
					}
				';
			}
		}

		$script = '<script type="text/JavaScript">
		<!--
		
		if (typeof db == "undefined") throw("Require form.js with JS framework enabled");

		var kH = function(e) { // Submit with Enter Key...
			var K = window.event ? window.event.keyCode : e.which;
			if (K == "13") { '.$this->scriptName.'(document.'.$this->formName.'); }
		};
		
		var postAction = function() {
			$("valider").innerHTML = "<div class=\'texte menu\'><b>ACTION EN COURS...</b></div>";
			$("valider2").innerHTML = "<div class=\'texte menu\'><b>ACTION EN COURS...</b></div>";
		};

		var callFormVerif_'.$this->scriptName.' = function(check) { // Call Verif formulaire		
			var param_'.$this->scriptName.' = { mep:"message", autoScroll:true, action:"submit", afterFinish:postAction};
			var champs_'.$this->scriptName.' = {'.substr($checkFieldArr, 0, -1).'};
			formVerif("'.$this->formName.'", champs_'.$this->scriptName.', param_'.$this->scriptName.');
		};

		var '.$this->scriptName.' = function() { // Verif formulaire		
			'.$multiselect.'
			callFormVerif_'.$this->scriptName.'('.$makeVerif.');
		}
		
		//-->
		</script>';
		
		return $script;
	}
	// - - - - - - - - - - - - - - - - - - - DETECT PREVIEW - - - - - - - - - - - - - - - - - - - //
	function detectPreview() {
		// form : &preview=1 //  hidden : preview=1
		if (!empty($this->table['preview']) || !empty($this->table['previewf'])) {
			for ($i=1; $i<count($this->data); $i++) {
				if ($this->data[$i]['name'] == 'actif' || $this->data[$i]['name'] == 'statut') {
					$this->Preview = '1';
					//return '&preview=1';
				}
			}
		}
	}
		
	// - - - - - - - - - - - - - - - - - - - FORM INPUT - - - - - - - - - - - - - - - - - - - //
	function createFORMINPUT() {
		global $langues;
		global $root,$grand,$medium,$mini,$bgcolor1,$maxUploadSize;
		global $WWW;
		global $R0,$R1,$R2,$R3,$R4,$R5,$R6,$R7,$R8,$R9,$R10;
		
		// GET Data if update
		if ($this->id > 0) { 
			$F = new SQL($this->table);
			$F->LireSql('*'," id='{$this->id}' LIMIT 1 ");
		}
		
		$this->detectPreview();
		
		$this->html = '<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
		<tr>
		<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="24" nowrap class="table-titre">&nbsp;<a href="index.php" class="whiteLink">'.($this->table['titres']!=''?aff($this->table['titres']):aff($this->table['titre']).'s').'</a>&nbsp;</td>
		<td width="67%" align="center" class="table-titre2">';
		if ($this->table['preview'] != '') $this->html .= '<a href="'.$this->table['preview'].'" target="_blank" class="sstitre">Ouvrir la page actuelle sur le site</a>';
		
		if ($this->table['boutonFiche'] != '') $this->html .= $this->table['boutonFiche'];
		else {
			
			// Version imprimable
			$data = '';
			if (!$this->rub_id || $this->rub_id > 0) { // $RO -> RUB // $R1 -> CAT // $R2 -> PROD
				if ($this->cat_id > 0) { // $R1 -> CAT // $R2 -> PROD
					switch($this->child) {
						case '1' : $data = 'R2';
						case '2' : $data = 'R3';
						case '3' : $data = 'R4';
						case '4' : $data = 'R5';
						case '5' : $data = 'R6';
						case '6' : $data = 'R7';
						case '7' : $data = 'R8';
						case '8' : $data = 'R9';
						default : $data = 'R2';
					}
				}
				else $data = 'R1';
			}
			else $data = 'R0';
			
			global $selfDir;
			$dir = explode('/', $selfDir);
			$dir = $dir[(count($dir)-2)];
	
			$this->html .= '<table  border="0" cellspacing="0" cellpadding="0"><tr><td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td><td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:void(0);" onclick="window.open(\'../print_data.php?id='.$this->id.'&data='.$data.'&dir='.$dir.'\',\'\',\'toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=600,height=600\');" class="menu">Version imprimable</a></td><td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td></tr></table>';
		}
		
		$this->html .= '</td>
		</tr>
		<tr align="center">
		<td colspan="2" class="bgTableauPcP">'.MyInfo().'</td>
		</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="15">
		<tr>
		<td class="texte">';
		
		// MENU SELECT RUBRIQUE ?
		if ($this->rub_id > 0 && !empty($R0['childRel'])) {
			$this->html .= '<table  border="0" cellspacing="0" cellpadding="2" class="texte"><tr>
			<td align="right" width="100"><b>Rubrique&nbsp;:</b></td>
    		<td><select name="rubrique" onChange="window.location=\'index.php?mode=liste&rub_id=\'+this.options[this.selectedIndex].value+\''.$this->urlchild.'\';"  style="font: 11px Verdana, helvetica, mono ;">';
			list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
			list($relRubId,$rubId) = explode('=',$relRubId_rubId);
			list($relProdId,$prodId) = explode('=',$relProdId_prodId);
			list($tablle,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);
			$arbo = array();
			$R = new SQL($table);
			$R->LireSql(array($idName,$champRel)," $idName!='' ORDER BY ordre DESC"); // Scan de toutes les rubriques
			for ($i=0; $i<count($R->V); $i++) { $arbo[] = array($R->V[$i][$idName], $R->V[$i][$champRel]); } // Build array
			list($minNiveau,$maxNiveau) = explode(':',$R0['rubLevel']);
			$maxNiveau++;
			$this->GetNiveau($arbo,'0','0',$maxNiveau, 1 , $this->rub_id);
			
			$this->html .= '</select></td>
			<td><a href="index.php">Retour</a></td></tr></table>&nbsp;';
		}
		// MENU SELECT CATEGORIE ?
		if ($this->table['relation'] != '' && $this->iscat != 1 && $this->isrub != 1) {
			$this->html .= '<table  border="0" cellspacing="0" cellpadding="2" class="texte"><tr>
			<td align="right" nowrap><b>'.($this->rub_id < 1 && $this->cat_id < 1 ? $R0['titre'] : $R1['titre']).'&nbsp;:</b></td>
    		<td><select name="categorie" onChange="window.location=\'index.php?mode=liste'.$this->urlrub.'&cat_id=\'+this.options[this.selectedIndex].value+\''.$this->urlchild.'\';">';
			$j = 0;
			list($tableName,$idName,$champsName,$unique,$condition) = explode(':',$this->table['relation']);
			if (strpos($champsName,'-') !== false) {
				$TchampsName = explode('-',$champsName);
				$select = array($idName);
				foreach($TchampsName as $champsN) $select[] = $champsN;
			}
			else $select = array($idName,$champsName);
			
			$this->tableName = $tableName; // Nom de la table categorie
			$this->idName = $idName; // nom du champs valeur
			$this->champsName = $champsName; // nom du champs titre
			$this->champRel = $champRel; // nom du champ qui prend la valeur
			if ($this->rub_id < 1) { // LISTE todo CAT
				$C = new SQL($tableName);
				$C->LireSql($select,$condition);
			}
			else { // LISTE CAT OF A RUB
				// childRel => 'categories:categories_produits:produits:cat_id=id:prod_id=id:titre:titre'
				list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
				list($relRubId,$rubId) = explode('=',$relRubId_rubId);
				list($relProdId,$prodId) = explode('=',$relProdId_prodId);
				$C = new SQL($tableProd);
				$C->customSql(" SELECT $tableProd.* FROM $tableProd LEFT JOIN $tableRel ON $tableProd.$prodId=$tableRel.$relProdId LEFT JOIN $table ON $tableRel.$relRubId=$table.$rubId WHERE $table.$rubId='{$this->rub_id}' ORDER BY $tableProd.ordre ");
			}
			for ($j=0; $j < count($C->V); $j++) {
				if ($this->rub_id < 1) {
					if (strpos($champsName,'-') !== false) {
						$valeur = ''; foreach($TchampsName as $champsN) $valeur .= ' '.aff($C->V[$j][$champsN]);
					}
					else $valeur = aff($C->V[$j][$champsName]);
				} else $valeur = aff($C->V[$j][$champsName]);
				
				$this->html .= '<option value="'.$C->V[$j][$idName].'"'; 
				if ($C->V[$j][$idName] == $this->cat_id) { $this->html .= 'selected'; } 
				$this->html .= '> '.$valeur.'</option>';
			}
			$this->html .= '</select></td>
			<td><a href="index.php?'.$this->urlrub.'">Retour</a></td></tr></table>&nbsp;';
		}
		
		// ---------------------------------------- // SS-MENU vers CHILD
		if ($this->table['relation'] != '' && $this->isrub != 1 && ($this->iscat != 1 || ($this->table['relation'] != '' && $this->id > 0))) {
			if ($this->numChild > 0) $child = $this->numChild;
			else $child = substr($R1['relation'],7,1);
			$this->html .= '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td align="right"><table border="0" cellpadding="4" cellspacing="0" class="bgTableauTitre" style="border-bottom: none;">
			<tr class="table-ligne2">';
			if ($this->iscat == 1) $style = ' class="bgMenuSelect" ';
			else $style = ' style="border-right:1px solid '.$bgcolor1.';" ';
			$this->html .= '<td onMouseOver="this.style.backgroundColor=\''.$bgcolor1.'\';" onMouseOut="this.style.backgroundColor=\'\';" '.$style.' nowrap><img src="../images/flech_menu.png" width="12" height="12" border="0" align="absmiddle"><b>'.$R1['titre'].'</b>';
			if ($this->iscat != 1) $this->html .= ' <a href="index.php?mode=fiche'.$this->urlrub.'&id='.$this->cat_id.'"><img src="../images/edit.gif" border="0" align="absmiddle" alt="Modifier"></a>';
			if ($R1['fixe'] != '1') $this->html .= ' <a href="index.php?mode=fiche'.$this->urlrub.'"><img src="../images/ajout.gif" border="0" align="absmiddle" alt="Ajouter"></a></td>';
			// EACH SS-CAT (CHILD)
			for ($c=0; $c<$child; $c++) {
				if ($this->iscat == 1) $catUrl = '&cat_id='.$this->id;
				else $catUrl = $this->urlcat;
				$childUrl = '&child='.($c+1);
				if ($c == 0) { global $R2,$R2_data; $RmyForm = $R2; $RmyFormdata = $R2_data; }
				elseif ($c == 1) { global $R3,$R3_data; $RmyForm = $R3; $RmyFormdata = $R3_data; }
				elseif ($c == 2) { global $R4,$R4_data; $RmyForm = $R4; $RmyFormdata = $R4_data; }
				elseif ($c == 3) { global $R5,$R5_data; $RmyForm = $R5; $RmyFormdata = $R5_data; }
				elseif ($c == 4) { global $R6,$R6_data; $RmyForm = $R6; $RmyFormdata = $R6_data; }
				elseif ($c == 5) { global $R7,$R7_data; $RmyForm = $R7; $RmyFormdata = $R7_data; }
				elseif ($c == 6) { global $R8,$R8_data; $RmyForm = $R8; $RmyFormdata = $R8_data; }
				elseif ($c == 7) { global $R9,$R9_data; $RmyForm = $R9; $RmyFormdata = $R9_data; }
				elseif ($c == 8) { global $R10,$R10_data; $RmyForm = $R10; $RmyFormdata = $R10_data; }
				if ($this->child == $c+1) $style = ' class="bgMenuSelect" ';
				else $style = ' style="border-right:1px solid '.$bgcolor1.';" ';
				$this->html .= '<td onMouseOver="this.style.backgroundColor=\''.$bgcolor1.'\';" onMouseOut="this.style.backgroundColor=\'\';" '.$style.' nowrap><img src="../images/flech_menu.png" width="12" height="12" border="0" align="absmiddle">'.$RmyForm['titre'].' <a href="index.php?mode=fiche'.$this->urlrub.$catUrl.$childUrl.'"><img src="../images/ajout.gif" border="0" align="absmiddle" alt="Ajouter"></a> <a href="index.php?mode=liste'.$this->urlrub.$catUrl.$childUrl.'"><img src="../images/liste.gif" border="0" align="absmiddle" alt="Lister"></a></td>';
			}
			$this->html .= '</tr>
			</table></td>
			</tr>
			</table>';
		}
		
		$this->html .= '<table width="100%"  border="0" cellpadding="4" cellspacing="0"  class="bgTableauTitre">
		<tr>
		<td height="20">&nbsp;';
		if ($this->id < 1) { $this->html .= 'Ajouter'; } else { $this->html .= 'Modifier'; }
		$this->html .= ' un'.$this->table['genre'].' '.$this->table['titre'].'&nbsp;<img src="../images/flech_show.png" width="14" height="14" align="absmiddle"></td>
		<td align="right">';
		if ($this->table['fixe'] != '1' && $this->table['fixe'] != '2') {
			$this->html .= '<table border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="javascript:history.back();" class="menu">RETOUR A LA LISTE</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr>
			</table>';
		}
		else $this->html .= '&nbsp;';
		$this->html .= '</td>
		</tr>
		</table>';

		if ($this->table['tips'] != '') $this->html .= '<span class="texte">'.$this->table['tips'].'</span><br />'; // TABLE TIPS
		
		$this->html .= '<br /><table width="100%" border="0" cellpadding="2" cellspacing="1" class="bor1">';
		// FORM ###
		$this->html .= '<form action="'.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.'&amp;action=update&id='.$this->id.'" method="'.$this->methode.'" enctype="multipart/form-data" id="'.$this->formName.'" name="'.$this->formName.'" >'; // onkeypress="kH();"
		// Script
		$this->html .= $this->createSCRIPT();

		//  VALIDER / PREVIEW / DUPLIQUER ------------------------------------
		$this->html .= '<tr>
		<td colspan="2" align="center" nowrap class="table-bas"><table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		';
		// Button VALIDER
		$this->html .= '<td  id="valider2" class="menu" height="20"><table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
		<td nowrap background="../images/images/button_02.png"><a href="javascript:'.$this->scriptName.'(document.'.$this->formName.');" class="menu">VALIDER</a></td>
		<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
		</tr>
		</table></td>';
		
		// Button Preview
		if ($this->Preview == 1) {
			$this->html .= '<td>&nbsp;&nbsp;</td>';
			$this->html .= '<td><input type="hidden" name="preview" id="preview" value="0"><table  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="javascript:$(\'preview\').value=\'1\';'.$this->scriptName.'(document.'.$this->formName.');" class="menu">PREVISUALISER</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr>
			</table></td>';
			
		}
		// Button DUPLICATE
		if ($this->table['duplicate'] == 1 && $this->id > 0) {
			$this->html .= '<td>&nbsp;&nbsp;</td>';
			$this->html .= '<td><input type="hidden" name="duplicate" id="duplicate" value="0"><table  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="javascript:$(\'duplicate\').value=\'1\';'.$this->scriptName.'(document.'.$this->formName.');" class="menu">DUPLIQUER</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr>
			</table></td>';
		}
		
		$this->html .= '
		</tr>
		</table>';
		$this->html .= '</td>
		</tr>';



		// LISTE MULTI-SELECT RUBRIQUE WITH NIVEAU ////////////////////
		if ($this->table['childRel'] == '1') {
			$this->html .= '<tr>
			<td align="right" nowrap class="table-entete'.$this->r.'" valign="top">Rubrique(s) parente(s) :</td>
			<td width="85%" class="table-ligne'.$this->r.' comment" style="padding:6px;">';
			$this->html .= '<div id="rubListe" style="position:relative; left:0; top:0; z-index:1; overflow: auto; background: #CCCCCC; layer-background-color: #CCCCCC; width: 100%; height: 200; " class="bor1"><table width="100%" border="0" cellpadding="2" cellspacing="1">';
			list($tablle,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']); //
			$H = new SQL(array(table=>$tablle));
			$H->LireSql(array($idName,$champRel)," $idName!='' ORDER BY ordre DESC ");
			$arbo = array();
			// Scan de toutes les rubriques --> ID <> PARENT_ID
			for ($i=0; $i<count($H->V); $i++) { $arbo[] = array($H->V[$i][$idName],$H->V[$i][$champRel]); } // Build array
			list($minNiveau,$maxNiveau) = explode(':',$R0['rubLevel']);
			$maxNiveau++; // Add 1 nivel to show liste (Difference with "create")
			$this->GetNiveau($arbo,'0','0',$maxNiveau,'2');
			$this->html .= '</table></div>';
			$this->html .= '</td>
			</tr>';
			$this->r==2 ? $this->r=1 : $this->r++;
		}
				
		// Here the input ROW ////////////////////
		$i = 1; // No id...
		while($i < count($this->data)) {
		
			if ($this->id < 1 && (empty($this->champRelValue) && $this->champRelValue != '0') )  $this->data[$i]['level']['0'] = 0; // Tout s'affiche a l'insertion si pas de post sur parent_id
			if (!!empty($this->data[$i]['level'])) $this->data[$i]['level']['0'] = $this->level; // Defaut data level array : s'affiche

			if ($this->data[$i]['input'] != '' && in_array($this->level,$this->data[$i]['level'])) { // D1
		
			// Rajout d'un TAG de ligne pour rajouter un séparateur
			if($this->data[$i]['separateur']) {
				$this->html .= '<tr>
				<td class="table-titre" align="center" colspan="2" height="30" class="whiteLink"><b>'.aff($this->data[$i]['separateur']).'</b></td>
				</tr>';
			}
			
			if ($this->data[$i]['bilingue'] == 1) { // BILINGUE // ------------------------------------
					foreach ($langues as $langue) { 
						$this->html .= '<tr>
						<td align="right" nowrap class="table-entete'.$this->r.'" valign="top">';
						if (!empty($this->data[$i]['titre']) && $this->data[$i]['titre'] != '') { $this->html .= aff($this->data[$i]['titre']); }
						else { $this->html .= ucfirst(aff($this->data[$i]['name'])); }
						if (count($langues) > 1) $this->html .= ' <em>'.strtoupper($langue).'</em>';
						if ($this->data[$i]['oblige'] == 1) $this->html .= '<sup>*</sup> ';
						$this->html .= ' :</td>
						<td width="85%" class="table-ligne'.$this->r.' comment">';
						
						if ($this->id > 0) { $valeur = $F->V['0'][$this->data[$i]['name'].'_'.$langue]; }
						elseif (!empty($this->data[$i]['htmDefaut']) && $this->data[$i]['htmDefaut'] != '') { 
							if ($this->data[$i]['htmDefaut'] == 'post') { $valeur = aff(clean($_POST[$this->data[$i]['name'].'_'.$langue])); }
							else { $valeur = $this->data[$i]['htmDefaut']; }
						} else { $valeur = ''; }
						
						switch($this->data[$i]['input']) { // SWITCH INPUT
							case 'text' :
							if ($this->data[$i]['htmDefaut'] == 'couleur') {
								$this->html .= '<table border="0" cellspacing="0" cellpadding="2">
								<tr>
								<td>';
							}
							$this->html .= '<input name="'.$this->data[$i]['name'].'_'.$langue.'" id="'.$this->data[$i]['name'].'_'.$langue.'" type="text" value="'.aff($valeur).'" size="';
							if (strpos($this->data[$i]['nbChar'],',') !== false) { 
								list($nbChar,$suite) = explode(',',$this->data[$i]['nbChar']);
								$nbChar++;
								$this->html .= $nbChar;
							}							
							else $this->html .= $this->data[$i]['nbChar'];
							$this->html .= '" maxlength="'.$this->data[$i]['nbChar'].'" ';
							if ($this->data[$i]['nbChar'] > 10) { $this->html .= 'style="width:80%;"'; }
							if ($this->data[$i]['disable'] == '1') { $this->html .= ' onfocus="blur();" '; }
							$this->html .= '>';
							if ($this->data[$i]['htmDefaut'] == 'date') {
								$this->html .= '&nbsp;<a href="javascript:void(0)" onclick="if(self.gfPop)gfPop.fPopCalendar(document.'.$this->formName.'.'.$this->data[$i]['name'].'_'.$langue.');return false;"><img class="PopcalTrigger" align="absmiddle" src="../lib/calendar/calendar.gif" width="20" height="15" border="0" alt="Calendrier"></a>';
								if ($this->iframeonce != true) {
									$this->iframeonce = true;
									$this->html .= '<iframe width="132 height=""142" name="gToday:contrast:../lib/agenda.js" id="gToday:contrast:../lib/agenda.js" src="../lib/calendar/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; left:-500px; top:0px;"></iframe>';
								}
							}
							if ($this->data[$i]['htmDefaut'] == 'couleur') {
								$this->html .= '</td>
								<td><div class="bor1" style="background-color:'.aff($valeur).'; width:20px; height:20px;"></div></td>
								</tr>
								</table>';
							}
							break;
							//
							case 'textarea' :
							if (!empty($this->data[$i]['wysiwyg']) && $this->data[$i]['wysiwyg'] > 0) {
								$$Ed = 'Ed_'.$langue.$i;
								$$Ed = new TinyMce($this->data[$i]['name'].'_'.$langue);
								
								$$Ed->width = '80%';
								$$Ed->height = '360';
								if ($this->data[$i]['wysiwyg'] == 2) $$Ed->ToolbarSet = 'BasicStyle';
								elseif ($this->data[$i]['wysiwyg'] == 3) 	{ $$Ed->ToolbarSet = 'BasicTab'; $$Ed->height = '500'; }
								elseif ($this->data[$i]['wysiwyg'] == 4) 	{ $$Ed->ToolbarSet = 'BasicImg'; $$Ed->height = '500'; }
								elseif ($this->data[$i]['wysiwyg'] == 5) 	{ $$Ed->ToolbarSet = 'BasicFormat'; $$Ed->height = '500'; }
								else $$Ed->ToolbarSet = 'Basic';
								
								if ($this->id > 0) { $$Ed->Value = str_replace('&quot;','"',aff($F->V['0'][$this->data[$i]['name'].'_'.$langue])); }
								else { $$Ed->Value = str_replace('&quot;','"',aff($valeur)); }
								$this->html .= $$Ed->Create();
							}
							else {
								$this->html .= '<textarea name="'.$this->data[$i]['name'].'_'.$langue.'" id="'.$this->data[$i]['name'].'_'.$langue.'" cols="60" rows="10" wrap="VIRTUAL" style="width:80%;" onfocus="new ResizingTextArea(this);" >';
								if ($this->id > 0) { $this->html .= aff($F->V['0'][$this->data[$i]['name'].'_'.$langue],0); }
								else { $this->html .= aff($valeur); }
								$this->html .= '</textarea>';
							}
							break;
							//
							case 'radio' :
							$j = 0;
							while($j < count($this->data[$i]['valeur'])) {
								$this->html .= '<input name="'.$this->data[$i]['name'].'_'.$langue.'" id="'.$this->data[$i]['name'].'_'.$langue.'" type="radio" class="radio" value="'.$this->data[$i]['valeur'][$j].'"';
								if ($this->id > 0) {
									if ($F->V['0'][$this->data[$i]['name'].'_'.$langue] == $this->data[$i]['valeur'][$j]) { $this->html .= ' checked'; } 
								}
								elseif ($j == '0') { $this->html .= ' checked'; }
								if ($this->data[$i]['disable'] == '1') { $this->html .= ' onfocus="blur();" '; }
								$this->html .= '>&nbsp;'.$this->data[$i]['titrevaleur'][$j].' ';
								$j++;
							}
							break;
							//
							case 'select' :
							if (!empty($this->data[$i]['inc']) || $this->data[$i]['inc'] != '') { // INCLUDE SELECT OTHER TABLE
								list($tableName,$idName,$champsName,$unique) = explode(':',$this->data[$i]['inc']);
								if (strpos('-',$champsName) !== false) { // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
									$TchampsName = explode('-',$champsName);
									$champsNameSelect = implode(',',$TchampsName);
								}
								else $champsNameSelect = $champsName;

								$this->html .= '<select name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'" ';
								if ($this->data[$i]['disable'] == '1') { $this->html .= ' onfocus="blur();" '; }
								$this->html .= '>';
								if ($this->id > 0) { $selected = $F->V['0'][$this->data[$i]['name']]==$S->V[$e]['id'] ? ' style="background:#CCCCCC;" selected ' : ''; }
								else { $selected = $valeur==$S->V[$e]['id'] ? ' style="background:#CCCCCC;" selected ' : ''; }
								$this->html .= '<option value="" '.$selected.'>Pas de valeur</option>';
								if ($unique == 'unique') { // Could not have same valor already declared with himself
									$U = new SQL($this->table);
									$U->LireSql(array($this->data[$i]['name']),'');
									$exist = array();
									for ($k=0; $k<count($U->V); $k++) {
										$exist[] = $U->V[$k][$this->data[$i]['name']];
									}
								}
								$S = new SQL($tableName); // Fetch Values
								$S->LireSql(array($idName,$champsNameSelect),'');
								
								if (count($S->V) > 0) {
									for ($e=0; $e<count($S->V); $e++) {
										if ($this->id > 0) { $selected = $F->V['0'][$this->data[$i]['name']]==$S->V[$e]['id'] ? ' style="background:#CCCCCC;" selected ' : ''; }
										else { $selected = $valeur==$S->V[$e][$idName] ? ' style="background:#CCCCCC;" selected ' : ''; }
										if (strpos('-',$champsName) !== false) { // TO CHECK FUCKING HELL ... CF  NO BILINGUE !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
											$valeurP = '';
											foreach($TchampsName as $champsNamesel)  { $valeurP .= ' '.aff($S->V[$e][$champsNamesel]); echo $champsNamesel.'<br />'; }
										} else {
											$valeurP = aff($S->V[$e][$champsName]);
										}
										if ($unique != 'unique') {
											$this->html .= '<option value="'.aff($S->V[$e][$idName]).'" '.$selected.'>'.cs($valeurP, 100).'</option>';
										}
										elseif (!in_array($S->V[$e][$idName],$exist) || $valeur==$S->V[$e][$idName]){
											$this->html .= '<option value="'.aff($S->V[$e][$idName]).'" '.$selected.'>'.cs($valeurP, 100).'</option>';
										}
									}
								}
								$this->html .= '</select>';
							}
							else {
								$this->html .= '<select name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'" ';
								if ($this->data[$i]['disable'] == '1') { $this->html .= ' onfocus="blur();" '; }
								$this->html .= '>';
								if ($this->data[$i]['relation'] == '1') { // RELATION CATEGORIE
									// $R1['table'].':id:titre:cat_id'
									list($tableName,$idName,$champsName,$champRel) = explode(':',$this->table['relation']);
										if (strpos('-',$champsName) !== false) { // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
										$TchampsName = explode('-',$champsName);
										$champsNameSelect = implode(',',$TchampsName);
									}
									else $champsNameSelect = $champsName;
									$C = new SQL($tableName);
									$C->LireSql(array($idName,$champsName),'');
									for ($j=0; $j < count($C->V); $j++) {
										$this->html .= '<option value="'.$C->V[$j][$idName].'"';
										if ($C->V[$j][$idName] == $this->cat_id) { $this->html .= 'selected'; }
										$this->html .= '>'.cs($C->V[$j][$champsName], 100).'</option>';
									}
								}
								else {
									for ($j=0; $j < count($this->data[$i]['valeur']); $j++) {
										$this->html .= '<option value="'.$this->data[$i]['valeur'][$j].'"';
										if ($this->id > 0) {
											if ($F->V['0'][$this->data[$i]['name']] == $this->data[$i]['valeur'][$j]) { $this->html .= 'selected'; } 
										}
										elseif ($j == '0') { $this->html .= ' selected'; }
										$this->html .= '> '.cs($this->data[$i]['titrevaleur'][$j], 100).'</option>';
									}
								}
								$this->html .= '</select>';
							}
							break;
							//
							case 'file' :
							$this->html .= '<input name="MAX_FILE_SIZE" type="hidden" value="'.$maxUploadSize.'"><input name="'.$this->data[$i]['name'].'_'.$langue.'" id="'.$this->data[$i]['name'].'_'.$langue.'" type="file" size="40"><br />';
							if ($this->id > 0) {
								if ($F->V['0'][$this->data[$i]['name'].'_'.$langue] != '') {
									$this->html .= '<input type="checkbox" name="eff_'.$this->data[$i]['name'].'_'.$langue.'" id="eff_'.$this->data[$i]['name'].'_'.$langue.'" value="1" class="radio"><span class="comment">&nbsp;Cocher pour effacer ce fichier</span> ';
									$ext = getExt($F->V['0'][$this->data[$i]['name'].'_'.$langue]);
									if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') {
										if (is_file($root.$this->table['rep'].$grand.$F->V['0'][$this->data[$i]['name'].'_'.$langue]))
											$big = $root.$this->table['rep'].$grand.$F->V['0'][$this->data[$i]['name'].'_'.$langue];
										else $big = $root.$this->table['rep'].$F->V['0'][$this->data[$i]['name'].'_'.$langue];
										$this->html .= '<a href="javascript:void(0);" onClick="popImg(\''.$big.'\',\'View\');"><img src="'.$root.$this->table['rep'].$mini.$F->V['0'][$this->data[$i]['name'].'_'.$langue].'" alt="" border="0" class="bor1" align="right"></a>';
									}
									$this->html .= ' (<a href="'.$root.$this->table['rep'].$F->V['0'][$this->data[$i]['name'].'_'.$langue].'" target="_blank">'.$F->V['0'][$this->data[$i]['name'].'_'.$langue].'</a>)';
								}
								else { $this->html .= ' (Aucun)'; }
							} else { $this->html .= ' (Aucun)'; }
							
							break;

						}
						
						if (!empty($this->data[$i]['tips']) && $this->data[$i]['tips'] != '') { 
							//<img src="../images/tips.gif" width="18" height="18" align="absmiddle" title="'.$this->data[$i]['tips'].'">
							// EVAL TIPS :
							// 'Liste des <a href="../mod_membres_cabinet/index.php?mode=fiche&id=\'.$F->V[\'0\'][$this->data[$i][\'name\'].\'_\'.$langue].\'" target="_blank">Profils</a>'
							if (strpos($this->data[$i]['tips'], '$') !== false) eval('$tips = \''.stripslashes($this->data[$i]['tips']).'\';');
							else $tips = aff($this->data[$i]['tips']);
							
							$this->html .= '<div class="comment" style="margin:4;">'.$tips.'</div>';
						}
						$this->html .= '</td>
						</tr>';
					}
				}
				else { // NO BILINGUE // ------------------------------------ ////////////////////////////////////////////////////
					$this->html .= '<tr>
					<td align="right" nowrap class="table-entete'.$this->r.'" valign="top">';
					
					if ($this->data[$i]['disable'] == 1) $this->html .= '<em style="font-weight:normal;" title="Automatique">';
					if ($this->data[$i]['titre'] != '') $this->html .= aff($this->data[$i]['titre']);
					else $this->html .= ucfirst($this->data[$i]['name']);
					if ($this->data[$i]['disable'] == 1) $this->html .= '</em>';
					if ($this->data[$i]['oblige'] == 1) $this->html .= '<sup>*</sup> ';
					$this->html .= ' :</td>
					<td width="85%" class="table-ligne'.$this->r.' comment">';
					
					if ($this->id > 0) { // FIND THE VALUE
						if ($this->data[$i]['htmDefaut'] == 'date') $valeur = rDate($F->V['0'][$this->data[$i]['name']]);
						elseif ($this->data[$i]['htmDefaut'] == 'datetime') {
							if ($F->V['0'][$this->data[$i]['name']] == '0000-00-00 00:00' || $F->V['0'][$this->data[$i]['name']] == '0000-00-00 00:00:00') $valeur = '';
							else $valeur = substr($F->V['0'][$this->data[$i]['name']],0,-3);
						}
						else $valeur = $F->V['0'][$this->data[$i]['name']];
					}
					elseif ($this->data[$i]['htmDefaut'] != '') { 
						if ($this->data[$i]['htmDefaut'] == 'post') $valeur = !empty($_POST[$this->data[$i]['name']]) ? aff(clean($_POST[$this->data[$i]['name']])) : aff(clean($_GET[$this->data[$i]['name']]));
						elseif ($this->data[$i]['htmDefaut'] == 'date') $valeur = date("d/m/Y");
						elseif ($this->data[$i]['htmDefaut'] == 'datetime') $valeur = $this->data[$i]['oblige'] == 1 ? getDateTime() : '';
						else $valeur = $this->data[$i]['htmDefaut'];
					}
					else $valeur = '';

					switch($this->data[$i]['input']) { // SWITCH INPUT
						case 'text' :
						if ($this->data[$i]['name'] == 'ordre' && $valeur == '') { // FIND ORDER IF EMPHTY......................................
							$N = new SQL($this->table);
							if ($this->cat_id > 0 && $this->table['relation'] != '') {
								list($tableName,$idName,$champsName,$champRel) = explode(':',$this->table['relation']);
								$where = " $champRel='$this->cat_id' ";
							} else $where = " id!='' ";
							$N->LireSql(array('ordre'),$where." ORDER BY ordre DESC LIMIT 1");
							$valeur = ($N->V[0]['ordre']+10);
						}
						if ($this->data[$i]['htmDefaut'] == 'couleur') {
							$this->html .= '<table border="0" cellspacing="0" cellpadding="2">
							<tr>
							<td>';
						}

						if ($this->data[$i]['htmDefaut'] == 'bibliotheque') {
							$this->html .= '<table border="0" cellspacing="0" cellpadding="2" width="60%"><tr><td>';
							if ($this->id < 1) $valeur = '';
							$bib_input_param = ' id="'.$this->data[$i]['name'].'" onfocus="this.blur();" onClick="myPop(\'../bibliotheque/pop.php?input='.$this->data[$i]['name'].'\',\'pop\',\'900\',\'700\');" ';
						}
						else $bib_input_param = '';

						// Input debut
						$size = intval($this->data[$i]['nbChar'])+4;
						$this->html .= '<input name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'" type="text" value="'.aff($valeur).'" size="'.$size.'" '.$bib_input_param.' maxlength="';
						if (strpos($this->data[$i]['nbChar'],',') !== false) { // FLOAT....
							list($nbChar,$suite) = explode(',',$this->data[$i]['nbChar']);
							$nbChar++;
							$this->html .= $nbChar;
						}							
						else $this->html .= $this->data[$i]['nbChar'];
						$this->html .= '" ';
						if ($this->data[$i]['htmDefaut'] == 'datetime' && $this->data[$i]['disable'] != '1') $this->html .= 'style="width:110px;" readonly onClick="displayCalendar(this,\'yyyy/mm/dd hh:ii\',this,true,true)" ';
						elseif ($this->data[$i]['htmDefaut'] == 'datetime') $this->html .= 'style="width:110px;" readonly ';
						elseif ($this->data[$i]['htmDefaut'] == 'date') $this->html .= 'style="width:70px;" readonly onClick="displayCalendar(this,\'dd/mm/yyyy\',this,false,false)" ';
						elseif($this->data[$i]['htmDefaut'] == 'video') $this->html .= 'onClick="javascript:promptVideo(\''.$this->data[$i]['name'].'\');" ';

						if ($this->data[$i]['htmDefaut'] == 'bibliotheque') $this->html .= 'style="width:100%;"';
						elseif ($this->data[$i]['nbChar'] > 12) $this->html .= 'style="width:80%;"';
						
						if ($this->data[$i]['disable'] == '1') $this->html .= 'onfocus="blur();" ';
						$this->html .= '/>';
						// Input fin

						if ($this->data[$i]['htmDefaut'] == 'couleur') {
							$this->html .= '</td>
							<td><div class="bor1" style="background-color:'.aff($valeur).'; width:20px; height:20px;"></div></td>
							</tr>
							</table>';
						}
						elseif ($this->data[$i]['htmDefaut'] == 'bibliotheque') {
							$this->html .= '</td><td><a href="javascript:void(0);" onClick="myPop(\'../bibliotheque/pop.php?input='.$this->data[$i]['name'].'\',\'pop\',\'900\',\'700\');"><img src="../images/image.gif" border="0" /></a></td></tr></table>';
							
							if ($this->id > 0) {
								if ($F->V['0'][$this->data[$i]['name']] != '') {
									$this->html .= '<div id="'.$this->data[$i]['name'].'Img"><a href="javascript:void(0);" onClick="popImg(\''.$root.'medias/bibliotheque/'.$F->V['0'][$this->data[$i]['name']].'\',\'View\');"><img src="'.$root.'medias/bibliotheque/'.$mini.$F->V['0'][$this->data[$i]['name']].'" alt="" border="0" class="bor1" align="right"></a></div>';
									$this->html .= '<input type="checkbox" name="eff_'.$this->data[$i]['name'].'" id="eff_'.$this->data[$i]['name'].'" value="1" class="radio"><span class="comment">&nbsp;Cocher pour effacer ce fichier</span> ';
								}
								else $this->html .= ' (Aucun)<div id="'.$this->data[$i]['name'].'Img"></div>';
							} else $this->html .= ' (Aucun)<div id="'.$this->data[$i]['name'].'Img"></div>';
						}
						break;
						//
						case 'textarea' :
						if (!empty($this->data[$i]['wysiwyg']) && $this->data[$i]['wysiwyg'] > 0) {
							
							$$Ed = 'Ed'.$i;
							$$Ed = new TinyMce($this->data[$i]['name']);
							
							$$Ed->width = '80%';
							$$Ed->height = '220';
							if ($this->data[$i]['wysiwyg'] == 2) $$Ed->ToolbarSet = 'BasicStyle';
							elseif ($this->data[$i]['wysiwyg'] == 3) { $$Ed->ToolbarSet = 'BasicTab'; $$Ed->height = '500'; }
							elseif ($this->data[$i]['wysiwyg'] == 4) { $$Ed->ToolbarSet = 'BasicImg'; $$Ed->height = '500'; }
							elseif ($this->data[$i]['wysiwyg'] == 5) { $$Ed->ToolbarSet = 'BasicFormat'; $$Ed->height = '500'; }
							else $$Ed->ToolbarSet = 'Basic';
							
							if ($this->id > 0) { $$Ed->Value = str_replace('&quot;','"',aff($F->V['0'][$this->data[$i]['name']])); }
							else { $$Ed->Value = str_replace('&quot;','"',aff($valeur)); }
							$this->html .= $$Ed->Create();
						}
						else {
							$row = 10;
							if ($this->data[$i]['wysiwyg'] === 'longText') $row = 33;
							$this->html .= '<textarea name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'" cols="60" rows="'.$row.'" wrap="VIRTUAL" style="width:80%;" ';
							if ($this->data[$i]['disable'] == '1') { $this->html .= ' onfocus="blur();" '; }
							else $this->html .= ' onfocus="new ResizingTextArea(this);" ';
							$this->html .= '>';
							if ($this->id > 0) { $this->html .= aff($F->V['0'][$this->data[$i]['name']],0); }
							else { $this->html .= aff($valeur); }
							$this->html .= '</textarea>';
						}
						break;
						//
						case 'radio' :
						for ($j=0; $j < count($this->data[$i]['valeur']); $j++) {
							$this->html .= '<input name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'_'.cleanName($this->data[$i]['valeur'][$j]).'" type="radio" class="radio" value="'.$this->data[$i]['valeur'][$j].'"';
							if ($this->id > 0) {
								if ($F->V['0'][$this->data[$i]['name']] == $this->data[$i]['valeur'][$j]) $this->html .= ' checked';
							}
							elseif ($j == '0') $this->html .= ' checked';
							if ($this->data[$i]['disable'] == '1') $this->html .= ' onfocus="blur();" ';
							$this->html .= '>&nbsp;<label for="'.$this->data[$i]['name'].'_'.cleanName($this->data[$i]['valeur'][$j]).'">'.$this->data[$i]['titrevaleur'][$j].'</label> ';
						}
						break;
						//
						case 'select' :

						if (!empty($this->data[$i]['inc']) || $this->data[$i]['inc'] != '') { // INCLUDE SELECT OTHER TABLE
							if (count(explode(':',$this->data[$i]['inc'])) >= 3) {
								list($tableName,$idName,$champsName,$unique,$condition) = explode(':',$this->data[$i]['inc']);
								if (strpos($champsName,'-') !== false) {
									$TchampsName = explode('-',$champsName);
									$select = array($idName);
									foreach($TchampsName as $champsN) $select[] = $champsN;
								}
								else $select = array($idName,$champsName);
										
								$this->html .= '<select name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'" ';
								if ($this->data[$i]['disable'] == '1') $this->html .= ' onfocus="blur();" ';
								
								if ($this->data[$i]['htmDefaut'] == 'cms') {
									$this->html .= ' onChange="$(\'cmsPreview\').src=\'cmsPreview.php?element='.$this->id.'&cms=\'+this.options[this.selectedIndex].value;" ';
								}
								
								$this->html .= '>';
								if ($this->id > 0) { $selected = $F->V['0'][$this->data[$i]['name']]=='' ? ' style="background:#CCCCCC;" selected ' : ''; }
								else { $selected = $valeur=='' ? ' style="background:#CCCCCC;" selected ' : ''; }
								$this->html .= '<option value="" '.$selected.'>Pas de valeur</option>';

								if ($unique == 'unique') { // Could not have same valor already declared for other product
									$U = new SQL($this->table);
									$U->LireSql(array($this->data[$i]['name']),'');
									$exist = array();
									for ($k=0; $k<count($U->V); $k++) $exist[] = $U->V[$k][$this->data[$i]['name']];
								}
								$S = new SQL($tableName);
								$S->LireSql($select,($condition!=''?$condition:'1')." ORDER BY ".$select[1]." ASC");

								for ($e=0; $e<count($S->V); $e++) {
									if ($this->id > 0) $selected = $F->V['0'][$this->data[$i]['name']]==$S->V[$e]['id'] ? ' style="background:#CCCCCC;" selected ' : '';
									else $selected = ( $valeur==$S->V[$e][$idName] ? ' style="background:#CCCCCC;" selected ' : '');

									if (strpos($champsName,'-') !== false) {
										$valeurP = '';
										foreach($TchampsName as $champsN) $valeurP .= ' '.aff($S->V[$e][$champsN]);
									}
									else $valeurP = aff($S->V[$e][$champsName]);

									if ($unique != 'unique')
										$this->html .= '<option value="'.aff($S->V[$e][$idName]).'" '.$selected.'>'.cs($valeurP, 100).'</option>';
									elseif (!in_array($S->V[$e][$idName],$exist) || $valeur==$S->V[$e][$idName])
										$this->html .= '<option value="'.aff($S->V[$e][$idName]).'" '.$selected.'>'.cs($valeurP, 100).'</option>';
								}
								$this->html .= '</select>';		
														
							}
							else $this->html .= eval(file_get_contents($this->data[$i]['inc'])); // Include a file who make the requete
						}
						else {
							$this->html .= '<select name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'"';
							if ($this->data[$i]['disable'] == '1') $this->html .= ' onfocus="blur();" ';

							if ($this->data[$i]['relation'] == '1') { // RELATION RUBRIQUE OU CATEGORIE
								// $R1['table'].':id:titre:cat_id' ///  
								
								list($tableName,$idName,$champsName,$champRel,$condition) = explode(':',$this->table['relation']);
								if (strpos($champsName,'-') !== false) {
									$TchampsName = explode('-',$champsName);
									$select = array($idName);
									foreach($TchampsName as $champsN) $select[] = $champsN;
								}
								else $select = array($idName,$champsName);
								if ($this->isrub) { // RELATION RUBRIQUE ////////////////////////////////////////////////////////////////////////////////////////////////

									//list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
									//list($relRubId,$rubId) = explode('=',$relRubId_rubId);
									list($relProdId,$prodId) = explode('=',$relProdId_prodId);
									list($table,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);
									list($minNiveau,$maxNiveau) = explode(':',$R0['rubLevel']);
									
									//if ($this->data[$i]['htmDefaut'] == 'post') $this->html .= ' onchange="if(this.options[this.selectedIndex].value!=\'\')window.location=\''.str_replace('&'.$champRel.'='.$_GET[$champRel],'',thisUrl()).'&'.$champRel.'=\'+this.options[this.selectedIndex].value;"';
									$this->html .= '>';
			
									$this->html .= '<option value="'.($minNiveau==0?'0':'').'">RACINE</option>';
									//
									$B = new SQL($table);
									$B->LireSql(array($idName,$champRel)," $idName!='' ORDER BY ordre DESC");
									$arbo = array();
									// Scan de toutes les rubriques
									for ($y=0; $y<count($B->V); $y++) { $arbo[] = array($B->V[$y][$idName],$B->V[$y][$champRel]); } // Build array
									
									$this->GetNiveau($arbo,'0', '0', $maxNiveau, 1, $valeur);
									
									// $VALEUR A METTRE DANS LES SELECTED DES AUTRES SELECT ???????????????????? ////////////////////////////////////////////////////////

								}
								else { // RELATION CATEGORIE
									$this->html .= '>';
									$C = new SQL($tableName);
									$C->LireSql($select,$condition);
									for ($j=0; $j < count($C->V); $j++) {
										if (strpos($champsName,'-') !== false) {
											$valeur = ''; foreach($TchampsName as $champsN) $valeur .= ' '.aff($C->V[$j][$champsN]);
										}
										else $valeur = aff($C->V[$j][$champsName]);
										
										$this->html .= '<option value="'.$C->V[$j][$idName].'"';
										if ($C->V[$j][$idName] == $this->cat_id) { $this->html .= 'selected'; }
										$this->html .= '>'.cs($valeur, 100).'</option>';
									}
								}
							}
							else { // BUILD SELECT WITH ARRAY
								$this->html .= '>';
								for ($j=0; $j < count($this->data[$i]['valeur']); $j++) {
									$this->html .= '<option value="'.$this->data[$i]['valeur'][$j].'"';
									if ($this->id > 0) {
										if ($F->V['0'][$this->data[$i]['name']] == $this->data[$i]['valeur'][$j]) { $this->html .= 'selected'; } 
									}
									elseif ($j == '0') { $this->html .= ' selected'; }
									$this->html .= '> '.cs($this->data[$i]['titrevaleur'][$j], 100).'</option>';
								}
							}
							$this->html .= '</select>';
						}
						

						if ($this->data[$i]['htmDefaut'] == 'cms') {
							$this->html .= '&nbsp;<b>PREVISUALISATION</b> <img width="14" height="14" align="absmiddle" src="../images/flech_show.png"/> (<a href="../../'.urlRewrite('previsualisation', 'r'.$this->rub_id).'" target="_blank">Voir la page sur le site</a>)<br /><iframe name="cmsPreview" id="cmsPreview" width="98%" height="280" src="cmsPreview.php?element='.$this->id.'&cms='.$F->V['0'][$this->data[$i]['name']].'" frameborder="0" allowtransparency="1" scrolling="auto" style="border:1px solid #000000; margin:8px;"></iframe>';
						}
						
						break;
						////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						case 'multiselect' :
						//array(name=>'produits_ids',titre=>'Produits associés',input=>'multiselect',relation=>'produits_relation_realisations:produits:cat_id=id:prod_id=id:nom-prenom#cat_id!=2'),
						list($tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$prodTitre) = explode(':',$this->data[$i]['relation']);
						list($relRubId,$rubId) = explode('=',$relRubId_rubId);
						list($relProdId,$prodId) = explode('=',$relProdId_prodId);

						if (strpos($prodTitre,'#') !== false) {
							list($prodTitre, $whereSel) = explode('#', $prodTitre);
						}
						if (strpos($prodTitre,'-') !== false) {
							$TprodTitre = explode('-',$prodTitre);
							$select = array($tableProd.'.'.$prodId);
							$selectT = array($prodId);
							foreach($TprodTitre as $champsN) { $select[] = $champsN; $selectT[] = $tableProd.'.'.$champsN; }
						}
						else { $select = array($prodId,$prodTitre); $selectT = array($tableProd.'.'.$prodId,$tableProd.'.'.$prodTitre); }
						
						
						$this->html .= '<table width="100%" border="0" cellpadding="3" cellspacing="0" class="table-ligne'.$this->r.' comment">
						<tr>
						<td width="50%" height="20" align="center" valign="bottom" nowrap="nowrap">Liste complète :</td>
						<td rowspan="2"><input type="button" class="bouton" title="Ajouter" onClick="MoveOption(\'select_'.$this->data[$i]['name'].'_list\',\'select_'.$this->data[$i]['name'].'\', \'MOVE\', \'1\');" value="&gt;&gt;" /><br/>
						<br />
						<input type="button" class="bouton" title="Retirer"  onClick="MoveOption(\'select_'.$this->data[$i]['name'].'\',\'select_'.$this->data[$i]['name'].'_list\', \'MOVE\', \'1\');" value="&lt;&lt;" /></td>
						<td width="50%" align="center" valign="bottom" nowrap="nowrap">';
						if ($this->data[$i]['titre'] != '') { $this->html .= aff($this->data[$i]['titre']); }
						else { $this->html .= ucfirst($this->data[$i]['name']); }
						$this->html .= ' :</td>
						<td align="center" valign="bottom" nowrap="nowrap">&nbsp;</td>
						</tr>
						<tr>
						<td height="25" align="center" valign="top" nowrap="nowrap"><select name="select_'.$this->data[$i]['name'].'_list[]" id="select_'.$this->data[$i]['name'].'_list" size="20" style="width:100%;" multiple="multiple">';
						
						// Tous les annonces qui ne sont PAS lies
						$IdIn = array();
						if ($this->id > 0) {
							$C = new SQL($tableRel);
							$C->LireSql(array($relProdId)," $relRubId='{$this->id}' ");
							for ($j=0; $j<count($C->V); $j++) $IdIn[] = $C->V[$j][$relProdId];
						}
						$C = new SQL($tableProd);
						$C->LireSql($select, (!empty($whereSel) ? $whereSel : "$prodId!=''")." ORDER BY ".($select[1]?$select[1]:implode(',', $select)).' ASC');
						for ($j=0; $j<count($C->V); $j++)  {
							if (!in_array($C->V[$j][$prodId],$IdIn)) {
								if (strpos($prodTitre,'-') !== false) {
									$valeur = '';
									foreach($TprodTitre as $champsN) $valeur .= ' '.aff($C->V[$j][$champsN]);
								}
								else $valeur = aff($C->V[$j][$prodTitre]);
								$this->html .= '<option value="'.$C->V[$j]['id'].'">'.$valeur.' (#'.$C->V[$j]['id'].')</option>'.$lb;
							}
						}
						$this->html .= '</select></td>
						<td width="50%" align="center" valign="top" nowrap="nowrap">';
						
						$this->html .= '<select name="select_'.$this->data[$i]['name'].'[]" id="select_'.$this->data[$i]['name'].'" size="20" style="width:100%;" multiple="multiple" />';
						
						// Tous les annonces qui SONT lies..
						if ($this->id > 0) {
							$P = new SQL($tableProd);
							$P->customSql("SELECT ".implode(',',$selectT)." FROM $tableProd LEFT JOIN $tableRel ON $tableRel.$relProdId=$tableProd.$prodId WHERE $tableRel.$relRubId='$this->id' ORDER BY $tableRel.ordre ASC "); // ORDER TO CHECK.................................................................. 
							for ($z=0; $z<count($P->V); $z++)  {
								if (strpos($prodTitre,'-') !== false) {
									$valeur = ''; foreach($TprodTitre as $champsN) $valeur .= ' '.aff($P->V[$z][$champsN]);
								}
								else $valeur = aff($P->V[$z][$prodTitre]);
								$this->html .= '<option value="'.aff($P->V[$z]['id']).'">'.$valeur.' (#'.$P->V[$z]['id'].')</option>'.$lb; // MULTI-NAME TO CHECK !!!!!!!!!!!!!!!!!
							}
						}
						$this->html .= '</select>';

						$this->html .= '</td>
						<td align="center" nowrap="nowrap"><input type="button" class="bouton" title="Retirer" onClick="UpDownOption(\'select_'.$this->data[$i]['name'].'\',\'up\');" value="haut" style="width:40px;" />
						<br/>
						<br />
						<input type="button" class="bouton" title="Ajouter" onClick="UpDownOption(\'select_'.$this->data[$i]['name'].'\',\'down\');" value="bas" style="width:40px;"/></td>
						</tr>
						</table>';
						
						break;
						
						////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						
						case 'file' :
						$this->html .= '<input name="MAX_FILE_SIZE" type="hidden" value="'.$maxUploadSize.'"><input name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'" type="file" size="60" style="width: 80%;"><br />';
						if ($this->id > 0) {
							if ($F->V['0'][$this->data[$i]['name']] != '') {
								$this->html .= '<div class="comment" style="margin:4;">';
								$ext = getExt($F->V['0'][$this->data[$i]['name']]);
								if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') {
									$m =& new FILE();
									$m->isMedia($root.$this->table['rep'].$mini.$F->V['0'][$this->data[$i]['name']]);
									$m->css = 'bor1';
									$m->attributes = array('align'=>'right','rel'=>'popimg');
									$this->html .= $m->popImage(FALSE);
									foreach ((array)$this->table['sizeimg'] as $rep=>$size) {
										if ($rep == 'tgrand') $rep = ''; // :)
										else $rep .= '/';
										$m =& new FILE();
										if ($m->isMedia($root.$this->table['rep'].$rep.$F->V['0'][$this->data[$i]['name']])) {
											$m->texte = $this->table['rep'].$rep.$F->V['0'][$this->data[$i]['name']];
											$this->html .= $m->info(FALSE).'<br />';
										}
									}
								}
								else {
									$m =& new FILE();
									$m->isMedia($root.$this->table['rep'].$F->V['0'][$this->data[$i]['name']]);
									$m->texte = $this->table['rep'].$F->V['0'][$this->data[$i]['name']];
									$this->html .= $m->info(FALSE).'<br />';	
								}
								$this->html .= '</div>';
								
								$this->html .= '<input type="checkbox" name="eff_'.$this->data[$i]['name'].'" id="eff_'.$this->data[$i]['name'].'" value="1" class="radio"><label for="eff_'.$this->data[$i]['name'].'"><span class="comment">&nbsp;Cocher pour effacer ce fichier</span></label><br />';
							}
							else { $this->html .= ' (Aucun)'; }
						} else { $this->html .= ' (Aucun)'; }
						
						break;
					}
					
					if (!empty($this->data[$i]['tips']) && $this->data[$i]['tips'] != '') { 
						//<img src="../images/tips.gif" width="18" height="18" align="absmiddle" title="'.$this->data[$i]['tips'].'">
						
						// EVAL TIPS :
						// 'Liste des <a href="../mod_membres_cabinet/index.php?mode=fiche&id=\'.$F->V[\'0\'][$this->data[$i][\'name\']].\'" target="_blank">Profils</a>'
						if (strpos($this->data[$i]['tips'], '$') !== false) @eval('$tips = \''.$this->data[$i]['tips'].'\';');
						else $tips = aff($this->data[$i]['tips']);
						
						$this->html .= '<div class="comment" style="margin:4;">'.$tips.'</div>';
					}
					
					$this->html .= '</td>
					</tr>';
					// ------------------------------------
				}
				$this->r==2 ? $this->r=1 : $this->r++; // 1 ligne sur 2
			} // F1
			else { // HIDDEN FIELD // ------------------------------------
				if ($this->id > 0) { 
					if ($this->data[$i]['htmDefaut'] == 'date') { $valeur = rDate($F->V['0'][$this->data[$i]['name']]); }
					else { $valeur = $F->V['0'][$this->data[$i]['name']]; }
				}
				elseif ($this->data[$i]['htmDefaut'] != '') { 
					if ($this->data[$i]['htmDefaut'] == 'post') { $valeur = clean($_POST[$this->data[$i]['name']]); }
					elseif ($this->data[$i]['htmDefaut'] == 'date') { $valeur = date("d/m/y"); }
					else { $valeur = $this->data[$i]['htmDefaut']; }
				}
				elseif ($this->id < 1 && $this->data[$i]['name'] == 'ordre' && $this->rub_id > 0) { 
					// Find dernier ordre relationnel du produit avant insertion dans sa rubrique
					global $R0;
					list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
					list($relRubId,$rubId) = explode('=',$relRubId_rubId);
					list($relProdId,$prodId) = explode('=',$relProdId_prodId);
					
					$F = new SQL($tableRel); // Scan rub_id deja liée
					$F->LireSql(array('ordre'), " $relRubId='{$this->rub_id}' ORDER BY ordre ASC LIMIT 1");

					$valeur = $F->V[0]['ordre'] - 10;
				}
				else $valeur = '';
				
				$this->hidden .= '<input type="hidden" name="'.$this->data[$i]['name'].'" id="'.$this->data[$i]['name'].'" value="'.aff($valeur).'">';
			}

			$i++; // ITERATION GLOBALE
		} // FIN WHILE
		
		// VALIDATE BUTTON // ------------------------------------
		$this->html .= '<tr>
		<td colspan="2" align="center" nowrap class="table-bas">'.$this->hidden.'<table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		';
		// Button VALIDER
		$this->html .= '<td  id="valider" class="menu" height="20"><table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
		<td nowrap background="../images/images/button_02.png"><a href="javascript:'.$this->scriptName.'(document.'.$this->formName.');" class="menu">VALIDER</a></td>
		<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
		</tr>
		</table></td>';
		
		// Button Preview
		if ($this->Preview == 1) {
			$this->html .= '<td>&nbsp;&nbsp;</td>';
			$this->html .= '<td><table  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="javascript:$(\'preview\').value=\'1\';'.$this->scriptName.'(document.'.$this->formName.');" class="menu">PREVISUALISER</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr>
			</table></td>';
			
		}
		// Button DUPLICATE
		if ($this->table['duplicate'] == 1 && $this->id > 0) {
			$this->html .= '<td>&nbsp;&nbsp;</td>';
			$this->html .= '<td><input type="hidden" name="duplicate" id="duplicate" value="0"><table  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="javascript:$(\'duplicate\').value=\'1\';'.$this->scriptName.'(document.'.$this->formName.');" class="menu">DUPLIQUER</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr>
			</table></td>';
		}
		
		$this->html .= '
		</tr>
		</table>';
		
		
		$this->html .= '</td>
		</tr>';
		// END the input ROW OF FORM // ------------------------------------
		$this->html .= '</form>';
		$this->html .= '</table>';
		
		// -------------------------------------------------------------------------------------------------------------------------
		$this->html .= '</td>
		</tr>
		</table></td>
		</tr>
		</table>';
		// --------------------------------
		echo $this->html;
	}
	
	// - - - - - - - - - - - - - - - - - - - PRINT FICHE MENU SELECT NIVEAU (SELECT) - - - - - - - - - - - - - - - - - - - //
	function PrintSelectNiveau($id,$niveau,$valeur) { // Print a single ROW in liste
		global $R0,$bgcolor1;
		//list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
		list($table,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);

		$N = new SQL($table);
		$N->LireSql(array($titreName)," id='$id' LIMIT 1 ");
		$titreSelect = $N->V[0][$titreName];
		
		list($minNiveau,$maxNiveau) = explode(':',$R0['rubLevel']);
		
		$niveauCheck = $niveau+1; // 1er rubrique a la racine est declare au niveau 0 a la place de 1

		if ($niveauCheck == $minNiveau || ($niveauCheck >= $minNiveau && $niveauCheck <= $maxNiveau)) $optionValue = $id; 
		else $optionValue = '';

		$gris = '#808080';
		if ($niveau == 0) {
			$style = 'style="background:'.$gris.';color:'.($valeur==$id?$bgcolor1:'').';"';
			$titreSelect = strtoupper($titreSelect);
		}
		else {
			$CoulTranche = 1/($maxNiveau+1);
			$rgb = html2rgb($gris); // Gris 50 % 
			$rgb[0] = $rgb[0]*(1+($niveau*$CoulTranche)); $rgb[1] = $rgb[1]*(1+($niveau*$CoulTranche)); $rgb[2] = $rgb[2]*(1+($niveau*$CoulTranche));
			$rgb = rgb2html($rgb);
			$style = 'style="background:'.$rgb.';color:'.($valeur==$id?$bgcolor1:'').';"';
			for ($e=0; $e<$niveau; $e++) $esp .= '—';
		}
		if ($niveauCheck == $minNiveau || ($niveauCheck >= $minNiveau && $niveauCheck <= $maxNiveau)) 
			$this->html .= '<option value="'.($optionValue!=$this->id?$optionValue:'').'" '.($valeur==$id?'selected':'').' '.$style.'>'.$esp.' '.aff($titreSelect).'</option>
			';
		else
			$this->html .= '<optgroup label="'.$esp.' '.aff($titreSelect).'" '.$style.'></optgroup>
			';
		
	}
	
	// - - - - - - - - - - - - - - - - - - - PRINT FICHE MULTI-NIVEAUX CHOOSE (TR) - - - - - - - - - - - - - - - - - - - //
	function PrintMultiSelectNiveau($rub_id_select,$niveau) { // Print arbo to select parent in FICHE prod
		global $R0,$bgcolor1;
		list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']); //
		list($relRubId,$rubId) = explode('=',$relRubId_rubId);
		list($relProdId,$prodId) = explode('=',$relProdId_prodId);
		list($tablle,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);

		$N = new SQL(array(table=>$tablle));
		$N->LireSql(array($catTitre)," id='$rub_id_select' LIMIT 1 ");
		$titreSelect = $N->V[0][$catTitre];
		
		list($minNiveau,$maxNiveau) = explode(':',$this->table['prodLevel']);

		// Find if this Rub_id is parent of the product
		$K = new SQL(array(table=>$tableRel));
		$K->LireSql(array($relRubId)," $relRubId='$rub_id_select' && $relProdId='{$this->id}' LIMIT 1 ");
		
		$this->html .= '<tr class="table-ligne'.$this->r.'" onMouseOver="this.style.backgroundColor=\''.$bgcolor1.'\';" onMouseOut="this.style.backgroundColor=\'\';">
		<td valign="top" '.(count($K->V)>0?'class="table-ligne3"':'').'>';
		
		if ($niveau >= $minNiveau && $niveau <= $maxNiveau) $this->html .= '<input type="checkbox" name="rub_id[]" class="radio" '.(count($K->V)>0?'checked':($this->rub_id==$rub_id_select&&$this->id<1?'checked':'')).' value="'.$rub_id_select.'">';
		
		if ($niveau == '0') $rep = '<img src="../images/navigation/dir/folder.png" width="20" height="17"align="absmiddle" />';
		else $rep = '<img src="../images/spacer.gif" width="'.(8+(($niveau-1)*20)).'" height="14" align="absmiddle" /><img src="../images/navigation/dir/folder_path.png" width="12" height="12" align="absmiddle" /><img src="../images/navigation/dir/folde_f.png" width="16" height="14" align="absmiddle" />';
		
		$this->html .= '</td><td '.(count($K->V)>0?'class="table-ligne3"':'').' width="99%">'.$rep.'&nbsp;<a href="'.$this->pageFiche.$this->urlcat.$this->urlchild.'&id='.$rub_id_select.'">'.aff($titreSelect).'</a></td>';
		$this->html .= '</tr>';
		$this->r==2 ? $this->r=1 : $this->r++;
	}
	
	// - - - - - - - - - - - - - - - - - - - FUNCTION GET NIVEAU - - - - - - - - - - - - - - - - - - - //
	function GetNiveau($arbo,$parent_id,$niveau,$niveauMax,$select=0,$valeur=0) {
		for ($i=0; $i<count($arbo); $i++) {
			if ($parent_id == $arbo[$i][1] ) {
				if ($select == '1') $this->PrintSelectNiveau($arbo[$i][0],$niveau,$valeur); // menu select
				elseif ($select == '2') $this->PrintMultiSelectNiveau($arbo[$i][0],$niveau);
				//else $this->PrintNiveau($arbo[$i][0],$niveau); // liste normale
				if ($niveau < $niveauMax) $this->GetNiveau($arbo,$arbo[$i][0],($niveau+1),$niveauMax,$select,$valeur);
			}
		}
	}
}
?>