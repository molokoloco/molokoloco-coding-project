<?php

if ( !defined('MLKLC') ) die('Lucky Duck');

// ----------------------------------------------- CLASS SQL  ----------------------------------------------- //
/*
	EXEMPLE :
	
	// SELECT
		$Q =& new Q("SELECT * FROM mod_actualites WHERE cat_id='$cat_id' AND actif='1' ORDER BY ordre DESC LIMIT 10");
		foreach ($Q->V as $V) echo $V['titre'].'<br />';
	
	// SELECT II
		$Q =& new Q();
		$Q1 = $Q->QUERY("SELECT * FROM mod_actualites");
		foreach ($Q1->V as $V) echo $V['titre'].'<br />';
		$Q2 = $Q->QUERY("SELECT * FROM mod_actualites");
		//...
	
	// SELECT III
		foreach (q("SELECT * FROM actualites ORDER BY titre ASC") as $V) echo '<option value="'.$V['id'].'">'.html(aff($V['titre'])).'<option>';
	
	// UPDATE // INSERT // DELETE
		$A = new Q();
		$A->update('mod_membres_blogs', array(
			'titre'=> $titre,
			'message'=> $message,
		), " id='$id' AND membre_id='{$_SESSION[SITE_CONFIG]['MEMBRE']['id']}' LIMIT 1 ");
	
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
	
*/


// ShorCut
function q($query) {
	$Q =& new Q($query);
	return ( count($Q->V) > 1 ? $Q->V : $Q->V[0] );
}

// Stock connexion between class instanciation
$_cConnexion = 0;
$_dbDataBase = 0;

class Q { // SQL QUERY MANAGER ;)

	public $_c = 0;
	public $_db = 0;
	public $query;
	public $id;
	public $affected;
	public $V = array();

	function __construct($query='') {
		global $debug;
		$this->debug = $debug;
		
		$this->id = 0;
		$this->affected = 0;
		$this->V = array();
		
		if (!$this->_c || !$this->_db) $this->C();
		$this->QUERY($query);
	}
		
	// PHP4
	function Q($query='') {
		$this->__construct($query);
	}
	
