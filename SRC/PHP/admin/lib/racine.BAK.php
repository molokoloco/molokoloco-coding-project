<?
/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////// VIRTUAL ADMIN V1.5 /////////////////////////////////////////////////////
////////////// Code mixing by Molokoloco for Agence Clark... [BETA TESTING FOR EVER] ... (o_O) //////////////////
////////////// Contact : molokoloco@gmail.com // Tous droits d'utilisation réservé /////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////*/

// ---------- CONFIG GENERALE -------------------- //
define('MLKLC', 1); // No Hack
define('SITE_CONFIG', 'J'); // Unique ID for session config
define('SITE_CMS', true); // Site CMS = menu cms + rep cms
define('SITE_REF', true); // Active referencement dans rubrique
define('VAD_VERSION', 'v1.0 - 03/12/2007]'); // Etes-vous à jour ? :)

$debug = 0; // Affiche toutes les requetes exécutées après cette déclaration
$site_en_production = 0; // Display no debug


// ---------- PHP.INI DEBUG -------------------- //
// die(phpinfo());
// ini_set('error_reporting', E_ALL & ~E_NOTICE);
// ini_set('display_errors', 'on'); 
// ini_set('track_errors', '1'); ### echo $php_errormsg;
// ignore_user_abort(true);
// ini_set('magic_quotes_runtime', 0);
// ini_set('memory_limit', 8000000);
// set_time_limit(500);
// ignore_user_abort(true);
// print_r(ini_get_all());
// set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/'.PATH_SEPARATOR.'../');
// print_r(apache_get_modules()); // ?


// ---------- PHP.INI -------------------- //
session_start();
ini_set('session.gc_maxlifetime', 3600);


// ---------- WHERE I'AM -------------------- //
$host = $_SERVER['HTTP_HOST'];
$hostIp = $_SERVER['SERVER_ADDR'];
$selfPageName = basename($_SERVER['PHP_SELF']);
$selfPageQuery = (!empty($_SERVER['QUERY_STRING']) ? $selfPageName.'?'.$_SERVER['QUERY_STRING'] : $selfPageName );
$selfDir = $_SERVER['REQUEST_URI'];
$root = '../../';

function isOvh() {
	global $hostIp;
	return ( $hostIp == '91.121.22.81' ? true : false );
}
function isOrnis() {
	global $hostIp;
	return ( $hostIp == '81.93.2.86' ? true : false );
}
function isProxit() {
	global $hostIp;
	return ( $hostIp == '213.198.28.130' ? true : false );
}
function isLocal() {
	global $host;
	return ( $host == 'saintmarc' || $host == 'saintmarc.proxitek.local' || $host == '192.168.0.1' || $host == 'clarkprod' || $host == '192.168.0.106' || $host == '127.0.0.1' || $host == 'localhost' ) ? true : false;
}

