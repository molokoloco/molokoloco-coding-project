<?


if (!isset($WWW)) require 'racine.php';


// ZIP COMPRESS DIR ---------------------------------------------

function getAllDir(&$_fileNames, $baseDir, $hideDir, $unCatchDir='', $unCatchFile='') {
	set_time_limit(360);
	
	$files = getFile($baseDir, 'file');
	foreach((array)$files as $file)
		if (!in_array($file, (array)$unCatchFile)) $_fileNames[] = str_replace($hideDir, '', $baseDir.$file);
	
	$reps = getFile($baseDir, 'rep');
	foreach((array)$reps as $rep) {
		if (!in_array($rep, (array)$unCatchDir)) getAllDir($_fileNames, $baseDir.$rep.'/', $hideDir, $unCatchDir, $unCatchFile);
	}
}

/*
	makeBigZip(
		$wwwRoot.'admin/lib/',
		'adminZip',
		array('_BAK_', 'tiny_mce'),
		array('.htaccess'),
		array($wwwRoot.'admin/lib/=>'_BAK_/index.php')
	);
*/
function makeBigZip($baseDir, $nom='', $unCatchDir='', $unCatchFile='', $fileNamesAdd='') {

	if (empty($nom)) $nom = basename($baseDir).'_'.date("Y-m-d").'.zip';
	
	$_fileNames = array(); // Ref
	getAllDir($_fileNames, $baseDir, $baseDir, $unCatchDir, $unCatchFile); // Stock dir hide root-path + don't catch some rep
	require(dirname(__FILE__).'/class/class_zip/zip.lib.php');
	
	set_time_limit(3600);
	ini_set('memory_limit', 8000000);
	
	$zip =& new zipfile();
	foreach($_fileNames as $filename) {
		$content = getFileContent($baseDir.$filename);
		$zip->addfile($content, $filename);
	}
	if (!empty($fileNamesAdd) && is_array($fileNamesAdd)) {
		foreach($fileNamesAdd as $baseDir=>$filename) {
			$content = getFileContent($baseDir.$filename);
			$zip->addfile($content, $filename);
		}
	}
	$archive = $zip->file();

	header('Content-Disposition: attachment; filename="'.$nom.'"');
	header('Content-Type: application/x-zip');
	if(navDetect() == 'msie') {
	  header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
	  header('Pragma: public');
	}
	else header('Pragma: no-cache');
	$maintenant = gmdate('D, d M Y H:i:s').' GMT';
	header('Last-Modified: '.$maintenant);
	header('Expires: '.$maintenant); 
	header('Content-Length: '.strlen($archive));
	die($archive);
}

// EXEC SQL FILE REQ ----------------------------------------
/*
$fileSql = $_FILES['filesql']['tmp_name'];
$fileSqlContent = getFileContent($fileSql);
if (empty($fileSqlContent)) alert('Le fichier .sql semble vide', '', 'alert');
$totalReq = exeSql($fileSqlContent);
alert('La base de donn&eacute;e a &eacute;t&eacute; enti&egrave;rement mise &agrave; jour : '.$totalReq.' requ&ecirc;tes ont &eacute;t&eacute; ex&eacute;cut&eacute;es','','alert');
*/

function exeSql($fileSqlContent) {
	$totalReq = 0;
	$query = '';
	
	$bad = array('|INTO OUTFILE|si', '|LOAD DATA|si'); // TOdo : a little of security
	$fileSqlContent = trim(preg_replace($bad, array(''), ' '.$fileSqlContent.' '));
	
	$fileSqlContentArr = explode(chr(10), $fileSqlContent);

	$m =& new Q("START TRANSACTION");
	foreach($fileSqlContentArr as $sql_line) {
		$sql_line_e = trim($sql_line);
		if (!empty($sql_line_e) && substr($sql_line, 0, 1) != '#' && !preg_match("/^--(.*)$/", $sql_line)) {

			$query .= chr(10).$sql_line;
			if (preg_match("/;$/", $sql_line)) { //  "/;$/" | "/(.*|\`);/" | "/;[\040]*\$/" | "/;[\r\n]$/"
				$query = substr($query, 0, -1);
				$query = preg_replace('/(;[\s]+)/', ';', $query); // Enleve espace supp ajouté pour detection req SQL cf. cleanSqlStrExport($str)
				
				### db($query);
				$m =& new Q($query);
				if ($m->error) alert(getDb($m), '', 'alert');
				$query = '';
				$totalReq++;
			}
		}
	}
	$m =& new Q("COMMIT");
	
	return $totalReq;
}


function exeCsvToSql($filePath, $table, $colNames='', $firstline=false) {
	// http://dev.mysql.com/doc/refman/5.0/en/load-data.html
	$query = '
		LOAD DATA INFILE `'.$filePath.'`
		INTO TABLE `'.$table.'`
		FIELDS
		TERMINATED BY \';\'
		ENCLOSED BY \'"\'
		ESCAPED BY \'\\\'
		LINES
		STARTING BY \'\'
		TERMINATED BY \'\r\n\'
		'.($firstline ? 'IGNORE 1 LINES' : '').'
		'.(!empty($colNames) ? '('.$colNames.')' : '').'
	';
	
	$m =& new Q($query);
	if ($m->error) alert(getDb($m), '', 'alert');
}

