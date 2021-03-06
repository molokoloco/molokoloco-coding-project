<?php require('admin/lib/racine.php');
ob_start();

// PARAMETRES ////////////////////////////////////////////////////////////////////
$maxItems = 15;


// CHANNEL ////////////////////////////////////////////////////////////////////
$rssChannel = array(
	'title' => $SITE,
	'description' => 'Les derniers travaux BornToBeWeb.fr ['.$WWW.']',
	'category' => 'Julien Gu�zennec - R�f�rences',
	'link' => $WWW,
	'language' => $lg,
	//'pubDate' => date("D, d M Y H:i:s").' GMT'
);

$S =& new ARBO();
$S->fields = array('id','ordre','pid','type_id','lien_fr', 'menu', 'titre_fr','titre_page_fr','meta_titre_fr','meta_url_fr','meta_description_fr','meta_key_fr');
$S->buildArbo();

// REQUETE ////////////////////////////////////////////////////////////////////
$G = new Q("SELECT * FROM mod_portofolio WHERE actif='1' ORDER BY date DESC LIMIT $maxItems ");

// ITEMS ////////////////////////////////////////////////////////////////////
$rssItems = array();
foreach ($G->V as $V) {
	$rssItems[] = array(
		'guid isPermaLink="false"' => aff($V['url']).' | ID '.$V['id'],
		'title' => aff($V['titre']).' : '.aff($V['url']),
		'pubDate' => sqlDateToRss($V['date']),
		'description' => '<a href="'.$V['url'].'" target="_blank"><img src="'.$WWW.'medias/portofolio/mini/'.$V['visuel'].'" alt="'.$V['url'].'" border="0" align="left" hspace="4"/></a> '.cs(stripTags(aff($V['texte'])), 500),
		'link' => $WWW.urlRewrite($V['titre'], 'r'.$S->getRidByType(8).'-r'.$V['id']),
		'image' => array('title' => affCleanName($V['visuel']), 'url' => $WWW.'portofolio/grand/'.aff($V['visuel']), 'link' => $WWW.urlRewrite($V['titre'], 'r'.$S->getRidByType(8).'-r'.$V['id'])),
	);
}

// PRINT ////////////////////////////////////////////////////////////////////
$rssChannel['items'] = $rssItems;
echo parseArrToRss($rssChannel);

$feed = ob_get_contents();
ob_end_clean();
header('Content-Type: text/xml; charset=iso-8859-1');
echo $feed;

?>