<?
if ( !defined('MLKLC') ) die('Lucky Duck');

// ------------------------------------------------- CLASS LISTE -------------------------------------------------//

/* ----------------
$A = new LISTE($R3,$R3_data,$id);
$A->miseenavant = 'titre';
$A->ordre = 'ordre DESC';
$A->createLISTE();
------------------ */

class LISTE {
	var $table,$data,$rub_id,$cat_id,$id;
	// - - - - - - - - - - - - - - - - - - - PAGE - - - - - - - - - - - - - - - - - - - //
	function LISTE($table,$data,$id=0) {
		$this->table		= $table;
		$this->data			= $data;
		$this->id			= $id;
		// Par defaut...
		$this->rub_id		= 0; // From a rub to liste prod
		$this->cat_id		= 0;
		$this->child		= 0; // Child n°2 == R3 data...
		$this->page			= 'index.php?mode=liste'; // Liste
		$this->pageBdd		= 'index.php?mode=bdd'; // Delete
		$this->pageFiche	= 'index.php?mode=fiche'; // Insert
		$this->pageCms		= 'index.php?mode=cms'; // CMS EDIT
		$this->formName		= 'F1';
		$this->scriptName	= 'V1';
		$this->method		= 'POST';
		$this->miseenavant	= (!empty($this->table['miseenavant']) ? $this->table['miseenavant'] : 'titre');
		$this->ordre		= (!empty($this->table['ordre']) ? $this->table['ordre'] : 'ordre DESC');
		$this->champSelect	= array();
		$this->ordreAff		= ''; // Ordre d'affichage dynamique
		$this->html			= ''; // Var stock HTML
		$this->rang			= 1; 
		$this->r			= 1; // Ligne 1 sur 2 (1-2-1-2-1-..)
		$this->idDataSelect	= ''; // Champs selectionnés pour affichage en colonne dans liste
		$this->ordreExist	= '';
		$this->pagehtm		= ''; // Pagination

	}
	function createLISTE() {

		$this->isrub		= $this->table['rubrelation'] != '' ? '1' : '0';
		$this->iscat		= substr($this->table['relation'],0,6) == 'parent' ? '1' : '0';
		$this->numChild		= substr($this->table['relation'],0,6) == 'parent' ? substr($this->table['relation'],7,1) : '0';
		$this->urlrub		= $this->rub_id > 0 ? '&rub_id='.$this->rub_id : '';
		$this->urlcat		= $this->cat_id > 0 ? '&cat_id='.$this->cat_id : '';
		$this->urlchild		= $this->cat_id > 0 ? '&child='.$this->child : '';
		echo $this->createFORMINPUT();
		
	}

