<?
/*///////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// VIRTUAL ADMIN V1.0 /////////////////////////////////////////////////////
////////////// Code mixing by Molokoloco... [BETA TESTING FOR EVER] ... (o_O) /////////////////////////////
////////////////////// Contact : molokoloco@gmail.com // CopyLeft  ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////*/


// ---------- CONFIG GENERALE -------------------- //
define('MLKLC', 1); // No Hack
define('SITE_CMS', true); // Site CMS = menu cms + rep cms
define('SITE_REF', true); // Active referencement dans rubrique
define('VAD_VERSION', 'v1.0 - 11/05/2008'); // Etes-vous a jour ? :)

$site_en_production = 0; // If "1" : Hide all debug -> db() + php.ini
$debug = 0; // Affiche toutes les requetes exécutées apres cette déclaration


// ---------- DEBUG PHP.INI -------------------- //
// die(phpinfo());
// @ini_set('error_reporting', E_ALL & ~E_NOTICE);
// @ini_set('display_errors', 'on'); 
// @ini_set('track_errors', '1'); ### echo $php_errormsg;
// @ignore_user_abort(true);
// @ini_set('magic_quotes_runtime', 0);
// @ini_set('memory_limit', 8000000);
// @set_time_limit(500);
// @ignore_user_abort(true);
// print_r(ini_get_all());
// @set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__).'/'.PATH_SEPARATOR.'../');
// print_r(apache_get_modules());


// ---------- INIT SESSION -------------------- //
session_start();


// ---------- WHERE I'AM -------------------- //
$selfPageName = basename($_SERVER['PHP_SELF']);
$selfPageQuery = (!empty($_SERVER['QUERY_STRING']) ? $selfPageName.'?'.$_SERVER['QUERY_STRING'] : $selfPageName );
$selfDir = $_SERVER['REQUEST_URI'];
$root = '../../'; // where the site root from here
$host = $_SERVER['HTTP_HOST'];
$hostIp = $_SERVER['SERVER_ADDR'];

function isClient() {
	global $hostIp;
	return ( $hostIp == 'XX.XXX.XXX.X' ? true : false );
}
function isLocal() {
	global $host;
	return ($host == '127.0.0.1' || $host == 'localhost') ? true : false;
}

$selfDirAdmin = ( strpos($selfDir,'/admin/') !== false ? true : false);

define('SITE_CONFIG', (isLocal() ? 'B2B' : 'B2BW')); // Unique ID for session config // No forget to change !!!

// ---------- SESSION DE CONFIGURATION -------------------- //
// ALL STATIC CONFIG HERE - MODIFICATION ALLOWED !!!
### $_SESSION[SITE_CONFIG]['WWW'] = NULL;

