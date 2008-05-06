<?
/**
 * Classe pour générer un calendrier
 * @author CrazyCat
 * @copyright 10/10/207
 * 
 * @package Dates
 */
class Calendar {
	
	/**
	 * Année à utiliser
	 * @var integer
	 */
	var $year;
	
	/**
	 * Mois à utiliser
	 * @var integer
	 */
	var $month;
	
	/**
	 * Liste des évènements
	 * @var mixed
	 */
	var $events;
	
	/**
	 * Evènement selectionné par défaut
	 * @var integer
	 */
	var $selected = 0;
	
	/**
	 * Séparateur de dates
	 * @var string
	 */
	var $separators = 'li';
	
	/**
	 * Liens pour les dates
	 * @var string
	 */
	var $links = '#{id}';
	
	/**
	 * Paramètre onClick du lien
	 * @var string
	 */
	var $onclick = '';
	
	/**
	 * Classe à appliquer sur un évènement
	 * @var string
	 */
	var $class_on = 'on';
	
	/**
	 * Classe à appliquer sur une date normale
	 * @var string
	 */
	var $class_off = '';
	
	/**
	 * Constructeur du calendrier (PHP5)
	 * @param integer $month Mois à utiliser
	 * @param integer $year Année à utiliser
	 * @param mixed $events Tableau d'évènements
	 * @param integer $selected Evènement sélectionné
	 */
	function __construct($month, $year, $events, $selected=0) {
		$this->_version = version_compare(phpversion(), "5.1.0", "<=");
		$this->setYear($year);
		$this->setMonth($month);
		$this->setEvents($events);
		$this->selected = $selected;
		$this->setClassOff();
		$this->setClassOn();
	}
	
	/**
	 * Constructeur du calendrier (PHP4)
	 * @param integer $month Mois à utiliser
	 * @param integer $year Année à utiliser
	 * @param mixed $events Tableau d'évènements
	 * @param integer $sel Evènement sélectionné
	 */
	function Calendar($month, $year, $events, $sel=0) {
		$this->__construct($month, $year, $events, $sel);
	}
	
	/**
	 * Affectation de l'année
	 * @param integer $year
	 */
	function setYear($year) { $this->year = (int) $year; }
	
	/**
	 * Affectation du mois
	 * @param integer $month
	 */
	function setMonth($month) { $this->month = (int) $month; }
	
	/**
	 * Affectation des évènements
	 * @param mixed $events
	 */
	function setEvents($events) { $this->events = $events; }
	
	/**
	 * jour de la semaine du 1er (format ISO-8601)
	 * @access private
	 */
	function _setStartDay() {
		if ($this->_version!=-1) {
			$this->startday = date("N", mktime(0, 0, 0,$this->month, 1, $this->year));
		} else {
			$this->startday = date("w", mktime(0, 0, 0,$this->month, 1, $this->year));
			if ($this->startday == 0) $this->startday = 7;
		}
	}
	
	/**
	 * Dernier jour du mois
	 * @access private
	 */
	function _setEndDay() {
		$this->enday = date("t", mktime(0, 0, 0,$this->month, 1, $this->year));
	}
	
	/**
	 * Affectation des séparateurs (li, td)
	 * @param string $separators
	 */
	function setSeparators($separators='li') { $this->separators = $separators; }
	
	/**
	 * Définition du lien (href) généré
	 * On utilise {id} pour remplacer par l'id
	 * de l'évènement
	 * @param string $links
	 */
	function setLinks($links="#{id}") { $this->links = $links; }
	
	/**
	 * Définition du paramètre optionnel onclick
	 * On utilise {id} pour remplacer par l'id
	 * de l'évènement
	 * @param string $onclick
	 */
	function setOnClick($onclick='') { $this->onclick = $onclick; }
	
