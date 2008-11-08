<?
/**
 * <molokoloco:2008/>
 *
 * Exemple :
 *
 * $R = new HTTP($path);
 * if (!$R->isUrl()) db($R->_error());
 * if (!$R->isFile()) db($R->_error());
 *
 * $R = new HTTP($path);
 * if ($R->getInfo()) db('IS URL', $R);
 * else db('IS NOT URL', $R);
 *
 */

class HTTP {
	
	private $_path = '';
	private $_error = array();
	
	// PHP 5
	function __construct($path) {
		$this->_path = $path;
	}
	
	// PHP 4
	function HTTP($path) {
		$this->__construct($path);
	}
	
	// Valider une URL complète avec réponse du serveur
	public function isUrl() {
		if (empty($this->_path)) {
			$this->_error[] = '[HTTP()] Le chemin sp&eacute;cifi&eacute; est vide';
			return FALSE;
		}
		elseif (substr($this->_path, 0, 7) != 'http://') {
			$this->_error[] = '[HTTP()] Adresse http:// obligatoire : '.$this->_path;
			return FALSE;
		}
		if (strpos($this->_path, '?') > 0) list($path, $query) = explode('?', $this->_path);
		else $path = $this->_path;
		eregi("^([a-z]*)(://([^/]+))?/?(.*)$", $path, $regs);
		$host = $regs[3];
		if (!$host) {
			$this->_error[] = '[HTTP()] Host inconnu : '.$this->_path;
			return FALSE;
		}
		list($host, $port) = explode(':', $host);
		if (!$port) $port = '80';
		@ini_set('default_socket_timeout', 3);
		$fp = @fsockopen($host, $port);
		if (!$fp) {
			$this->_error[] = '[HTTP()] Host inatteignable : '.$this->_path;
			return FALSE;
		}
		else @fclose($fp);
		return TRUE;
	}
	
	// Vérifier en lien relatif, un fichier ou un répertoire existant
	public function isFile() {
		if (empty($this->_path)) {
			$this->_error[] = '[HTTP()] Le chemin sp&eacute;cifi&eacute; est vide';
			return FALSE;
		}
		else if (!@is_file($this->_path) && !@is_dir($this->_path)) { // LOCAL ? Becareful for URL rewriting.. no scan for html ? strpos($this->_path,'.html') === FALSE && 
			$this->_error[] = '[HTTP()] Répertoire ou ficher local introuvable : '.$this->_path;
			return FALSE;
		}
		return TRUE;
	}
	
	// Obtenir toutes les infos sur un path
	public function getInfo() {
		if (!$this->isUrl() && !$this->isFile()) return FALSE;
		$pathinfo = @pathinfo($this->_path);
		$this->rep = $pathinfo['dirname'].'/';
		$this->ext = $pathinfo['extension'];
		$this->name = $pathinfo['basename'];
		$this->cname = affCleanName($pathinfo['basename'], 0); // Titre "Clean" sans ext. par defaut
		$this->size = @filesize($this->_path); // Erreur si http://
		if ($this->size) $this->size = cleanKo($this->size);
		return TRUE;
	}
	
	// Obtenir le contenu d'une fichier
	public function getContent() {
		if (!$this->isUrl() && !$this->isFile()) return FALSE;
		return @file_get_contents($this->_path);
	}
	
	// Récupérer le(s) message(s) d'erreur
	public function error() {
		return implode('<br />', $this->_error);
	}
	
}
?>