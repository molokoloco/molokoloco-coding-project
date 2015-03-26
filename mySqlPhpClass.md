# Building SQL query with PHP #

exemple.php :

```
// EXEMPLES

	// SELECT I
		$Q =& new Q("SELECT titre FROM mod_actualites WHERE cat_id='$cat_id' AND actif='1' ORDER BY ordre DESC LIMIT 10");
		foreach ($Q->V as $V) echo $V['titre'].'<br />';
	
	// SELECT II
		$Q =& new Q();
		$Q1 = $Q->QUERY("SELECT * FROM mod_actualites");
		foreach ($Q1->V as $V) echo $V['titre'].'<br />';
		$Q2 = $Q->QUERY("SELECT * FROM mod_actualites");
		//...
	
	// SELECT III
		foreach ((array)q("SELECT * FROM actualites ORDER BY titre ASC") as $V) echo '<option value="'.$V['id'].'">'.html(aff($V['titre'])).'<option>';
	
	// SELECT IV
		$R = q('SELECT temp FROM device WHERE id='10' LIMIT 1')
		echo $R['temp'];
	
	// INSERT
		$A = new Q();
		$A->insert('mod_membres_blogs', array(
			'titre'=> $titre,
			'message'=> $message,
		));
		echo $A->id;
	
	// UPDATE
		$A = new Q();
		$A->update('mod_membres_blogs', array(
			'titre'=> $titre,
			'message'=> $message,
		), " id='$id' LIMIT 1 ");
		echo $A->affected;
	
	// DELETE
		$A = new Q();
		$A->update('mod_membres_blogs', " id='$id' LIMIT 1 ");
		echo $A->affected;
	
	// CREATE XML
		$X = new Q();
		$XML = $X->getXml("
			SELECT id,titre,texte,miniature,
			CONCAT('".$WWW."galeries.php?galerie_id=',id) AS url
			FROM galeries
			WHERE clients_id='$clients_id' AND actif='1'
			ORDER BY ordre ASC, id DESC
		", array('galeries','galerie'), './rep/file.xml');
		db($XML);
```

class\_sql.php

