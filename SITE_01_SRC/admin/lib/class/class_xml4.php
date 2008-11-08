<?
/**
 * <molokoloco:2008/>
 *
 * Exemple :
 *
 * $X = new XML('http://127.0.0.1/wall4php/iptv.xml');
 * if (!($xResulat = $X->getItems('//item'))) db($X->error());
 * else {
 * 		foreach($xResulat as $item) db((string)$item->title);
 * }
 *
 */

class XML {

	private $_path = '';
	private $_xml = '';
	private $_error = array();
	
	// PHP 5
	function __construct($path) {
		$this->_path = $path;
	}
	
	// PHP 4
	function XML($path) {
		$this->__construct($path);
	}
	
	private function _loadXml() {
		if (empty($this->_path)) {
			$this->_error[] = '[XML()] Le chemin sp&eacute;cifi&eacute; est vide';
			return FALSE;
		}
		$this->_xml = @simplexml_load_file($this->_path); // CORE FUNCTION // --------------------------------------------- //
		if (!$this->_xml) {
			$this->_error[] = '[XML()] Probleme de lecture du flux XML : '.$this->_path;
			return FALSE;
		}
		return TRUE;
	}
	
	// Obtenir l'Objet XML
	public function getXml() {
		if (!$this->_loadXml()) return FALSE;
		else return $this->_xml;
	}
	
	// Obtenir un objet représentant les items spécifiés par un Xpath
	public function getItems($xPath) {
		if (empty($xPath)) {
			$this->_error[] = '[XML()] Le xPath sp&eacute;cifi&eacute; est vide';
			return FALSE;
		}
		
		if (!$this->_loadXml()) return FALSE;

		$xResult = $this->_xml->xpath($xPath);
		if (empty($xResult)) {
			$this->_error[] = '[XML()] Le xPath sp&eacute;cifi&eacute; ne renvoi aucune info : '.$xPath;
			return FALSE;
		}
		return $xResult;
    }
	
	// Obtenir un array représentant les items spécifiés par un Xpath
	public function getItemsArray($xPath) {
		if (!($xResult = $this->getItems($xPath))) return FALSE;
		$items = array();
		foreach((array)$xResult as $item) $items[] = (array)$item;
		return $items;
    }
	
	// Récupérer le(s) message(s) d'erreur
	public function error() {
		return implode('<br />', $this->_error);
	}
}
?>