	// CONNEXION
	private function C() {
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
	
	// OPEN SQL QUERY MAKER
	public function QUERY($query) {
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
    	$this->QUERY($query);
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
    	$this->QUERY($query);
    }
	
	// DELETE
    public function delete($table, $where='') {
    	$query = "DELETE FROM `$table`";
    	if (!empty($where)) $query .= ' WHERE '.$where;
    	$this->QUERY($query);
    }

	// CREATE XML FROM REQUETE
	public function getXml($query, $tags=array(), $xmlPath='') {
		$this->query = $query;
		if (empty($tags[0])) $tags[0] = 'root';
		if (empty($tags[1])) $tags[1] = 'row';
		$xml = '<?xml version="1.0" encoding="UTF-8"?>'.chr(13).chr(10);
		$req = mysql_query($this->query, $this->_c) or die(db($this, '[Q()] Erreur mySQL : '.htmlspecialchars(mysql_error($this->_c))));
		if (@mysql_num_rows($req) && @mysql_num_rows($req) > 0) {
			$xml.= '<'.aff($tags[0]).'>'.chr(13).chr(10);
			while ($res = @mysql_fetch_assoc($req)) {
				$xml .= chr(9).'<'.aff($tags[1]).'>'.chr(13).chr(10);
				foreach ($res as $titre=>$value) $xml .= chr(9).chr(9).'<'.$titre.'>'.cleanXml($value, 1). '</'.$titre.'>'.chr(13).chr(10);
				$xml.= chr(9).'</'.aff($tags[1]).'>'.chr(13).chr(10);
				$i++;
			}
			$xml.= '</'.$tags[0].'>';
		}
		@mysql_free_result($req);
		if ($this->debug == '1') db($xml);
		if (!empty($xmlPath)) return writeFile($xmlPath, $xml);
		else return $xml;
    }
}


// - - - - - - - - - - - - - - - - - - - ALL ABOVE DEPRECATED - - - - - - - - - - - - - - - - - - - //


/*
// CREATE  ////////////////////////////////////////
$C = new SQL($R3['table']);
$C->debug = '1';
$C->createSql($R3_data);

// READ  ////////////////////////////////////////
$R =& new SQL($R3['table']);
$R->LireSql(array('titre')," id='$id' LIMIT 1 ");
if ($R->nb > 0) echo $R->V['0']['titre']);

// INSERT  ////////////////////////////////////////
$champs = array(
	0=>array('actif','ordre'),
	1=>array($galerie_actif,$galerie_ordre)
);
$G =& new SQL('galeries');
$G->insertSql($champs,'1');
$galerie_id = $G->id;

// UPDATE ////////////////////////////////////////
$champs = array(
	array('actif',$galerie_actif),
	array('titre',$galerie_titre),
);
$G =& new SQL('galeries');
$G->updateSql($champs," id='$galerie_id' AND clients_id='$clients_id' LIMIT 1 ");
	

*/

// - - - - - - - - - - - - - - - - - - - BIG CLASS - - - - - - - - - - - - - - - - - - - //
class SQL {
	var $table; // Array table 
	var $data; // Array champs
	var $debug; // Debug
	var $db; // Database
	var $nb; // Nb resultats
	var $V; // Res. row
	var $createRow;
	var $result;
	var $error;
	// - - - - - - - - - - - - - - - - - - - INIT - - - - - - - - - - - - - - - - - - - //
	function SQL($table) {
		global $dbase,$debug;
		if (!is_array($table)) $table = array(table=>$table);
		$this->table = $table;
		$this->debug = $debug;
		$this->db = $dbase;
	}
	// - - - - - - - - - - - - - - - - - - - GetDatabases() - - - - - - - - - - - - - - - - - - - //
	function GetDatabases() {
        if ($this->result = mysql_list_dbs()){
            $i=0;
            while($i < mysql_num_rows($this->result)){
                $db_names[$i] = mysql_tablename($this->result,$i);
                $i++;
            }
            return($db_names);
        } else  return db("Unable to find a database on server: ".$this->host);
    }
    // - - - - - - - - - - - - - - - - - - - CreateDB($database) - - - - - - - - - - - - - - - - - - - //
    /*function CreateDB($database) {
        if ($this->result = mysql_create_db($database)) return true;
        else return db("Unable to create database: $database");
    }*/
	// - - - - - - - - - - - - - - - - - - - DropDB($database) - - - - - - - - - - - - - - - - - - - //
   /* function DropDB($database){
        if($this->result = mysql_drop_db($database)) return true;
        else  return db("Unable to drop database: $database");
    }*/
	// - - - - - - - - - - - - - - - - - - - GetTableList() - - - - - - - - - - - - - - - - - - - //
	function GetTableList() {
		$initConnexion =& new Q();
        if ($this->result = mysql_list_tables($this->db,$initConnexion->_c)){
            $i=0;
            while($i < mysql_num_rows($this->result)){
                $tb_names[$i] = mysql_tablename($this->result,$i);
                $i++;
            }
            return($tb_names);
        } else return db("Unable to find any tables in database: $this->db");
    }
	// - - - - - - - - - - - - - - - - - - - GetFieldList($tbl_name) - - - - - - - - - - - - - - - - - - - //
    function GetFieldList($tbl_name) {
		$initConnexion =& new Q();
        if ($this->result = mysql_list_fields($this->db,$tbl_name, $initConnexion->_c)){
            $i=0;
            while($i < mysql_num_fields($this->result)){
                $fd_names[$i] = mysql_field_name($this->result,$i);
                $i++;
            }
            return($fd_names);
        }
		else return db("Unable to find any field list in table: $tbl_name");
    }
	// - - - - - - - - - - - - - - - - - - - CREATE - - - - - - - - - - - - - - - - - - - //
	function createSql($data,$drop=0) {
		$initConnexion =& new Q();
		global $langues;
		global $wwwRoot,$grand,$medium,$mini;
		for ($i=1; $i < count($data); $i++) { // Each champs // "0"=>"id"
			if ($data[$i]['htmDefaut']  != 'relation') {
				if ($data[$i]['bilingue'] == 1) {
					foreach ($langues as $langue) { 
						$createRow .= " {$data[$i]['name']}_{$langue} {$data[$i]['sqlType']}";
						if (!empty($data[$i]['nbChar'])) { $createRow .= "({$data[$i]['nbChar']})"; }
						if (!empty($data[$i]['sqlDefaut']) && empty($data[$i]['sqlDefaut'])) { $createRow .= " NOT NULL , ".chr(13).chr(10); }
						else if (!empty($data[$i]['sqlDefaut'])) { $createRow .= " NOT NULL default '".$data[$i]['sqlDefaut']."', ".chr(13).chr(10); }
						else { $createRow .= " default NULL, ".chr(13).chr(10); }
					}
				}
				else {
					$createRow .= " {$data[$i]['name']} {$data[$i]['sqlType']}";
					if (!empty($data[$i]['nbChar'])) { $createRow .= "({$data[$i]['nbChar']})"; }
					if (!empty($data[$i]['sqlDefaut']) && empty($data[$i]['sqlDefaut'])) { $createRow .= " NOT NULL , ".chr(13).chr(10); }
					else if (!empty($data[$i]['sqlDefaut'])) { $createRow .= " NOT NULL default '".$data[$i]['sqlDefaut']."', ".chr(13).chr(10); }
					else { $createRow .= " default NULL, ".chr(13).chr(10); }
				}
			}
		}
		// Delete ?
		if ($drop == '1') { 
			$req = "DROP TABLE {$this->table['table']} ";
			if ($this->debug == '1') db($req);
			mysql_query($req,$initConnexion->_c);
		}
		// Create
		$create = "CREATE TABLE {$this->table['table']} (\n";
		$create .= "\t{$data['0']['name']} int(8) NOT NULL auto_increment,\n";
		$create .= "\t$createRow\n";
		$create .= "\tPRIMARY KEY ({$data['0']['name']})\n";
		$create .= ") TYPE=MyISAM\n";
		
		if ($this->debug == '1') db($create);
		$create = mysql_query($create,$initConnexion->_c) or die(mysql_error($initConnexion->_c));
		
		// Make Directory
		if (!empty($this->table['rep']) && $this->table['rep'] != '') {
			if (!is_dir($wwwRoot.$this->table['rep'])) { 
				//mkdir($wwwRoot.$this->table['rep'], 0755);
				//chmod($wwwRoot.$this->table['rep'], 0777);
				createRep($wwwRoot.$this->table['rep'].'/', 0777);
			}
			foreach ($this->table['sizeimg'] as $subrep=>$size) {
				if ($subrep != 'tgrand' && !is_dir($wwwRoot.$this->table['rep'].$subrep)) createRep($wwwRoot.$this->table['rep'].$subrep.'/', 0777);
			}
		}
		if ($create) db('YO :) Table "'.$this->table['table'].'" : Cree avec succes');
		else db('GetDown :( Table "'.$this->table['table'].'" : Echec de la creation');
	}
	