```
// ----------------------------------------------- CLASS SQL  ----------------------------------------------- //

// ShorCut
function q($query) {
	$Q =& new Q($query);
	return ( count($Q->V) > 1 ? $Q->V : $Q->V[0] );
}

// Stock connexion between class instanciation
$_cConnexion = 0;
$_dbDataBase = 0;

class Q { // SQL query MANAGER ;)

	public $_c = 0;
	public $_db = 0;
	public $query;
	public $id;
	public $affected;
	public $V;

	function __construct($query='') {
		global $debug;
		$this->debug = $debug;
		if (!$this->_c || !$this->_db) $this->c();
		$this->query($query);
	}
		
	// PHP4
	function Q($query='') {
		$this->__construct($query);
	}
	
	// EMPTY
	private function clean() {
		$this->id = 0;
		$this->affected = 0;
		$this->V = array();
		$this->query = '';
	}
	
	// CONNEXION
	private function c() {
		global $_cConnexion, $_dbDataBase;
		if ($_cConnexion && $_dbDataBase) {
			 $this->_c = $_cConnexion;
			 $this->_db = $_dbDataBase;
		}
		else {
			global $dbhost, $dbase, $dblogin, $dbmotdepasse;
			if (empty($dbhost) || empty($dbase) || empty($dblogin)) die(db('[Q()] Desole, il manque un parametre ['.$dbhost.', '.$dbase.', '.$dblogin.']'));
			$this->_c = mysql_connect($dbhost, $dblogin, $dbmotdepasse) or die(db('[Q()] Desole, connexion impossible sur le host ['.$dbhost.']'));
			$this->_db = mysql_select_db($dbase, $this->_c) or die(db('[Q()] Desole, connexion impossible a la base ['.$dbhost.' : '.$dbase.'] '.htmlspecialchars(mysql_error($this->_c))));
			$_cConnexion = $this->_c;
			$_dbDataBase = $this->_db;
		}
		if (!is_resource($this->_c) || !$this->_db)  die(db('[Q()] Pb inconnu ['.$dbase.' : '.$dbhost.'] '.htmlspecialchars(mysql_error($this->_c))));
	}
	
	// OPEN SQL query MAKER
	public function query($query) {
		$this->clean();
		if (empty($query)) return;
		$this->query = trim($query);
		$result = mysql_query($this->query, $this->_c) or die(db($this, '[Q()] Erreur mySQL : '.htmlspecialchars(mysql_error($this->_c))));
		if (is_resource($result) && preg_match('/^SELECT /', $this->query)) {
			while ($arrRow = @mysql_fetch_array($result, MYSQL_ASSOC)) $this->V[] = $arrRow;
		}
		else {
			$this->id = @mysql_insert_id();
			$this->affected = @mysql_affected_rows();
		}
		if ($result) @mysql_free_result($result);
		if ($this->debug) db($this);
	}

	// UPDATE
	public function update($table, $fields, $where='') {
    	$query = 'UPDATE `'.$table.'` SET ';
    	$i=0;
		foreach ((array)$fields as $name=>$value) {
    		if ($i == 0) $i = 1;
			else $query .= ', ';
			$query .= '`'.$name.'`=';
			if (empty($value)) $query .= "''";
    		else if ($this->NoClean || preg_match('/^(NOW|CURDATE|CURTIME|UNIX_TIMESTAMP|RAND|USER|LAST_INSERT_ID)/', $value)) $query .= $value;
    		else $query .= "'".clean($value)."'";
    	}
    	if (!empty($where)) $query .= ' WHERE '.$where;
    	$this->query($query);
    }
	
	// INSERT
    public function insert($table, $fields) {
    	$query = 'INSERT INTO `'.$table.'` (';
		$query .= '`'.implode('`, `', array_keys($fields)).'`';
    	$query .= ') VALUES (';
    	$i=0;
    	foreach ((array)$fields as $value) {
    		if ($i == 0) $i = 1;
			else $query .= ', ';
    		if ($this->NoClean || preg_match('/^(NOW|CURDATE|CURTIME|UNIX_TIMESTAMP|RAND|USER|LAST_INSERT_ID)/', $value)) $query .= $value;
    		else $query .= "'".clean($value)."'";
    	}
    	$query .= ')';
    	$this->query($query);
    }
	
	// DELETE
    public function delete($table, $where='') {
    	$query = "DELETE FROM `$table`";
    	if (!empty($where)) $query .= ' WHERE '.$where;
    	$this->query($query);
    }

	// CREATE XML FROM REQUETE
	public function getXml($query, $tags=array(), $xmlPath='') {
		$this->clean();
		if (empty($query)) return;
		$this->query = $query;
		if (empty($tags[0])) $tags[0] = 'root';
		if (empty($tags[1])) $tags[1] = 'row';
		$xml = '<?xml version="1.0" encoding="UTF-8"?>'.chr(13).chr(10);
		$result = mysql_query($this->query, $this->_c) or die(db($this, '[Q()] Erreur mySQL : '.htmlspecialchars(mysql_error($this->_c))));
		if (@mysql_num_rows($result) && @mysql_num_rows($result) > 0) {
			$xml.= '<'.aff($tags[0]).'>'.chr(13).chr(10);
			while ($res = @mysql_fetch_assoc($result)) {
				$xml .= chr(9).'<'.aff($tags[1]).'>'.chr(13).chr(10);
				foreach ($res as $titre=>$value) $xml .= chr(9).chr(9).'<'.$titre.'>'.cleanXml($value, 1). '</'.$titre.'>'.chr(13).chr(10);
				$xml.= chr(9).'</'.aff($tags[1]).'>'.chr(13).chr(10);
			}
			$xml.= '</'.$tags[0].'>';
		}
		if ($result) @mysql_free_result($result);
		if ($this->debug) db($this);
		if (!empty($xmlPath)) return writeFile($xmlPath, $xml);
		else return $xml;
    }
}
```


http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC/admin/lib/class/class_sql.php