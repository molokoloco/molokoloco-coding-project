<?
/* //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////// PHP FONCTIONS INDEX /////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// */
if ( !defined('MLKLC') ) die('Lucky Duck');



////////////////////////////////////////////////////// AFFICHAGE ET PARSING /////////////////////////////////////////////////////////////////

// MAGIC QUOTES // (if magic_quotes_gpc is enabled : enleve les slash) ---------------------------------------------
if (get_magic_quotes_gpc()) {
	function stripslashes_array($array) {
		return is_array($array) ? array_map('stripslashes_array',$array) : stripslashes($array);
	}
	if ($_GET) $_GET = stripslashes_array($_GET);
	if ($_POST) $_POST = stripslashes_array($_POST);
	if ($_COOKIE) $_COOKIE = stripslashes_array($_COOKIE);
}

// NO HACK ---------------------------------------------
function sanitize($string) {
	if ($string === 0 || $string === '0') return 0;
	elseif (is_numeric($string)) return $string;
	elseif (empty($string)) return $string;
	$bad = array('|</?\s*SCRIPT.*?>|si', '|</?\s*OBJECT.*?>|si', '|</?\s*META.*?>|si', '|</?\s*APPLET.*?>|si', '|</?\s*LINK.*?>|si', '|</?\s*FRAME.*?>|si', '|</?\s*IFRAME.*?>|si', '|</?\s*JAVASCRIPT.*?>|si', '|JAVASCRIPT:|si', '|</?\s*FORM.*?>|si', '|</?\s*INPUT.*?>|si', '|CHAR\(|si', '|INTO OUTFILE|si', '|LOAD DATA|si');
	$string = preg_replace($bad, array(''), ' '.$string.' ');
	//if ($string != @mysql_real_escape_string($string)) {
		if (class_exists('Q')) {
			$initConnexion =& new Q(); 
			$string  = mysql_real_escape_string($string);
		}
	//}
	else $string = addslashes($string);
	$string = str_replace("\\n","\n",$string);
	$string = str_replace("\\r","\r",$string);
	return trim($string);
}

function clean($string, $br='') { // ShortCut
	if (is_array($string)) array_map('stripslashes_array', $string);
	else return sanitize($string);
}

// AVANT TOUTE INSERTION EN BDD ---------------------------------------------
function cleanWysiwyg($string, $br=1) {
	if ($string === 0 || $string === '0') return 0;
	elseif (is_numeric($string)) return $string;
	elseif (empty($string)) return $string;
	
	$bad = array('\'', '&rsquo;', '&hellip;');
	$good = array('\'', '\'', '...');
	$string = str_replace($bad, $good, $string);
	$string = sanitize($string);
	$string = preg_replace('|</?\s*BR/?>|si','<br />',$string);
	if ($br == 2) $string = str_replace(chr(13).chr(10),'<br />',$string);
	if (strpos($string,'<br />') !== false) {
		if ($br == 0) $string = str_replace('<br />',chr(13).chr(10),$string);
		$string = str_replace('<br />&nbsp;<br />','',$string);
		while (strpos(substr($string,0,8), '<br />') !== false) $string = str_replace('<br />', '',substr($string,0,8)).substr($string,8); // BR de début
		while (strpos(substr($string,-8),'<br />') !== false) $string = substr($string,0,-8).str_replace('<br />','',substr($string,-8));
	}
	return trim($string);
}

// IF VARS SEND BY AJAX GET/POST ---------------------------------------------
function cleanAjax($string) {
	return clean(make_iso(urldecode($string)));
	//return clean(html_entity_decode($string));
}