	// - - - - - - - - - - - - - - - - - - - AJOUTER CHAMPS - - - - - - - - - - - - - - - - - - - //
	function addSql($data) {
		/* $C = new SQL($R1); $C->addSql($R1_data); */
		$initConnexion =& new Q();
		global $langues;
		global $root,$medium,$mini;
		
		$V = new SQL(NULL);
		$fieldList = $V->GetFieldList($this->table['table']); // Table Field Liste

		for ($i=0; $i<count($data); $i++) { // Each champs
			if ($data[$i]['input'] == 'multiselect') continue;
			
			if ($data[$i]['bilingue'] == 1) {
				foreach ($langues as $langue) {
					if (!in_array($data[$i]['name'].'_'.$langue,$fieldList)) {
						$createRow = " {$data[$i]['name']}_{$langue} {$data[$i]['sqlType']}";
						if (!empty($data[$i]['nbChar'])) { $createRow .= "({$data[$i]['nbChar']})"; }
						if (!empty($data[$i]['sqlDefaut'])) { $createRow .= " NOT NULL default '".$data[$i]['sqlDefaut']."' "; }
						else { $createRow .= " default NULL "; }
						if ($this->debug == '1') db("ALTER TABLE ".$this->table['table']." ADD $createRow AFTER $nameEx ;");
						$alter = mysql_query("ALTER TABLE ".$this->table['table']." ADD $createRow AFTER $nameEx ;",$initConnexion->_c) or die(mysql_error($initConnexion->_c));
						if ($alter) db('Add : '.$data[$i]['name']);
						else db('GetDown :(');
						$alter = NULL;
					}
					$nameEx = $data[$i]['name'].'_'.$langue;
				}
			}
			else {
				if (!in_array($data[$i]['name'],$fieldList)) {
					$createRow = " {$data[$i]['name']} {$data[$i]['sqlType']}";
					if (!empty($data[$i]['nbChar'])) { $createRow .= "({$data[$i]['nbChar']})"; }
					if (!empty($data[$i]['sqlDefaut'])) { $createRow .= " NOT NULL default '".$data[$i]['sqlDefaut']."' "; }
					else { $createRow .= " default NULL "; }
					if ($this->debug == '1') db("ALTER TABLE ".$this->table['table']." ADD $createRow AFTER $nameEx ;");
					$alter = mysql_query("ALTER TABLE ".$this->table['table']." ADD $createRow AFTER $nameEx ;",$initConnexion->_c) or die(mysql_error($initConnexion->_c));
					if ($alter) db('Add : '.$data[$i]['name']);
					else db('GetDown :(');
					$alter = NULL;
				}
				$nameEx = $data[$i]['name'];
			}
		}
		if (!empty($this->table['rep']) && $this->table['rep'] != '') {
			if (!is_dir($root.$this->table['rep'])) { 
				mkdir($root.$this->table['rep'], 0755); chmod($root.$this->table['rep'], 0777);
			}
			foreach ($this->table['sizeimg'] as $subrep => $size) {
				if (!is_dir($root.$this->table['rep'].$subrep)) mkdir($root.$this->table['rep'].$subrep, 0755); chmod($root.$this->table['rep'].$subrep, 0777);
			}
		}
	}
	// - - - - - - - - - - - - - - - - - - - READ - - - - - - - - - - - - - - - - - - - //
	function lireSql($champs, $where, $selectOpt='') {
		$initConnexion =& new Q();
		if ($champs == '' && $where == '') return false;
		$r = $i = 0;
		if ($champs[0] == '*') { // Get All champs
			$champs = NULL;
			$req = mysql_query("SELECT * FROM {$this->table['table']} LIMIT 1",$initConnexion->_c) or die(mysql_error($initConnexion->_c));
			$nbchamps = mysql_num_fields($req);
			while($i<$nbchamps)  {
				$champs[] = mysql_field_name($req,$i); 
				$i++;
			}
			$champ = '*';
		}
		else { // Get Some champs
			$champ = implode(',',$champs);
		}
		if ($where != '') { $where = ' WHERE '.$where.' '; }
		$i = 0;
		if ($this->debug == '1') { db("SELECT $selectOpt $champ FROM {$this->table['table']} $where "); }
		$req = mysql_query("SELECT $selectOpt $champ FROM {$this->table['table']} $where ", $initConnexion->_c) or die(mysql_error($initConnexion->_c));
		if (mysql_num_rows($req) == 0) { $this->nb = 0; }
		else {
			$this->nb = mysql_num_rows($req);
			while ($Res = mysql_fetch_array($req)) { // Each Result
				$i=0;
				while ($i < count($champs)) { // Each champs
					$this->V[$r][$champs[$i]] = $Res[$champs[$i]];
					$i++;
				}
				$r++;
			}
		}
	}
	// - - - - - - - - - - - - - - - - - - - CUSTOM REQUETE - - - - - - - - - - - - - - - - - - - //
	function customSql($requete) { 
		$initConnexion =& new Q();
		if ($this->debug == '1') db($requete);
		$req = mysql_query(" $requete ",$initConnexion->_c) or die(mysql_error($initConnexion->_c));
		if (!@mysql_num_rows($req) || mysql_num_rows($req) == 0) { // mySQL ne renvois pas de resultats...
			$this->nb = 0;
		}
		else {
			$this->nb = mysql_num_rows($req);
			$r = 0;
			while ($res = mysql_fetch_array($req)) { // Each Result
				foreach ($res as $titre=>$value) { // Each Result
					if (!is_numeric($titre)) $this->V[$r][$titre] = $value;
				}
				$r++;
			}
		}
	}
	// - - - - - - - - - - - - - - - - - - - INSERT - - - - - - - - - - - - - - - - - - - //
	function insertSql($champs,$notAll=0) { // champs = [0=>'15/02/78',1=>'john',...] >> NotAll == 1 : only some...
		$initConnexion =& new Q();
		if ($notAll == 0) {
			if (is_array($champs)) $champ = implode("','",$champs);
			if ($this->debug == '1')  echo ("INSERT INTO {$this->table['table']} VALUES ('','$champ')");
			$req = mysql_query("INSERT INTO {$this->table['table']} VALUES ('','$champ')", $initConnexion->_c) or die(mysql_error($initConnexion->_c));
		}
		else { 
			if (!is_array($champs)) return false;
			$V = new SQL(NULL);
			$fieldList = $V->GetFieldList($this->table['table']); // Table Field Liste // get unKnow field !!!
			
			$champVal = array();
			for ($i=0; $i<count($fieldList); $i++) $champVal[] = '';
			for ($i=0; $i<count($champs[0]); $i++) { // $champs[0] champName to insert <> $champs[1] champVal to insert
				$key = array_search($champs[0][$i], $fieldList);
				if ($key === false) {
					if ($this->debug == '1') db($fieldList,'$fieldList');
					die('error : field ['.$champs[0][$i].'] no exist');
				}
				$champVal[$key] = $champs[1][$i];
			}
			$champValString = implode("','",$champVal);
			if ($this->debug == '1') db("INSERT INTO {$this->table['table']} VALUES ('$champValString') ");
			$req = mysql_query("INSERT INTO {$this->table['table']} VALUES ('$champValString') ", $initConnexion->_c) or die(mysql_error($initConnexion->_c));
		}
		$this->id = mysql_insert_id();
	}
	// - - - - - - - - - - - - - - - - - - - UPDATE - - - - - - - - - - - - - - - - - - - // TO MAKE BETTER CF. INSERT WITH UNKOWN FIELDS
	function updateSql($champs,$where) { // champs = [0=>array(0=>'date',1=>'15/02/78'),1=>array(0=>'nom',1=>'john'),...]
		$initConnexion =& new Q();
		if (!is_array($champs)) { return false; }
		else {
			for ($i=0; $i < count($champs); $i++) { 
				$champ .= $champs[$i][0]."='".addslashes(stripslashes($champs[$i][1]))."',";
			}
			$champ = substr($champ,0,-1);
			if ($where != '') { $where = " WHERE $where "; }
			if ($this->debug == '1') db(" UPDATE {$this->table['table']} SET $champ $where ");
			$req = mysql_query("UPDATE {$this->table['table']} SET $champ $where ", $initConnexion->_c) or die(mysql_error($initConnexion->_c));			
		}
	}
	// - - - - - - - - - - - - - - - - - - - DELETE - - - - - - - - - - - - - - - - - - - //
	function delSql($where) {
		$initConnexion =& new Q();
		if ($where == '') return NULL;
		if ($this->debug == '1') db("DELETE FROM {$this->table['table']} WHERE $where ");
		else mysql_query("DELETE FROM {$this->table['table']} WHERE $where ",$initConnexion->_c) or die(mysql_error($initConnexion->_c));
	}
}
?>