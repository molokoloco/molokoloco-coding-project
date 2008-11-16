<?
/*
	// A Class writed by Crazycat and reWorked by Molokoloco // Version du 08/10/07 //
	// Based on a SQL table containing "pid" field for pointing ancestor

	// INITIALISATION
	
		$S =& new ARBO();
		$S->fields = array('*'); // Custom param
		$S->accueilTypeId = '19';
		
		$S->buildArbo();
		// $S->arbo; > Array Containing all data
		// $S->rid; > Current rid
		// $S->prid; > Current pid
		// $S->rrid; > Root rid
		// $S->arid; > Acceuil rid
		
		echo $S->arbo[$S->rid]['titre_'.$lg];
		> Accueil du site
	
	// UTILITIES
		
		$S->getRidByType(8)
		
		$S->sep = '</div><div>';
		$rootMenuHtml = '<div>'.$S->getRootMenuHtml($S->rid).'</div>';
		
		echo $S->getRootMenuUl($S->rid);
		
		echo $S->totalRootMenu;
		
		echo $S->getAriane($S->rid);

		$childMenuHtm = $S->getChildMenuHtml($S->rid);
		if (empty($childMenuHtm)) $childMenuHtm = $S->childMenuHtm($S->prid);
		
		$visiteurs_rid = $S->getRidByType(13);
		$visiteurs_first_child_rid = $S->getFirstChildRid($visiteurs_rid);
		$visiteurs_url = $S->arbo[$visiteurs_first_child_rid]['url'];
		if ($S->rid == $visiteurs_rid) $S->setCurrentRid($visiteurs_first_child_rid);
		
	// EXEMPLES
	
		- Pour avoir le chemin de la rid Root jusqu'a la rid en cour : 
			foreach($arbo[$S->rid]['parents'] as $ridSel) {}
		
		- Pour avoir les rid childs de la rid en cour : 
			foreach($arbo[$S->rid]['childs'] as $ridSel) {}
		
		- Pour avoir les rid Roots du site : 
			foreach($arbo[$S->arid]['childs'] as $ridSel) {}
		
		- Pour avoir les rid childs de la rid parente (de meme niveau) :
			foreach($arbo[$S->prid]['childs'] as $ridSel) {}
		
		- Pour connaitre la rubrique ayant le module "Contact"
			<a href="<?=$S->arbo[$S->getRidByType(8)]['url'];?>">Nous contacter</a>;
		
		- Connaitre le lien vers un produit dans une rub
			$actuRubRid = $S->getRidByType(4);
			$articleUrl = urlRewrite($S->arbo[$actuRubRid]['titre_'.$lg].'-'.$A->V[0]['titre_'.$lg], 'r'.$actuRubRid.'ar'.$A->V[0]['id']);
		
		etc...

*/

class ARBO {

	/* ------------------------------ PRIVATES PROCESSED VALUES ------------------------------ */
	
	var $rid; // id of the current rubrique
	var $prid; // id of the direct PARENT rubrique
	var $rrid; // id of the ROOT node of the current rubrique
	var $arid; // id of the rubrique accueil
	var $arbo = array(); // Array global des valeurs de la table rubriques
	
	var $lg; // Langue en cour
	var $basePath; // Url relative lien
	
	/* ------------------------------ PUBLIC PARAMS ------------------------------ */
	
	var $accueilTypeId; // type_id si page d'accueil du site
	var $redirtofirstTypeId; // type_id si redirection sur le firstChild
	var $redirtoLink; // si redirection vers lien...
	var $table; // Table rubriques
	var $where; // Rubrique where
	var $fields = array(); // "Fields" de la table rubrique