if (empty($_SESSION[SITE_CONFIG]['WWW'])) {
	// SQL CONFIG
	if (isOvh()) {
		$dbase = 'juss';
		$dbhost = 'localhost';
		$dblogin = 'xxxxxxxxx';
		$dbmotdepasse = 'xxxxxx';
		$convert = '/usr/bin/';
		$wwwRoot = $_SERVER['DOCUMENT_ROOT'].'/';
		$WWW = 'http://'.$host.'/';
	}
	elseif (isLocal()) { // Sites are in C:/www/
		$dbase = 'juss';
		$dbhost = 'localhost';
		$dblogin = 'root';
		$dbmotdepasse = '';
		$convert = NULL;// 'C:/localhost/ImageMagick/';
		list(,$firstRep,$secondRep) = explode('/',$_SERVER['PHP_SELF']);
		$wwwRoot = $_SERVER['DOCUMENT_ROOT'].'/'.$firstRep.'/'; //.$secondRep.'/';
		$WWW = 'http://'.$host.'/'.$firstRep.'/'; //.$secondRep.'/';
	}
	else die('Config Serveur non connue !');
	
	// ---------- FIRST FILL VARS WITH SESSION CONFIG -------------------- //
	if (!isset($_SESSION[SITE_CONFIG])) session_register(SITE_CONFIG);
	
	$_SESSION[SITE_CONFIG]['dbase'] 			= $dbase;
	$_SESSION[SITE_CONFIG]['dbhost'] 			= $dbhost;
	$_SESSION[SITE_CONFIG]['dblogin'] 			= $dblogin;
	$_SESSION[SITE_CONFIG]['dbmotdepasse'] 		= $dbmotdepasse;
	$_SESSION[SITE_CONFIG]['convert'] 			= $convert;
	$_SESSION[SITE_CONFIG]['root'] 				= $root;
	$_SESSION[SITE_CONFIG]['wwwRoot'] 			= $wwwRoot;
	$_SESSION[SITE_CONFIG]['WWW'] 				= $WWW;
	
	$_SESSION[SITE_CONFIG]['libRootDir'] 		= dirname(__FILE__);
	$_SESSION[SITE_CONFIG]['isUtf8'] 			= ($selfDirAdmin ? 0 : 0); // ($selfDirAdmin ? 0 : 1);
	$_SESSION[SITE_CONFIG]['encoding'] 			= 'ISO-8859-1'; // UTF-8 | ISO-8859-15
	$_SESSION[SITE_CONFIG]['langues'] 			= array(0=>'fr');
	$_SESSION[SITE_CONFIG]['lg'] 				= $_SESSION[SITE_CONFIG]['langues'][0];
	$_SESSION[SITE_CONFIG]['maxUploadSize'] 	= ceil(1048576 * 99);
	$_SESSION[SITE_CONFIG]['maxAttachements'] 	= 7;
	$_SESSION[SITE_CONFIG]['jpgquality'] 		= 85;
	$_SESSION[SITE_CONFIG]['rep'] 				= 'medias/';
	$_SESSION[SITE_CONFIG]['grand'] 			= 'grand/';
	$_SESSION[SITE_CONFIG]['medium'] 			= 'medium/';
	$_SESSION[SITE_CONFIG]['mini'] 				= 'mini/';
	$_SESSION[SITE_CONFIG]['repTemp'] 			= 'cache/';
	$_SESSION[SITE_CONFIG]['JS'] 				= '<script type="text/javascript">'.chr(13).chr(10).'// <![CDATA['.chr(13).chr(10);
	$_SESSION[SITE_CONFIG]['JSE'] 				= chr(13).chr(10).'// ]]>'.chr(13).chr(10).'</script>';
	$_SESSION[SITE_CONFIG]['TinyMceEditorDone'] = 0;
	$_SESSION[SITE_CONFIG]['allowedIp'] 		= array('127.0.0.1');
	$_SESSION[SITE_CONFIG]['allowedRef'] 		= array($WWW);
	
	$_SESSION[SITE_CONFIG]['extensionsImg'] 	= array('jpg', 'png', 'gif');
	$_SESSION[SITE_CONFIG]['extensionsVideo'] 	= array('mov', 'mpg', 'avi', 'wmv', 'wma', 'asf', 'wm', 'flv', 'rm', 'ram');
	$_SESSION[SITE_CONFIG]['extensionsMusique'] = array('mp3', 'wav', 'ogg');
	$_SESSION[SITE_CONFIG]['extensionsFlash'] 	= array('swf');
	$_SESSION[SITE_CONFIG]['extensionsDocument'] = array('psd', 'pdf', 'zip', 'doc', 'txt', 'xls', 'ppt', 'pps', 'xml', 'fla', 'exe');
	$_SESSION[SITE_CONFIG]['extensionsFiles'] 	= array_merge($_SESSION[SITE_CONFIG]['extensionsImg'], $_SESSION[SITE_CONFIG]['extensionsVideo'], $_SESSION[SITE_CONFIG]['extensionsMusique'], $_SESSION[SITE_CONFIG]['extensionsFlash'], $_SESSION[SITE_CONFIG]['extensionsDocument']); 
	$_SESSION[SITE_CONFIG]['allowedTypesImg'] 	= array('image/pjpeg', 'image/jpeg', 'image/jpg', 'image/gif', 'image/png', 'x-png');
	$_SESSION[SITE_CONFIG]['allowedTypesDocument'] = array('image/tiff', 'audio/mpeg', 'audio/x-wav', 'audio/x-ms-wma', 'audio/wav', 'audio/x-ms-wma', 'video/mpeg', 'video/mpg', 'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/avi', 'video/x-ms-asf', 'video/x-ms-wm', 'video/x-ms-wmv', 'video/x-flv', 'text/plain', 'text/xml', 'text/html', 'application/pdf', 'application/zip', 'application/x-zip-compressed', 'application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/excel', 'application/x-shockwave-fla', 'application/x-shockwave-flash', 'application/octet-stream', 'application/x-msdos-program');
	$_SESSION[SITE_CONFIG]['allowedTypesFiles'] = array_merge($_SESSION[SITE_CONFIG]['allowedTypesImg'], $_SESSION[SITE_CONFIG]['allowedTypesDocument']);
	//$_SESSION[SITE_CONFIG]['allowedHtml'] 	= array('b', 'strong', 'i', 'em', 'u', 'p', 'pre', 'center', 'div', 'br', 'table', 'tbody', 'tr', 'td', 'a', 'a href', 'img', 'hr', 'font', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'li', 'ol', 'ul', 'blockquote', 'embed', 'span', 'style');
}
extract($_SESSION[SITE_CONFIG], EXTR_OVERWRITE); 
	

