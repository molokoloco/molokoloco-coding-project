<?

////////////////////////////////////////////////////// FONCTIONS DEBUGGING //////////////////////////////////////////////



// DIRECT DEBUG ALL VARS :) ---------------------------------------------------------------------------------------------------------- //
function db($var='') { // Affichage de X arguments
	global $site_en_production;
	if ($site_en_production) return '';
	$args = func_get_args();
	if (count($args) > 1) {
		foreach ($args as $arg) db($arg);
		return;
	}
	$t_id = generateId('db_');
	echo '<textarea id="'.$t_id.'" style="width:100%;height:250px;font:11px courier;color:#FFFFFF;background:#FF66CC;text-align:left;white-space:pre;padding:4px" rows="3" cols="7">';
	if (is_bool($var)) echo ($var ? 'TRUE' : 'FALSE');
	elseif (!empty($var)) var_export($var);
	elseif ($var != '' || $var === '0' || $var === 0) echo $var;
	else echo '*** No Value ***';
	echo '</textarea><br />';
	
	js("var lignes = document.getElementById('".$t_id."').value.split('\\n');
	document.getElementById('".$t_id."').style.height = (lignes.length*18+30)+'px';");
}

// Kill It ! ------------------------------------------------------------------------------ //
function d($var='<< PHP says that you killing him softly >>') {
	die(db($var));
}

// RETURN DEBUG ALL VARS :) ---------------------------------------------------------------------------------------------------------- //
function getDb($var='', $name='') {
	ob_start();
	db($var, $name);
	$db = ob_get_contents();
	ob_end_clean();
	return $db;
}

// DEBUG MAIS EN CONSOLE JS (Pratique pour les actions dans les iframes cachées) ------------------------------------------- //
function jsDb($var) {
	$js = '';
	if (is_array($var)) foreach($var as $key=>$value) $js .= $key.' > '.$var."\n";
	else $js = $var;
	js("db('".$js."');");
}

// DEBUG AFFICHE ARGUMENTS A UN ENDROIT DONNEE ------------------------------------------------------------------------- //
// db(getVars(get_defined_vars()));
function getVars($varList, $excludeList=array()) {
	if (empty($excludeList)) $excludeList = array('GLOBALS', '_ENV', 'HTTP_ENV_VARS', '_REQUEST', '_FILES', 'HTTP_POST_FILES', '_COOKIE', 'HTTP_COOKIE_VARS', '_POST', 'HTTP_POST_VARS', '_GET', 'HTTP_GET_VARS', '_SERVER', 'HTTP_SERVER_VARS', '_SESSION', 'HTTP_SESSION_VARS', 'excludeList');
	$temp1 = array_values(array_diff(array_keys($varList), $excludeList));
	$temp2 = array();
	while (list($key, $value) = each($temp1)) {
		global $$value;
		$temp2[$value] = $$value;
	}
	return $temp2;
}

// DEBUG AFFICHE SESSION / FILES / COOKIES... ------------------------------------------- //
// Framework debug
function dbb() {
	echo '<div style="font:11px verdana;color:red"><strong>=&gt; DEBUG</strong><br />';
	echo '<pre style="padding-left:15px;font:11px verdana;color:black;" id="debug">';
	echo '+ SESSION<br />';
	print_r($_SESSION);
	echo '<br /><br />+ POST<br />';
	print_r($_POST);
	echo '<br /><br />+ GET<br />';
	print_r($_GET);
	echo '<br /><br />+ FILES<br />';
	print_r($_FILES);
	echo '<br /><br />+ COOKIES<br />';
	print_r($_COOKIE);
	echo '<br /><br />+ Fichier inclus<br />';
	$included_files = get_included_files();
	foreach ($included_files as $filename) echo $filename.'<br />';
	echo "<br /><br />+ Fonctions<br />";
	$arr = get_defined_functions();
	print_r($arr);
	echo '</pre></div>';
	exit();
}

// Admin Infos Panel ------------------------------------------- //
function MyInfo() {
	global $bgcolor1;
	if ($_GET['intitule'] != '' || $_GET['info'] != '') {
		$intitule = clean($_GET['intitule']);
		$info = clean($_GET['info']);
		$printInfo = '<table width="100%"  border="0" cellspacing="0" cellpadding="10"><tr><td><table width="100%" height="40" border="0" cellpadding="3" cellspacing="4" class="table-dialogue"><tr><td align="center" bgcolor="'.$bgcolor1.'" >';
		if ($intitule != '') { $printInfo .= aff($intitule).'<br />'; }
		if ($info == "crea") { $printInfo .= 'Cr&eacute;ation effectu&eacute;e'; }
		if ($info == "erreur") { $printInfo .= 'Erreur dans le traitement du formulaire'; }
		if ($info == "nosel") { $printInfo .= 'Vous n\'avez rien s&eacute;lectionn&eacute;'; }
		if ($info == "modif") { $printInfo .= 'Modification effectu&eacute;e'; }
		if ($info == "ajout") { $printInfo .= 'La s&eacute;lection &agrave; &eacute;t&eacute; Ajout&eacute;e'; }
		if ($info == "supp") { $printInfo .= 'Suppression effectu&eacute;e'; }
		if ($info == "ordre") { $printInfo .= 'Ordre modifi&eacute;'; }
		$printInfo .= '</td></tr></table></td></tr></table>';
		return $printInfo;
	}
}

////////////////////////////////////////////////////// FONCTIONS LOLO DEBUGGING //////////////////////////////////////////////

define('VAR_DUMP_NAME','___var_dumped');

/** my_var_dump() is a substitute for the var_dump PHP fonction.
 * It uses a marking algorithm to know which references it has allready
 * visited. It creates unique ids so that you may understand where thoses
 * allready visited references were displayed.
 *
 * @param $var the var to dump
 * @param $unsetAll true if you wish to unset all the labeled ids
 * @param $links true if you wish to display links between referees and
 *        references
 */

function my_var_dump(&$var,$unsetAll = true,$links = true) {
	_my_var_dump_aux($var,$links);
	if ($unsetAll) _unset_all_var_dump($var);
	print('<br/>');
}

function _generateId($isArray) {
	static $idArrays = 0;
	static $idObjects = 0;
	if ($isArray) return 'ARR_'.$idArrays++;
	else return 'OBJ_'.$idObjects++;
}

define('GLOBAL_STYLE','style="font-family: tahoma; font-size:xx-small;"');
define('NULL_STYLE','style="font-family: tahoma; font-weight:bolder; font-size:xx-small; color:red;"');

function _my_var_dump_aux(&$var,$links = true) {
	if ($var === NULL)
		print('<font '.NULL_STYLE.'>NULL</font>');
	elseif ((is_array($var) && isset($var[VAR_DUMP_NAME])) || (is_object($var) && isset($var->___var_dumped))) {
		$id = (is_array($var) ? $var[VAR_DUMP_NAME] : $var->___var_dumped);
		if ($links) print('<b><a href="#'.$id.'">Ref '.$id.'</a></b>');
		else print('<b>Ref '.$id.'</b>');
	}
	else {
		if (is_array($var)) {
			$id = _generateId(true);
			$size = sizeof($var);
			print '<table '.GLOBAL_STYLE.' width="100%" border=1 cellspacing=0 cellpadding=1 bgcolor="#D0F0D0">';
			print '<tr><td rowspan="'.sizeof($var).'" align="center" valign="top"><b><a name="'.$id.'">'.$id.'</a></b><br />[<b>'.$size.'</b>]</td>';
			
			$var[VAR_DUMP_NAME] = $id;
			
			reset($var);
			$index = 1;
			foreach ($var as $key => $value) {
				if (is_int($key) || $key != VAR_DUMP_NAME) {
					print('<td align="center" valign="top"><i>'.$key.'</i></td><td>');
					/*if ($var[$key] == NULL) print('<font '.NULL_STYLE.'>NULL</font>');
					else*/ _my_var_dump_aux($var[$key],$links);
					print '</td></tr>';
					if ($index < $size) print('<tr>');
				}
				$index++;
			}
			print( '</table>' );
		}
		elseif (is_object($var)) {
			$id = _generateId(false);
			$size = sizeof($var);
			$index = 1;
			$var->___var_dumped = $id;
			print '<table '.GLOBAL_STYLE.' width="100%" border=1 cellspacing=0 cellpadding=1 bgcolor="#FFDD77">';
			print('<tr><td rowspan="'.sizeof(get_object_vars($var)).'" align="center" valign="top"><b><a name="'.$id.'">'.$id.'</a></b><br />:'.get_class($var).'</td>');
			foreach ($var as $key => $value) {
				if ($key != VAR_DUMP_NAME) {
					print('<td align="center" valign="top"><i>'.$key.'</i></td><td>');
					/*if (!isset($var->$key))
						print('<font '.NULL_STYLE.'>NULL</font>');
					else*/
						_my_var_dump_aux($var->$key,$links);
					print '</td></tr>';
					if ($index < $size) print('<tr>');
				}
				$index++;
			}
			print( '</table>' );
		}
		else {
			 print (is_string($var)? 'string['.strlen($var).'] <b>'.$var :
				 (is_int($var)? 'int <b>'.$var :
				 (is_float($var)? 'float <b>'.$var :
				 (is_bool($var)? 'bool <b>'.($var?'true':'false') : '' ) ) ) ).'</b>';
		}
	}
}

function _unset_all_var_dump(&$var) {
	if (is_array($var) && isset($var[VAR_DUMP_NAME])) {
		unset($var[VAR_DUMP_NAME]);
		reset($var);
		foreach ($var as $key => $value) {
			if ((is_int($key) || $key != VAR_DUMP_NAME)
			&& $var[$key] != NULL)
				_unset_all_var_dump($var[$key]);
		}
	}
	elseif (is_object($var) && isset($var->___var_dumped)) {
		unset($var->___var_dumped);
		foreach ($var as $key => $value) {
			if ($key != VAR_DUMP_NAME && isset($var->$key))
				_unset_all_var_dump($var->$key);
		}
	}
}


// MY_VAR_DUMP: Raccourci + pre ;-)
function VD(&$var,$ob=false ) {
	$str = "";
	if( $ob ) ob_start();
	echo "<PRE>";
	my_var_dump( $var );
	echo "</PRE>";
	if( $ob ) {
		$str = ob_get_contents();
		ob_end_clean();
	}
	return $str;
}

// MY_VAR_DUMP: Raccourci + pre + die ;-) 
function VDD(&$var) {
	$str = "";
	echo "<PRE>";
	my_var_dump( $var );
	echo "</PRE>";
	die( "End Var Dump And Die" );
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



?>