	// - - - - - - - - - - - - - - - - - - - FORM INPUT - - - - - - - - - - - - - - - - - - - //
	function createFORMINPUT() {
		global $langues;
		global $grand,$medium,$mini,$paginationa;
		global $root,$WWW;
		global $R0,$R1,$R2,$R3,$R4,$R5,$R6,$R7,$R8,$R9,$R10;
		
		$this->urlPagination = '&page='.Clean($_GET['page']);
		$this->urlOrdre = '&ordreAff='.Clean($_GET['ordreAff']);
		
		$this->urlFiltre = '';
		if (is_array($this->table['filtre'])) { // url Filtre
			foreach ($this->table['filtre'] as $inputname => $defaut) {
				if (isset($_GET[$inputname])) $this->urlFiltre .= '&'.$inputname.'='.Clean($_GET[$inputname]);
			}
		}

		// Verif if ordre exist
		$this->ordreExist = 0;
		for ($i=0; $i<count($this->data); $i++) { 
			if ($this->data[$i]['name'] == 'ordre') { 
				$this->ordreExist = 1;
				break;
			}
		}

		if (!$this->ordreExist && $this->ordre == 'ordre DESC') { $this->ordre = 'id DESC'; }
		
		// ------------------------------------
		$this->html = '<table width="100%" height="100%"  border="0" cellpadding="0" cellspacing="0" class="borCote">
		<tr>
		<td valign="top"><table width="100%"  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td height="24" nowrap class="table-titre">&nbsp;<a href="'.$this->page.'" class="whiteLink">'.($this->table['titres']!=''?aff($this->table['titres']):aff($this->table['titre']).'s').'</a>&nbsp;</td>
		<td width="67%" class="table-titre2" align="center">';
		if ($this->table['preview'] != '') $this->html .= '<a href="'.$this->table['preview'].'" target="_blank" class="sstitre">Voir la rubrique concernée dans le site</a>';
		if ($this->table['boutonListe'] != '') $this->html .= $this->table['boutonListe'];
		$this->html .= '</td>
		</tr>
		<tr align="center">
		<td colspan="2" class="bgTableauPcP">'.MyInfo().'</td>
		</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="15">
		<tr>
		<td class="texte">';
		
		// MENU SELECT RUBRIQUE  ############################################################################
		if ($this->rub_id > 0) {
			$this->html .= '<table  border="0" cellspacing="0" cellpadding="2" class="texte"><tr>
			<td align="right" width="100"><b>Rubrique :</b></td>
    		<td><select name="rubrique" onChange="window.location=\'index.php?mode=liste&rub_id=\'+this.options[this.selectedIndex].value+\''.$this->urlchild.'\';"  style="font: 11px Verdana, helvetica, mono ;">';
			// childRel => 'categories:categories_produits:produits:cat_id=id:prod_id=id:titre:titre'
			list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
			list($relRubId,$rubId) = explode('=',$relRubId_rubId);
			list($relProdId,$prodId) = explode('=',$relProdId_prodId);
			//
			list($tablle,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);
			
			$F = new SQL($table);
			$F->LireSql(array($idName,$champRel)," $idName!='' ORDER BY ordre DESC");
			$arbo = array();
			// Scan de toutes les rubriques
			for ($i=0; $i<count($F->V); $i++) { $arbo[] = array($F->V[$i][$idName],$F->V[$i][$champRel]); } // Build array
			list($minNiveau,$maxNiveau) = explode(':',$R0['rubLevel']);
			$maxNiveau++;
			$this->GetNiveau($arbo,'0','0',$maxNiveau,1);
			
			$this->html .= '</select></td>
			<td><a href="index.php">Retour</a></td></tr></table>&nbsp;';
		}
		
		
		// MENU SELECT CATEGORIE ############################################################################
		if ($this->table['relation'] != '' && $this->iscat != 1 && $this->isrub != 1) {
			list($tableName,$idName,$champsName,$champRel) = explode(':',$this->table['relation']);
			$this->tableName = $tableName; // Nom de la table categorie
			$this->idName = $idName; // nom du champs valeur
			$this->champsName = $champsName; // nom du champs titre
			$this->champRel = $champRel; // nom du champ qui prend la valeur		
			if (strpos($champsName,'-') !== false) {
				$TchampsName = explode('-',$champsName);
				$select = array($idName);
				foreach($TchampsName as $champsN) $select[] = $champsN;
			}
			else $select = array($idName,$champsName);	
			$this->html .= '<table  border="0" cellspacing="0" cellpadding="2" class="texte"><tr>
			<td align="right" nowrap><b>'.($this->rub_id < 1 && $this->cat_id < 1 ? $R0['titre'] : $R1['titre']).'&nbsp;:</b></td>
    		<td><select name="categorie" onChange="window.location=\'index.php?mode=liste'.$this->urlrub.'&cat_id=\'+this.options[this.selectedIndex].value+\''.$this->urlchild.'\';">';
			
			if ($this->rub_id < 1) { // LISTE todo CAT
				if ($this->table['wherenot'] != '') $where = " {$this->table['wherenot']} ";
				else $where = '';
				$C = new SQL($tableName);
				$C->LireSql($select,$where);
				for ($j=0; $j<count($C->V); $j++) {
					$this->html .= '<option value="'.$C->V[$j][$idName].'"'; 
					if ($C->V[$j][$idName] == $this->cat_id) $this->html .= 'selected="selected"';
					if (strpos($champsName,'-') !== false) {
						$valeur = '';
						foreach($TchampsName as $champsN) $valeur .= ' '.aff($C->V[$j][$champsN]);
					}
					else $valeur = aff($C->V[$j][$champsName]);
					$this->html .= '> '.$valeur.'</option>';
				}
			}
			else { // LISTE CAT OF A RUB
				list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
				list($relRubId,$rubId) = explode('=',$relRubId_rubId);
				list($relProdId,$prodId) = explode('=',$relProdId_prodId);
				
				if ($this->table['wherenot'] != '') $where = " AND {$this->table['wherenot']} ";
				else $where = '';
				
				$C = new SQL($tableName);
				$C->customSql(" SELECT $tableProd.* FROM $tableProd LEFT JOIN $tableRel ON $tableProd.$prodId=$tableRel.$relProdId LEFT JOIN $table ON $tableRel.$relRubId=$table.$rubId WHERE $table.$rubId='{$this->rub_id}' $where ORDER BY $tableProd.ordre ");
				for ($j=0; $j < count($C->V); $j++) {
					$this->html .= '<option value="'.$C->V[$j][$idName].'"'; 
					if ($C->V[$j][$idName] == $this->cat_id) { $this->html .= 'selected="selected"'; } 
					$this->html .= '> '.aff($C->V[$j][$champsName]).'</option>';
				}
			}
			$this->html .= '</select></td>
			<td><a href="index.php?'.$this->urlrub.'">Retour</a></td></tr></table>&nbsp;';
		}
		
		// SS-MENU vers CHILD ############################################################################
		if ($this->table['relation'] != '' &&  $this->isrub != 1 && ($this->iscat != 1 || ($this->table['relation'] != '' && $this->id > 0))) {
			global $R1;
			if ($this->numChild > 0) $child = $this->numChild;
			else $child = substr($R1['relation'],7,1); // parent:1
			$this->html .= '<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td align="right"><table border="0" cellpadding="4" cellspacing="0" class="bgTableauTitre" style="border-bottom: none;">
			<tr class="table-ligne2">';
			if ($this->iscat == 1) $style = ' class="bgMenuSelect" ';
			else $style = ' style="border-right:1px solid #FFFFFF;" ';
			$this->html .= '<td onMouseOver="this.style.backgroundColor=\'#FFFFFF\';" onMouseOut="this.style.backgroundColor=\'\';" '.$style.' nowrap>&nbsp;<img src="../images/flech_menu.png" width="12" height="12" border="0" align="absmiddle"><b>'.($this->isrub && $this->rub_id < 1 ? $R0['titre'] : $R1['titre']).'</b>';
			
			if ($this->iscat != 1) $this->html .= ' <a href="index.php?mode=fiche'.$this->urlrub.'&id='.$this->cat_id.'"><img src="../images/edit.gif" border="0" align="absmiddle" alt="Modifier"></a>';
			if ($R1['fixe'] != '1') $this->html .= ' <a href="index.php?mode=fiche'.$this->urlrub.'"><img src="../images/ajout.gif" border="0" align="absmiddle" alt="Ajouter"></a></td>';
			// EACH SS-CAT (CHILD)
			for ($c=0; $c<$child; $c++) {
				if ($this->iscat == 1) $catUrl = '&cat_id='.$this->id;
				else $catUrl = $this->urlcat;
				$childUrl = '&child='.($c+1);
				if ($c == 0) { global $R2,$R2_data; $Rwhom = $R2; $Rwhomdata = $R2_data; }
				elseif ($c == 1) { global $R3,$R3_data; $Rwhom = $R3; $Rwhomdata = $R3_data; }
				elseif ($c == 2) { global $R4,$R4_data; $Rwhom = $R4; $Rwhomdata = $R4_data; }
				elseif ($c == 3) { global $R5,$R5_data; $Rwhom = $R5; $Rwhomdata = $R5_data; }
				elseif ($c == 4) { global $R6,$R6_data; $Rwhom = $R6; $Rwhomdata = $R6_data; }
				elseif ($c == 5) { global $R7,$R7_data; $Rwhom = $R7; $Rwhomdata = $R7_data; }
				elseif ($c == 6) { global $R8,$R8_data; $Rwhom = $R8; $Rwhomdata = $R8_data; }
				elseif ($c == 7) { global $R9,$R9_data; $Rwhom = $R9; $Rwhomdata = $R9_data; }
				elseif ($c == 8) { global $R10,$R10_data; $Rwhom = $R10; $Rwhomdata = $R10_data; }
				if ($this->child == $c+1) $style = ' class="bgMenuSelect" ';
				else $style = ' style="border-right:1px solid #FFFFFF;" ';
				$this->html .= '<td onMouseOver="this.style.backgroundColor=\'#FFFFFF\';" onMouseOut="this.style.backgroundColor=\'\';" '.$style.' nowrap>&nbsp;<img src="../images/flech_menu.png" width="12" height="12" border="0" align="absmiddle">'.$Rwhom['titre'].' <a href="index.php?mode=fiche'.$this->urlrub.$catUrl.$childUrl.'"><img src="../images/ajout.gif" border="0" align="absmiddle" alt="Ajouter"></a> <a href="index.php?mode=liste'.$this->urlrub.$catUrl.$childUrl.'"><img src="../images/liste.gif" border="0" align="absmiddle" alt="Lister"></a></td>';
			}
			$this->html .= '</tr>
			</table></td>
			</tr>
			</table>';
		}
		// ------------------------------------
		$this->html .= '<table width="100%"  border="0" cellpadding="4" cellspacing="0"  class="bgTableauTitre">
		<tr>
		<td height="20">&nbsp;Liste des '.($this->table['titres']!=''?aff($this->table['titres']):aff($this->table['titre']).'s').' &nbsp;<img src="../images/flech_show.png" width="14" height="14" align="absmiddle"></td>
		<td align="right">';
		if ($this->table['fixe'] != '1' && $this->table['fixe'] != '2') {
			$this->html .= '<table border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="'.$this->pageFiche.$this->urlrub.$this->urlcat.$this->urlchild.'" class="menu">AJOUTER</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr>
			</table>';
		}
		else $this->html .= '&nbsp;';
		$this->html .= '</td>
		</tr>
		</table>';
		
		if ($this->table['tips']!='') {
			$this->html .= '<br />
			<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="texte">
			<tr><td height="20" align="center">'.$this->table['tips'].'</td></tr></table>';
		}

		// FILTRE D'AFFICHAGE ############################################################################
		if (is_array($this->table['filtre'])) {
			
			$this->html .= '&nbsp;<table border="0" '.(count($this->table['filtre']) > 3 ? 'width="100%"':'').' cellpadding="3" cellspacing="0" class="tablebor" >
			<tr>';
			
			$selectValeur = array();

			foreach ($this->table['filtre'] as $inputname => $defaut) {
				$selectValeur[$inputname] = Clean(urldecode($_GET[$inputname]));
				
				// Check champs "select" filtre
				for ($i=0; $i<count($this->data); $i++) { // Find data Filtre
					if ($this->data[$i]['name'] == $inputname) {	
						$titre = $this->data[$i]['titre']!=''?aff($this->data[$i]['titre']):aff(ucfirst($this->data[$i]['name']));
						if (!empty($this->data[$i]['inc']) && $this->data[$i]['inc'] != '') {// SELECT + INC
							list($tableName,$idName,$champsName) = explode(':',$this->data[$i]['inc']);
							if (strpos($champsName,'-') !== false) {
								$TchampsName = explode('-',$champsName);
								$select = array($idName);
								foreach($TchampsName as $champsN) $select[] = $champsN;
							}
							else $select = array($idName,$champsName);
							
							$S = new SQL($tableName);
							$S->LireSql($select," $idName!='' ORDER BY {$select[1]} DESC "); //$idName='".$F->V[$i][$this->champSelect[$u]]."' LIMIT 1
							$valeur = $titrevaleur = array();
							
							for ($e=0; $e<count($S->V); $e++) {
								if (strpos($champsName,'-') !== false) {
									$tvaleur = ''; 
									foreach($TchampsName as $champsN) $tvaleur .= ' '.$S->V[$e][$champsN];
									$titrevaleur[] = $tvaleur;
								}
								else $titrevaleur[] = $S->V[$e][$champsName];
								$valeur[] = $S->V[$e][$idName];
							}
						}
						elseif ($this->data[$i]['input'] == 'select' || $this->data[$i]['input'] == 'radio') { // SELECT
							$valeur = $this->data[$i]['valeur'];
							$titrevaleur = $this->data[$i]['titrevaleur'];
						}
						elseif ($this->data[$i]['input'] == 'text') { // TEXT VARCHAR
							$V = new SQL($this->table);
							$V->LireSql(array($this->data[$i]['name'])," id!='' ORDER BY ".$this->data[$i]['name']." ASC "," DISTINCT");
							$valeur = $titrevaleur = array();
							for ($e=0; $e<count($V->V); $e++) {
								$titrevaleur[] = $V->V[$e][$this->data[$i]['name']];
								$valeur[] = $V->V[$e][$this->data[$i]['name']];
							}
						}
						break;
					}
				}

				$this->html .= '<td align="right" nowrap class="table-ligne1">'.$titre.'&nbsp;:</td>
				<td nowrap class="table-ligne1" width="'.(100/count($this->table['filtre'])).'%"><select name="'.$inputname.'" onchange="window.location=\''.$this->page.$this->urlrub.$this->urlcat.$this->urlchild.'&ordreAff='.$this->ordreAff.$this->urlPagination;
				
				$thisselecturl = $this->urlFiltre;

				if ($thisselecturl != '') {
					$thisselecturl = preg_replace('/('.$inputname.'=.*?&)/is','&',$thisselecturl);
					$thisselecturl = preg_replace('/('.$inputname.'=.*?)$/is','',$thisselecturl);
					$this->html .= $thisselecturl;
				}
				$this->html .= '&'.$inputname.'=\'+this.options[this.selectedIndex].value;" style="width:100%">
				<option value="todo" ';
				if ($selectValeur[$inputname] == 'todo' || ($defaut === '' && $selectValeur[$inputname] === ''))  $this->html .= 'selected="selected"';
				$this->html .= '>Filtre --&gt;</option>'; //Tous les '.$inputname.'s

				if ($selectValeur[$inputname] == '' && $selectValeur[$inputname] != '0') $selectValeur[$inputname] = $defaut; // Pour la requete
				
				for ($i=0; $i<count($valeur); $i++) {
					$this->html .= '<option value="'.urlencode(aff($valeur[$i])).'" ';
					if ($selectValeur[$inputname] === '' && $defaut == $valeur[$i]) $this->html .= 'selected="selected"';
					elseif ($selectValeur[$inputname] == $valeur[$i]) $this->html .= 'selected="selected"';
					$this->html .= '>'.aff($titrevaleur[$i]).'</option>';
				}
				$this->html .= '</select></td>';

			} // Fin foreach $this->table['filtre']
			
			$this->html .= '</tr>
			</table>';
		}

		// PAGINATION ##############################################################################################
		$this->html .= '<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="texte">
		<tr><td height="20" align="right" valign="bottom" id="rang">&nbsp;</td></tr></table>';
		
		$this->html .= '&nbsp;';
		// ------------------------------------
		$this->html .= '<table width="100%" border="0" cellpadding="2" cellspacing="1" class="tablebor">';

		### FORM 
		$this->html .= '<form action="'.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.$this->urlPagination.$this->urlOrdre.$this->urlFiltre.'&amp;action=eff" method="'.$this->method.'" enctype="multipart/form-data" id="'.$this->formName.'" name="'.$this->formName.'">
		
		<script type="text/javascript" language="javascript">
		<!--
		function clicTous(booleen) {
			form = document.'.$this->formName.';
			for (i=0; i<form.elements.length; i++) { 
				if (form.elements[i].name.indexOf("eff") != -1) form.elements[i].checked = booleen;
			}
		}
		//-->
		</script>';
			
		// Here the ENTETE ROW ############################################################################
		$this->html .= '<tr align="center">
		<td class="table-sstitre">#</td>'; // Rang
		
		//$this->champSelect[] = $this->data['0']['name']; // Ajoute l'ID a la requete // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
		//$this->idDataSelect[] = 0;

		for ($i=0; $i<count($this->data); $i++) {

			if ($this->data[$i]['index'] == '1') { // D1
				$this->html .= '<td class="table-sstitre"';
				if ($this->miseenavant == $this->data[$i]['name']) $this->html .= ' width="40%"';
				if ($this->data[$i]['titre'] != '') $titre = aff($this->data[$i]['titre']);
				else $titre = ucfirst($this->data[$i]['name']);
				$this->html .= ' nowrap><a href="'.$this->page.$this->urlrub.$this->urlcat.$this->urlchild.'&ordreAff='.$this->data[$i]['name'];
				if ($this->data[$i]['bilingue'] == '1') $slg = '_'.$langues[0]; else $slg = '';
				$this->html .= $slg.$this->urlPagination.$this->urlFiltre.'" title="Afficher par '.$titre.'" class="';
				
				if (!empty($_GET['ordreAff']) && $_GET['ordreAff'] != '') $this->ordreAff = Clean($_GET['ordreAff']);
				elseif (!empty($this->miseenavant)) $this->ordreAff = $this->miseenavant;
				elseif ($this->ordreExist) $this->ordreAff = 'ordre';
				else $this->ordreAff = 'titre';
				
				if ($this->ordreAff == $this->data[$i]['name'].$slg) $this->html .= 'sstitreSelect';
				else $this->html .= 'sstitre';
				$this->html .= '">'.$titre;
				
				if ($this->data[$i]['bilingue'] == '1') $this->html .= '&nbsp;('.$langues[0].')'; // Si bilingue, 1ere langue par defaut
				$this->html .= '</a></td>';
				$this->champSelect[] = $this->data[$i]['name'].$slg;
				$this->idDataSelect[] = $i;
			}
		}

		$this->html .= '<td nowrap class="table-sstitre"';
		if ($this->table['fixe'] != 2) { 
			if ($this->isrub == '1' && !empty($this->table['childRel'])) $colspan = 2; // RUB
			if ($this->iscat == '1') $colspan += 1+$this->numChild; // CAT
			$this->html .= ' colspan="'.$colspan.'" ';
		}
		$this->html .= '>Actions</td>';
		if (!!empty($this->table['fixe'])) { $this->html .= '<td class="table-sstitre">Effacer</td>'; }
		$this->html .= '</tr>';
		if (!empty($_GET['ordreAff']) && $_GET['ordreAff'] != '') { // Witch order ?
			$this->ordreAff = clean($_GET['ordreAff']);
			$_SESSION[SITE_CONFIG]['ordreAff'] = ( $_SESSION[SITE_CONFIG]['ordreAff'] == 'DESC' ? 'ASC' : 'DESC' );
			$this->ordreAff .= ' '.$_SESSION[SITE_CONFIG]['ordreAff'];
		}
		else { $this->ordreAff = $this->ordre; }


		if ($this->isrub == 1){ // IS RUB // PRINT LISTE WITH NIVEAU

			list($self,$idName,$titreName,$champRel) = explode(':',$this->table['rubrelation']);
			$F = new SQL($this->table);
			$F->LireSql(array($idName,$champRel)," $idName!='' ORDER BY ".$this->ordreAff);

			$arbo = array();
			// Scan de toutes les rubriques --> ID <> PARENT_ID
			for ($i=0; $i<count($F->V); $i++) { $arbo[] = array($F->V[$i][$idName], $F->V[$i][$champRel]); } // Build array
			list($minNiveau,$maxNiveau) = explode(':',$R0['rubLevel']);
			$maxNiveau++; // Add 1 nivel to show liste (Difference with "create")
			$this->GetNiveau($arbo,'0','0',$maxNiveau);

		}
		else { // LISTE SANS GESTION DES NIVEAUX...
		
			// PAGINATION ##############################################################################################

			if ($this->rub_id < 1 || $this->cat_id > 1) { // COUNT Cat/prod
				// Witch Cat ?
				if ($this->cat_id > 0) $where = $this->champRel."='".$this->cat_id."'"; // Seulement les prod d'une cat
				else $where = " id!='' "; // Tous...
				if (is_array($this->table['filtre'])) { // FILTRE
					foreach ($this->table['filtre'] as $inputname => $defaut) {
						if ($selectValeur[$inputname] === 0 || $selectValeur[$inputname] === '0' || ( $selectValeur[$inputname] != 'todo' && !empty($selectValeur[$inputname]) ) )
							$where .= " AND $inputname='{$selectValeur[$inputname]}' ";
					}
				}
				$F = new SQL($this->table); // Here the SQL REQ
				$F->LireSql(array($this->data[0]['name'])," $where ");
			}
			else { // COUNT ARBO : prod of a rub
						
				list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
				list($relRubId,$rubId) = explode('=',$relRubId_rubId);
				list($relProdId,$prodId) = explode('=',$relProdId_prodId);
				$F = new SQL($this->table);
				$F->customSql(" SELECT $tableProd.$prodId FROM $tableProd LEFT JOIN $tableRel ON $tableProd.$prodId=$tableRel.$relProdId LEFT JOIN $table ON $tableRel.$relRubId=$table.$rubId WHERE $table.$rubId='{$this->rub_id}' ");
			}
			
			$page = intval($_GET['page'])<1 ? 1 : intval($_GET['page']);
			$nbpage = ceil(count($F->V)/$paginationa);
			if ($page > $nbpage) $page = $nbpage;
			$range = $paginationa * ($page-1);
			if ($range < 0) $range = 0;
			$limit = " LIMIT $range,$paginationa ";

			if ($nbpage > 1) {
				$offset = 10;
				
				// Base path
				$pageHref = 'index.php?mode=liste'.$this->urlrub.$this->urlcat.$this->urlchild.$this->urlOrdre;
				
				// Page 1
				$this->pagehtm = 'Pages : '.($page!=1?'<a href="'.$pageHref.'&page=1" class="navliste">':'').'1'.($page!=1?'</a>':'');
				
				// Suite
				$this->pagehtm .= ' - ';
				
				if ($page < $offset || $page > $nbpage - $offset) $offset *= 2;

				$start = $page-$offset;
				if ($start > 2) $this->pagehtm .= '... - ';
				for ($p=($start > 2 ? $start : 2); $p<=$page+$offset; $p++) { // offset page avant, offset page après
					if ($p > 1 && $p < $nbpage) $this->pagehtm .= ' '.($page!=$p?'<a href="'.$pageHref.'&page='.$p.'" class="navliste">':'').$p.($page!=$p?'</a>':'').' - ';
				}
				if ($page+$offset < $nbpage-1) $this->pagehtm .= '... - ';
				$this->pagehtm .= ' '.($page!=$nbpage?'<a href="'.$pageHref.'&page='.$nbpage.'" class="navliste">':'').$nbpage.($page!=$nbpage?'</a>':''); // DERNIERE PAGE

				$pageScript = '<script type="text/JavaScript">
				$("rang").innerHTML = $("rang2").innerHTML = \''.$this->pagehtm.'\';
				</script>';
			}
			// FIN PAGINATION ##############################################################################################
			
			
			
			// REQUETE GLOBALE POUR LA LISTE PRODUITS ##############################################################################################
 			if ($this->rub_id < 1 || $this->cat_id > 1 || $this->child > 0) { // LISTE Cat/prod
				// Witch Cat ?
				if ($this->cat_id > 0) $where = $this->champRel."='".$this->cat_id."'"; // Seulement les prod d'une cat
				elseif ($this->table['wherenot'] != '') $where = $this->table['wherenot'];
				else $where = " id!='' "; // Tous...
				if (is_array($this->table['filtre'])) { // FILTRE
					foreach ($this->table['filtre'] as $inputname => $defaut) {
						if ($selectValeur[$inputname] === 0 || $selectValeur[$inputname] === '0' || ( $selectValeur[$inputname] != 'todo' && !empty($selectValeur[$inputname]) ) )
							$where .= " AND $inputname='{$selectValeur[$inputname]}' ";
					}
				}
				
				// Add id in requete if not present..
				$champToSel = $this->champSelect;
				if (!in_array('id',$this->champSelect)) $champToSel[] = 'id';
				$F = new SQL($this->table); // Here the SQL
				$F->LireSql($champToSel," $where ORDER BY ".$this->ordreAff." ".$limit);
			}
			else { // LISTE PROD WITH RELATION
				if ($this->ordreAff == 'ordre DESC') $ordreCustom = "$tableRel.ordre DESC";
				else $ordreCustom = $tableProd.'.'.$this->ordreAff;
				if (is_array($this->table['filtre'])) { // FILTRE
					$arrayFiltres = $this->table['filtre'];
					foreach ($arrayFiltres as $inputname => $defaut) {
						if ($selectValeur[$inputname] !== 'todo') $and .= " AND $tableProd.$inputname='{$selectValeur[$inputname]}' ";
					}
				}			
				if ($this->table['wherenot'] != '') $and .= " AND {$this->table['wherenot']} ";
				$F = new Q(" SELECT $tableProd.*,$tableRel.ordre FROM $tableProd LEFT JOIN $tableRel ON $tableProd.$prodId=$tableRel.$relProdId LEFT JOIN $table ON $tableRel.$relRubId=$table.$rubId WHERE $table.$rubId='{$this->rub_id}' $and ORDER BY $ordreCustom $limit"); // 
			}
			// FIN REQUETE GLOBALE POUR LA LISTE PRODUITS ##############################################################################################
			

			
			$u = 0; // var champs
			$this->r = 1; // td row 1-2-1-2...
			$this->rang = $range+1;
			if ($this->ordreExist) $ordreFin = intval($F->V['0']['ordre'])+15;

			for ($i=0; $i<count($F->V); $i++) { // BIG BOUCLE EACH ROW................................ ########################
				
				$ficheLink = $this->pageFiche.$this->urlrub.$this->urlcat.$this->urlchild.'&id='.$F->V[$i]['id'];
				
				if ($this->ordreExist) { $ordreUp = intval($F->V[$i]['ordre'])+15; $ordreDo = intval($F->V[$i]['ordre'])-15; }
				$this->html .= '<tr class="table-ligne';
				if ($F->V[$i]['id'] != '' && $this->id == $F->V[$i]['id']) $this->html .= '3';
				elseif ($this->r == 1) $this->html .= '1';
				else $this->html .= '2';
				$this->html .= '" onMouseOver="this.style.backgroundColor=\'#FFFFFF\';" onMouseOut="this.style.backgroundColor=\'\';" ';
				if ($this->table['fixe'] != 2) $this->html .= ' onDblClick="redir(\''.$ficheLink.'\');"';
				$this->html .= '>';
				$this->html .= '<td valign="top" align="center" class="texte">'.$this->rang++.'</td>';

				for ($u=0; $u<count($this->champSelect); $u++) { // $u = 1 >>> No print for id ?????
					$this->html .= '<td valign="top" class="texte">';
					if ($this->miseenavant == $this->data[$this->idDataSelect[$u]]['name'] || $this->miseenavant == $this->data[$this->idDataSelect[$u]]['name'].'_'.$langues[0]) {
						if ($this->table['fixe'] != 2) $this->html .= '<a href="'.$ficheLink.'">';
						else $this->html .= '<a href="'.$this->page.$this->urlrub.'&cat_id='.$F->V[$i]['id'].'&child=1">';
					}
					
					switch ($this->data[$this->idDataSelect[$u]]['name']) { // SWITCH SPECIAL ROW VALUES (ordre/date..)
						case 'ordre' :
						$this->html .= '<div align="center"><a href="'.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.'&amp;action=move&selectId='.$F->V[$i]['id'].'&ordre='.$ordreUp.$this->urlPagination.$this->urlFiltre.$this->urlOrdre.'"><img src="../images/flech_ha.gif" width="14" height="14" align="absmiddle" border="0" title="Monter"></a><a href="'.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.'&amp;action=move&selectId='.$F->V[$i]['id'].'&ordre='.$ordreDo.$this->urlPagination.$this->urlFiltre.$this->urlOrdre.'"><img src="../images/flech_ba.gif" width="14" height="14" align="absmiddle" border="0" title="Descendre"></a></div>';
						break;

						default :
						switch ($this->data[$this->idDataSelect[$u]]['input']) { // SWITCH SPECIAL INPUT (checkbox....)
							case 'radio' :
								if (count($this->data[$this->idDataSelect[$u]]['valeur']) == 2) {
									$this->html .= '<input type="';
									if ($this->data[$this->idDataSelect[$u]]['unique'] == '1') { $this->html .= 'radio'; }
									else { $this->html .= 'checkbox'; }
									$this->html .= '" name="'.$this->data[$this->idDataSelect[$u]]['name'].'[]" onfocus="JavaScript:self.location.href=\''.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.'&amp;action='.$this->data[$this->idDataSelect[$u]]['name'].'&selectId='.$F->V[$i]['id'].$this->urlPagination.$this->urlFiltre.$this->urlOrdre.'\';" class="radio"';
									if ($F->V[$i][$this->champSelect[$u]] == '1') { $this->html .= 'checked'; }
									if ($this->data[$this->idDataSelect[$u]]['disable'] == '1') { $this->html .= ' disabled="disabled"'; }
									$this->html .= '>';
								}
								else {
									$key = array_search($F->V[$i][$this->champSelect[$u]], $this->data[$this->idDataSelect[$u]]['valeur']);    
									$this->html .= $this->data[$this->idDataSelect[$u]]['titrevaleur'][$key];
								}
							break;

							case "file" : 
								if ($F->V[$i][$this->champSelect[$u]] != '') {
									$ext = getExt($F->V[$i][$this->champSelect[$u]]);
									if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') {
										$big = false;
										if (!file_exists($root.$this->table['rep'].$mini.$F->V[$i][$this->champSelect[$u]]))
										$this->html .= '<img src="../images/error.gif" border="0" align="absmiddle" title="ATTENTION, il semble que le fichier ne soit pas présent sur le serveur" /> '.wrap($F->V[$i][$this->champSelect[$u]], 20);
										elseif (is_file($root.$this->table['rep'].$F->V[$i][$this->champSelect[$u]]))
											$big = $root.$this->table['rep'].$F->V[$i][$this->champSelect[$u]];
										elseif (is_file($root.$this->table['rep'].$grand.$F->V[$i][$this->champSelect[$u]]))
											$big = $root.$this->table['rep'].$grand.$F->V[$i][$this->champSelect[$u]];
										
										if ($big) $this->html .= '<a href="javascript:void(0);" onClick="popImg(\''.$big.'\',\'View\');"><img src="'.$root.$this->table['rep'].$mini.$F->V[$i][$this->champSelect[$u]].'" alt="" border="0" class="bor1"></a>';
									}
									else {
									if (!file_exists($root.$this->table['rep'].$F->V[$i][$this->champSelect[$u]]))
										$this->html .= '<img src="../images/error.gif" border="0" align="absmiddle" title="ATTENTION, il semble que le fichier ne soit pas présent sur le serveur" /> '.wrap($F->V[$i][$this->champSelect[$u]], 20);
										else $this->html .= getExt($F->V[$i][$this->champSelect[$u]],1).'&nbsp;<a href="'.$root.$this->table['rep'].$F->V[$i][$this->champSelect[$u]].'" target="_blank">'.wrap(affCleanName($F->V[$i][$this->champSelect[$u]],20)).'</a>';
									}
								}
								else $this->html .= '[ ]';
							break;
							
							case 'select': // SELECT INCLUDE FROM OTHER TABLE // FETCH TITRE
								if (!empty($this->data[$this->idDataSelect[$u]]['inc']) && $this->data[$this->idDataSelect[$u]]['inc'] != '') {
									list($tableName,$idName,$champsName) = explode(':',$this->data[$this->idDataSelect[$u]]['inc']);
									if (strpos($champsName,'-') !== false) {
										$TchampsName = explode('-',$champsName);
										$select = array($idName);
										foreach($TchampsName as $champsN) $select[] = $champsN;
									}
									else $select = array($idName,$champsName);
									$S = new SQL($tableName);
									$S->LireSql($select," $idName='".$F->V[$i][$this->champSelect[$u]]."' LIMIT 1 ");
									if (count($S->V) > 0) {
										if (strpos($champsName,'-') !== false) {
											foreach($TchampsName as $champsN) $this->html .= ' '.aff($S->V[0][$champsN]);
										}
										else $this->html .= aff($S->V[0][$champsName]);
									}
									else $this->html .= '[ ]';
								}
								elseif (!empty($this->data[$this->idDataSelect[$u]]['valeur']) && $this->data[$this->idDataSelect[$u]]['valeur'] != '') {
									$key = array_search($F->V[$i][$this->champSelect[$u]], $this->data[$this->idDataSelect[$u]]['valeur']);
									$this->html .= aff($this->data[$this->idDataSelect[$u]]['titrevaleur'][$key]);
								}
								elseif (!empty($this->data[$this->idDataSelect[$u]]['relation']) && $this->data[$this->idDataSelect[$u]]['relation'] == '1') {
									list($tableName,$idName,$champsName,$champRel) = explode(':',$this->table['relation']);
									if (strpos($champsName,'-') !== false) {
										$TchampsName = explode('-',$champsName);
										$select = array($idName);
										foreach($TchampsName as $champsN) $select[] = $champsN;
									}
									else $select = array($idName,$champsName);
									$C = new SQL($tableName);
									$C->LireSql($select," $idName='".$F->V[$i][$this->champSelect[$u]]."' LIMIT 1 ");
									if (count($C->V) > 0) {
										if (strpos($champsName,'-') !== false) {
											foreach($TchampsName as $champsN) $this->html .= ' '.aff($C->V[0][$champsN]);
										}
										else $this->html .= aff($C->V[0][$champsName]);
									}
									else $this->html .= '[ ]';
								}
							break;
							
							default :
								if ($this->data[$this->idDataSelect[$u]]['htmDefaut'] == 'bibliotheque') {
									$this->html .= '<img src="'.$root.'medias/bibliotheque/'.$mini.$F->V[$i][$this->champSelect[$u]].'" alt="" border="0" class="bor1">';
								}
								elseif ($this->data[$this->idDataSelect[$u]]['htmDefaut'] == 'date') 
									$this->html .= rDate($F->V[$i][$this->champSelect[$u]]);
								elseif ($this->data[$this->idDataSelect[$u]]['htmDefaut'] == 'datetime') 
									$this->html .= (!empty($F->V[$i][$this->champSelect[$u]]) ? printDateTime($F->V[$i][$this->champSelect[$u]]) : '-');
								else 
									$this->html .= ($F->V[$i][$this->champSelect[$u]] != '' ? affVeryClean($F->V[$i][$this->champSelect[$u]], 160, 43) : '[<i>vide</i>]');
							break;
						}
						break;
					}
					
					if ($this->miseenavant == $this->data[$this->idDataSelect[$u]]['name']) { $this->html .= '</a>'; }
					$this->html .= '</td>';
				}
				
				if ($this->table['fixe'] != 2) { 
					$this->html .= '<td align="center"><a href="'.$this->pageFiche.$this->urlrub.$this->urlcat.$this->urlchild.'&id='.$F->V[$i]['id'].'"><img src="../images/edit.gif" align="absmiddle" border="0">&nbsp;Modifier</a></td>';
				} 
				else $this->html .= '<td align="center">&nbsp;</td>';
				
				if ($this->iscat == '1') { // LISTER ELEMENTS
					for ($c=0; $c<$this->numChild; $c++) { // get titre ss-cat + nb elements
						if ($c == 0) $arrayT = $R2;
						elseif ($c == 1) $arrayT = $R3;
						elseif ($c == 2) $arrayT = $R4;
						elseif ($c == 3) $arrayT = $R5;
						elseif ($c == 4) $arrayT = $R6;
						elseif ($c == 5) $arrayT = $R7;
						elseif ($c == 6) $arrayT = $R8;
						elseif ($c == 7) $arrayT = $R9;
						elseif ($c == 8) $arrayT = $R10;
						$titre = $arrayT['titres']!='' ? aff($arrayT['titres']) : aff($arrayT['titre']).'s';
						list($tableName,$idName,$champsName,$champRel) = explode(':',$arrayT['relation']);
						$N = new SQL($arrayT);
						$N->LireSql(array('id')," $champRel='{$F->V[$i]['id']}' "); // Nb d'element rattachés
						$titre .= '&nbsp;('.count($N->V).')';
						$this->html .= '<td align="center"><a href="'.$this->page.$this->urlrub.'&cat_id='.$F->V[$i]['id'].'&child='.($c+1).'"><img src="../images/attach.gif" align="absmiddle" border="0">&nbsp;'.$titre.'</a></td>';
						/*if ($c == 0) {
							$titre = $R2['titres']!='' ? aff($R2['titres']) : aff($R2['titre']).'s';
							$N = new SQL($R2);
						}
						elseif ($c == 1) {
							$titre = $R3['titres']!='' ? aff($R3['titres']) : aff($R3['titre']).'s';
							$N = new SQL($R3);
						}
						elseif ($c == 2) {
							$titre = $R4['titres']!='' ? aff($R4['titres']) : aff($R4['titre']).'s';
							$N = new SQL($R4);
						}
						elseif ($c == 3) {
							$titre = $R5['titres']!='' ? aff($R5['titres']) : aff($R5['titre']).'s';
							$N = new SQL($R5);
						}
						elseif ($c == 4) {
							$titre = $R6['titres']!='' ? aff($R6['titres']) : aff($R6['titre']).'s';
							$N = new SQL($R6);
						}
						elseif ($c == 5) {
							$titre = $R7['titres']!='' ? aff($R7['titres']) : aff($R7['titre']).'s';
							$N = new SQL($R7);
						}
						elseif ($c == 6) {
							$titre = $R8['titres']!='' ? aff($R8['titres']) : aff($R8['titre']).'s';
							$N = new SQL($R8);
						}
						elseif ($c == 7) {
							$titre = $R9['titres']!='' ? aff($R9['titres']) : aff($R9['titre']).'s';
							$N = new SQL($R9);
						}
						elseif ($c == 8) {
							$titre = $R10['titres']!='' ? aff($R10['titres']) : aff($R10['titre']).'s';
							$N = new SQL($R10);
						}
						$N->LireSql(array('id')," cat_id='{$F->V[$i]['id']}' "); // Nb d'element rattachés
						$titre .= '&nbsp;('.count($N->V).')';
						$this->html .= '<td align="center"><a href="'.$this->page.$this->urlrub.'&cat_id='.$F->V[$i]['id'].'&child='.($c+1).'"><img src="../images/attach.gif" align="absmiddle" border="0">&nbsp;'.$titre.'</a></td>';*/
					}
				}
				
				if (!!empty($this->table['fixe'])) { 
					$this->html .= '<td align="center"><input name="eff[]" type="checkbox" class="radio" value="'.$F->V[$i]['id'].'"></td>';
				}
				$this->html .= '</tr>';
				$this->r==2 ? $this->r=1 : $this->r++;
			}
		} // FIN NOT RUB

		
		$span = count($this->champSelect)+1;
		if ($this->table['fixe'] != 2) { 
			if ($this->iscat == '1') { $span += $this->numChild; }
			if ($this->isrub == '1' && !empty($this->table['childRel'])) { $span += 1; }
		}
		$this->html .= '<tr>
		<td align="center" class="table-bas">&nbsp;</td>
		<td colspan="'.$span.'" class="table-bas">';
		
		
		// Reordonner ############################################################################
		if (!!empty($this->table['fixe'])) { // && $this->rub_id < 1
			$this->html .= '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="texte">
			<tr>';
			if ($this->ordreExist) {
				$this->html .= '<td>R&eacute;ordonner par : ';
				$this->html .= '<select name="ordreSelect" onChange="window.location=\''.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.$this->urlPagination.$this->urlFiltre.'&amp;action=move&ordre=\'+this.options[this.selectedIndex].value;">
				<option value="">Choisir --&gt;</option>';
				for($p=0; $p<count($this->data); $p++) {
					if ($this->data[$p]['titre'] != '') { $titre = aff($this->data[$p]['titre']); }
					else { $titre = ucfirst(aff($this->data[$p]['name'])); }
								
					if ($this->data[$p]['bilingue'] == 1) {
						foreach ($langues as $langue) { 
							$this->html .= '<option value="'.$this->data[$p]['name'].'_'.$langue.'-ASC">'.$titre.' ('.$langue.') ASC</option>';
							$this->html .= '<option value="'.$this->data[$p]['name'].'_'.$langue.'-DESC">'.$titre.' ('.$langue.') DESC</option>';
						}
					} else {
						$this->html .= '<option value="'.$this->data[$p]['name'].'-ASC">'.$titre.' ASC</option>';
						$this->html .= '<option value="'.$this->data[$p]['name'].'-DESC">'.$titre.' DESC</option>';
					}
				}
				$this->html .= '</select></td>';
			}
			$this->html .= '
			<td align="right"><label for="checkall">Tout s&eacute;lectionner</label></td>
			<td width="1%"><input name="checkall" id="checkall" type="checkbox" class="radio" value="1" onClick="javascript:if(this.checked) clicTous(true); else clicTous(false);"></td>
			</tr>
			</table>';
		}
		else $this->html .= '&nbsp;';
		$this->html .= '</td>';
		