// ---------- PHP.INI -------------------- //
$lifeSecTime = 90;
@set_time_limit($lifeSecTime);
@ini_set('session.gc_maxlifetime', $lifeSecTime);
@ini_set('memory_limit', $maxUploadSize);
@ini_set('post_max_size', $maxUploadSize);
@ini_set('upload_max_filesize', $maxUploadSize);
	
if ($site_en_production) @ini_set('display_errors', 'off'); 


// ---------- DROIT ADMIN -------------------- //
if (isLocal() && !isset($_SESSION[SITE_CONFIG]['ADMIN'])) { // AUTO CONNECT ADMIN IF LOCAL :) !!!!!!!!!!!!!!!!!!!!!!!!!!
	$_SESSION[SITE_CONFIG]['ADMIN']['id'] = 1; // !
	$_SESSION[SITE_CONFIG]['ADMIN']['type'] = 3;
}


// ---------- INCLUDES & CLASS -------------------- //
require($libRootDir.'/fonctions_debug.php');
require($libRootDir.'/fonctions.php');
require($libRootDir.'/fonctions_parse.php');
require($libRootDir.'/fonctions_files.php');
require($libRootDir.'/class/class_xml.php');
require($libRootDir.'/class/class_sql.php');
require($libRootDir.'/class/class_fonctions.php');
require($libRootDir.'/class/class_files.php');

if ($selfDirAdmin) {
	require($libRootDir.'/class/class_tinymce.php');
	require($libRootDir.'/class/class_admin_liste.php');
	require($libRootDir.'/class/class_admin_fiche.php');
	require($libRootDir.'/class/class_admin_bdd.php');
}

// ---------- MODULE_ID spécifiques pour les pages CMS -------------------- //
if (SITE_CMS) {
	require $libRootDir.'/class/class_arbo.php';
	@include $wwwRoot.'admin/cms_pages/cms_fonctions.php';
	
	$accueilPageTypeId = 1; // type_id si page d'accueil du site
	$redirtoChildPageTypeId = 2; // type_id si redirection sur le firstChild
	$redirtoLinkPageTypeId = 3;
	$cmsPageTypeId = 4;
	$conteneurPageTypeId = 5;
	
	$frontPageWidth = '620';
	$arrBiblioImages = array(mini=>'100x88888', medium=>'210x88888', grand=>'460x8888', tgrand=>'800x600');
}

// ---------- INFO FROM TABLE admin_parametres -------------------- //
###$_SESSION[SITE_CONFIG]['emailAdmin'] = '';
if (empty($_SESSION[SITE_CONFIG]['emailAdmin'])) {
	$AP =& new Q(" SELECT * FROM admin_parametres WHERE id='1' LIMIT 1 ");

	$_SESSION[SITE_CONFIG]['emailAdmin'] = $AP->V[0]['email'] != '' ? aff($AP->V[0]['email']) : 'molokoloco@gmail.com'; // Email contact
	$_SESSION[SITE_CONFIG]['pagination'] = $AP->V[0]['pagination'] > 0 ? $AP->V[0]['pagination'] : 10; // Pagination site
	
	$_SESSION[SITE_CONFIG]['noscript'] = false; // Alerte si erreur javascript

	// Referencement
	$_SESSION[SITE_CONFIG]['SITE'] = $AP->V[0]['meta_url_fr']; // Titre/url du site
	$_SESSION[SITE_CONFIG]['meta_titre'] = $AP->V[0]['meta_title_fr'];
	$_SESSION[SITE_CONFIG]['meta_description'] = $AP->V[0]['meta_desc_fr'];
	$_SESSION[SITE_CONFIG]['meta_key'] = implode(', ', explode("\r\n", $AP->V[0]['meta_key_fr']));
}
extract($_SESSION[SITE_CONFIG], EXTR_SKIP);

