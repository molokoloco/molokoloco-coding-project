<?
/**
 * This class is used to generate a tree
 * Usage:
 * $arbo = new arbo_tree('rubriques', $lg);
 * if ($goto=='') $goto = 'accueil'; // The default page
 * $arbo->getid($goto);
 * $rub_activ = $arbo->curid;
 * $arbo->getparent($arbo->curid);
 * 
 * @author Olivier "CrazyCat" Locatelli <olivier.locatelli@gmail.com>
 * @copyright 20071024
 * @version 1.2
 *
 */
class arbo_tree {
	
	/**
	 * Current language of the site
	 * @var string
	 */
	var $lang = 'fr';

	/**
     * An array containing all datas about the rubs
     * @var array
     */
	var $menu = array();
	
	/**
	 * An array containing the list of rubs
	 * @var array
	 */
	var $rubs = array();
	
	/**
	 * Array of correspondance id / title
	 * @var array
	 */
	var $id_titre = array();
	
	/**
	 * Id of current rub
	 * @var int
	 */
	var $curid;
	
	/**
	 * Array of ALL the parents of the current rubrik
	 * @var mixed
	 */
	var $parent = array();
	
	/**
	 * Array of the children of the current rubrik
	 * @var mixed
	 */
	var $child = array();
	
	/**
	 * Strings containing the path to the rubrik
	 * @var string
	 */
	var $filariane, $filarianeS;
	
	/**
	 * Mode used to create the URLS
	 * @var string
	 */
	var $mode = 'text';
	
	/**
     * Constructor PHP5
     * @magic 
     * @param string $table Name of the table SQL
     * @param string $lang Current language
     * @see Exe()
     */
	function __construct($table, $lang='fr') {
		$this->lang = $lang;
		$sql = Exe("SELECT * FROM `".$table."` WHERE actif=1 ORDER BY parent_id ASC, ordre ASC", "row");
		$i = 1;
		if(sizeof($sql)) {
			foreach($sql as $row) {
				if ($row['type'] != 1) {
					$row['lien'] = "./index2.php?goto=".$row['goto'];
					$row['target'] = "";
				} else {
					if (preg_match('!^http!', $row['lien'])) { $row['target'] = "target=\"_blank\""; } else { $row['target'] = ""; }
				}
				$this->id_titre[$row['id']] = $row['goto'];
				if ($row['parent_id']!= 0) {
					$this->menu[$row['parent_id']]['child'][] = $row['id'];
				} else {
					$this->rubs[$i] = $row['id'];
					$i++;
				}
				$this->menu[$row['id']] = $row;
			}
		}
	}
	
	/**
	 * constructor PHP4 for compatibility
	 * @magic 
	 * @param string $table Name of the table SQL
     * @param string $lang Current language
	 */
	function arbo_tree($table, $lang='fr') {
		$this->__construct($table, $lang);
	}

	/**
     * Return the children of the current rubrik
     * @param integer $id
     */
	function getchild($id) {
		if (is_array($this->menu[$id]['child'])) {
			foreach($this->menu[$id]['child'] as $curChild) {
				$this->child[] = $this->menu[$curChild['id']];
			}
		}
	}

	/**
     * Returns ALL the parents of the rubrik
     * @param integer $id id of the current rubrik
     */
	function getparent($id) {
		if ($this->menu[$id]['parent_id'] != 0) {
			$this->parent[] = $this->menu[$id]['parent_id'];
			$this->getparent($this->menu[$id]['parent_id']);
		} else {
			$this->master = $this->menu[$id]['goto'];
			$this->masterdesc = $this->menu[$id]['titre_'.$this->lang];
			$this->masterid = $id;
		}
		if (is_array($this->parent)) {
			krsort($this->parent);
		}
	}

	/**
     * Renvoit l'id de la rubrique actuelle
     * @param string $goto "goto" of the current rubrik
     * @param string $mode Using text ids (default) or numeric ids
     * @return int $curid
     * @since 2007/10/31 : dual mode
     */
	function getid($goto='accueil',$mode='text') {
		// Verification of the goto type
		if (($mode!='text') && ($goto=='')) {
			$goto = 1;
			$this->mode = 'numeric';
		} elseif ($goto == '') {
			$goto = 'accueil';
			$this->mode = 'text';
		}
		
		// Transforms a numeric goto in a string goto
		if (is_numeric($goto)) $goto = $this->menu[$goto]['goto'];
		
		//print('--- '.$goto.' ---');
		$this->curid = array_search($goto, $this->id_titre);
		if (($this->menu[$this->curid]['type'] == 0) && is_array($this->menu[$this->curid]['child'])) {
			$this->curid = $this->menu[$this->curid]['child'][0];
			$this->getid($this->menu[$this->curid]['goto']);
		}
		return $this->curid;
	}

	/**
     * Genere le fil d'ariane
     * @return string
     */
	function ariane() {
		unset($this->ArrAriane);
		$this->ArrAriane[] = "<a href=\"./index.php\" title=\"Accueil\">Accueil</a>";
		if (($this->curid!='') && (is_array($this->parent))) {
			foreach($this->parent as $key => $value) {
				if ($value != $this->curid) {
					$this->ArrAriane[] = "<a href=\"./index2.php?goto=".$this->menu[$value]['goto']."\" title=\"".stripslashes($this->menu[$value]['titre'])."\">".stripslashes($this->menu[$value]['titre_'.$this->lang])."</a>";
				}
			}
		}
		$this->ArrAriane[] = '<span>'.stripslashes($this->menu[$this->curid]['titre_'.$this->lang]).'</span>';
		$this->filariane = "<div class=\"chemin\">".implode(" > ", $this->ArrAriane)."</div>";
		return $this->filariane;
	}

	/**
     * Renvoit un fil d'ariane réduit (pour les recherches
     * @return string $filarianeS
     * @since version 1.1
     */
	function ariane_search() {
		unset($this->ArrArianeS);
		$this->ArrArianeS[] = "Accueil";
		if (($this->curid!='') && (is_array($this->parent))) {
			foreach($this->parent as $key => $value) {
				if ($value != $this->curid) {
					$this->ArrArianeS[] = stripslashes($this->menu[$value]['titre_'.$this->lang]);
				}
			}
		}
		$this->ArrArianeS[] = '<span>'.stripslashes($this->menu[$this->curid]['titre_'.$this->lang]).'</span>';
		$this->filarianeS = implode(" > ", $this->ArrArianeS);
		return $this->filarianeS;
	}
}
?>