// NETTOIE UN NOM : UPLOAD D'UN FICHIER ---------------------------------------------
function cleanName($string) {
	if (empty($string)) return $string;
	elseif (is_numeric($string)) return $string;
	$string = strtolower(' '.trim($string).' ');
	$special = array('&', 'O', 'Z', '-', 'o', 'z', 'Y', 'À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', '.', ' ', '+', '\'');
	$normal = array('et', 'o', 'z', '-', 'o', 'z', 'y', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'd', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', '_', '_', '-', '-');
	$string = str_replace($special, $normal, $string);
	$string = preg_replace('/[^a-z0-9_\-]/', '', $string);
	$string = preg_replace('/[\-]{2,}/', '-', $string);
	$string = preg_replace('/[\_]{2,}/', '_', $string);
	$string = substr($string, 1, -1);
	return $string;
}

// MAKE URL REWRITE NAME --------------------------------------------- 
function urlRewrite($name, $params) {
	// RewriteEngine on
	// RewriteRule ^([a-z,-]+)-t([0-9]+)i([0-9]+)p([0-9]+).html$ index.php?goto=detail&theme=$2&id=$3&page=$4 [L]
	$url = '';
	$words = explode(' ', $name);
	foreach ($words as $word) {
		if (strlen($word) > 1) $url .= '-'.cleanName($word);
		if (strlen($url) > 240) break;
	}
	$url = substr($url, 1);
	$url = str_replace('_', '-', $url);
	$url = preg_replace('/[^a-z\-]/', '', $url);
	$url = urlencode($url.'-'.$params.'.html');
	$url = preg_replace('/[\-]{2,}/', '-', $url);
	return $url;
}

// NETTOIE UN NOM : STRIP TAG ---------------------------------------------
function stripTags($data, $keepBr=FALSE) {
	$data = unhtmlentities($data);
	$string = str_replace(chr(13).chr(10), ' ', $string);
	$data = preg_replace('/<br(.*?)>/si', chr(13).chr(10), $data);
	$data = preg_replace('/<\/(pre|ul|li|p|table|tr)>/si', chr(13).chr(10), $data);
	$data = preg_replace('/<(.*?)>/si', '', $data);
	$data = preg_replace('/['.chr(13).chr(10).']{2,}/', chr(13).chr(10), $data);
	if ($keepBr) $data = str_replace(chr(13).chr(10), '<br />', $data);
	return $data;
}

// AFFICHE STRING --------------------------------------------- 
function aff($string, $br=1, $tag=1) { // Clean AND HTMLilise
	if (empty($string)) return $string;
	elseif (is_numeric($string)) return $string;
	elseif (is_array($string)) {
		array_map('aff', $string, $br, $tag);
		return $string;
	}
	$string = trim(str_replace('"','&quot;',stripslashes($string)));
	if ($br == 2) $string = str_replace(chr(13).chr(10), '<br />', $string);
	elseif ($br == 3) $string = str_replace(chr(13).chr(10), ' ', $string);
	elseif ($br == 0) {
		$string = str_replace('<br />', chr(13).chr(10), $string);
		$string = str_replace(chr(13).chr(10).' ',chr(13).chr(10), $string);
	}
	if ($tag == 0) $string = stripTags($string);
	return $string;
}

function affVeryClean($string, $length=160, $wrap=43) {
	return wrap(cs(stripTags(aff($string), true), $length), $wrap);
}

// AFFICHE NOM DE FICHIER --------------------------------------------- 
function affCleanName($string,$ext=1) { // Enleve les chiffres de la date...
	$string = preg_replace("|[0-9]{4,}_|",'',$string);
	$string = ucwords(str_replace('_',' ',$string));	
	if ($ext != '1') $string = str_replace('.'.getExt($string),'',$string);
	return $string;
}

// AFFICHE STRING FROM HTML WYSIWYG --------------------------------------------- 
function quote($string) { // quote(aff($V['texte']))
	if (empty($string)) return $string;
	$string = str_replace('&quot;', '"', $string);
	$string = preg_replace('|<blockquote(.*?)>(.*?)</blockquote>|si', '<blockquote$1>&laquo;$2&raquo;</blockquote>', $string);
	$string = preg_replace('|<cite(.*?)>(.*?)</cite>|si', '<cite$1>&laquo;$2&raquo;</cite>', $string);
	$string = preg_replace('|<div align="center">|si', '<div style="text-align:center">', $string);
	
	$string = makeEncoding($string);
	
	return $string;
}

// For put in JS string...
function squote($string) { 
	if (empty($string)) return $string;
	$string = str_replace("'", "\'", aff($string));
	$string = makeEncoding($string);
	return $string;
}

// AFFICHE STRING FROM TEXTAREA | INPUT TEXT
function html($string) { // html(aff($V['titre']))
	if (empty($string)) return $string;
	if (is_array($string)) {
		array_map('html', $string);
		return $string;
	}
	$string = str_replace('&quot;', '"', $string);
	$string = htmlentities(make_iso($string), ENT_QUOTES);
	$string = makeEncoding($string);
	return $string;
}
function html_array($array) {
	return is_array($array) ? array_map('html_array',$array) : html($array);
}

function htmlButTags($string) {
	if (empty($string)) return $string;
	$string = str_replace('&quot;', '"', $string);
	$caracteres = get_html_translation_table(HTML_ENTITIES);
	$remover = get_html_translation_table(HTML_SPECIALCHARS);
	$caracteres = array_diff($caracteres, $remover);
	$string = strtr($string, $caracteres);
	$string = makeEncoding($string);
	return $string;
}

// XML PARSE ---------------------------------------------
function cleanXml($string,$cddata=0) {
	$string = str_replace(chr(13).chr(10), "\n", $string);
	if ($cddata == 0) return utf8_encode(trim($string));
	else return '<![CDATA['.utf8_encode(trim($string)).']]>';
}

function affXml($texte) {
	return aff(utf8_decode($texte));
}

// RSS PARSE ---------------------------------------------
function cleanRss($string, $cddata=0, $notag=false) {
	$string = html_entity_decode(trim(str_replace(chr(13).chr(10), "\n", $string)));
	if ($notag) $string = stripTags($string);
	if ($cddata == 0) return $string;
	else return '<![CDATA['.$string.']]>';
}

// MAKE FILE NAME --------------------------------------------- 
function makeName($filename,$nb='60') { // 070220155402_las-vegas-blvd.jpg
	$ext = getExt($filename);
	return date(ymdHis).'_'.cleanName(substr(trim(preg_replace('|.'.$ext.'|si','',' '.$filename.' ')),0,$nb)).'.'.$ext;
}

// CHECK & NETTOIE NOUVEAU MOT // A travailler avec les slashes.... :-/ --------------------------------------------- 
function cleanTag($string) { // l\\\'ApostrophE > l'apostrophe > l\\\'apostrophe
	if ($string == '') return false;
	$string = strtolower(stripslashes(aff($string))); 
	if (count(explode(' ',$string)) > 3) {
		return false;
	}
	preg_match("/^[a-z0-9&éèàùâêîûôùëïöüç\-' ]{2,150}$/",$string, $matches); // Validate STRING as WORD
	if (count($matches) == 1) { // Enleve le dernier S si pas "'s" (DJ's) ou 2 "ss" (Dress).... A reflechir...
		if (strpos(substr($string,-1),'s') !== false 
		&& strpos(substr($string,-2,1),'\'') === false
		&& strpos(substr($string,-2,1),'p') === false
		&& $string !== 'croquis'
		&& $string !== 'londres'
		&& $string !== 'ailleurs'
		&& strpos(substr($string,-2,1),'s') === false) 
			$string = substr($string,0,-1); 	
		return clean($string); // add SLASHES : "prud\\\'hom"
	}
	else return false;
}

// ISO <> UTF-8 ---------------------------------------------
function detectEncoding($string) {
	if (!function_exists('mb_detect_encoding')) return false;
	else return mb_detect_encoding($string, 'UTF-8,ISO-8859-1,ISO-8859-15');
}
function make_iso($string) {
	global $selfDirAdmin;
	if ($selfDirAdmin) return $string; // patch
	if (empty($string)) return $string;
	if (is_array($string)) return array_map('make_iso', $string);
	if (detectEncoding($string) == 'UTF-8') $string = utf8_decode($string); // BUG ?
	return $string;
}
function make_utf($string) {
	if (empty($string)) return $string;
	if (is_array($string)) return array_map('make_utf', $string);
	if (detectEncoding($string) !== FALSE && detectEncoding($string) != 'UTF-8') return utf8_encode($string);
	else return $string;
}

function makeEncoding($string) {
	global $isUtf8;
	if ($isUtf8) return make_utf($string);
	else return make_iso($string);
}

// SQL REGEX WITHOUT ACCENT NO MORE SLASHES --------------------------------------------- 
function stringToRegex($string,$sql=1) { // String is "clean" ("act\\\'ion")
	// WHERE "act\'ion" REGEXP "^[aàâä][cç]t\\\\'[iîï][oôö]n[s]?$" 
	// SELECT QUOTE("Don't") -> 'Don\'t!'
	if (!$string) return;
	$string = strtolower($string);
	if ($sql == 1)
		$string = preg_replace('|[\\\]{1,}|', '\\\\\\\\\\\\\\', clean($string)); // 3 slashes > 4 slashes : pour trouver 1 slashes dans SQL...
	else
		$string = stripslashes(stripslashes($string));
	$string = preg_replace('|[aàâä]{1}|', '[aàâä]', $string); // SQL REGEX
	$string = preg_replace('|[eéèêë]{1}|', '[eéèêë]', $string);
	$string = preg_replace('|[iîï]{1}|', '[iîï]', $string);
	$string = preg_replace('|[uùûü]{1}|', '[uùûü]', $string);
	$string = preg_replace('|[oôö]{1}|', '[oôö]', $string);
	$string = preg_replace('|[cç]{1}|', '[cç]', $string);
	$string = preg_replace('|[ ]{1}|', '[s]? ', $string); // mots clé == mot clé
	$string = preg_replace("|[ -']{1}|", "[ -']", $string);

	return '^'.$string.'[s]?$'; // Insensible au pluriel
}

// IN_ARRAY_REGEX ---------------------------------------------
function inArrayRegex($exp, $array) {
	if (!is_array($array)) return false;
	foreach($array as $chaine) {
		//db($exp.' - '.$chaine.' > '.(preg_match($exp,$chaine)?'true':'false'));
		if (preg_match($exp, $chaine)) return true; // ereg ?
	}
	return false;
}

function isSetArray($arr) {
	return (!is_array($arr) || !count($arr) ? false : true);
}

// FONCTION HIGHTLIGHT ---------------------------------------------------------------------------------------------------------- //
function strHighlight($texte, $words, $cut=0) {
	$highlight = '<span class="surbrillance">\1</span>';
	if (count($words) < 1 || empty($words) || empty($texte)) return $texte;
	foreach ((array)$words as $word) {
		if (empty($word)) continue;
		$word = preg_quote($word);
		//$word = '\b'.$word.'\b'; // whole word ?
		$texte = preg_replace('#<a\s(?:.*?)>('.$word.')<\/a>#si', '\1', $texte);
		$texte = preg_replace('#(?!<.*?)('.$word.')(?![^<>]*?>)#si', $highlight, $texte); /* '#(%s)#si' - '|'.$word.'|si' */
	}
	if ($cut > 0) { // Cut ?
		$pos = strpos($texte, '<span class="surbrillance">');
		if ($pos > $cut) $texte = '...'.substr($texte, ($pos-20));
		$texte = cs($texte,$cut).'...';
	}
	return $texte;
}

// NO HTML ENTITIES --------------------------------------------- 
function unhtmlentities($string) {
	//global $encoding;
	$string = make_iso($string);
	if (function_exists('html_entity_decode')) {
		$string = html_entity_decode($string, ENT_COMPAT, 'ISO-8859-15'); // NOTE: UTF-8 does not work!
    }
    else {
		$trans_tbl = get_html_translation_table(HTML_ENTITIES, ENT_COMPAT);
	$trans_tbl = array_flip($trans_tbl);
		$string = strtr($string, $trans_tbl);
    }
    $string = preg_replace('/&#(\d+);/me',"chr(\\1)", $string); #decimal notation
    $string = preg_replace('/&#x([a-f0-9]+);/mei',"chr(0x\\1)", $string);  #hex notation
	return $string;
}


// BORNES ---------------------------------------------
function bornes($number,$min=1,$max=255) {
	if ($number < $min) $number = $min;
	elseif ($number > $max) $number = $max;
	return $number;
}

// PAD AND CENTIMES --------------------------------------------- 
function pad($number,$decimal=2,$pad=0) {
	$Tnumber = explode('.',floatval($number));
	$number = str_pad(intval($Tnumber[0]),$pad,'0',STR_PAD_LEFT);
	if ($decimal > 0) $number .= '.'.str_pad(substr($Tnumber[1],0,$decimal),$decimal,'0');
	return $number;
}

// AFFICHE PROPREMENT le KILOOCTETS ---------------------------------------------
function cleanKo($Ko) {
	$kb=1024; $mb=1048576; $gb=1073741824; $tb=1099511627776;
	if($Ko < $kb) $Ko = $Ko." Octets";
	elseif($Ko < $mb) $Ko =  round($Ko/$kb,2)." Ko";
	elseif($Ko < $gb) $Ko =  round($Ko/$mb,2)." Mo";
	elseif($Ko < $tb) $Ko =  round($Ko/$gb,2)." Go";
	else $Ko =  round($Ko/$tb,2)." To";
	return($Ko);
}

// CONVERSION DES URL EN LIEN + CLEAN ---------------------------------------------
function makeClickable($string) {
	$string = preg_replace("#([\n ])([a-z]+?)://([a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]+)#i", "\\1<a href=\"\\2://\\3\" target=\"_blank\">\\2://\\3</a>",' '.$string.' ');
	$string = preg_replace("#([\n ])www\.([a-z0-9\-]+)\.([a-z0-9\-.\~]+)((?:/[a-z0-9\-\.,\?!%\*_\#:;~\\&$@\/=\+]*)?)#i", "\\1<a href=\"http://www.\\2.\\3\\4\" target=\"_blank\">www.\\2.\\3\\4</a>", $string);
	$string = preg_replace("#([\n ])([a-z0-9\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)?[\w]+)#i", "\\1<a href=\"mailto:\\2@\\3\">\\2@\\3</a>", $string);
	
	// $text = preg_replace('#([\s\(\)])(https?|ftp|news) {1}://([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^"\s\(\)<\[]*)?)#ie', '\'$1\'.handle_url_tag(\'$2://$3\')', $text);
	// $text = preg_replace('#([\s\(\)])(www|ftp)\.(([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^"\s\(\)<\[]*)?)#ie', '\'$1\'.handle_url_tag(\'$2.$3\', \'$2.$3\')', $text);
	return trim(stripslashes($string));
}

// COULEURS HEXADECIMALES ---------------------------------------------
function html2rgb($color) {
	if (substr($color,0,1) == '#') $color = substr($color,1,6); // gestion du #...
	$tablo[0] = hexdec(substr($color, 0, 2));
	$tablo[1] = hexdec(substr($color, 2, 2));
	$tablo[2] = hexdec(substr($color, 4, 2));
	return $tablo;
}
function rgb2html($tablo) {
	for ($i=0;$i<3;$i++) $tablo[$i] = bornes($tablo[$i]);
	return '#'.str_pad(dechex( ($tablo[0]<<16)|($tablo[1]<<8)|$tablo[2] ),6,'0',STR_PAD_LEFT);
}

// (CLEAN) CUT STRING ---------------------------------------------
function ccs($string, $max=0, $keepBr=FALSE, $ponct='...') {
	$string = aff($string);
	$string = stripTags($string);
	$string = cs($string, $max, $ponct);
	$string = html($string);
	if ($keepBr) $string = str_replace(chr(13).chr(10), '<br />', $string);
	return $string;
}
function cs($string, $max=0, $ponct='...') {
	if (strlen($string) > $max) {
		$chaine = substr($string, 0, $max);
		$espace = strrpos($chaine, ' ');
		$string = substr($string, $debut, $espace).$ponct;
	} 
	$string = makeEncoding($string);
	return $string;
}

// WRAP des mots trop long  ---------------------------------------------
function wrap($phrase, $maxlength=43, $spacer=' ') {//$spacer='<span style=font-size:0;> </span>'
	include_once dirname(__FILE__).'/fonctions_htmlwrap.php';
	return htmlwrap($phrase, $maxlength, $spacer, '');
}

// Tabulation ---------------------------------------------
function t($count) {
	return str_repeat(chr(9), $count);
}

// PLURIEL ---------------------------------------------
function s($i) {
	return $i > 1 ? 's' : '';
}

// CHECK EMAIL ---------------------------------------------
function checkMail($email) {
	global $host;
	if (empty($email)) return false;
	if (!preg_match("/[a-z0-9_-]+(\.[a-z0-9_-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,10})/i",$email)) return false;
	if (!isLocal()) { // Ne marche pas sur Windows
		list($userName,$mailDomain) = explode('@',$email); 
		if (checkdnsrr($mailDomain,'MX')) return true;
		else return false;
	}
	else return true;
}

// MANIPULATE DATE ---------------------------------------------
function rDate($MyDate,$lettre='0',$lg='fr') { // 781502 <-> 15/02/78 || 781502 -> Mercredi 31 Janvier 2007 <- fr <-> uk
	if ((strpos($MyDate,'/') == false && strpos($MyDate,'-') == false) && (strlen($MyDate) == 6 || strlen($MyDate) == 8)) {
		if (strlen($MyDate) == 8) {
			$a = substr($MyDate,0,4);
			$m = substr($MyDate,4,-2);
			$j = substr($MyDate,6);
		} elseif (strlen($MyDate) == 6) {
			$a = substr($MyDate,0,2);
			$m = substr($MyDate,2,-2);
			$j = substr($MyDate,4);
		}
		if ($lettre == '0') return $j.'/'.$m.'/'.$a;
		else {
			
			if ($lg != 'uk' ) {
				setlocale(LC_TIME, $lg);
				switch($lettre) {
					case '2' : $format = "%d %B %Y"; break; // 30 Decembre 2007
					case '3' : $format = "%A %d %B"; break; // Dimanche 30 Decembre
					default : $format = "%A %d %B %Y"; break; // Dimanche 30 Decembre 2007
				}
			}
			else {
				setlocale(LC_TIME, "eng");
				$format = "%A, %B %d %Y"; // Sunday, December 30 2005
				switch($lettre) {
					case '2' : $format = "%B %d %Y"; break; // December 30 2005
					case '3' : $format = "%A, %B %d"; break; // Sunday, December 30
					default : $format = "%A, %B %d %Y"; break; // Sunday, December 30 2005
				}
			}
			$stampeddate = mktime(12,0,0,$m,$j,$a); 
			$dateform = strftime($format, $stampeddate);

			$dateform = htmlentities($dateform);
			
			$mois_en = array('January', 'February','March','April','May','June','July','August','September','October','November','December');
			$mois_fr = array('Janvier', 'F&eacute;vrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Ao&ucirc;t', 'Septembre', 'Octobre', 'Novembre', 'D&eacute;cembre');
			$mois_es = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
			
			$jour_en = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'); 
			$jour_fr = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi'); 
			$jour_es = array('Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'); 

			if ($lg == 'fr') {
				$dateform = str_replace($mois_en, $mois_fr,$dateform);
				$dateform = str_replace ($jour_en, $jour_fr,$dateform); 
				$dateform = preg_replace("/(\D)1st/","\${1}1er",$dateform); // 1st qui n'est pas précédé par un chiffre 
				$dateform = preg_replace("/(\d)(st|th|nd|rd)/","\${1}",$dateform); 
			}
			if ($lg == 'es') {
				$dateform = str_replace($mois_en,$mois_es,$dateform);
				$dateform = str_replace ($jour_en,$jour_es,$dateform); 
				$dateform = preg_replace("/(\D)1st/","\${1}1er",$dateform); // 1st qui n'est pas précédé par un chiffre 
				$dateform = preg_replace("/(\d)(st|th|nd|rd)/","\${1}",$dateform); 
			}			
			
			$string = makeEncoding($string);
			
			return ucwords($dateform);
		}
	}
	elseif (strpos($MyDate,'/') != false && (strlen($MyDate) == 8 || strlen($MyDate) == 10)) { // 17/02/78 to SQL 780217
		list($j,$m,$a) = explode('/',$MyDate);
		return clean($a.$m.$j);
	}
	elseif (strpos($MyDate,'-') != false && (strlen($MyDate) == 8 || strlen($MyDate) == 10)) { // 17/02/1978
		list($j,$m,$a) = explode('-',$MyDate);
		return clean($a.$m.$j);
	}
	else return;
}

// SQL DATE TO RSS RFC ---------------------------------------------
function sqlDateToRss($sqlDate) {
	list($date, $time) = explode(' ', $sqlDate);
	if (!empty($time)) { // Date Time stamp
	list($a, $m, $j) = explode('-',$date);
	list($h, $mi, $s) = explode(':',$time);
	}
	else { // Custom juss stamp : 20071101
		$a = substr($date,0,4);
		$m = substr($date,4,-2);
		$j = substr($date,6);
	}
	$rss_date = mktime(intval($h), intval($mi), intval($s), intval($m), intval($j), intval($a));
	$rss_date = date("r", $rss_date);
	
	return $rss_date;
}

// DATE TO ARRAY --------------------------------------------- 
// Construit un array a partir d'une date SQL >>> (yy)yymmjj || 11/12/2008 >>> array('y'=>'(aa)aa','m'=>'mm','d'=>'dd')
function dateToArray($datestring) {
	if (!$datestring) return false;
	if (preg_match('/[\/.-]*/i', $datestring) > 0) { // 11/12/2008
		list($d, $m, $y) = split ('[/.-]', $datestring);
	}
	else if (strlen($datestring) == 19 || strlen($datestring) == 16) { // 2008-01-28 20:02:00 || 2008-01-28 20:02
		list($datestring) = explode(' ', $datestring);
		$y = substr($datestring, 0, 4);
		$m = substr($datestring, 5, 2);
		$d = substr($datestring, 8, 2);
	}
	elseif (strlen($datestring) == 8 && is_numeric($datestring)) { // yyyymmjj
		$y = substr($datestring, 0, 4);
		$m = substr($datestring, 4, -2);
		$d = substr($datestring, 6);
	}
	elseif (strlen($datestring) == 6 && is_numeric($datestring)) { // yymmjj
		$y = substr($datestring, 0, 2);
		$m = substr($datestring, 2, -2);
		$d = substr($datestring, 4);
	}
	else return false;
	return array('y'=>$y, 'm'=>$m, 'd'=>$d);
}

// DATE du jour ---------------------------------------------
function printDate($lg='fr') { // fr <-> uk
	return rDate(date("ymd"),'1',$lg);
}

// DATE SQL ET DATE HUMAINE ---------------------------------------------
function getDateTime($full=false) { //2007-01-01 00:00:00
	if ($full) return date("Y-m-d H:i:s");
	else return date("Y/m/d H:i");
}

function printDateTime($dateTime,$lettre=0,$lg='fr') {
	list($date, $time) = explode(' ', $dateTime);
	list($a, $m, $j) = explode('-', $date);

	if ($lettre == 0) { // 12/01/07 à 10h30
		$date = $j.'/'.$m.'/'.substr($a, 2);
		$time = substr($time, 0, -3);
		$time = str_replace(':', 'h', $time);
		return $date.' &agrave; '.$time;
	}
	elseif ($lettre == 1) { // 12-01-07
		if ($lg == 'uk') return $m.'-'.$j.'-'.substr($a,2);
		else  return $j.'-'.$m.'-'.substr($a,2);
	}
	elseif ($lettre == 2) { // Mercredi 31 Janvier 2007 à 10h30
		$date = rDate($a.$m.$j, '1', $lg);
		$time = substr($time, 0, -3);
		$time = str_replace(':', 'h', $time);
		return $date.' &agrave; '.$time;
	}
	elseif ($lettre == 3) { // Mercredi 31 Janvier à 10h30
		$date = rDate($a.$m.$j, '1', $lg); 
		$date = substr($date, 0, -5);
		$time = substr($time, 0, -3);
		$time = str_replace(':', 'h', $time);
		return $date.' &agrave; '.$time;
	}
	elseif ($lettre == 4) { // Mercredi 31 Janvier
		$date = rDate($a.$m.$j, '1', $lg); 
		$date = substr($date, 0, -5);
		return $date;
	}
	elseif ($lettre == 5) { // Mercredi 31 Janvier 2007
		return rDate($a.$m.$j, '1', $lg); 
	}
	elseif ($lettre == 6) { // 31 Janvier 2007
		$newDate = rDate($a.$m.$j, '1', $lg);
		list($d, $dn, $m, $y) = explode(' ', $newDate);
		return $dn.' '.$m.' '.$y; 
	}
	elseif ($lettre == 7) { // 10:30
		return substr($time, 0, -3);
	}
}

// DATE < POSTE IL Y A ...  ---------------------------------------------
function relativeDate($dateFromSql) {
	$m = $dateFromSql; // 2007-03-20 16:49:12 
	$date_annee = date('Y',time());
	$dateSql_annee = substr($m,0,4);
	$annee_diff = $date_annee - $dateSql_annee;
	
	if ($annee_diff < 0) return 'le '.printDateTime($dateFromSql); // retour dans le futur
	elseif ($annee_diff > 1) return 'le '.printDateTime($dateFromSql); // 'il y a '.$annee_diff.' ans';
	else {
		$date_jour = date('z',time());
		$dateSql_jour = date('z',mktime(substr($m,11,2),substr($m,14,2),substr($m,17,2),substr($m,5,2),substr($m,8,2),substr($m,0,4))); // (hour,minute,second,month,day,year)
		$day_diff = ($date_jour  -  $dateSql_jour);
		
		if ($day_diff < 0) return 'le '.printDateTime($dateFromSql); // retour dans le futur :)
		if ($day_diff == 0) { // Aujourd'hui > Heures
			$time_diff = (time() - mktime(substr($m,11,2),substr($m,14,2),substr($m,17,2),substr($m,5,2),substr($m,8,2),substr($m,0,4)));
			if($time_diff < 60) return 'il y a '.$time_diff.' seconde'.s($time_diff);
			elseif ($time_diff < 120) return 'il y a 1 minute';
			elseif ($time_diff < 3600)  return 'il y a '.intval($time_diff/60).' minute'.s(intval($time_diff/60));
			elseif ($time_diff < 7200) return 'il y a 1 heure';
			elseif ($time_diff < 86400) return 'il y a '.intval($time_diff/3600).' heure'.s(intval($time_diff/3600));
		}
		elseif($day_diff == 1) return 'hier';
		elseif ($day_diff < 7) return 'il y a '.$day_diff.' jour'.s($day_diff);
		elseif ($day_diff < 31) return 'il y a '.ceil($day_diff/7).' semaine'.s(ceil($day_diff/7));
		elseif ($day_diff < 361) return 'il y a '.ceil($day_diff/30).' mois';
	}
}

// MAKE URL REWRITE NAME --------------------------------------------- 
function urlRewrite($name, $params) {
	// RewriteEngine on
	// RewriteRule ^([a-z,-]+)-t([0-9]+)i([0-9]+)p([0-9]+).html$ index.php?goto=detail&theme=$2&id=$3&page=$4 [L]
	$url = '';
	$words = explode(' ', $name);
	foreach ($words as $word) {
		if (strlen($word) > 1) $url .= '-'.cleanName($word);
		if (strlen($url) > 240) break;
	}
	$url = substr($url, 1);
	$url = str_replace('_','-',$url);
	$url = preg_replace('/[^a-z\-]/', '', $url);
	$url = urlencode($url.'-'.$params.'.html');
	$url = preg_replace('/[\-]{2,}/','-',$url);
	return $url;
}

// MAKE AND WRITE SIMPLE XML FROM ARRAY ---------------------------------------------
function makeXml($arrXml,$xmlPath='') {
	if (!is_array($arrXml)) return FALSE;
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'.chr(13);
	foreach($arrXml as $root=>$channels) { 
		$xml .= '<'.$root.'>'.chr(13);
		foreach($channels as $channel=>$items) {
			foreach($items as $key=>$item) {
				$xml .= chr(9).'<'.$channel.'>'.chr(13);
				foreach($item as $input=>$value) {
					$xml .= chr(9).chr(9).'<'.$input.'>'.cleanXml($value,1). '</'.$input.'>'.chr(13);
				}
				$xml.= chr(9).'</'.$channel.'>'.chr(13); 
			}
		}
		$xml.= '</'.$root.'>'.chr(13); 
	}
	//echo '<textarea name="textarea" cols="160" rows="50">'.$xml.'</textarea>'; die();
	if ($xmlPath) return writeFile($xmlPath,$xml);
	else return $xml;
}

// MAKE AND WRITE SIMPLE RSS FROM ARRAY ---------------------------------------------
/*
	$rssChannel = array(
		'title' => $SITE,
		'link' => $WWW,
		'description' => fetchValue('meta_description'),
		'language' => $lg,
		'pubDate' => date("D, d M Y H:i:s")
	);
	$rssItems = array(
		0 => array(
			'title' => 'Blah blah',
			'link' => 'Blah blah',
			'description' => 'Full text of the article...'
			'author' => 'Blah blah',
			'pubDate' => 'Blah blah',
		)
	);
	$rssChannel['items'] = $rssItems;
*/
function parseArrToRss($rssChannel, $rssPath='', $encoding='iso-8859-1', $version='2.0') {
	
	if (!is_array($rssChannel)) return FALSE;
	
	$rss = '<?xml version="1.0" encoding="'.$encoding.'"?>'."\n";
	$rss .= '<rss version="'.$version.'">'."\n";
	$rss .= t(1).'<channel>'."\n";
	
	foreach($rssChannel as $key=>$value) {
		if ($key == 'items') continue;
		$rss .= t(2).'<'.$key.'>'.cleanRss($value, 1, true).'</'.$key.'>'."\n";
	}
	
	foreach($rssChannel['items'] as $rssItem) { 
		$rss .= t(2).'<item>'."\n";
		foreach($rssItem as $key=>$value) {
			list($keyEnd) = explode(' ', $key);
			if (!is_array($value)) $rss .= t(3).'<'.$key.'>'.cleanRss($value,1).'</'.$keyEnd.'>'."\n";
			else {
				$rss .= t(3).'<'.$key.'>'."\n";
				foreach($value as $k=>$val)  $rss .= t(4).'<'.$k.'>'.cleanRss($val,1).'</'.$k.'>'."\n";
				$rss .= t(3).'</'.$keyEnd.'>'."\n";
			}
		}
		$rss.= t(2).'</item>'."\n";
	}
	$rss .= t(1).'</channel>'."\n";
	$rss .= '</rss>'."\n";
	if ($rssPath) return writeFile($rssPath, $rss);
	else return $rss;
}

// Extract Images from Feed --------------------------------------------- //
function getRssImage($feed) {

	die('TODO !!!');
	
	if (empty($feed)) return false;
	
	require_once FMK_CLS_PTH."accessors/ClassRSSAccessor.php";
	$rss =& new RSSAccessor( $feed, $this->app_url, $this->app_pth );
	if (!$rss) return 'D&eacute;sol&eacute; ce flux rss ne semble pas avoir d\'image';
	$rss = $rss->flow->channel;

	$i = 0;
	$max_feed = 16;
	$arr_gal = array();
	
	foreach($rss->items as $item) {
		$desc = utf8_decode(html_entity_decode($item->description));
		
		$pattern_img_src = '!.*<img.+src=("|\')([^\1]+)\1.*!Ui'; // '!<([biu])>(.)*<\1>!Ui'
		preg_match($pattern_img_src,$desc,$links);
		if (!$links[2]) continue;

		$img = $links[2];
		$link = $item->link;
		$arr_gal[] = array('img'=>$img, 'link'=>$link);
		$i++;
		if ($i >= $max_feed) break;
	}
	
	return $arr_gal;
}

// JSON CLEAN STRING --------------------------------------------- //

function cleanJson($string) {
	$string = str_replace("'", "\'", $string);
	$string = str_replace("{", "\{", $string);
	$string = str_replace("}", "\}", $string);
	$string = str_replace(chr(10), '', $string);
	$string = str_replace(chr(13), '', $string);
	return $string;
}

// Convert bi-dimentional php array to javascript array --------------------------------------------- //
function getScriptBiArray($arr_name, $arr) {
	$myJs = "var ".$arr_name." = {";
	$j=0;
	foreach($arr as $key=>$val) {
		if (is_array($val)) {
		$myJs .= $j.":{";
		foreach($val as $key2=>$val2) {
				$myJs .= $key2.":'".cleanJson($val2)."',";
		}
		$myJs = substr($myJs, 0, -1);
		$myJs .= "},";
		}
		else $myJs .= $key.":'".cleanJson($val)."',";
		$j++;
	}
	$myJs = substr($myJs, 0, -1).'};';
	return $myJs;
}

// URL string to JSON array... (embed flash from dailymotion) --------------------------------------------- //
// list($video_url, $video_params) = urlToJson('http://www.dailymotion.com/swf/x5uug&v3=1&autoPlay=0');
// var flashvars = {< ?=$video_params;? >};

function urlToJson($url, $js=TRUE) {
	if (strpos($url, '?') !== false) {
		list($tmp_url, $tmp_params) = explode('?', $url);
		$tmp_params = explode('&', $tmp_params);
	}
	elseif (strpos($url, '&') !== false) {
		$tmp_params = explode('&', $url);
		$tmp_url = $tmp_params[0];
		$tmp_params = array_slice($tmp_params, 1);
	}
	else $tmp_url = $url;

	if ($js) $jsonVars = '';
	else $jsonVars = array();
	foreach((array)$tmp_params as $i=>$pairs) {
		list($key, $val) = explode('=', $pairs);
		if ($js) $jsonVars .= ($i != 0 ? ',' : '').$key.":'".$val."'";
		else $jsonVars[$key] = $val;
	}
	
	return array($tmp_url, $jsonVars);
}
?>