// PARSE SQL VALUES EXPORT ---------------------------------------------
// SQL import explode dump with newline and look for semicolon for each req execution
// So we add space after semicolon in each value before export
function cleanSqlStrExport($str) {
	$str = str_replace("'", "\'", $str);
	$str = str_replace(';', '; ', $str);
	$str = str_replace(chr(13), "", $str);
	$str = str_replace(chr(10), "\\r\\n", $str);
	return $str;
}

// BACKUP SQL ---------------------------------------------
/*
	$chemin = 'tmp';
	$nom_fichier = 'bak.sql';

	backup($chemin , $nom_fichier );
*/

function sqlBackup($chemin, $nom_fichier) {

	function get_list_tables($dbase){
		$i = 0;
		$nbtab = mysql_list_tables($dbase);
		while($i < mysql_num_rows($nbtab)){
			$tb_names[$i] = mysql_tablename($nbtab, $i);
			$i++;
		}
		return $tb_names;
	}

	function get_table_structure($struct, $fd){
		$requete = mysql_query("show create table $struct");
		if ($requete == FALSE) return "la recuperation de la structure '".$struct."' a echoue.<br />";
		else{
			$structure = mysql_fetch_row($requete);
			$ligne = 0;
			return $structure[1];
		}
	}

	function get_table_data($table, $fd){
		$tableau = array();
		$j = 0;
		$resultat = mysql_query("select * from $table");
		if ($resultat == FALSE) return "La requete dans la table '".$table."' a echoue.<br />";
		else {
			$infos = "#\n# donnees de la table ".$nom_table ."\n#\n\n";
			fwrite($fd, $infos, strlen($infos));
			while($valeurs = mysql_fetch_row($resultat)) ecrire_ligne($valeurs, $table, $fd);
			return $valeurs;
		}
	}
	
	function ecrire_ligne($donnees, $nom_table, $fd){
		$debut = "INSERT INTO `".$nom_table."` VALUES('".cleanSqlStrExport($donnees[0])."'";
		fwrite($fd, $debut, strlen($debut));
		$i = 1;
		while(isset($donnees[$i])){
			$chp = ", '".cleanSqlStrExport($donnees[$i])."'";
			fwrite($fd, $chp, strlen($chp));
			$i++;
		}
		$fin = ");\n";
		fwrite($fd, $fin, strlen($fin));
	}
	
	// integre la structure dans un fichier
	function put_struct_into_file($structure, $nom_table, $fd){
		$struct = "\n#\n# Structure de la table ".$nom_table."\n#\n\n";
		$struct .= "DROP TABLE IF EXISTS `".$nom_table."`;\n";
		$struct .= $structure;
		$struct .= ";\n\n";
		$ecriture = fwrite($fd, $struct, strlen($struct));
		if ($ecriture == 0) die("erreur lors de l'ecriture de la strucuture dans le fichier de sauvegarde.");
		else return $fd;
	}

	// START BACKUP
	$emplacement = $chemin.'/'.$nom_fichier;
	
	if (!isset($chemin) || !@is_dir($chemin) || empty($nom_fichier)) die("$chemin n'est pas un repertoire ou le nom n'existe pas.");
	else {
	
		global $dbhost, $dbase, $dblogin, $dbmotdepasse;
		$connec = mysql_connect($dbhost, $dblogin, $dbmotdepasse) or die(db('Erreur mySQL : '.htmlspecialchars(mysql_error())));
		mysql_select_db($dbase, $connec);

		if (is_file($emplacement)) { // Empty it
			$fd = fopen($emplacement, 'w+b');
			rewind($fd);
			fwrite($fd, '');
			fclose($fd);
		}
		
		$fd = fopen($emplacement, 'a+b');
		$list = get_list_tables($dbase);
		$tab = 0;
		while(isset($list[$tab])){
			$structure = get_table_structure($list[$tab], $fd);
			$backup = put_struct_into_file($structure, $list[$tab], $fd);
			$query = get_table_data($list[$tab], $fd);
			$tab++;
		}
		fclose($fd);
		
		mysql_close($connec);
		return 'Sauvegarde reussie';
	}
}


function sqlDump(){

	$dateofday = date("d-m-Y", time());
	$directory = "$dateofday\\";
	$basedir = "C:\\";
	$name = "Backup_". date("d-m-Y", time()).".sql";
	
	if(file_exists("$dateofday\\")) return '';
	mkdir($directory, 0777);

	$req = mysql_query("SHOW VARIABLES LIKE 'basedir';");
	$var = mysql_fetch_array($req);

	passthru("\"".$var[1]."bin\\mysqldump.exe\" --databases $cfgDBName --opt > $basedir$name");
	copy($basedir.$name, $directory.$name);
	unlink($basedir.$name);
	
	$fp = fopen($directory.$name, "r");
	$contents = fread($fp, filesize($directory.$name));

	$zp = gzopen($directory.$name.'.gz', "w9");
	gzwrite($zp, $contents);
	gzclose($zp);
	
	fclose($fp);

	unlink($directory.$name);
}


?>