// ---------- SESSION DE CONFIGURATION -------------------- //
// ALL STATIC CONFIG HERE - MODIFICATION ALLOWED !!!
### $_SESSION[SITE_CONFIG]['WWW'] = NULL;
if (empty($_SESSION[SITE_CONFIG]['WWW'])) { 
	// SQL CONFIG
	if (isOvh()) {
		$dbase = 'juss';
		$dbhost = 'localhost';
		$dblogin = 'juss';
		$dbmotdepasse = 'leprelapidus';
		$convert = '/usr/bin/';
		$wwwRoot = $_SERVER['DOCUMENT_ROOT'].'/';
		$WWW = 'http://'.$host.'/';
	}
	elseif (isLocal()) {
		$dbase = 'juss';
		$dbhost = 'localhost';
		$dblogin = 'root';
		$dbmotdepasse = '';
		$convert = NULL;// 'D:/localhost/ImageMagick/';
		list(,$firstRep,$secondRep) = explode('/',$_SERVER['PHP_SELF']);
		$wwwRoot = $_SERVER['DOCUMENT_ROOT'].'/'.$firstRep.'/'; //.$secondRep.'/';
		$WWW = 'http://'.$host.'/'.$firstRep.'/'; //.$secondRep.'/';
		
		$WWW = 'http://'.$host.'/'.$firstRep.'/'.$secondRep.'/';
	}
	else die('Config Serveur non connue !');
	
	// LANGUES
	$langues = array(0=>'fr');
	$lg = $langues[0];
	
	// DIVERS
	$maxUploadSize = ceil(1048576 * 16); // 16mo
	$maxAttachements = 50;
	$jpgquality = 85;
	$rep = 'medias/'; $grand = 'grand/'; $medium = 'medium/'; $mini = 'mini/'; $repTemp = 'temp/';
	
	$JS = '<script type="text/javascript">'.chr(13).chr(10).'// <![CDATA['.chr(13).chr(10);
	$JSE = chr(13).chr(10).'// ]]>'.chr(13).chr(10).'</script>';	
	
	 // Once init for tinyIce
	 $TinyMceEditorDone = 0;
	
	// ALLOWED RESSOURCES
	$allowedIp = array('127.0.0.1');
	$allowedRef = array($WWW);
	
	$extensionsImg = array('jpg', 'png', 'gif');
	$extensionsVideo = array('mov', 'mpg', 'avi', 'wmv', 'wma', 'asf', 'wm', 'flv', 'rm', 'ram');
	$extensionsMusique = array('mp3', 'wav', 'ogg');
	$extensionsFlash = array('swf');
	$extensionsDocument = array('psd', 'pdf', 'zip', 'doc', 'txt', 'xls', 'ppt', 'pps', 'xml', 'fla', 'exe');
	$extensionsFiles = array_merge($extensionsImg, $extensionsVideo, $extensionsMusique, $extensionsFlash, $extensionsDocument); 
	
	$allowedTypesImg = array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/gif', 'image/png', 'x-png');
	$allowedTypesFiles = array('image/tiff', 'audio/mpeg', 'audio/x-wav', 'audio/x-ms-wma', 'audio/wav', 'audio/x-ms-wma', 'video/mpeg', 'video/mpg', 'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/avi', 'video/x-ms-asf', 'video/x-ms-wm', 'video/x-ms-wmv', 'video/x-flv', 'text/plain', 'text/xml', 'text/html', 'application/pdf', 'application/zip', 'application/x-zip-compressed', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/excel', 'application/x-shockwave-fla', 'application/x-shockwave-flash', 'application/octet-stream', 'application/x-msdos-program');
	$allowedTypesFiles = array_merge($allowedTypesFiles, $allowedTypesImg);
		
	//$allowedHtml = array('b', 'strong', 'i', 'em', 'u', 'p', 'pre', 'center', 'div', 'br', 'table', 'tbody', 'tr', 'td', 'a', 'a href', 'img', 'hr', 'font', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'ol', 'ul', 'blockquote', 'embed', 'span', 'style');

	// ---------- FILL VARS WITH SESSION CONFIG -------------------- //
	session_register(SITE_CONFIG);
	$arrConfig = array(
		
		'dbase' => 					$dbase,
		'dbhost' => 				$dbhost,
		'dblogin' => 				$dblogin,
		'dbmotdepasse' => 			$dbmotdepasse,
		
		'convert' => 				$convert,
		'root' => 					$root,
		'wwwRoot' => 				$wwwRoot,
		'WWW' => 					$WWW,
		
		'langues' => 				$langues,
		'lg' => 					$lg,
		
		'maxUploadSize' => 			$maxUploadSize,
		'maxAttachements' => 		$maxAttachements,
		'jpgquality' => 			$jpgquality,
		
		'rep' => 					$rep,
		'grand' => 					$grand,
		'medium' => 				$medium,
		'mini' => 					$mini,
		'repTemp' => 				$repTemp,
		
		'JS' => 					$JS,
		'JSE' => 					$JSE,
		'TinyMceEditorDone' => 		$TinyMceEditorDone,
		'allowedIp' => 				$allowedIp,
		'allowedRef' => 			$allowedRef,
		
		'extensionsImg' => 			$extensionsImg,
		'extensionsVideo' => 		$extensionsVideo,
		'extensionsMusique' => 		$extensionsMusique,
		'extensionsFlash' => 		$extensionsFlash,
		'extensionsDocument' => 	$extensionsDocument,
		'extensionsFiles' => 		$extensionsFiles,
		'allowedTypesImg' => 		$allowedTypesImg,
		'allowedTypesFiles' => 		$allowedTypesFiles,
		'allowedTypesFiles' => 		$allowedTypesFiles,
		//'allowedHtml' =>			$allowedHtml
	);
	
	$_SESSION[SITE_CONFIG] = array_merge((array)$_SESSION[SITE_CONFIG], $arrConfig);
}
else extract($_SESSION[SITE_CONFIG], EXTR_SKIP); 
// Correspond à : $dbase = $_SESSION[SITE_CONFIG]['dbase']; ...etc


// ---------- DROIT ADMIN -------------------- //
if (isLocal() && !isset($_SESSION[SITE_CONFIG]['ADMIN'])) { // AUTO CONNECT ADMIN IF LOCAL :) !!!!!!!!!!!!!!!!!!!!!!!!!!
	$_SESSION[SITE_CONFIG]['ADMIN']['type'] = 3;
}


// ---------- INCLUDES & CLASS -------------------- //
if (isLocal()) require dirname(__FILE__).'/fonctions_debug.php';
require dirname(__FILE__).'/fonctions.php';
require dirname(__FILE__).'/fonctions_files.php';
require dirname(__FILE__).'/class/class_xml.php';
require dirname(__FILE__).'/class/class_sql.php';
require dirname(__FILE__).'/class/class_fonctions.php';
require dirname(__FILE__).'/class/class_files.php';

if (strpos($selfDir,'/admin/') !== false) {
	require dirname(__FILE__).'/class/class_tinymce.php';
	require dirname(__FILE__).'/class/class_admin_liste.php';
	require dirname(__FILE__).'/class/class_admin_fiche.php';
	require dirname(__FILE__).'/class/class_admin_bdd.php';
}


// ---------- INFO FROM TABLE admin_parametres -------------------- //
if (empty($_SESSION[SITE_CONFIG]['emailAdmin'])) {
	$AP =& new Q(" SELECT * FROM admin_parametres WHERE id='1' LIMIT 1 ");
	
	$_SESSION[SITE_CONFIG]['emailAdmin'] = $AP->V[0]['email'] != '' ? aff($AP->V[0]['email']) : 'molokoloco@gmail.com'; // Email contact
	$_SESSION[SITE_CONFIG]['pagination'] = $AP->V[0]['pagination'] > 0 ? $AP->V[0]['pagination'] : 10; // Pagination site
	$_SESSION[SITE_CONFIG]['SITE'] = aff($AP->V[0]['site']); // Titre du site
	$_SESSION[SITE_CONFIG]['noscript'] = false; // Alerte si erreur javascript
	
	
	// Referencement
	$_SESSION[SITE_CONFIG]['meta_titre'] = aff($AP->V[0]['site']);
	$_SESSION[SITE_CONFIG]['meta_description'] = aff($AP->V[0]['meta_description']);
	$_SESSION[SITE_CONFIG]['meta_key'] = aff($AP->V[0]['meta_key']);
}
extract($_SESSION[SITE_CONFIG], EXTR_SKIP);


// ---------- MODULE_ID spécifiques pour les pages CMS -------------------- //
// A RANGER QUELQUE PART o_O !!!!!!!!!!!!!!!
if (SITE_CMS) {
	$accueilPageTypeId = 1; // type_id si page d'accueil du site
	$redirtoChildPageTypeId = 2; // type_id si redirection sur le firstChild
	$redirtoLinkPageTypeId = 3;
	$cmsPageTypeId = 4;
	$conteneurPageTypeId = 5;
}


// ---------- admin_parametres DYN ADMIN -------------------- //
if (strpos($selfDir,'/admin/') !== false) {

	// Menu de l'administration /////////////////////////////////
	$adminMenuCms = array(
		'', 'ARBORESCENCE',
			'cms_pages', '-Rubriques',
			'cms_blocs', '-Blocs de colonne',
		
		'', 'DEV PHP',
			'cms_pages_types', '-Types de page',
			'cms_elements_types', '-Types d\'&eacute;l&eacute;ment',
	);
	$adminMenuAdmin = array(		
		'', 'TYPES DE MODULES',
			'#', '-Mod actualit&eacute;s',

		'', 'PARAMETRES',
			'dat_bibliotheque_fichiers', '-Biblioth&egrave;que fichiers',
			'dat_bibliotheque_images', '-Biblioth&egrave;que images',
			'dat_trad_mots','-Traduction mots',
			'dat_trad_textes','-Traduction textes',
			'dat_email_alertes', '-Email alertes',

		'', 'CONFIGURATION',
			'admin_parametres', '-Param&egrave;tres',
			//'admin_recherche', '-Recherche',
			'admin_utilisateurs','-Utilisateurs',
	);
	
	if (SITE_CMS) $adminMenu = array_merge($adminMenuCms, $adminMenuAdmin);
	else $adminMenu = $adminMenuAdmin;

	// Admin config... /////////////////////////////////
	if (!is_dir($wwwRoot.$rep)) { mkdir($wwwRoot.$rep, 0755); chmod($wwwRoot.$rep, 0777); }

	$AP =& new Q(" SELECT * FROM admin_parametres WHERE id='1' LIMIT 1 ");
	
	$logoc = aff($AP->V[0]['logoc']);
	if ($logoc == '') $logoc = '<img src="../images/virtual_admin.gif" width="75" height="75" border="0">'; // Logo Client
	else $logoc = '<img src="'.$root.$rep.'parametres/'.$mini.$logoc.'" border="0">';
	$logoa = aff($AP->V[0]['logoa']);
	if ($logoa == '') $logoa = '<img src="../images/proxitek_logo.gif" alt="ADMIN '.$vad_version.'" width="557" height="81" border="0">'; // Logo Admin
	else $logoa = '<img src="'.$root.$rep.'parametres/'.$medium.$logoa.'" alt="ADMIN '.$vad_version.'" border="0">';
	
	$paginationa = aff($AP->V[0]['paginationa']); if ($paginationa < 1) $paginationa = '50'; // Pagination admin
	$fontcolor1 = aff($AP->V[0]['fontcolor1']); if ($fontcolor1 == '') $fontcolor1 = '#FFFFFF'; // Couleur police titre
	$fontcolor2 = aff($AP->V[0]['fontcolor2']); if ($fontcolor2 == '') $fontcolor2 = '#666666'; // Couleur police texte
	$linkcolor = aff($AP->V[0]['linkcolor']); if ($linkcolor == '') $linkcolor = '#016AC5'; // Couleur lien
	$linkcoloron = aff($AP->V[0]['linkcoloron']); if ($linkcoloron == '') $linkcoloron = '#FF0000'; // Couleur lien survol
	$bgcolor1 = aff($AP->V[0]['bgcolor1']); if ($bgcolor1 == '') $bgcolor1 = '#FFFFFF'; // Couleur du fond 1
	$bgcolor2 = aff($AP->V[0]['bgcolor2']); if ($bgcolor2 == '') $bgcolor2 = '#999999'; // Couleur du fond 2
	$ligneentete = aff($AP->V[0]['ligneentete']); if ($ligneentete == '') $ligneentete = '#BBBBBB'; // Couleur ligne entete
	$ligne1 = aff($AP->V[0]['ligne1']); if ($ligne1 == '') $ligne1 = '#E4E4E4'; // Couleur ligne 1
	$ligne2 = aff($AP->V[0]['ligne2']); if ($ligne2 == '') $ligne2 = '#F4F4F4'; // Couleur ligne 2
	$ligneon = aff($AP->V[0]['ligneon']); if ($ligneon == '') $ligneon = '#FFFFFF'; // Couleur ligne survol

}
else {
	
	// ---------- FRONT LANGUES -------------------- //
	if (count($langues) > 1) {
		if (isset($_GET['lg']) && in_array(gpc('lg'), $langues)) {
			$lg = gpc('lg');
			setMyCookie('lg', $lg);
			$_SESSION[SITE_CONFIG]['lg'] = $lg;
		}
		elseif (isset($_COOKIE['lg']) && in_array(getMyCookie('lg'), $langues)) {
			$lg = getMyCookie('lg');
		}
		else if ($_SESSION[SITE_CONFIG]['detectLg'] != 1) {
			$_SESSION[SITE_CONFIG]['detectLg'] = 1;
			include dirname(__FILE__).'/class/class_lang_detect.php';
			$lv = new detect_language();
			$langueVisiteur = $lv->getLanguage();
			if (in_array($langueVisiteur, $langues)) $lg = $langueVisiteur;
			setMyCookie('lg', $lg);
			$_SESSION[SITE_CONFIG]['lg'] = $lg;
		}
		else $lg = $_SESSION[SITE_CONFIG]['lg'];
		// Include constantes de langue
		if (is_file(dirname(__FILE__).'/langues/lang_mots_'.$lg.'.php')) require dirname(__FILE__).'/langues/lang_mots_'.$lg.'.php';  
		if (is_file(dirname(__FILE__).'/langues/lang_textes_'.$lg.'.php')) require dirname(__FILE__).'/langues/lang_textes_'.$lg.'.php';
	}

	// ---------- CONNECTION DES MEMBRES -------------------- //
	function initClientSession($clients_id) {
		/*
		if (isset($_SESSION[SITE_CONFIG]['CLIENT'])) $_SESSION[SITE_CONFIG]['CLIENT'] = NULL;
		
		$P =& new Q(" SELECT * FROM mod_ec_auteurs WHERE id='$clients_id' AND actif='1' LIMIT 1 ");
		if (count($P->V) < 1) alert('D&eacute;sol&eacute;, votre compte est d&eacute;sactiv&eacute;', 'back');
		
		$_SESSION[SITE_CONFIG]['CLIENT'] = $P->V[0];
		*/
	}
	
	// ---------- AUTO-CONNECT COOKIE MEMBRE -------------------- //
	if ($_SESSION[SITE_CONFIG]['CLIENT']['id'] < 1 && !empty($_COOKIE['CLIENTIDS'])) {
		/*$c = @unserialize(getMyCookie('CLIENTIDS'));
		$clients_id = $c['clients_id'];
		$ids = $c['ids'];
		$P =& new Q(" SELECT id FROM mod_ec_auteurs WHERE id='$clients_id' AND ids='$ids' AND actif='1' LIMIT 1 ");
		if (count($P->V) == 1) initClientSession($clients_id);
		else delMyCookie('CLIENTIDS'); // Del bad Cook*/
	}
	
	// ---------- GRAD SOME COMMON VARS ----------//
	$menu = 	gpc('menu');
	$action = 	gpc('action');
	$id = 		gpc('id');
	$module = 	gpc('module');
	$rid = 		gpc('rid');
	$goto = 	gpc('goto');
	
	if (SITE_CMS) {
		
		require(dirname(__FILE__).'/class/class_arbo.php');
		// $S =& new ARBO();
		// $S->fields = array('id','ordre','pid','type_id','titre_fr','titre_pag_fre','meta_description_fr','meta_key_fr');
		// $S->buildArbo();
		### db($S);
	}
}
?>