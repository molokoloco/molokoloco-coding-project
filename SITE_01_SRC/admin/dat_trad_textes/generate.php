<?
global $langues,$wwwRoot;
foreach($langues as $langue) {
	$PHP = '';
	$F =& new Q(" SELECT * FROM dat_trad_textes ORDER BY titre ASC ");
	for ($i=0; $i<count($F->V); $i++) {
		$const = '_'.strtoupper(str_replace('-','_',cleanName($F->V[$i]['titre']))).'_';
		$PHP .= 'define("'.$const.'", "'.($F->V[$i]['trad_'.$langue] != '' ? str_replace(chr(13).chr(10),' ',aff($F->V[$i]['trad_'.$langue])) : '<span style=color:red>{'.substr($const, 1, -1).'}</span>').'");'.chr(10);
	}
	writeFile($wwwRoot.'admin/lib/langues/lang_textes_'.$langue.'.php','<?'.chr(10).$PHP.chr(10).'?>'); 
}
?>