	/* ------------------------------ INIT ------------------------------ */
	/**
     * ARBO Constructeur
	 * @return defaut values
     */
	function ARBO() {
		
		global $lg;
		$this->lg = $lg;
		$this->table = 'cms_pages';
		$this->where = "actif='1'";
		$this->fields = array('id', 'pid', 'type_id', 'lien_'.$this->lg, 'menu', 'titre_'.$this->lg);
		$this->accueilTypeId = 1;
		$this->redirtofirstTypeId = 2;
		$this->redirtoLink = 3;
		$this->totalRootMenu = 0;
		$this->basePath = './';
	}
	
	/**
     * ARBO Builder
     * @return array $arbo and others essentials
     */
	function buildArbo() {
	
		if ($this->lg != 'fr' && !in_array('titre_fr', $this->fields)) $this->fields[] = 'titre_fr';
		
		$A =& new Q("
			SELECT ".implode(',', $this->fields)."
			FROM ".$this->table."
			WHERE ".$this->where."
			ORDER BY pid ASC, ordre DESC
		");
		
		foreach($A->V as $V) {
			
			$this->arbo[$V['id']] = $V;
			$this->arbo[$V['id']]['url'] = '';
			$this->arbo[$V['id']]['parents'] = array();
			$this->arbo[$V['id']]['childs'] = array();
			
			if ($this->lg != 'fr' && empty($V['titre_'.$this->lg]))
				$this->arbo[$V['id']]['titre_'.$this->lg] = $V['titre_'.$this->lg] = '{'.$V['titre_fr'].'}';
			
			if (empty($V['titre_page_'.$this->lg]))
				$this->arbo[$V['id']]['titre_page_'.$this->lg] = $V['titre_page_'.$this->lg] = $V['titre_'.$this->lg];
			
			// Rubrique Accueil
			if ($V['type_id'] == $this->accueilTypeId) { 
				$this->arid = $V['id'];
				foreach($A->V as $R) { // Stock toutes les rubriques Roots dans childs de rid accueil
					if ($R['pid'] == 0 && $R['id'] != $this->arid)
						$this->arbo[$this->arid]['childs'][] = $R['id'];
				}
			}

			// Redirection auto
			if ($V['type_id'] == $this->redirtofirstTypeId) {
				foreach($A->V as $R) { // Find first child
					if ($R['pid'] == $V['id']) {
						$this->arbo[$V['id']]['url'] = $this->basePath.urlRewrite($R['titre_'.$this->lg], 'r'.$R['id']);
						break;
					}
				}
			}
			elseif ($V['type_id'] == $this->redirtoLink && !empty($V['lien_'.$this->lg])) { // Lien
				$this->arbo[$V['id']]['url'] = ( strpos($V['lien_'.$this->lg], 'http://') !== false ? $V['lien_'.$this->lg] : $this->basePath.$V['lien_'.$this->lg] );
			}
			else { // Url rewriting par defaut
				$this->arbo[$V['id']]['url'] = $this->basePath.urlRewrite($V['titre_page_'.$this->lg], 'r'.$V['id']);
			} 

			if ($V['pid'] > 0) { // Array recursive of parents rid
				$pid = $V['pid'];
				$this->arbo[$V['id']]['parents'] = array();
				while($pid > 0) { 
					$this->arbo[$V['id']]['parents'][] = $pid;
					$pid = ( $this->arbo[$pid]['pid'] > 0 ? $this->arbo[$pid]['pid'] : 0 );
				}
				$this->arbo[$V['id']]['parents'] = array_reverse($this->arbo[$V['id']]['parents'], TRUE); // from root to child..
				
				// Also add as childs of his direct parent
				$this->arbo[$V['pid']]['childs'][] = $V['id']; 
			}
			else if ($V['menu'] == 1) $this->totalRootMenu++;
		}

		if ($this->arid < 1) die(getDb('[ARBO()] Il faut configurer au moins <strong>une rubrique</strong> pour l\'acceuil'));
		
		//$this->arbo[$this->arid]['url'] = 'accueil.php'; // SPECIFIK !!!
		
		$this->setCurrentRid();
	}

	/**
     * Rid de la rubrique actuelle
     * @param $_GET['rid']
     * @return int $rid
     */
	function setCurrentRid($rid=0) {
		$this->rid = ( $rid > 0 ? $rid : ( isset($_GET['rid']) ? intval($_GET['rid']) : 0));
		if ($this->rid < 1 || empty($this->arbo[$this->rid])) $this->rid = $this->arid; // N'existe pas : Redir accueil ? 
		// Normalement les liens ne doivent pas porter sur cette rubrique
		if ($this->arbo[$this->rid]['type_id'] == $this->redirtofirstTypeId) $this->rid = $this->getFirstChildRid($this->rid);
		$this->setRootRid($this->rid);
		$this->setParentRid($this->rid);
	}
	
	/**
     * Rubrique parente Root
     * @return int $rid
     */
	function setRootRid($rid) {
		
		if ($this->arbo[$rid]['pid'] > 0) {
			$this->rrid = $this->arbo[$rid]['pid'];
			$this->setRootRid($this->rrid);
		}
		else $this->rrid = $rid;
		
	}
	
	/**
     * Rubrique parente directe
     * @return int $rid
     */
	function setParentRid($rid) {
		
		if ($this->arbo[$rid]['pid'] > 0) $this->prid = $this->arbo[$rid]['pid'];
		else $this->prid = $rid;
		
	}

	/* ------------------------------ METHODES SUR LA CLASS ------------------------------ */

	/**
     * Retourne Rubrique parente directe
     * @return int $rid
     */
	function getParentRid($rid) {
		
		if ($this->arbo[$rid]['pid'] > 0) $rid = $this->arbo[$rid]['pid'];
		else $rid = $this->arid;
		return $rid;
		
	}
	
	/**
     * Retourne la 1ere rubrique child
     * @return int $rid
     */
	function getFirstChildRid($rid) {
		if ($this->arbo[$rid]['childs'][0] > 0) return $this->arbo[$rid]['childs'][0];
		else return $rid;
	}

	/**
     * Retourne la 1ere rubrique ayant le module voulu
     * @return int $rid
     */
	function getRidByType($type_id) {
		if (!isset($this->arboModId)) {
			$this->arboModId = array();
			foreach($this->arbo as $V) $this->arboModId[$V['type_id']] = $V['id'];
		}
		if ($this->arboModId[$type_id]) return $this->arboModId[$type_id];
		else return 0;
	}
	
	/* ------------------------------ METHODES HTML ------------------------------ */
	/**
	 * FIL D'ARIANE OF A RID - SIMPLE HTML
     * @return string $ariane
     */
	function getAriane($rid=0) {
		
		if ($rid < 1) $rid = $this->rid;
		
		// Custom CSS
		$this->sep = (isset($this->sep) ? $this->sep : ' &gt; ');
		$this->css = (isset($this->css) ? $this->css : '');
		$this->cssOn = (isset($this->cssOn) ? $this->cssOn : '');
		$this->noAccueil = (isset($this->noAccueil) ? TRUE : FALSE);
		
		$this->ariane = '';
		
		// Accueil
		if (!$this->noAccueil)
			$this->ariane .= '<a href="'.$this->arbo[$this->arid]['url'].'" class="'.$this->css.'">'.html($this->arbo[$this->arid]['titre_'.$this->lg]).'</a>';
		
		if ($rid == $this->arid) return $this->ariane;
		
		if (!$this->noAccueil)
			$this->ariane .= ' '.$this->sep.' ';
		
		// In between
		if (!empty($this->arbo[$rid]['parents'])) {
			foreach($this->arbo[$rid]['parents'] as $prid) {
				$this->ariane .= '<a href="'.$this->arbo[$prid]['url'].'" class="'.$this->css.'">'.html($this->arbo[$prid]['titre_'.$this->lg]).'</a> '.$this->sep.' ';
			}
		}
		
		// Current
		$this->ariane .= '<a href="'.$this->arbo[$rid]['url'].'" class="'.$this->cssOn.'">'.html($this->arbo[$rid]['titre_'.$this->lg]).'</a>';
		
		return $this->ariane;
		
	}

	/**
     * ROOT MENU - IF ONLY NEED SIMPLE HTML
     * @return string $rootMenuHtml
     */
	function getRootMenuHtml($rid=0) {
		
		if ($rid < 1) $rid = $this->arid;
		
		// Custom CSS
		$this->sep = (isset($this->sep) ? $this->sep : ' | ');
		
		$rootMenuHtml .= '<a href="'.$this->arbo[$this->arid]['url'].'" class="'.($ridSel==$this->arid ? $this->cssOn : $this->css).'">'.html(aff($this->arbo[$this->arid]['titre_'.$this->lg])).'</a>'.$this->sep;
		foreach($this->arbo[$rid]['childs'] as $ridSel) {
			if ($this->arbo[$ridSel]['menu'] != 1 && empty($this->menuAll)) continue;
			$rootMenuHtml .= '<a href="'.$this->arbo[$ridSel]['url'].'" class="'.($ridSel==$this->rid ? $this->cssOn : $this->css).($i==(count($this->arbo[$rid]['childs'])-1)?' last':'').'">'.html(aff($this->arbo[$ridSel]['titre_'.$this->lg])).'</a>'.$this->sep;
		}
		$rootMenuHtml = substr($rootMenuHtml, 0, -strlen($this->sep));

		return $rootMenuHtml;
		
	}
	
	/**
     * ROOT MENU - IF ONLY NEED SIMPLE HTML
     * @return string $rootMenuHtml
     */
	function getBottomMenuHtml($rid=0) {
		
		if ($rid < 1) $rid = $this->arid;

		// Custom CSS
		$this->sep = (isset($this->sep) ? $this->sep : ' | ');

		foreach($this->arbo[$rid]['childs'] as $i=>$ridSel) {
			if ($this->arbo[$ridSel]['type_id'] == 11 || ($this->arbo[$ridSel]['menu'] == 1 && empty($this->menuAll))) continue;
			$bottomMenuHtml .= '<a href="'.$this->arbo[$ridSel]['url'].'" class="'.($ridSel==$this->rid ? $this->cssOn : $this->css).($i==(count($this->arbo[$rid]['childs'])-1)?'last':'').'" id="r'.($i+1).'">'.html(aff($this->arbo[$ridSel]['titre_'.$this->lg])).'</a>'.$this->sep;
		}
		$bottomMenuHtml = substr($bottomMenuHtml, 0, -strlen($this->sep));

		return $bottomMenuHtml;
		
	}
	
	/**
     * ROOT MENU WITH UL AND LI - IF ONLY NEED SIMPLE HTML
	 * Need : menu.css && 215_menu.js
     * @return string $rootMenuHtml
     */
	function getRootMenuUl($rid=0) {
		
		if ($rid < 1) $rid = $this->arid;

		if (empty($this->arbo[$rid]['childs'][0])) return '';

		$rootMenuHtml .= '<ul id="menu">';
		
		$sep_img = ''; //<img src="images/nav_divider.png" width="1" height="32" class="absmiddle" alt="" />';
		$li_sel_class = ($this->arid == $rid ? 'current_page' : 'page');
		
		// Accueil
		if ($this->arbo[$this->arid]['menu'] == 1)
			$rootMenuHtml .= '<li class="'.$li_sel_class.'"><a href="'.$this->arbo[$this->arid]['url'].'" class="menu c'.$this->arbo[$this->arid]['couleur'].'"><span>'.html(aff($this->arbo[$this->arid]['titre_'.$this->lg])).'</span></a></li>';

		// LEVEL 1
		foreach($this->arbo[$rid]['childs'] as $i=>$ridSel) {
		
			if ($this->arbo[$ridSel]['menu'] != 1 && empty($this->menuAll)) continue;

			$li_sel_class = ($ridSel == $this->rid || in_array($ridSel, $this->arbo[$this->rid]['parents']) ? 'current' : '');
			
			if ($i == 0) $li_sel_class .= ' first';
			elseif ($i == count($this->arbo[$this->arid]['childs']) - 2) $li_sel_class .= ' last'; // -2 >>> // Accueil d�sactiv�""
			
			// HAVE LEVEL 2 ?
			if (!empty($this->arbo[$ridSel]['childs']) && is_array($this->arbo[$ridSel]['childs'])) {
				
				$rootMenuHtml .= '<li class="'.$li_sel_class.'">'.$sep_img.'<a href="'.$this->arbo[$ridSel]['url'].'" id="menu_'.$this->arbo[$ridSel]['id'].'" class="menu c'.$this->arbo[$ridSel]['couleur'].'"><span>'.html(aff($this->arbo[$ridSel]['titre_'.$this->lg])).'</span></a>';
				$rootMenuHtml .= '<ul style="display:none;" class="sousMenu" id="smenu_'.$this->arbo[$ridSel]['id'].'">';
				
				//$rootMenuHtml .= '<li '.$class.'><a href="'.$this->arbo[$ridSel]['url'].'" class="c'.$this->arbo[$ridSel]['couleur'].'"><span>Accueil '.html(aff($this->arbo[$ridSel]['titre_'.$this->lg])).'</span></a></li>';
				
				// LEVEL 2
				foreach($this->arbo[$ridSel]['childs'] as $k=>$ridSel2) {
					
					if ($this->arbo[$ridSel2]['menu'] != 1 && empty($this->menuAll)) continue;
					
					if ($k == 0) $li_sel_class2 = 'first';
					elseif ($k == count($this->arbo[$ridSel]['childs']) - 1) $li_sel_class2 = 'last';
					else $li_sel_class2 = '';
					
					// HAVE LEVEL 3 ?
					if (!empty($this->arbo[$ridSel2]['childs']) && is_array($this->arbo[$ridSel2]['childs'])) {
						
						$rootMenuHtml .= '<li class="'.$li_sel_class2.'">'.$sep_img.'<a href="'.$this->arbo[$ridSel2]['url'].'" id="menu_'.$this->arbo[$ridSel2]['id'].'" class="menuParent c'.$this->arbo[$ridSel]['couleur'].'"><span>'.html(aff($this->arbo[$ridSel2]['titre_'.$this->lg])).'</span></a>';
						$rootMenuHtml .= '<ul style="display:none;" class="sousMenu" id="smenu_'.$this->arbo[$ridSel2]['id'].'">';
						
						// LEVEL 3
						foreach($this->arbo[$ridSel2]['childs'] as $j=>$ridSel3) {
							
							if ($this->arbo[$ridSel3]['menu'] != 1 && empty($this->menuAll)) continue;
							
							if ($j == 0) $li_sel_class3 = 'first';
							elseif ($j == count($this->arbo[$ridSel2]['childs']) - 1) $li_sel_class3 = 'last';
							else $li_sel_class3 = '';
							
							$rootMenuHtml .= '<li class="'.$li_sel_class3.'"><a href="'.$this->arbo[$ridSel3]['url'].'" class="c'.$this->arbo[$ridSel]['couleur'].'"><span>'.html(aff($this->arbo[$ridSel3]['titre_'.$this->lg])).'</span></a></li>';
						}
						$rootMenuHtml .= '</ul></li>';
						
					}
					else $rootMenuHtml .= '<li '.$class.'><a href="'.$this->arbo[$ridSel2]['url'].'" class="c'.$this->arbo[$ridSel]['couleur'].'"><span>'.html(aff($this->arbo[$ridSel2]['titre_'.$this->lg])).'</span></a></li>';
					
				}
				$rootMenuHtml .= '</ul></li>';
				
			}
			else $rootMenuHtml .= '<li class="'.$li_sel_class.'" '.($i==5?'id="menu_root_'.$i.'"':'').'>'.$sep_img.'<a href="'.$this->arbo[$ridSel]['url'].'" class="menu c'.$this->arbo[$ridSel]['couleur'].'"><span>'.html(aff($this->arbo[$ridSel]['titre_'.$this->lg])).'</span></a></li>'; // ($i==5?'menu_root_'.$i:'') > IE SUCK need ID to hide Element

		}
		$rootMenuHtml .= '</ul>';
		
		return $rootMenuHtml;
	}
	
	
	/**
     * ROOT MENU WITH UL AND LI - RECURSIVE X LEVEL
	 * Need : menu.css && 215_menu.js
     * @return string $rootMenuHtml
     */
	function getChildsUlRecur($childs) {

		$totalChilds = count($childs);
		foreach($childs as $i=>$ridSel) {
			if ($this->arbo[$ridSel]['menu'] != 1 && empty($this->menuAll)) {
				$totalChilds--; // Attention class "last"...
				continue;
			}
			$li_sel_class = ($ridSel == $this->rid || in_array($ridSel, $this->arbo[$this->rid]['parents']) ? $this->liCss : '');
			if ($i == 0) $li_sel_class .= ' first';
			elseif ($i == ($totalChilds - 1)) $li_sel_class .= ' last';
			
			$sep_img = '<img src="images/nav_divider.png" width="1" height="32" class="absmiddle" alt="" />';

			$link_sel_class = ($ridSel == $this->rid ? $this->cssOn : $this->css);
			$link_href = ($this->isJsMenu && $this->arbo[$ridSel]['type_id'] == $this->redirtofirstTypeId ? 'javascript:void(0);' : $this->arbo[$ridSel]['url'] );

			// HAVE LEVEL X ?
			if (!empty($this->arbo[$ridSel]['childs']) && is_array($this->arbo[$ridSel]['childs']) && count($this->arbo[$ridSel]['parents']) <= $this->maxLevel) {
				
				$this->RootMenuUl .= '<li class="'.$li_sel_class.'">'.$sep_img.'<a href="'.$link_href.'" id="menu_'.$this->arbo[$ridSel]['id'].'" class="menuParent '.$link_sel_class.'"><span>'.htmlentities(aff($this->arbo[$ridSel]['titre_'.$this->lg])).'</span></a>';
				
				$show = '';
				if ($this->isJsMenu) $show = ($ridSel == $this->rid || in_array($ridSel, $this->arbo[$this->rid]['parents']) ? '' : 'display:none;');
				
				$this->RootMenuUl .= '<ul style="'.$show.'" class="sousMenu" id="smenu_'.$this->arbo[$ridSel]['id'].'">';
				// LEVEL X
				$this->getChildsUlRecur($this->arbo[$ridSel]['childs']);
				$this->RootMenuUl .= '</ul></li>';
			}
			else $this->RootMenuUl .= '<li class="'.$li_sel_class.'">'.$sep_img.'<a href="'.$link_href.'" class="menu '.$link_sel_class.'"><span>'.htmlentities(aff($this->arbo[$ridSel]['titre_'.$this->lg])).'</span></a></li>';
		}
	}

	function getRootMenuUlRecur($rid=0) {

		if ($rid < 1) $rid = $this->arid;

		if (empty($this->arbo[$this->arid]['childs'][0])) return '';

		$this->menuId = (!empty($this->menuId) ? $this->menuId : 'menu_ul');
		$this->liCss = (!empty($this->liCss) ? $this->liCss : 'current');
		$this->css = (!empty($this->css) ? $this->css : '');
		$this->cssOn = (!empty($this->cssOn) ? $this->cssOn : '');
		$this->maxLevel = (!empty($this->maxLevel) ? $this->maxLevel : 4);

		// LEVEL RECUR
		$this->RootMenuUl = '';
		$this->getChildsUlRecur($this->arbo[$rid]['childs']);
		if (empty($this->RootMenuUl)) return '';
		
		$rootMenuHtml = '<ul id="'.$this->menuId.'">';
		
		// Add Accueil ?
		$li_sel_class = ($this->arid == $this->rid ? 'current' : '');
		$rootMenuHtml .= '<li class="'.$li_sel_class.'"><a href="'.$this->arbo[$this->arid]['url'].'" class="menu '.$li_sel_class.'"><span>'.htmlentities(aff($this->arbo[$this->arid]['titre_'.$this->lg])).'</span></a></li>';
		
		
		$rootMenuHtml .= $this->RootMenuUl;
		$this->RootMenuUl = NULL;				
		$rootMenuHtml .= '</ul>';

		return $rootMenuHtml;
	}

	/**
     * CHILD MENU HTML - IF ONLY NEED SIMPLE HTML
     * @return string $rid
     */

	function getChildMenuHtml($rid=0) {

		if ($rid < 1) $rid = $this->rid;

		$parent_titre = $this->arbo[$rid]['titre_'.$this->lg];
		$smenuLink = $smenuClass = $smenuTitre = array();

		//$this->css = (empty($this->css) ? $this->css : '');
		$this->cssOn = (!empty($this->cssOn) ? $this->cssOn : 'on');
			
		switch($this->arbo[$rid]['type_id']){ // LA CLASS DOIT ETRE EDITE POUR CHAQUE SITE......
			
			case 6 : // 'mod_actualites'
				$P =& new Q("SELECT id, titre_{$this->lg} FROM mod_actualites_cat WHERE actif='1' ORDER BY ordre DESC");
				foreach($P->V as $V) {
					if (empty($_GET['article_cat_id'])) $article_cat_id = $V['id'];
					else $article_cat_id = $_GET['article_cat_id'];
					$smenuLink[] = urlRewrite($parent_titre.'-'.$V['titre_'.$this->lg], 'r'.$rid.'-ac'.$V['id']);
					$smenuClass[] = ($_GET['article_cat_id'] == $V['id'] ? 'class="'.$this->cssOn.'"' : '');
					$smenuTitre[] = aff($V['titre_'.$this->lg]);
				}
			break;

			default:
				if (empty($this->arbo[$rid]['childs'])) return ''; // return PARENT CHILDS ?
				foreach($this->arbo[$rid]['childs'] as $srid) {
					$smenuLink[] = urlRewrite($parent_titre.'-'.$this->arbo[$srid]['titre_'.$this->lg], 'r'.$srid);
					$smenuClass[] = ($_GET['rid'] == $srid ? 'class="'.$this->cssOn.'"' : '');
					$smenuTitre[] = aff($this->arbo[$srid]['titre_'.$this->lg]);
				}
			break;
		}

		if (empty($smenuLink)) return '';
		
		//$smenuHtml = '<h1><span>'.aff($parent_titre).'</span></h1>';
		$smenuHtml .= '<ul id="ssmenu">';
		foreach($smenuLink as $k=>$link) $smenuHtml .= '<li '.$smenuClass[$k].'><a href="'.$smenuLink[$k].'" '.$smenuClass[$k].'>'.$smenuTitre[$k].'</a></li>';
		$smenuHtml .= '</ul>';

		return $smenuHtml;
	}

}

/*
class ARBOSPECIAL extends ARBO {
	
	function ARBOSPECIAL() {
		parent::arbo();
	}
	
	function getChildMenuHtml($rid) {
		if ($this->arbo[$rid]['type_id'] == 99) {
			//...
		}
		else return parent::getChildMenuHtml($rid);
	}
}
*/

?>