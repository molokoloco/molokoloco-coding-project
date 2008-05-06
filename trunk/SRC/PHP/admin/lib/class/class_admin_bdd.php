<?
if ( !defined('MLKLC') ) die('Lucky Duck');

// ------------------------------------------------- CLASS BDD -------------------------------------------------//

/* ----------------
$A = new BDD($R3,$R3_data,$action,$id);
$A->from = 'index.php?mode=liste';
$A->createBDD();
------------------ */

class BDD {
	var $table,$data,$cat_id,$id;
	var $action;
	var $info; // Information en retour
	// - - - - - - - - - - - - - - - - - - - PAGE - - - - - - - - - - - - - - - - - - - //
	function BDD($table,$data,$action,$id=0) {
		$this->table		= $table;
		$this->data			= $data;
		$this->action		= $action;
		$this->rub_id		= 0;
		$this->cat_id		= 0;
		$this->child		= 0; // Child n°2 == R3 data...
		$this->id			= $id;
		// optionnel...
		$this->from			= 'index.php?mode=liste';
		$this->champs		= array();
	}
	function createBDD() {

		$this->isrub		= $this->table['rubrelation'] != '' ? '1' : '0';
		$this->iscat		= substr($this->table['relation'],0,6) == 'parent' ? '1' : '0';
		$this->urlrub		= $this->rub_id > 0 ? '&rub_id='.$this->rub_id : '';
		$this->urlcat		= $this->cat_id > 0 ? '&cat_id='.$this->cat_id : '';
		$this->numChild		= substr($this->table['relation'],0,6) == 'parent' ? substr($this->table['relation'],7,1) : '0'; // Cat number of child
		$this->urlchild		= $this->cat_id > 0 ? '&child='.$this->child : '';
		$this->haveMultirel = false; //multiselect
		
		if ($this->table['prebdd'] != '') include $this->table['prebdd']; // Mise a jour XML, DELETE CAT, si besoin
		
		global $selfPageQuery;
		$from = str_replace('mode=bdd','mode=liste', $selfPageQuery); // Keep filter en page get... // 'index.php?mode=liste';
	
		switch ($this->action) {

			case 'update' : // Insert/Update
			$this->processInput();
			if (isset($_POST['duplicate']) && $_POST['duplicate'] == 1) {
				$this->duplicateMedias();
				$this->id = 0; // Reset
			}
			$this->id > 0 ? $this->updateBdd() : $this->insertBdd();
			break; 
			
			case 'attache' : // Attacher un produit à une rub
			$this->ajouteProdToRub();
			break;
			
			case 'eff' : // Effacer
			$this->deleteBdd();
			$this->from = $from; // 'index.php?mode=liste';
			break;
			
			case 'move' : // Reordonner
			if ($this->rub_id < 1) $this->makeOrdre();
			else $this->makeRubOrdre();
			$this->from = $from; //'index.php?mode=liste';
			break;
			
			default :
			$this->makeAction();
			$this->from = $from; //'index.php?mode=liste';
			break;
		}

		//////////////////////// UPDATE RUBRIQUE RELATION WITH PROD
		if ($this->action == 'update' && $this->table['childRel'] == '1') {
			global $R0;
			list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
			list($relRubId,$rubId) = explode('=',$relRubId_rubId);
			list($relProdId,$prodId) = explode('=',$relProdId_prodId);
			if (count($_POST['rub_id']) > 0) {
				$rub_id_array = array(); // Stock rub_id deja liée
				$rub_id_toKeep_array = array(); // Stock rub_id a garder
				$F = new SQL($tableRel); // Scan rub_id deja liée
				$F->LireSql(array($relRubId), " $relProdId='{$this->id}' ");
				for ($p=0; $p<count($F->V); $p++) $rub_id_array[] = $F->V[$p][$relRubId];
				foreach($_POST['rub_id'] as $rubSelId) {
					if (in_array($rubSelId,$rub_id_array)) $rub_id_toKeep_array[] = $rubSelId; // Stock rub_id a garder
					else { // Ajoute
						$C = new SQL($tableRel);
						$champs = array(array($relRubId,$relProdId,'ordre'),array($rubSelId,$this->id,$_POST['ordre'])); // Array champsName // Array champsVal
						$C->insertSql($champs,1);
					}
				}
				$pasdansarray = array_diff($rub_id_array,$rub_id_toKeep_array);			
				foreach($pasdansarray as $rubSelId) { // Efface Ex-relation
					$C = new SQL($tableRel);
					$C->delSql(" $relProdId='{$this->id}' AND $relRubId='$rubSelId' LIMIT 1");
				}
			}
			else {
				$C = new SQL($tableRel); // Efface toutes les relations
				$C->delSql(" $relProdId='{$this->id}' ");
			}
		}
		////////////////////////// URL
		$this->urlPagination = '&page='.$_GET['page'];
		$this->urlOrdre = '&ordreAff='.$_GET['ordreAff'];
		
		if ($this->haveMultirel == true) { // Multiselect -> After insert ID...
		
			for ($i=1; $i < count($this->data); $i++) {
				if ($this->data[$i]['htmDefaut'] == 'relation') {
					list($tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$prodTitre) = explode(':',$this->data[$i]['relation']);
					list($relRubId,$rubId) = explode('=',$relRubId_rubId);
					list($relProdId,$prodId) = explode('=',$relProdId_prodId);

					$C = new SQL($tableRel);
					$C->delSql(" $relRubId='$this->id' "); // Reset
					
					$inc = 10;
					$name = 'select_'.$this->data[$i]['name'];
					for ($u=0; $u<count($_POST[$name]); $u++) {
						$idSelect = intval($_POST[$name][$u]);
						$champs = array(array($relRubId,$relProdId,'ordre'),array($this->id,$idSelect,$inc)); // Array champsName // Array champsVal
						$C = new SQL($tableRel);
						$C->insertSql($champs,1);
						$inc += 10;
					}
				}
			}
		}

		if ($this->table['postbdd'] != '') require($this->table['postbdd']); // Mise a jour XML, DELETE CAT, si besoin


		### d('HERE END UPDATE');

		if ($_POST['preview'] == '1') { // PREVIEW //intval($_GET['preview']) == 1 && 
			js('
			var pop = window.open(\''.(!empty($this->table['previewf'])?$this->table['previewf']:$this->table['preview']).'\',\'Preview\',\'location=1,status=1,scrollbars=1,resizable=1,top=0,left=0,width=800,height=600\'); //&id='.$this->id.'&cat_id='.$this->cat_id.'
			pop.focus();
			redir("index.php?mode=fiche'.$this->urlrub.$this->urlcat.$this->urlchild.'&id='.$this->id.'&info='.$this->info.$this->urlPagination.$this->urlOrdre.$this->urlFiltre.'");
			');
		}
		else goto($this->from.$this->urlrub.$this->urlcat.$this->urlchild.'&id='.$this->id.'&info='.$this->info.$this->urlPagination.$this->urlOrdre.$this->urlFiltre, 0); // Redirect
	}
	// - - - - - - - - - - - - - - - - - - - AJOUT PROD TO RUB - - - - - - - - - - - - - - - - - - - //
	function ajouteProdToRub() { 
		global $R0;
		list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
		list($relRubId,$rubId) = explode('=',$relRubId_rubId);
		list($relProdId,$prodId) = explode('=',$relProdId_prodId);

		$C = new SQL($tableRel);
		$champs = array(array($relRubId,$relProdId,'ordre'),array($this->rub_id,intval($_POST[$prodId]),'999')); // Array champsName // Array champsVal
		$C->insertSql($champs,1);
		$this->info = 'ajout';
	}
	// - - - - - - - - - - - - - - - - - - - PROCESS INPUT - - - - - - - - - - - - - - - - - - - //
	function processInput() { // Clean, upload... RDate...
		global $langues,$root;
		global $_FILES,$_POST,$_GET;
		
		for ($i=1; $i < count($this->data); $i++) {
			if ($this->data[$i]['bilingue'] == 1) { // BILINGUE // ------------------------------------
				foreach ($langues as $langue) {
					switch ($this->data[$i]['htmDefaut']) {
						case 'date' : // DATE
							$this->inputVal[$this->data[$i]['name'].'_'.$langue] = rDate(clean($_POST[$this->data[$i]['name'].'_'.$langue]));
						break;
						case 'datetime' : // DATE
							$this->inputVal[$this->data[$i]['name'].'_'.$langue] = rDate(str_replace('/','-',clean($_POST[$this->data[$i]['name'].'_'.$langue])));
						break;
						
						case 'img' : // IMAGE ---------------------  A FAIRE <--- CF NO BILINGUE
							if ($this->id > 0) {
								$this->inputVal[$this->data[$i]['name'].'_'.$langue] = $this->getExValue($this->data[$i]['name'].'_'.$langue);
								if ($_POST['eff_'.$this->data[$i]['name'].'_'.$langue] == 1 && $this->inputVal[$this->data[$i]['name'].'_'.$langue] != '') {
									$this->delImg($this->inputVal[$this->data[$i]['name'].'_'.$langue]);
									$this->inputVal[$this->data[$i]['name'].'_'.$langue] = '';
								}
							}
							if ($_FILES[$this->data[$i]['name'].'_'.$langue]['name'] != '') {
								$m =& new FILE();
								$m->resize = $this->data[$i]['resize'];
								$m->uploadFile($this->data[$i]['name'].'_'.$langue,$root.$this->table['rep'],$this->table['sizeimg'],'IMAGE');
								if ($m->error) {
									@unlink($_FILES[$this->data[$i]['name'].'_'.$langue]['tmp_name']);
									alert($m->error,'back','alert');
									die();
								}
								else $this->inputVal[$this->data[$i]['name'].'_'.$langue] = $m->name;
							}
						break;

						case 'fichier' : // FICHIER or IMAGE
							if ($this->id > 0) {
								$this->inputVal[$this->data[$i]['name'].'_'.$langue] = $this->getExValue($this->data[$i]['name'].'_'.$langue);
	
								if ($_POST['eff_'.$this->data[$i]['name'].'_'.$langue] == 1 && $this->inputVal[$this->data[$i]['name'].'_'.$langue] != '') { // Eff
									$ext = getExt($this->inputVal[$this->data[$i]['name'].'_'.$langue]);
									if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') $this->delImg($this->inputVal[$this->data[$i]['name'].'_'.$langue]);
									else $this->delFile($this->inputVal[$this->data[$i]['name'].'_'.$langue]);
									$this->inputVal[$this->data[$i]['name'].'_'.$langue] = '';
								}
							}
							if ($_FILES[$this->data[$i]['name'].'_'.$langue]['tmp_name'] != '') {
								$m =& new FILE();
								$m->resize = $this->data[$i]['resize'];
								$m->uploadFile($this->data[$i]['name'].'_'.$langue,$root.$this->table['rep'],$this->table['sizeimg'],'');
								if ($m->error) {
									@unlink($_FILES[$this->data[$i]['name'].'_'.$langue]['tmp_name']);
									alert($m->error,'back','alert');
									die();
								}
								else $this->inputVal[$this->data[$i]['name'].'_'.$langue] = $m->name;
							}
						break;
					
					
						default : // OTHERS
							if ($this->data[$i]['sqlType'] == 'int' || $this->data[$i]['sqlType'] == 'tinyint') { // INT
								$this->inputVal[$this->data[$i]['name'].'_'.$langue] = intval($_POST[$this->data[$i]['name'].'_'.$langue]);
							}
							else if ($this->data[$i]['sqlType'] == 'float') { // FLOAT
								$this->inputVal[$this->data[$i]['name'].'_'.$langue] = floatval($_POST[$this->data[$i]['name'].'_'.$langue]);
							}
							else if ($this->data[$i]['wysiwyg'] > 0) { // HTML wysiwyg
								$this->inputVal[$this->data[$i]['name'].'_'.$langue] = cleanWysiwyg($_POST[$this->data[$i]['name'].'_'.$langue]);
							}
							else { // CHAR
								$this->inputVal[$this->data[$i]['name'].'_'.$langue] = clean($_POST[$this->data[$i]['name'].'_'.$langue]);
							}
						break;
					} // Fin switch
				}
			}
			else { // NO BILINGUE // ------------------------------------
			
				if (!empty($this->data[$i]['action']) && $this->data[$i]['action'] != '') { // ACTION TO MAKE ?
					/* action=>'!=:2:==:2:<script>window.open(\'send_mail.php?id='.$id.'\',\'\',\'width=250,height=100\');</script>' */
					$ExValue = $this->getExValue($this->data[$i]['name']);
					$NewValue = $_POST[$this->data[$i]['name']];
					list($bool1,$Exval,$bool2,$Newval,$actiontomake) = explode(':',$this->data[$i]['action']);

					$firstcondition = $secondcondition = false;
					switch($bool1) {
						case '!=' : if ($ExValue != $Exval) $firstcondition = true; break;
						case '==' : if ($ExValue == $Exval) $firstcondition = true; break;
						case '<' : if ($ExValue < $Exval) $firstcondition = true; break;
						case '>' : if ($ExValue > $Exval) $firstcondition = true; break;
						default : die('class.bdd > action : Manque booleen');
					}
					switch($bool2) {
						case '!=' : if ($NewValue != $Newval) $secondcondition = true; break;
						case '==' : if ($NewValue == $Newval) $secondcondition = true; break;
						case '<' : if ($NewValue < $Newval) $secondcondition = true; break;
						case '>' : if ($NewValue > $Newval) $secondcondition = true; break;
						default : die('class.bdd > action : Manque booleen');
					}
					if ($firstcondition && $secondcondition) echo $actiontomake;
				}
			
				switch ($this->data[$i]['htmDefaut']) {
					case 'date' : // DATE
						$this->inputVal[$this->data[$i]['name']] = rDate(clean($_POST[$this->data[$i]['name']]));
					break;
					
					case 'img' : // IMAGE
						if ($this->id > 0) {
							$this->inputVal[$this->data[$i]['name']] = $this->getExValue($this->data[$i]['name']);
							if ($_POST['eff_'.$this->data[$i]['name']] == 1 && $this->inputVal[$this->data[$i]['name']] != '') {
								$this->delImg($this->inputVal[$this->data[$i]['name']]);
								$this->inputVal[$this->data[$i]['name']] = '';
							}
						}
						if ($_FILES[$this->data[$i]['name']]['tmp_name'] != '') {
							$m =& new FILE();
							$m->resize = $this->data[$i]['resize'];
							$m->uploadFile($this->data[$i]['name'],$root.$this->table['rep'],$this->table['sizeimg'],'IMAGE');
							if ($m->error) {
								@unlink($_FILES[$this->data[$i]['name']]['tmp_name']);
								alert($m->error,'back','alert');
								die();
							}
							else $this->inputVal[$this->data[$i]['name']] = $m->name;
						}
					break;
					
					case 'fichier' : // FICHIER or IMAGE
						if ($this->id > 0) {
							$this->inputVal[$this->data[$i]['name']] = $this->getExValue($this->data[$i]['name']);
							if ($_POST['eff_'.$this->data[$i]['name']] == 1 && $this->inputVal[$this->data[$i]['name']] != '') { // Eff
								$ext = getExt($this->inputVal[$this->data[$i]['name']]);
								if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') $this->delImg($this->inputVal[$this->data[$i]['name']]);
								else $this->delFile($this->inputVal[$this->data[$i]['name']]);
								$this->inputVal[$this->data[$i]['name']] = '';
							}
						}
						if ($_FILES[$this->data[$i]['name']]['name'] != '' && $_FILES[$this->data[$i]['name']]['tmp_name'] == '') {
							alert('Attention, votre fichier semble trop lourd (php.ini)', 'back', 'alert');
							die();
						}
						if ($_FILES[$this->data[$i]['name']]['tmp_name'] != '') {
							$m =& new FILE();
							$m->resize = $this->data[$i]['resize'];
							$m->uploadFile($this->data[$i]['name'],$root.$this->table['rep'],$this->table['sizeimg'],'');
							if ($m->error) {
								@unlink($_FILES[$this->data[$i]['name']]['tmp_name']);
								alert($m->error,'back','alert');
								die();
							}
							else $this->inputVal[$this->data[$i]['name']] = $m->name;
						}
					break;
					
					case 'bibliotheque' :
					$this->inputVal[$this->data[$i]['name']] = clean($_POST[$this->data[$i]['name']]);
					if ($this->id > 0) {
						if ($_POST['eff_'.$this->data[$i]['name']] == 1 && $this->inputVal[$this->data[$i]['name']] != '') { // Eff
							$this->inputVal[$this->data[$i]['name']] = '';
						}
					}
					break;
					
					case 'relation' : // MultiSelect Relations... No bilingue...
						$this->haveMultirel = true;
					break;
					
					default : // OTHERS
						if ($this->data[$i]['sqlType'] == 'int' || $this->data[$i]['sqlType'] == 'tinyint') { // INT
							$this->inputVal[$this->data[$i]['name']] = intval($_POST[$this->data[$i]['name']]);
						}
						else if ($this->data[$i]['sqlType'] == 'float') { // FLOAT
							$this->inputVal[$this->data[$i]['name']] = floatval($_POST[$this->data[$i]['name']]);
						}
						else if ($this->data[$i]['wysiwyg'] > 0) { // HTML wysiwyg
							$this->inputVal[$this->data[$i]['name']] = cleanWysiwyg($_POST[$this->data[$i]['name']]);
						}
						else { // CHAR
							$this->inputVal[$this->data[$i]['name']] = clean($_POST[$this->data[$i]['name']]);
						}
					break;
				} // Fin switch

			}
		}
	}
	// - - - - - - - - - - - - - - - - - - - DUPLICATE MEDIAS - - - - - - - - - - - - - - - - - - - //
	function duplicateMedias() {
		global $langues;
		global $root,$grand,$medium,$mini;
		global $_FILES, $_POST;
		
		for ($i=1; $i < count($this->data); $i++) {
			if ($this->data[$i]['htmDefaut'] == 'post' || $this->data[$i]['name'] == $this->miseenavant) {
				if ($this->data[$i]['name'] == 'titre' || $this->data[$i]['name'] == 'nom' || $this->data[$i]['name'] == $this->miseenavant) {
					if ($this->data[$i]['bilingue'] == 1) $this->inputVal[$this->data[$i]['name'].'_'.$langues[0]] .= ' [COPIE]';
					else $this->inputVal[$this->data[$i]['name']] .= ' [COPIE]';
				}
			}
			switch ($this->data[$i]['htmDefaut']) { // Ne marche pas en bilingue...
				case 'img' : // IMAGE
					if ($this->id > 0) { // GET EX // EFF
						$this->inputVal[$this->data[$i]['name']] = $this->getExValue($this->data[$i]['name']);
						if ($_POST['eff_'.$this->data[$i]['name']] == 1 && $this->inputVal[$this->data[$i]['name']] != '') {
							//$this->delImg($this->inputVal[$this->data[$i]['name']]);
							$this->inputVal[$this->data[$i]['name']] = '';
						}
					}
					if ($_FILES[$this->data[$i]['name']]['name'] != '') { // UPLOAD NEW ?
						$m =& new FILE();
						$m->resize = $this->data[$i]['resize'];
						$m->uploadFile($this->data[$i]['name'],$root.$this->table['rep'],$this->table['sizeimg'],'IMAGE');
						if ($m->error) {
							@unlink($_FILES[$this->data[$i]['name']]['tmp_name']);
							alert($m->error,'back','alert');
							die();
						}
						else $this->inputVal[$this->data[$i]['name']] = $m->name;
					}
					else if ($this->inputVal[$this->data[$i]['name']] != '') { // COPY SOMETHING ?
						$file_nom = $this->inputVal[$this->data[$i]['name']];						
						$ext = getExt($file_nom);
						$file_new_nom = date(ymdHis).'_'.preg_replace('|.'.$ext.'|si','',preg_replace("|[_0-9]{13}|",'',trim($file_nom))).'2.'.$ext;
						$dir = $this->table['rep'];
						
						if (file_exists($root.$dir.$file_nom)) { copy($root.$dir.$file_nom, $root.$dir.$file_new_nom); }
						if (file_exists($root.$dir.$mini.$file_nom)) { copy($root.$dir.$mini.$file_nom, $root.$dir.$mini.$file_new_nom); }
						if (file_exists($root.$dir.$medium.$file_nom)) { copy($root.$dir.$medium.$file_nom, $root.$dir.$medium.$file_new_nom); }
						if (file_exists($root.$dir.$grand.$file_nom)) { copy($root.$dir.$grand.$file_nom, $root.$dir.$grand.$file_new_nom); }

						$this->inputVal[$this->data[$i]['name']] = $file_new_nom;
					}
					
				break;
				case 'fichier' : // FICHIER or IMAGE
					if ($this->id > 0) {
						$this->inputVal[$this->data[$i]['name']] = $this->getExValue($this->data[$i]['name']);
						if ($_POST['eff_'.$this->data[$i]['name']] == 1 && $this->inputVal[$this->data[$i]['name']] != '') { // Eff
							//$ext = getExt($this->inputVal[$this->data[$i]['name']]); // Ne pas effacer l'image du produit copié....
							//if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') $this->delImg($this->inputVal[$this->data[$i]['name']]);
							//else $this->delFile($this->inputVal[$this->data[$i]['name']]);
							$this->inputVal[$this->data[$i]['name']] = '';
						}
					}
					if ($_FILES[$this->data[$i]['name']]['name'] != '') {
							$m =& new FILE();
							$m->resize = $this->data[$i]['resize'];
							$m->uploadFile($this->data[$i]['name'],$root.$this->table['rep'],$this->table['sizeimg'],'');
							if ($m->error) {
								@unlink($_FILES[$this->data[$i]['name']]['tmp_name']);
								alert($m->error,'back','alert');
								die();
							}
							else $this->inputVal[$this->data[$i]['name']] = $m->name;
						}
					else if ($this->inputVal[$this->data[$i]['name']] != '') { // COPY SOMETHING ?
						$file_nom = $this->inputVal[$this->data[$i]['name']];						
						$ext = getExt($file_nom);
						$file_new_nom = date(ymdHis).'_'.preg_replace('|.'.$ext.'|si','',preg_replace("|[_0-9]{13}|",'',trim($file_nom))).'2.'.$ext;
						$dir = $this->table['rep'];
												
						if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') {
							if (file_exists($root.$dir.$file_nom)) { copy($root.$dir.$file_nom, $root.$dir.$file_new_nom); }
							if (file_exists($root.$dir.$mini.$file_nom)) { copy($root.$dir.$mini.$file_nom, $root.$dir.$mini.$file_new_nom); }
							if (file_exists($root.$dir.$medium.$file_nom)) { copy($root.$dir.$medium.$file_nom, $root.$dir.$medium.$file_new_nom); }
							if (file_exists($root.$dir.$grand.$file_nom)) { copy($root.$dir.$grand.$file_nom, $root.$dir.$grand.$file_new_nom); }
						}
						if (file_exists($root.$dir.$file_nom)) { copy($root.$dir.$file_nom, $root.$dir.$file_new_nom); }
						
						$this->inputVal[$this->data[$i]['name']] = $file_new_nom;
					}
				break;
				default : break;
			} // Fin switch
		}
		return NULL;
	}
	
	// - - - - - - - - - - - - - - - - - - - GET EX-VALUE - - - - - - - - - - - - - - - - - - - //
	function getExValue($inputName) {
		if ($this->id > 0) {
			$F = new SQL($this->table);
			$F->LireSql(array(0=>$inputName)," id='$this->id' LIMIT 1 ");
			$exValue = $F->V['0'][$inputName];
			return $exValue;
		}
		return NULL;
	}
	// - - - - - - - - - - - - - - - - - - - DEL IMG - - - - - - - - - - - - - - - - - - - //
	function delImg($file_nom,$child=0) {

		global $root,$grand,$medium,$mini;
		if ($child < 1) { $dir = $this->table['rep']; }
		else if ($child == 1) { global $R2; $dir = $R2['rep']; }
		else if ($child == 2) { global $R3; $dir = $R3['rep']; }
		else if ($child == 3) { global $R4; $dir = $R4['rep']; }
		else if ($child == 4) { global $R5; $dir = $R5['rep']; }
		else if ($child == 5) { global $R6; $dir = $R6['rep']; }
		else if ($child == 6) { global $R7; $dir = $R7['rep']; }
		else if ($child == 7) { global $R8; $dir = $R8['rep']; }
		else if ($child == 8) { global $R9; $dir = $R9['rep']; }
		else if ($child == 9) { global $R10; $dir = $R10['rep']; }
		if (file_exists($root.$dir.$file_nom)) { unlink($root.$dir.$file_nom); }
		if (file_exists($root.$dir.$mini.$file_nom)) { unlink($root.$dir.$mini.$file_nom); }
		if (file_exists($root.$dir.$medium.$file_nom)) { unlink($root.$dir.$medium.$file_nom); }
		if (file_exists($root.$dir.$grand.$file_nom)) { unlink($root.$dir.$grand.$file_nom); }
	}
	// - - - - - - - - - - - - - - - - - - - UPLOAD IMG - - - - - - - - - - - - - - - - - - - //
	function sendImage($inputName,$imgEx='',$resize='') {
		global $root,$grand,$medium,$mini,$convert;
		
		die('DEPRECIATED'); /////////////////////////////////////////////////
		
		if ($imgEx != '') $this->delImg($imgEx);
		
		$file_nom = $_FILES[$inputName]['name'];
		$ext = getExt($file_nom);
		$file_new_nom = makeName($file_nom);
		$file_img = $_FILES[$inputName]['tmp_name'];
		
		if (!empty($convert)) { // IMAGE MAGICK
			if (!empty($this->table['sizeimg']['tgrand'])) {
				$file_dir = $root.$this->table['rep'];
				uploadFileImg($file_img,$file_nom,$file_new_nom,$file_dir,$this->table['sizeimg']['tgrand'],''); // $resize
			}	
			if (!empty($this->table['sizeimg']['grand'])) {	
				$file_dir_grand = $root.$this->table['rep'].$grand;
				uploadFileImg($file_img,$file_nom,$file_new_nom,$file_dir_grand,$this->table['sizeimg']['grand'],$resize);
			}
			if (!empty($this->table['sizeimg']['medium'])) {
				$file_dir_med = $root.$this->table['rep'].$medium;
				uploadFileImg($file_img,$file_nom,$file_new_nom,$file_dir_med,$this->table['sizeimg']['medium'],$resize);
			}
			if (!empty($this->table['sizeimg']['mini'])) {
				$file_dir_mini = $root.$this->table['rep'].$mini;
				uploadFileImg($file_img,$file_nom,$file_new_nom,$file_dir_mini,$this->table['sizeimg']['mini'],$resize);
			}
		}
		else { // GD GRAPHICS
			if (!empty($this->table['sizeimg']['tgrand'])) {
				$taille = explode('x',$this->table['sizeimg']['tgrand']); // Grand
				$media_taille = getimagesize($file_img);
				$file_dir = $root.$this->table['rep'];
				image_createThumb($file_img,$file_dir.$file_new_nom,$taille[0],$taille[1],'75',$file_ext,$media_taille);
			}		
			if (!empty($this->table['sizeimg']['grand'])) {
				$taille = explode('x',$this->table['sizeimg']['grand']); // Grand
				$media_taille = getimagesize($file_img);
				$file_dir = $root.$this->table['rep'].$grand;
				image_createThumb($file_img,$file_dir.$file_new_nom,$taille[0],$taille[1],'75',$file_ext,$media_taille);
			}
			if (!empty($this->table['sizeimg']['medium'])) {
				$taille = explode('x',$this->table['sizeimg']['medium']); // Medium
				$media_taille = getimagesize($file_dir.$file_new_nom);
				$file_dir_med = $root.$this->table['rep'].$medium;
				image_createThumb($file_dir.$file_new_nom,$file_dir_med.$file_new_nom,$taille[0],$taille[1],'75',$file_ext,$media_taille);
			}
			if (!empty($this->table['sizeimg']['mini'])) {
				$taille = explode('x',$this->table['sizeimg']['mini']); // Mini
				$media_taille = getimagesize($file_dir_med.$file_new_nom);
				$file_dir_mini = $root.$this->table['rep'].$mini;
				image_createThumb($file_dir_med.$file_new_nom,$file_dir_mini.$file_new_nom,$taille[0],$taille[1],'75',$file_ext,$media_taille);
			}
		}
		
		if (is_file($_FILES[$inputName]['tmp_name'])) @unlink($_FILES[$inputName]['tmp_name']);
		return $file_new_nom;

	}
	// - - - - - - - - - - - - - - - - - - - PROCESS FILE - - - - - - - - - - - - - - - - - - - //
	function sendFile($inputName,$fileEx) {
		global $root;
		
		die('DEPRECIATED'); /////////////////////////////////////////////////
		
		$fileDir = $root.$this->table['rep'];
		if ($fileEx != '') $this->delFile($fileEx);
		$file = uploadFile($inputName,$fileDir);
		return $file;
	}
	// - - - - - - - - - - - - - - - - - - - DEL FILE - - - - - - - - - - - - - - - - - - - //
	function delFile($fileName,$child=0) {
		global $root;

		if ($child < 1) { $dir = $this->table['rep']; }
		else if ($child == 1) { global $R2; $dir = $R2['rep']; }
		else if ($child == 2) { global $R3; $dir = $R3['rep']; }
		else if ($child == 3) { global $R4; $dir = $R4['rep']; }
		else if ($child == 4) { global $R5; $dir = $R5['rep']; }
		else if ($child == 5) { global $R6; $dir = $R6['rep']; }
		else if ($child == 6) { global $R7; $dir = $R7['rep']; }
		else if ($child == 7) { global $R8; $dir = $R8['rep']; }
		else if ($child == 8) { global $R9; $dir = $R9['rep']; }
		else if ($child == 9) { global $R10; $dir = $R10['rep']; }
		if (file_exists($root.$dir.$fileName)) unlink($root.$dir.$fileName);
	}
	// - - - - - - - - - - - - - - - - - - - INSERTION - - - - - - - - - - - - - - - - - - - //
	function insertBdd() {
		global $langues;
		for ($i=1; $i<count($this->data); $i++) {
			if ($this->data[$i]['bilingue'] == '1' && $this->data[$i]['htmDefaut'] != 'relation') { // BILINGUE
				foreach ($langues as $langue) { 
					$this->champs[0][] = $this->data[$i]['name'].'_'.$langue;
					$this->champs[1][] = $this->inputVal[$this->data[$i]['name'].'_'.$langue];
				}
			} 
			else if ($this->data[$i]['htmDefaut'] != 'relation') {
				$this->champs[0][] = $this->data[$i]['name']; // NO BILINGUE
				$this->champs[1][] = $this->inputVal[$this->data[$i]['name']];
			}
				
			if ($this->data[$i]['unique'] == '1' && $this->inputVal[$this->data[$i]['name']] == '1') { // CHAMPS UNIQUE
				$champUnique[] =  array($this->data[$i]['name'],'0');
			}
		}

		$C = new SQL($this->table);
		$C->insertSql($this->champs,1);
		$this->id = $C->id;
		if (count($champUnique) > 0) { // Chps Unique
			$U = new SQL($this->table);
			$U->updateSql($champUnique,$this->data['0']['name']."!='".$this->id."'");
		}
		$this->info = 'crea';
	}
	// - - - - - - - - - - - - - - - - - - - UPDATE - - - - - - - - - - - - - - - - - - - //
	function updateBdd() {
		global $langues;
		
		for ($i=1; $i<count($this->data); $i++) {
			if ($this->data[$i]['bilingue'] == '1' && $this->data[$i]['htmDefaut'] != 'relation') { // BILINGUE
				foreach ($langues as $langue) { 
					$this->champs[] = array(0=>$this->data[$i]['name'].'_'.$langue, 1=>$this->inputVal[$this->data[$i]['name'].'_'.$langue]);
				}
			}
			else if ($this->data[$i]['htmDefaut'] != 'relation') { // NO BILINGUE
				$this->champs[] =  array(0=>$this->data[$i]['name'], 1=>$this->inputVal[$this->data[$i]['name']]);
			}
			if ($this->data[$i]['unique'] == '1' && $this->inputVal[$this->data[$i]['name']] == '1') { // Chps Unique
				$U = new SQL($this->table);
				$champUnique[] =  array(0=>$this->data[$i]['name'], 1=>'0');
				$U->updateSql($champUnique,$this->data['0']['name']."!='".$this->id."'");
			}
		}
		$C = new SQL($this->table);
		$C->updateSql($this->champs, $this->data['0']['name']."='".$this->id."'");
		$this->info = 'modif';
	}
	// - - - - - - - - - - - - - - - - - - - DELETE - - - - - - - - - - - - - - - - - - - //
	function deleteBdd() {
		global $langues;

		if (count($_POST["eff"]) > 0) {
			if ($this->iscat == 1 && $this->numChild > 0) { // Verif if got SOUS CAT //////////////////////////
				$tableDel = $imgToDel = $fileToDel = array();
				for ($c=0; $c<$this->numChild; $c++) {
					if ($c == 0) { 		global $R2,$R2_data; $tableDel[$c] = $R2; $tableData = $R2_data; }
					else if ($c == 1) { global $R3,$R3_data; $tableDel[$c] = $R3; $tableData = $R3_data; }
					else if ($c == 2) { global $R4,$R4_data; $tableDel[$c] = $R4; $tableData = $R4_data; }
					else if ($c == 3) { global $R5,$R5_data; $tableDel[$c] = $R5; $tableData = $R5_data; }
					else if ($c == 4) { global $R6,$R6_data; $tableDel[$c] = $R6; $tableData = $R6_data; }
					else if ($c == 5) { global $R7,$R7_data; $tableDel[$c] = $R7; $tableData = $R7_data; }
					else if ($c == 6) { global $R8,$R8_data; $tableDel[$c] = $R8; $tableData = $R8_data; }
					else if ($c == 7) { global $R9,$R9_data; $tableDel[$c] = $R9; $tableData = $R9_data; }
					else if ($c == 8) { global $R10,$R10_data; $tableDel[$c] = $R10; $tableData = $R10_data; }
					$this->champsAlire[$c] = array(0=>$tableData[0]['name']); // First chamsp to get = ID
					$imgToDel[$c] = $fileToDel[$c] = array();
					for ($i=0; $i<count($tableData); $i++) {
						if ($tableData[$i]['relation'] == 1) $relationName[$c] = $tableData[$i]['name']; // Find champs Rel Name
						if ($tableData[$i]['htmDefaut'] == 'img') {
							if (!empty($tableData[$i]['bilingue'])) { // BILINGUE // ------------------------------------
								foreach ($langues as $langue) {
									$this->champsAlire[$c][] = $tableData[$i]['name'].'_'.$langue;
									$imgToDel[$c][]= $tableData[$i]['name'].'_'.$langue;
								}
							}
							else {
								$this->champsAlire[$c][] = $tableData[$i]['name'];
								$imgToDel[$c][]= $tableData[$i]['name'];
							}
						}
						if ($tableData[$i]['htmDefaut'] == 'fichier') {
							if (!empty($tableData[$i]['bilingue'])) { // BILINGUE // ------------------------------------
								foreach ($langues as $langue) {
									$this->champsAlire[$c][] = $tableData[$i]['name'].'_'.$langue;
									$fileToDel[$c][] = $tableData[$i]['name'].'_'.$langue;
								}
							}
							else {
								$this->champsAlire[$c][] = $tableData[$i]['name'];
								$fileToDel[$c][] = $tableData[$i]['name'];
							}
						}
					}
				}
			}
			foreach($_POST["eff"] as $idSelect) { // FOR each Selected PROD or CAT
				$this->id = $idSelect;
				if ($this->iscat == 1 && $this->numChild > 0) { // N'efface pas les relations MultiSelect !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
					for ($c=0; $c<$this->numChild; $c++) {
						if ($tableDel[$c] != '') { // If exist find EACH ss-cat and DEL IMG/FILE
							$F = new SQL($tableDel[$c]);
							$F->LireSql($this->champsAlire[$c]," {$relationName[$c]}='$this->id' ");
							for ($i=0; $i<count($F->V); $i++) { // FOR EACH PROD of this CAT
								foreach ($imgToDel[$c] as $img) $this->delImg($F->V[$i][$img],($c+1)); // FOR EACH IMG of this PROD
								foreach ($fileToDel[$c] as $file) { // FOR EACH FILE of this PROD
									$ext = getExt($F->V[$i][$file]);
									if ($ext == 'gif' || $ext == 'jpg' || $ext == 'png') $this->delImg($F->V[$i][$file],($c+1));
									else $this->delFile($F->V[$i][$file],($c+1)); 
								}
								$C = new SQL($tableDel[$c]);
								$C->delSql($this->champsAlire[$c][0]." = '".$F->V[$i][$this->champsAlire[$c][0]]."' "); // Del sscat
							}
						}
					}
				}
				
				// Find specifique DATA of this Selected PROD/CAT
				for ($i=1; $i<count($this->data); $i++) {
					if ($this->data[$i]['htmDefaut'] == 'img' || $this->data[$i]['htmDefaut'] == 'fichier') {
						
					if ($this->data[$i]['bilingue'] == 1) { // BILINGUE // ------------------------------------
						foreach ($langues as $langue) {
								if ($this->getExValue($this->data[$i]['name'].'_'.$langue) != '') {
									if ($this->data[$i]['htmDefaut'] == 'img') $this->delImg($this->getExValue($this->data[$i]['name'].'_'.$langue));
									else $this->delFile($this->getExValue($this->data[$i]['name'].'_'.$langue));
								}
							}
						}
						else {
							if ($this->getExValue($this->data[$i]['name']) != '') {
								if ($this->data[$i]['htmDefaut'] == 'img') $this->delImg($this->getExValue($this->data[$i]['name']));
								else $this->delFile($this->getExValue($this->data[$i]['name']));
							}
						}
					}
					if ($this->data[$i]['htmDefaut'] == 'relation') { // Efface les relations MultiSelect
						list($tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$prodTitre) = explode(':',$this->data[$i]['relation']);
						list($relRubId,$rubId) = explode('=',$relRubId_rubId);
						list($relProdId,$prodId) = explode('=',$relProdId_prodId);
		
						$C = new SQL($tableRel);
						$C->delSql(" $relRubId='$this->id' "); // Reset
						
						if ($tableProd == $this->table['table']) {
							$C = new SQL($tableRel);
							$C->delSql(" $relProdId='$this->id' ");
						}
					}
				}
				$C = new SQL($this->table);
				$C->delSql($this->data[0]['name']."='".$this->id."' "); // Del PROD or CAT

				// DEL RELATION RUBRIQUES
				if ($this->table['childRel'] != '') { // RUB ou CAT/PROD
					global $R0;
					list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
					list($relRubId,$rubId) = explode('=',$relRubId_rubId);
					list($relProdId,$prodId) = explode('=',$relProdId_prodId);
					$C = new SQL($tableRel);
					if ($this->isrub == '1') $C->delSql(" $relRubId='{$this->id}' "); // EFFACER LES RELATIONS AVEC UNE RUBRIQUE
					else $C->delSql(" $relProdId='{$this->id}' "); // EFFACER LES RELATIONS D'UN PRODUIT DANS UNE RUBRIQUE
					
					
					// FONCTION SPECIFIQUE CITE DVD ------------------------------------------------------------------------------------------------- !!!!!!!!!!!!!!!!!!!!!!
					// Efface dans le catalogue produit et dans les Offres pack...
					/*if ($this->isrub != '1') {
						if ($R0['table'] == 'categories_offres') {
							$C = new SQL('categories_produits');
							$C->delSql(" prod_id='{$this->id}' ");
						}
						else  {
							$C = new SQL('categories_offres_produits');
							$C->delSql(" prod_id='{$this->id}' ");
						}
					}*/
					// FIN FONCTION SPECIFIQUE ------------------------------------------------------------------------------------------------- !!!!!!!!!!!!!!!!!!!!!!
					
				}
			}
			$this->id = NULL;
			$this->from = 'index.php?mode=liste';
			$this->info = 'supp';
		}
		else $this->info = 'nosel';
	}
	// - - - - - - - - - - - - - - - - - - - ACTIONS - - - - - - - - - - - - - - - - - - - //
	function makeAction() {
		global $langues; //////////////////////////
		for ($i=1; $i<count($this->data); $i++) { // ordre / 1-2 unique / 1-2 / Bilingue !!!!!
			if ($this->action == $this->data[$i]['name'] && count($this->data[$i]['valeur']) == 2) { // 0<->1
				$this->id = intval($_GET['selectId']);
				if ($this->data[$i]['bilingue'] == '1') { // BILINGUE
					$langue = $langues[0];
					//foreach ($langues as $langue) { 
						if ($this->data[$i]['unique'] == '1') {
							$this->champs['0'] =  array(0=>$this->data[$i]['name'].'_'.$langue, 1=>'0');
							$C = new SQL($this->table);
							$C->updateSql($this->champs,''); // Reset other
							$this->champs['0'] = array(0=>$this->data[$i]['name'].'_'.$langue, 1=>'1');
							$C = new SQL($this->table);
							$C->updateSql($this->champs,$this->data['0']['name']."='".$this->id."'");
							$this->info = 'modif';
						} else {
							$this->exValue = $this->getExValue($this->data[$i]['name'].'_'.$langue);
							$this->newValue = $this->exValue == '1' ? '0' : '1';
							$this->champs['0'] =  array(0=>$this->data[$i]['name'].'_'.$langue, 1=>$this->newValue);
							$C = new SQL($this->table);
							$C->updateSql($this->champs,$this->data['0']['name']."='".$this->id."'");
							$this->info = 'modif';
						}
					//}
				}
				else {
					if ($this->data[$i]['unique'] == '1') {
						$this->champs['0'] =  array(0=>$this->data[$i]['name'], 1=>'0');
						$C = new SQL($this->table);
						$C->updateSql($this->champs,''); // Reset other
						$this->champs['0'] = array(0=>$this->data[$i]['name'], 1=>'1');
						$C = new SQL($this->table);
						$C->updateSql($this->champs,$this->data['0']['name']."='".$this->id."'");
						$this->info = 'modif';
					} else {
						$this->exValue = $this->getExValue($this->data[$i]['name']);
						$this->newValue = $this->exValue == '1' ? '0' : '1';
						$this->champs['0'] =  array(0=>$this->data[$i]['name'], 1=>$this->newValue);
						$C = new SQL($this->table);
						$C->updateSql($this->champs,$this->data['0']['name']."='".$this->id."'");
						$this->info = 'modif';
					}
				}
			}
		}
	}
	// - - - - - - - - - - - - - - - - - - - ORDRE - - - - - - - - - - - - - - - - - - - //
	function makeOrdre() {
		global $langues;
		for ($i=0; $i<count($this->data); $i++) {
			if ($this->data[$i]['name'] == 'ordre') $ordreExist = 1;  // Verif if ORDRE exist
			if ($this->data[$i]['relation'] == 1) $relationName = $this->data[$i]['name']; // Verif if got a CAT (cat_id) or RUB (parent_id)
		}

		if ($ordreExist) {
			if ($_GET['ordre'] == intval($_GET['ordre']) && intval($_GET['selectId']) > 0) { // Reorder One & all
				$newOrdre = intval($_GET['ordre']);
				$selectId = intval($_GET['selectId']);
				$this->id = $selectId;
				// Update selected ordre
				$this->champs['0'] =  array(0=>'ordre',1=>$newOrdre);
				$C = new SQL($this->table);
				$C->updateSql($this->champs, $this->data['0']['name']."='".$selectId."'"); // where id='$id'
				
				$where = '';
				if (($this->isrub || $this->iscat) && !empty($relationName)) { // GET SELECTED PARENT VALUE
					$where = $relationName."='".$this->getExValue($relationName)."' AND "; // Ex . cat_id=17 > reorder only in his CAT or PARENT_ID
				}
				elseif ($relationName != '')  $where .= $relationName."='".$this->cat_id."' AND ";
				
				// Get order for all
				$F = new SQL($this->table);
				$F->LireSql(array(0=>$this->data['0']['name']), $where." ".$this->data['0']['name']."!='' ORDER BY ordre ASC ");

				// Clean re-order all
				$inc = 10;
				for ($p=0; $p<count($F->V); $p++) {
					$C = new SQL($this->table);
					$this->champs['0'] =  array(0=>'ordre',1=>$inc);
					$C->updateSql($this->champs,$this->data['0']['name']."='".$F->V[$p][$this->data['0']['name']]."'");
					$inc += 10;
				}
				$this->info = 'ordre';
			}
			else { // Reorder All
				list($newOrdre,$order) = explode('-',clean($_GET['ordre']));
				// Get order for all
				$F = new SQL($this->table);
				if ($relationName != '')  $where = $relationName."='".$this->cat_id."' AND ";
				$F->LireSql(array(0=>$this->data['0']['name']),$where." ".$this->data['0']['name']."!='' ORDER BY $newOrdre $order ");
				// Clean re-order all
				$inc = 10;
				for ($p=0; $p<count($F->V); $p++) {
					$C = new SQL($this->table);
					$this->champs['0'] =  array(0=>'ordre',1=>$inc);
					$C->updateSql($this->champs,$this->data['0']['name']."='".$F->V[$p][$this->data['0']['name']]."'");
					$inc += 10;
				}
				$this->info = 'ordre';
			}
		}
	}
	// - - - - - - - - - - - - - - - - - - - ORDRE INNER RUBRIQUE - - - - - - - - - - - - - - - - - - - //
	function makeRubOrdre() { // Ordre dans la table relation rubriques/produits
		global $langues,$R0;
		for ($i=0; $i<count($this->data); $i++) {
			if ($this->data[$i]['name'] == 'ordre') $ordreExist = 1;  // Verif if ORDRE exist
			if ($this->data[$i]['relation'] == 1) $relationName = $this->data[$i]['name']; // Verif if got a CAT
		}
		if ($ordreExist) {
		
			list($table,$tableRel,$tableProd,$relRubId_rubId,$relProdId_prodId,$catTitre,$prodTitre) = explode(':',$R0['childRel']);
			list($relRubId,$rubId) = explode('=',$relRubId_rubId);
			list($relProdId,$prodId) = explode('=',$relProdId_prodId);
			
			if ($_GET['ordre'] == intval($_GET['ordre']) && intval($_GET['selectId']) > 0) { // Reorder One & all
				$newOrdre = intval($_GET['ordre']);
				$selectId = intval($_GET['selectId']);
				$this->id = $selectId;
				// Update selected ordre
				$this->champs['0'] =  array(0=>'ordre',1=>$newOrdre);
				$C = new SQL($tableRel);
				$C->updateSql($this->champs,  $relRubId."='".$this->rub_id."' AND ".$relProdId."='".$selectId."' "); // where id='$id'
				// Get order for all
				$F = new SQL($tableRel);
				$F->LireSql(array($relProdId),$relRubId."='".$this->rub_id."' ORDER BY ordre ASC ");
				$inc = 10;
				for ($p=0; $p<count($F->V); $p++) { // Clean re-order all (prod_id)
					$C = new SQL($tableRel);
					$this->champs['0'] =  array(0=>'ordre',1=>$inc);
					$C->updateSql($this->champs,$relRubId."='".$this->rub_id."' AND ".$relProdId."='".$F->V[$p][$relProdId]."'");
					$inc += 10;
				}
				$this->info = 'ordre';
			}
			else { // Reorder All
				list($newOrdre,$order) = explode('-',clean($_GET['ordre']));
				// Get order for all
				$F = new SQL($tableRel);
				//$F->debug = 1 ;
				$F->customSql(" SELECT $tableProd.$prodId FROM $tableProd LEFT JOIN $tableRel ON $tableProd.$prodId=$tableRel.$relProdId WHERE $tableRel.$relRubId='{$this->rub_id}' ORDER BY $tableProd.$newOrdre $order ");
				// Clean re-order all
				$inc = 10;
				for ($p=0; $p<count($F->V); $p++) { // Clean re-order all
					$C = new SQL($tableRel);
					$this->champs['0'] =  array(0=>'ordre',1=>$inc);
					$C->debug = 1 ;
					$C->updateSql($this->champs,$relRubId."='".$this->rub_id."' AND ".$relProdId."='".$F->V[$p][$prodId]."'");
					$inc += 10;
				}
				$this->info = 'ordre';
			}
		}
	}
	// - - - - - - - - - - - - - - - - - - - END - - - - - - - - - - - - - - - - - - - //
}
?>