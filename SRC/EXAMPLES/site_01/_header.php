<? 

require('admin/lib/racine.php');
//header('Content-Type: text/html; charset=utf-8');

// ------------------------- VARS (FROM HTACCESS) ----------------------------------//
$ajax = intval(gpc('ajax'));

$prestation_id = intval(gpc('prestation_id'));
$reference_id = intval(gpc('reference_id'));

$action = gpc('action');
if ($action != '') require('_actions.php'); // ACTIONS ?
if (isset($_GET['noscript'])) $_SESSION[SITE_CONFIG]['noscript'] = ( $_GET['noscript'] == 1 ? true : false );


// ------------------------- FETCH ARRAY ALL ARBO ----------------------------------//
$S =& new ARBO();
$S->fields = array('id','ordre','pid','type_id','lien_'.$lg,'menu','titre_'.$lg,'titre_page_'.$lg,'meta_titre_'.$lg,'meta_url_'.$lg,'meta_description_'.$lg,'meta_key_'.$lg);
$S->buildArbo();
###db($S); // Toutes l'arbo
###db($S->arbo[$S->rid]);
###db($S->arbo[$S->getRidByType(1)]);

//require_once('admin/lib/fonctions_cms.php'); // CMS CONTENT
//echo getCmsHtml(6, 0);
//die();

// $catalogue_url = $S->arbo[$S->getRidByType(6)]['url'];

//db($S->arbo[$S->rid]);
// ------------------------- GET BASE PAGE CONFIG ----------------------------------//

if (!empty($S->arbo[$S->rid]['meta_titre_'.$lg])) 			$meta_titre = $S->arbo[$S->rid]['meta_titre_'.$lg];
elseif (!empty($S->arbo[$S->arid]['meta_titre_'.$lg]))		$meta_titre = $S->arbo[$S->arid]['meta_titre_'.$lg];

if (!empty($S->arbo[$S->rid]['meta_description_'.$lg]))		$meta_description = $S->arbo[$S->rid]['meta_description_'.$lg];
elseif (!empty($S->arbo[$S->arid]['meta_description_'.$lg])) $meta_description = $S->arbo[$S->arid]['meta_description_'.$lg];

if (!empty($S->arbo[$S->rid]['meta_key_'.$lg])) 			$meta_key = $S->arbo[$S->rid]['meta_key_'.$lg];
elseif (!empty($S->arbo[$S->arid]['meta_key_'.$lg]))		$meta_key = $S->arbo[$S->arid]['meta_key_'.$lg];

$titre = $S->arbo[$S->rid]['titre_'.$lg];
$titre_page = $S->arbo[$S->rid]['titre_page_'.$lg];
if (empty($meta_titre)) $meta_titre = $titre_page;

// Valeurs par defaut et infos complmentaires CMS
// $R =& new Q("SELECT * FROM cms_pages WHERE id='{$S->rid}' LIMIT 1 ");



$arr_Acc = array();
$infoScroll = '';
$A = new Q("SELECT * FROM mod_prestations WHERE actif='1' ORDER BY ordre DESC");
foreach($A->V as $V) { 
	$str = aff($V['texte']);
	$str = str_replace('&nbsp;', ' ', $str);
	$str = preg_replace('(\\n)', '', $str);
	$str = str_replace('<br />', '#', $str);
	$str = trim(strip_tags($str));
	$str = preg_replace('/[\\n]{2,}/', "\n", $str);
	$arr_Acc[] = array(
		'goto' => urlRewrite($V['titre'], 'r'.$S->getRidByType(($V['type']==1?'10':'6')).'-p'.$V['id']),
		'titre' => htmlentities(aff($V['titre'])),
		'texte' => $str
	);
	$infoScroll .= '<p>'.htmlentities(aff($V['titre']))."</p>\n";
	$infoScroll .= aff($V['texte'])."\n";
}
$js_arr_Acc = getScriptBiArray('arrAccroche', $arr_Acc);


if ($ajax != 1) { // !!!

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?=$lg;?>" xml:lang="<?=$lg;?>">
<head>
	<title><?=html(aff($meta_titre));?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<meta http-equiv="imagetoolbar" content="no"/>
	<meta http-equiv="content-language" content="<?=$lg;?>"/>
	<title><?=str_replace('&amp;#9734;', '☆', html(aff($meta_titre)));?></title>
	<meta name="description" content="<?=html(aff($meta_description));?>"/>
	<meta name="keywords" content="<?=html(aff($meta_key));?>"/>
	<meta name="robots" content="index, follow, all"/>
	<meta name="revisit_after" content="7Days"/>
	<meta name="identifier-url" content="<?=$WWW;?>"/>
	<meta name="author" content="www.borntobeweb.fr | Julien Gu&eacute;zennec 2008"/>	
	<meta name="country" content="France"/>
	<meta name="language" content="Franais"/>
	<meta name="copyright" content="copyright"/>
	<meta name="coverage" content="Worldwide"/>
	<link rel="alternate" type="application/rss+xml" href="<?=$WWW;?>photos.rss" title="My Piclens Galerie - http://piclens.com/" id="gallery" />
	<link rel="alternate" type="application/rss+xml" href="<?=$WWW;?>rss.php" title="Les derniers travaux" />
	<link rel="icon" href="<?=$WWW;?>favicon.ico" />
	<link rel="shortcut icon" type="image/icon" href="<?=$WWW;?>favicon.ico" />
	<link href="<?=$WWW;?>css/styles_noprint.css" rel="stylesheet" type="text/css" media="print"/>
	<link href="<?=$WWW;?>css/styles.css" rel="stylesheet" type="text/css" media="all"/>
	<link href="<?=$WWW;?>css/menu.css" rel="stylesheet" type="text/css" media="all"/>
	<link href="<?=$WWW;?>cms_files/css/cms.css" rel="stylesheet" type="text/css" media="all"/>
	<link href="<?=$WWW;?>js/lightwindow/css/lightwindow.css" rel="stylesheet" type="text/css" media="screen"/>
	<script src="<?=$WWW;?>js/proto/init.js" type="text/javascript"></script>
	<script src="<?=$WWW;?>js/lightwindow/javascript/lightwindow.js" type="text/javascript"></script>
	<script src="<?=$WWW;?>js/reflex/reflex.js" type="text/javascript"></script>
	<!--<script type="text/javascript" src="http://lite.piclens.com/current/piclens.js"></script>-->
	<? js($js_arr_Acc); ?>
	
</head>
<body>
<div style="display:inline"><a name="TOP" id="TOP"></a></div>
<div id="divNode" style="display:none;"></div>
<? } ?>