		if (!!empty($this->table['fixe'])) { 
			$this->html .= '<td align="center" class="table-bas"><table  border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="javascript:document.'.$this->formName.'.submit();" onClick="return window.confirm(\'Etes vous s&ucirc;r de vouloir effacer d&eacute;finitivement le/les élément(s) s&eacute;lectionn&eacute;(s)';
			if ($this->iscat == '1') $this->html .= ', ainsi que TOUS les élèments qui y sont attachés';
			$this->html .= ' ?\')" class="menu">EFFACER</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr></table></td>';
		}
		$this->html .= '</tr>
		</form>
		</table>';
		
		// Pagination (fill by JS with ID "rang2") ############################################################################
		$this->html .= '<table width="100%"  border="0" cellpadding="0" cellspacing="0"  class="texte">
		<tr><td height="20" align="right" valign="bottom" id="rang2">&nbsp;</td></tr></table>';
		
		$this->html .= '</td>
		</tr>';
		
		// INSERTION ############################################################################
		if (!!empty($this->table['fixe'])) {
			$this->html .= '<tr>
			<td align="center"><table width="100%"  border="0" cellpadding="4" cellspacing="0"  class="bgTableauTitre">
			<tr>
			<td height="20">&nbsp;Liste des '.($this->table['titres']!=''?aff($this->table['titres']):aff($this->table['titre']).'s').' &nbsp;<img src="../images/flech_showup.png" width="14" height="14" align="absmiddle"></td>
			<td align="right"><table border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
			<td nowrap background="../images/images/button_02.png"><a href="'.$this->pageFiche.$this->urlrub.$this->urlcat.$this->urlchild.'" class="menu">AJOUTER</a></td>
			<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
			</tr>
			</table></td>
			</tr>
			</table>
			
			';
			
			/*if ($this->rub_id > 0) { // AJOUTER PRODUIT EXISTANT DANS UNE RUBRIQUE (PAGE "LISTER")
				
				// childRel => 'categories_offres:categories_offres_produits:produits:cat_id=id:prod_id=id:titre:titre',
				list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
				list($relRubId,$rubId) = explode('=',$relRubId_rubId);
				list($relProdId,$prodId) = explode('=',$relProdId_prodId);

				$this->html .= '<br />
				<table width="60%"  border="0" cellpadding="2" cellspacing="1" bgcolor="#FFFFFF" class="tablebor">
				
				<form action="'.$this->pageBdd.$this->urlrub.$this->urlcat.'&amp;action=attache" method="'.$this->method.'" enctype="multipart/form-data" name="'.$this->formName.'3" id ="'.$this->formName.'3">
				<tr>
				<td height="25" colspan="2" align="center" nowrap class="table-sstitre">Attacher un'.$this->table['genre'].' '.$this->table['titre'].' existant'.$this->table['genre'].'</td>
				</tr>
				<tr>
				<td align="right" nowrap class="table-entete1">'.ucfirst($prodTitre).' :</td>
				<td class="table-ligne1" width="80%"><select name="'.$prodId.'" style="width:100%;font: 11px Verdana, helvetica, mono ;">';

				$F = new SQL($tableProd); // Produits deja selectionnés dans cette rubrique
				$F->customSql(" SELECT $tableProd.$prodId FROM $tableProd LEFT JOIN $tableRel ON $tableProd.$prodId=$tableRel.$relProdId LEFT JOIN $table ON $tableRel.$relRubId=$table.$rubId WHERE $table.$rubId='{$this->rub_id}' ORDER BY $tableProd.$prodTitre ASC ");
				$ArrayIdExist = array();
				for ($i=0; $i<count($F->V); $i++) $ArrayIdExist[] = $F->V[$i][$prodId];
				
				$S = new SQL($tableProd); // Tous les produits
				$S->LireSql(array($prodId,$prodTitre)," $prodId!='' ORDER BY ordre DESC ");
				for ($e=0; $e<count($S->V); $e++) {
					if (!in_array($S->V[$e][$prodId],$ArrayIdExist)) $this->html .= '<option value="'.$S->V[$e][$prodId].'" > '.aff($S->V[$e][$prodTitre]).' (Id:'.$S->V[$e][$prodId].')</option>';
				}
				
				$this->html .= '</select></td>
				</tr>
				<tr>
				<td colspan="2" align="center" nowrap class="table-bas"><table  border="0" cellspacing="0" cellpadding="0">
				<tr>
				<td width="1"><img src="../images/images/button_01.png" width="15" height="18"></td>
				<td nowrap background="../images/images/button_02.png"><a href="javascript:document.'.$this->formName.'3.submit();" class="menu">AJOUTER</a></td>
				<td width="1"><img src="../images/images/button_04.png" width="7" height="18"></td>
				</tr>
				</table></td>
				</tr>
				</form>
				</table>';
			}*/
			$this->html .= '</td>
			</tr>';
		}
		// FIN INSERTION  ////////////////////
		