	/**
	 * Classe à affecter pour un lien actif
	 * @param string $class_on
	 */
	function setClassOn($class_on='on') {
		if ($class_on!='') $this->class_on = ' class="'.$class_on.'"';
		else $this->class_on = '';
	}
	
	/**
	 * Classe à affecter pour un lien inactif
	 * @param string $class_off
	 */
	function setClassOff($class_off='') {
		if ($class_off!='') $this->class_off = ' class="'.$class_off.'"';
		else $this->class_off = '';
	}
	
	/**
	 * Génération du tableau des dates
	 * Affecte les évènements aux dates
	 *@access private
	 */
	function _genDates() {
		$this->arrCal = array();
		$this->_setStartDay();
		$this->_setEndDay();
		$tmpid = $this->year.sprintf("%02d", $this->month);
		if($this->startday>1) $this->arrCal = array_fill(0, $this->startday-1, '');
		$tmplink = 'href="'.$this->links.'"';
		if ($this->onclick!='') $tmplink .= ' onclick="'.$this->onclick.'"';
		for ($i=1;$i<=$this->enday;$i++) {
			if (is_array($this->events) && array_key_exists($i, $this->events)) $this->arrCal[] = str_replace('{id}', $tmpid.sprintf("%02d",$i), '<a '.$tmplink.$this->class_on.'>'.$i.'</a>');
			else $this->arrCal[] = str_replace('{id}', $tmpid.sprintf("%02d",$i), '<a href="'.$this->links.'"'.$this->class_off.'>'.$i.'</a>');
		}
	}
	
	/**
	 * Génération du code html
	 * @return string
	 */
	function genHtml() {
		$this->period = date("F Y", mktime(0, 0, 0,$this->month, 1, $this->year));
		$this->_genDates();
		$startTag = '<'.$this->separators.'>';
		$endTag = '</'.$this->separators.'/>'."\n";
		$this->Html = $startTag.implode($endTag.$startTag, $this->arrCal).$endTag;
		return $this->Html;
	}
}

/**
 * Internationalisation du calendrier
 * @author CrazyCat
 * @copyright 10/10/2007
 *
 * @package Dates
 */
class CalendarMultil extends Calendar {
	
	/**
	 * Internationalisation des mois
	 * @var mixed
	 */
	var $months = array(
		'de' => array(),
		'es' => array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'),
		'fr' => array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre'),
		'it' => array(),
		'uk' => array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
		);
	
	/**
	 * Internationalisation des jours
	 * @var mixed
	 */
	var $days = array(
		'de' => array(),
		'es' => array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'),
		'fr' => array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'),
		'it' => array(),
		'uk' => array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'),
		);
		
	/**
	 * Constructeur du calendrier (PHP5)
	 * @param integer $month Mois à utiliser
	 * @param integer $year Année à utiliser
	 * @param mixed $events Tableau d'évènements
	 * @param integer $selected Evènement sélectionné
	 * @param string $lang Langue à utiliser
	 */
	function __construct($month, $year, $events, $selected=0,$lang='fr') {
		parent::__construct($month, $year, $events, $selected);
		$this->setLanguage($lang);
	}
	
	/**
	 * Constructeur du calendrier (PHP4)
	 * @param integer $month Mois à utiliser
	 * @param integer $year Année à utiliser
	 * @param mixed $events Tableau d'évènements
	 * @param integer $selected Evènement sélectionné
	 * @param string $lang Langue à utiliser
	 */
	function CalendarMultil($month, $year, $events, $selected=0, $lang='fr') {
		$this->__construct($month, $year, $events, $selected, $lang='fr');
	}
	
	/**
	 * Affectation de la langue
	 * @param string $lang
	 */
	function setLanguage($lang) { $this->lang = $lang; }
	
	/**
	 * Génération du HTML et traduction
	 * @return string
	 */
	function genHtml() {
		parent::genHtml();
		$this->period = str_replace($this->months['uk'], $this->months[$this->lang], $this->period);
		return $this->Html;
	}
}
?>