// ---------- admin_parametres DYN ADMIN -------------------- //
if ($selfDirAdmin) {
	// Menu de l'administration /////////////////////////////////
	$adminMenuCms = array(
		'', 'ARBORESCENCE',
			'cms_pages', '-Rubriques',
			'cms_pages/index.php?mode=cms', '-Pages CMS',
			'cms_blocs', '-Blocs de colonne',
	);
	$adminMenuAdmin = array(		
		'', 'MODULES',
			'mod_portofolio','-Portofolio',
			'mod_contact','-Contacts',
			'mod_prestations','-Prestations',

		'', 'PARAMETRES',
			'dat_bibliotheque_fichiers', '-Biblioth&egrave;que fichiers',
			'dat_bibliotheque_images', '-Biblioth&egrave;que images',
			'dat_trad_mots','-Traduction mots',
			'dat_trad_textes','-Traduction textes',
			'dat_email_alertes', '-Email alertes',

		'', 'CONFIGURATION',
			'admin_recherche', '-Rechercher',
			'admin_parametres', '-Param&egrave;tres',
			'admin_utilisateurs','-Utilisateurs',
	);
	$adminMenuDev = array(
		'', 'DEV PHP',
			'cms_pages_types', '-Types de page',
			'cms_elements_types', '-Types d\'&eacute;l&eacute;ment',
	);
	
	// Affichage du menu /////////////////////////////////
	if (SITE_CMS) $adminMenu = array_merge($adminMenuCms, $adminMenuAdmin);
	else $adminMenu = $adminMenuAdmin;
	if (isLocal()) $adminMenu = array_merge($adminMenu, $adminMenuDev);

	// Admin config... /////////////////////////////////
	if (!is_dir($wwwRoot.$rep)) createRep($wwwRoot.$rep); // Rep Medias dyn

	if (empty($_SESSION[SITE_CONFIG]['AP'])) {
		$AP =& new Q(" SELECT * FROM admin_parametres WHERE id='1' LIMIT 1 ");
		$_SESSION[SITE_CONFIG]['AP'] = $AP->V[0];
	}
	
	$logoc = aff($_SESSION[SITE_CONFIG]['AP']['logoc']);
	if ($logoc == '') $logoc = '<img src="../images/virtual_admin.gif" width="75" height="75" border="0">'; // Logo Client
	else $logoc = '<img src="'.$root.$rep.'parametres/'.$logoc.'" border="0">';
	$logoa = aff($_SESSION[SITE_CONFIG]['AP']['logoa']);
	if ($logoa == '') $logoa = '<img src="../images/virtual_admin.gif" alt="ADMIN '.$vad_version.'" border="0">'; // Logo Adminproxitek_logo.gif
	else $logoa = '<img src="'.$root.$rep.'parametres/'.$medium.$logoa.'" alt="ADMIN '.$vad_version.'" border="0">';
	
	$paginationa = aff($_SESSION[SITE_CONFIG]['AP']['paginationa']); if ($paginationa < 1) $paginationa = '50'; // Pagination admin
	$fontcolor1 = aff($_SESSION[SITE_CONFIG]['AP']['fontcolor1']); if ($fontcolor1 == '') $fontcolor1 = '#FFFFFF'; // Couleur police titre
	$fontcolor2 = aff($_SESSION[SITE_CONFIG]['AP']['fontcolor2']); if ($fontcolor2 == '') $fontcolor2 = '#666666'; // Couleur police texte
	$linkcolor = aff($_SESSION[SITE_CONFIG]['AP']['linkcolor']); if ($linkcolor == '') $linkcolor = '#016AC5'; // Couleur lien
	$linkcoloron = aff($_SESSION[SITE_CONFIG]['AP']['linkcoloron']); if ($linkcoloron == '') $linkcoloron = '#FF0000'; // Couleur lien survol
	$bgcolor1 = aff($_SESSION[SITE_CONFIG]['AP']['bgcolor1']); if ($bgcolor1 == '') $bgcolor1 = '#FFFFFF'; // Couleur du fond 1
	$bgcolor2 = aff($_SESSION[SITE_CONFIG]['AP']['bgcolor2']); if ($bgcolor2 == '') $bgcolor2 = '#999999'; // Couleur du fond 2
	$ligneentete = aff($_SESSION[SITE_CONFIG]['AP']['ligneentete']); if ($ligneentete == '') $ligneentete = '#BBBBBB'; // Couleur ligne entete
	$ligne1 = aff($_SESSION[SITE_CONFIG]['AP']['ligne1']); if ($ligne1 == '') $ligne1 = '#E4E4E4'; // Couleur ligne 1
	$ligne2 = aff($_SESSION[SITE_CONFIG]['AP']['ligne2']); if ($ligne2 == '') $ligne2 = '#F4F4F4'; // Couleur ligne 2
	$ligneon = aff($_SESSION[SITE_CONFIG]['AP']['ligneon']); if ($ligneon == '') $ligneon = '#FFFFFF'; // Couleur ligne survol

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
			include $libRootDir.'/class/class_lang_detect.php';
			$lv = new detect_language();
			$langueVisiteur = $lv->getLanguage();
			if (in_array($langueVisiteur, $langues)) $lg = $langueVisiteur;
			setMyCookie('lg', $lg);
			$_SESSION[SITE_CONFIG]['lg'] = $lg;
		}
		else $lg = $_SESSION[SITE_CONFIG]['lg'];
	}

	// ---------- CONNECTION DES MEMBRES -------------------- //
	function initClientSession($clients_id, $cook=0) {
		if (isset($_SESSION[SITE_CONFIG]['CLIENT'])) $_SESSION[SITE_CONFIG]['CLIENT'] = NULL;
		$P =& new Q(" SELECT * FROM mod_clients WHERE id='$clients_id' AND actif='1' LIMIT 1 ");
		if (count($P->V) < 1) return alert('D&eacute;sol&eacute;, votre compte est d&eacute;sactiv&eacute;', 'back');
		$_SESSION[SITE_CONFIG]['CLIENT'] = $P->V[0];
		if ($cook) { // If cookie
			$ids = genPass().genPass().genPass();// Regenerate IDS...
			$U = new Q();
			$U->updateSql('mod_clients', array('ids'=>$ids)," id='$client_id' LIMIT 1");
			$cookie_val = serialize(array(
				'client_id' => $client_id,
				'ids' => $ids
			));
			setMyCookie('CLIENTIDS', $cookie_val);
		}
	}

	// ---------- AUTO-CONNECT COOKIE MEMBRE ? -------------------- //
	if (!isset($_SESSION[SITE_CONFIG]['CLIENT']['id']) && isset($_COOKIE['CLIENTIDS'])) {
		$c = @unserialize(getMyCookie('CLIENTIDS'));
		$clients_id = $c['clients_id'];
		$ids = $c['ids'];
		$P =& new Q(" SELECT id FROM mod_ec_auteurs WHERE id='$clients_id' AND ids='$ids' AND actif='1' LIMIT 1 ");
		if ($P->V[0]['id'] > 0) initClientSession($clients_id);
		else delMyCookie('CLIENTIDS'); // Del bad Cook
	}

	// ---------- GRAD SOME COMMON VARS ----------//
	$menu = 	gpc('menu');
	$action = 	gpc('action');
	$id = 		gpc('id');
	$type = 	gpc('type');
	$rid = 		gpc('rid');
	$goto = 	gpc('goto');
}
if (count($langues) > 1) {
	// Include constantes de langue
	if (is_file($libRootDir.'/langues/lang_mots_'.$lg.'.php')) require $libRootDir.'/langues/lang_mots_'.$lg.'.php';  
	if (is_file($libRootDir.'/langues/lang_textes_'.$lg.'.php')) require $libRootDir.'/langues/lang_textes_'.$lg.'.php';
}
?>