		$this->html .= '</table>';
		// Include IFRAME Spécial ?  ////////////////////
		if (!empty($this->table['ifr']) && $this->table['ifr'] != '') {
			$this->html .= '<iframe src="'.$this->table['ifr'].'" allowtransparency="true" frameborder="0" width="100%" height="500"></iframe>';
		}
		$this->html .= '</td>
		</tr>
		</table>';

		$this->html .= $pageScript; // pagination
		return $this->html; 
	}
	
	
	
	
	/////////////////////////////////////////////////// RUBRIQUES /////////////////////////////////////////////////////////////////////////////////
	
	// - - - - - - - - - - - - - - - - - - - FUNCTION GET NIVEAU - - - - - - - - - - - - - - - - - - - //
	function GetNiveau($arbo,$parent_id,$niveau,$niveauMax,$select=0,$valeur=0) {
		for ($i=0; $i<count($arbo); $i++) {
			if ($parent_id == $arbo[$i][1] ) {
				if ($select == '1') $this->PrintSelectNiveau($arbo[$i][0],$niveau,$valeur); // menu select
				elseif ($select == '2') $this->PrintMultiSelectNiveau($arbo[$i][0],$niveau);
				elseif ($select == '3') $this->PrintSelectNiveauInsert($arbo[$i][0],$niveau,$valeur); // select insert
				else $this->PrintNiveau($arbo[$i][0],$niveau); // liste normale... to check
				if ($niveau < $niveauMax) $this->GetNiveau($arbo,$arbo[$i][0],($niveau+1),$niveauMax,$select,$valeur);
			}
		}
	}

	// - - - - - - - - - - - - - - - - - - - PRINT NIVEAU - - - - - - - - - - - - - - - - - - - //
	function PrintNiveau($id,$niveau) { // Print a single ROW in liste
		global $R0,$root,$mini,$medium,$grand;
		
		list($minRubNiveau,$maxRubNiveau) = explode(':',$R0['rubLevel']);
		list($minNiveau,$maxNiveau) = explode(':',$R0['prodLevel']);
		
		if (!in_array('id',$this->champSelect)) {
			$this->champSelect[] = 'id';
			$delId = true;
		}

		$N = new SQL($this->table);
		$N->LireSql($this->champSelect," id='$id' LIMIT 1 ");
		if ($delId) {
			$this->champSelect = array_slice($this->champSelect, 0, -1);
		}
		if ($this->ordreExist) { $ordreUp = intval($N->V[0]['ordre'])+15; $ordreDo = intval($N->V[0]['ordre'])-15; }
		
		$this->html .= '<tr class="table-ligne';
		if ($F->V[$i]['id'] != '' && $this->id == $F->V[$i]['id']) $this->html .= '3';
		elseif ($this->r == 1) $this->html .= '1';
		else $this->html .= '2';
		
		$this->html .= '" onMouseOver="this.style.backgroundColor=\'#FFFFFF\';" onMouseOut="this.style.backgroundColor=\'\';">';
		$this->html .= '<td valign="top" align="center" class="texte">'.$this->rang++.'</td>';
		
		if ($niveau == '0') $rep = '<img src="../images/navigation/dir/folder.png" width="20" height="17"align="absmiddle" />';
		else $rep = '<img src="../images/spacer.gif" width="'.(8+(($niveau-1)*20)).'" height="14" align="absmiddle" /><img src="../images/navigation/dir/folder_path.png" width="12" height="12" align="absmiddle" /><img src="../images/navigation/dir/folde_f.png" width="16" height="14" align="absmiddle" />';

		for ($u=0; $u<count($this->champSelect); $u++) { // $u = 1 >>> No print for id
			$this->html .= '<td valign="top" class="texte">';
			
			if ($this->miseenavant == $this->data[$this->idDataSelect[$u]]['name']) {
				$this->html .= '<div>'.$rep.'&nbsp;';
				if ($niveau >= $minRubNiveau && $niveau <= $maxRubNiveau) {
					if ($this->table['fixe'] != 2) { // Ne marche pas.... (plusieur possibilite de child..) marche sans NIVEAU
						$this->html .= '<a href="'.$this->pageFiche.$this->urlrub.$this->urlcat.$this->urlchild.'&id='.$N->V[0]['id'].'">';
					} else {
						$this->html .= '<a href="'.$this->page.$this->urlrub.$this->urlcat.$this->urlchild.'&rub_id='.$N->V[0]['id'].'&id='.$N->V[0]['id'].'">';
					}
				}
			}
			
			switch ($this->data[$this->idDataSelect[$u]]['name']) { // SWITCH SPECIAL ROW VALUES (ordre/date..)
				case 'ordre' :
					if ($niveau >= $minRubNiveau && $niveau <= $maxRubNiveau) {
						$this->html .= '<div align="center"><a href="'.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.'&amp;action=move&selectId='.$N->V[0]['id'].'&ordre='.$ordreUp.$this->urlPagination.$this->urlOrdre.$this->urlFiltre.'"><img src="../images/flech_ha.gif" width="14" height="14" align="absmiddle" border="0" title="Monter"></a><a href="'.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.'&amp;action=move&selectId='.$N->V[0]['id'].'&ordre='.$ordreDo.$this->urlPagination.$this->urlOrdre.$this->urlFiltre.'"><img src="../images/flech_ba.gif" width="14" height="14" align="absmiddle" border="0" title="Descendre"></a></div>';
					}
				break;

				case 'date' :
					$this->html .= rDate($N->V[0][$this->champSelect[$u]]);
				break;
				
				case 'datetime' :
					$this->html .= (!empty($N->V[0][$this->champSelect[$u]]) ? printDateTime($N->V[0][$this->champSelect[$u]]) : '-');
				break;

				default :
				switch ($this->data[$this->idDataSelect[$u]]['input']) { // SWITCH SPECIAL INPUT (checkbox....)
					case 'radio' :
						if ($niveau >= $minRubNiveau && $niveau <= $maxRubNiveau) {
							$this->html .= '<input type="';
							if ($this->data[$this->idDataSelect[$u]]['unique'] == '1') { $this->html .= 'radio'; }
							else { $this->html .= 'checkbox'; }
							$this->html .= '" name="'.$this->data[$this->idDataSelect[$u]]['name'].'[]" onfocus="JavaScript:self.location.href=\''.$this->pageBdd.$this->urlrub.$this->urlcat.$this->urlchild.'&amp;action='.$this->data[$this->idDataSelect[$u]]['name'].'&selectId='.$N->V[0]['id'].$this->urlPagination.$this->urlOrdre.$this->urlFiltre.'\';" class="radio"';
							if ($N->V[0][$this->champSelect[$u]] == '1') { $this->html .= 'checked'; }
							if ($this->data[$this->idDataSelect[$u]]['disable'] == '1') { $this->html .= ' disabled="disabled"'; }
							$this->html .= '>';
						}
					break;
					
					case "file" : 
						if ($N->V[0][$this->champSelect[$u]] != '') {
							$ext = getExt($N->V[0][$this->champSelect[$u]]);
							if (!file_exists($root.$this->table['rep'].$mini.$N->V[0][$this->champSelect[$u]]))
								$this->html .= '<img src="../images/error.gif" border="0" align="absmiddle" alt="ATTENTION, il semble que le fichier ne soit pas présent sur le serveur">&nbsp;'.wrap($N->V[0][$this->champSelect[$u]],20);
							else {
								if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') 
									$this->html .= '<img src="'.$root.$this->table['rep'].$mini.$N->V[0][$this->champSelect[$u]].'" class="bor1">';
								else $this->html .= getExt($N->V[0][$this->champSelect[$u]],1).'&nbsp;'.substr($N->V[0][$this->champSelect[$u]],13);
							}
						}
					break;
					
					case 'select': // SELECT INCLUDE FROM OTHER TABLE // FETCH TITRE
	
						if (!empty($this->data[$this->idDataSelect[$u]]['inc'])) {
	
							list($tableName,$idName,$champsName) = explode(':',$this->data[$this->idDataSelect[$u]]['inc']);
							if (strpos($champsName,'-') !== false) {
								$TchampsName = explode('-',$champsName);
								$select = array($idName);
								foreach($TchampsName as $champsN) $select[] = $champsN;
							}
							else $select = array($idName,$champsName);
							$S = new SQL($tableName);
							$S->LireSql($select," $idName='".$N->V[0][$this->champSelect[$u]]."' LIMIT 1 ");
							if (count($S->V) > 0) {
								if (strpos($champsName,'-') !== false) {
									foreach($TchampsName as $champsN) $this->html .= ' '.aff($S->V[0][$champsN]);
								}
								else $this->html .= aff($S->V[0][$champsName]);
							}
							else $this->html .= '[ ]';
						}
						elseif (!empty($this->data[$this->idDataSelect[$u]]['valeur']) && $this->data[$this->idDataSelect[$u]]['valeur'] != '') {
							$key = array_search($N->V[0][$this->champSelect[$u]], $this->data[$this->idDataSelect[$u]]['valeur']);
							$this->html .= aff($this->data[$this->idDataSelect[$u]]['titrevaleur'][$key]);
						}
						elseif (!empty($this->data[$this->idDataSelect[$u]]['relation']) && $this->data[$this->idDataSelect[$u]]['relation'] == '1') {
							
							list($tableName,$idName,$champsName,$champRel) = explode(':',(!empty($this->table['rubrelation'])?$this->table['rubrelation']:$this->table['relation']));
	
							if (strpos($champsName,'-') !== false) {
								$TchampsName = explode('-',$champsName);
								$select = array($idName);
								foreach($TchampsName as $champsN) $select[] = $champsN;
							}
							else $select = array($idName,$champsName);
							$C = new SQL($tableName);
							$C->LireSql($select," $idName='".$N->V[0][$this->champSelect[$u]]."' LIMIT 1 ");
							if (count($C->V) > 0) {
								if (strpos($champsName,'-') !== false) {
									foreach($TchampsName as $champsN) $this->html .= ' '.aff($C->V[0][$champsN]);
								}
								else $this->html .= aff($C->V[0][$champsName]);
							}
							else $this->html .= '[ ]';
						}
					
					break;
					
					default :
						if ($this->data[$this->idDataSelect[$u]]['htmDefaut'] == 'date') 
							$this->html .= rDate($N->V[0][$this->champSelect[$u]]);
						elseif ($this->data[$this->idDataSelect[$u]]['htmDefaut'] == 'datetime') 
							$this->html .= !empty($N->V[0][$this->champSelect[$u]]) ? printDateTime($N->V[0][$this->champSelect[$u]]) : '-';
						else $this->html .= aff(($N->V[0][$this->champSelect[$u]]!=''?$N->V[0][$this->champSelect[$u]]:'[ ]'));
						// .' (ID <b>'.$N->V[0]['id'].'</b>)'
						// else $this->html .= aff(($N->V[0][$this->champSelect[$u]]!=''?$N->V[0][$this->champSelect[$u]]:'[ ]'));
					break;
				}
				break;
			}
			if ($this->miseenavant == $this->data[$this->idDataSelect[$u]]['name']) { 
				if ($niveau >= $minRubNiveau && $niveau <= $maxRubNiveau) $this->html .= '</a></div>';
			}
			$this->html .= '</td>';
		}
		
		// Action sur l'element
		if ($this->table['fixe'] != 2 && $this->table['cms'] != 1) {
			$this->html .= '<td align="center">';
			if ($niveau >= $minRubNiveau && $niveau <= $maxRubNiveau)
				$this->html .= '<a href="'.$this->pageFiche.$this->urlrub.$this->urlcat.$this->urlchild.'&niveau='.$niveau.'&id='.$N->V[0]['id'].'"><img src="../images/edit.gif" align="absmiddle" border="0">&nbsp;Modifier</a>';
			else
				$this->html .= '&nbsp;';
			$this->html .= '</td>';
		}

		
		// EXTEND FOR CMS
		$isCms = false;
		if ($this->table['cms'] == 1) {
			$this->html .= '<td align="center">';
			
			global $cmsPageTypeId;
			if ($N->V[0]['type_id'] == $cmsPageTypeId) {
				$isCms = true;
				$this->html .= '<a href="'.$this->pageCms.$this->urlrub.$this->urlcat.'&id='.$N->V[0]['id'].'"><img src="../images/edit.gif" align="absmiddle" border="0">&nbsp;Editer la page</a>';
			}
			else $this->html .= '&nbsp;';
			
			$this->html .= '</td>';
		}
			
		// SPEC Campus !!!
		//if ($this->table['cms'] == 1) { 
			//global $articlesTypeId, $sortirTypeId;
			//if ($N->V[0]['type_id'] != $articlesTypeId && $N->V[0]['type_id'] != $sortirTypeId) $isCms = true;
		//}
		
		// LISTER RUB PROD
		if ($niveau >= $minNiveau && $niveau <= $maxNiveau && !empty($R0['childRel']) && !$isCms) { // Find how many product is attached
			list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
			list($relRubId,$rubId) = explode('=',$relRubId_rubId);
			list($relProdId,$prodId) = explode('=',$relProdId_prodId);
			$F = new SQL($tableRel);
			$F->customSql(" SELECT $relProdId FROM $tableRel WHERE $relRubId='{$N->V[0]['id']}' ");
			
			if (!isset($R1)) global $R1;
			$this->html .= '<td align="center" nowrap><a href="'.$this->page.$this->urlrub.$this->urlcat.$this->urlchild.'&rub_id='.$N->V[0]['id'].'"><img src="../images/attach.gif" align="absmiddle" border="0">&nbsp;'.($R1['titres']!='' ? aff($R1['titres']) : aff($R1['titre']).'s').' ('.intval(count($F->V)).')</a></td>'; //&id='.$N->V[0]['id'].' // Lister
		}
		else if (!empty($R0['childRel'])) {
			$this->html .= '<td align="center" nowrap>&nbsp;</td>';
		}

		// LISTER CAT PROD
		if ($this->iscat == '1') { // LISTER ELEMENTS
			
			global $R0,$R1,$R2,$R3,$R4,$R5,$R6,$R7,$R8,$R9,$R10;
			
			for ($c=0; $c<$this->numChild; $c++) { // get titre ss-cat + nb elements
				if ($c == 0) $arrayT = $R2;
				elseif ($c == 1) $arrayT = $R3;
				elseif ($c == 2) $arrayT = $R4;
				elseif ($c == 3) $arrayT = $R5;
				elseif ($c == 4) $arrayT = $R6;
				elseif ($c == 5) $arrayT = $R7;
				elseif ($c == 6) $arrayT = $R8;
				elseif ($c == 7) $arrayT = $R9;
				elseif ($c == 8) $arrayT = $R10;
				$titre = $arrayT['titres']!='' ? aff($arrayT['titres']) : aff($arrayT['titre']).'s';

				list($tableName,$idName,$champsName,$champRel) = explode(':',$arrayT['relation']);
				$C = new SQL($arrayT);
				$C->LireSql(array('id')," $champRel='{$N->V[0]['id']}' "); // Nb d'element rattachés
				$titre .= '&nbsp;('.count($C->V).')';
				$this->html .= '<td align="center"><a href="'.$this->page.$this->urlrub.'&cat_id='.$N->V[0]['id'].'&child='.($c+1).'"><img src="../images/attach.gif" align="absmiddle" border="0">&nbsp;'.$titre.'</a></td>';
			}
		}

		if ($this->table['fixe'] != 1 && $this->table['fixe'] != 2) {
			if ($niveau >= $minRubNiveau && $niveau <= $maxRubNiveau)
				$this->html .= '<td align="center"><input name="eff[]" type="checkbox" class="radio" value="'.$N->V[0]['id'].'"></td>';
			else $this->html .= '<td align="center">&nbsp;</td>';
		}
		else $this->html .= '<td align="center">&nbsp;</td>';
		
		$this->html .= '</tr>';
		$this->r==2 ? $this->r=1 : $this->r++;
	}
	
	// - - - - - - - - - - - - - - - - - - - PRINT MENU SELECT NIVEAU - - - - - - - - - - - - - - - - - - - //

	function PrintSelectNiveau($id,$niveau) { // Print a single ROW in liste
		global $R0;
		list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
		//list($relRubId,$rubId) = explode('=',$relRubId_rubId);//list($relProdId,$prodId) = explode('=',$relProdId_prodId);
		list($tablle,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);
		
		$N = new SQL($table);
		$N->LireSql(array($catTitre)," id='$id' LIMIT 1 ");
		$titreSelect = $N->V[0][$catTitre];
		$gris = '#808080'; // gris 50% de depart
		
		list($minNiveau,$maxNiveau) = explode(':',$R0['rubLevel']);
		$minNiveau++;

		if ($niveau >= $minNiveau && $niveau <= $maxNiveau) $value = $id; else $value = '';
		
		if ($niveau == 0) {
			$style = 'style="background:'.$gris.';color:'.($id==$this->rub_id?'#FFFFFF':'').';"';
			$titreSelect = strtoupper($titreSelect);
		}
		else {
			$CoulTranche = 1/($maxNiveau+2);
			$rgb = html2rgb($gris); // Gris 50 % 
			$rgb[0] = $rgb[0]*(1+($niveau*$CoulTranche)); $rgb[1] = $rgb[1]*(1+($niveau*$CoulTranche)); $rgb[2] = $rgb[2]*(1+($niveau*$CoulTranche));
			$rgb = rgb2html($rgb);
			$style = 'style="background:'.$rgb.';color:'.($id==$this->rub_id?'#FFFFFF':'').';"';
			for ($e=0; $e<$niveau; $e++) $esp .= '—';
		}
		// Valeur  ID ? pour index.php?mode=liste&rub_id=3 : navigation...
		$this->html .= '<option value="'.$id.'" '.($id==$this->rub_id ? 'selected="selected"':'').' '.$style.'>'.$esp.' '.aff($titreSelect).'</option>';

	}
	
	// - - - - - - - - - - - - - - - - - - - PRINT FICHE MENU SELECT NIVEAU (SELECT) - - - - - - - - - - - - - - - - - - - //
	function PrintSelectNiveauInsert($id,$niveau,$valeur) { // Print a single ROW in liste
		global $R0,$bgcolor1;
		list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
		list($tablle,$idName,$titreName,$champRel) = explode(':',$R0['rubrelation']);
		$N = new SQL($table);
		$N->LireSql(array($catTitre)," id='$id' LIMIT 1 ");
		$titreSelect = $N->V[0][$catTitre];
		
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
			$this->html .= '<option value="'.($optionValue!=$this->id?$optionValue:'').'" '.($valeur==$id?'selected="selected"':'').' '.$style.'>'.$esp.' '.aff($titreSelect).'</option>
			';
		else
			$this->html .= '<optgroup label="'.$esp.' '.aff($titreSelect).'" '.$style.'></optgroup>
			';
		
	}
}
?>