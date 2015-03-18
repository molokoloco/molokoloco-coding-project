_Library edited from 2005 to 2007..._ Framework here : [trunk/SITE\_01\_SRC/admin/lib/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fadmin%2Flib) et ici [trunk/SITE\_01\_SRC/admin/lib/class/](http://code.google.com/p/molokoloco-coding-project/source/browse/trunk/SITE_01_SRC#SITE_01_SRC%2Fadmin%2Flib%2Fclass)

# ./rss.php #

```

<?php require('admin/lib/racine.php');
ob_start();

// PARAMETRES ////////////////////////////////////////////////////////////////////
$maxItems = 15;


// CHANNEL ////////////////////////////////////////////////////////////////////
$rssChannel = array(
	'title' => $SITE,
	'description' => 'Les derniers travaux BornToBeWeb.fr ['.$WWW.']',
	'category' => 'JG - Portofolio',
	'link' => $WWW,
	'language' => $lg,
	//'pubDate' => date("D, d M Y H:i:s").' GMT'
);

$S =& new ARBO();
$S->fields = array('id','ordre','pid','type_id','lien_fr');
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

```

# ./admin/lib/fonctions\_parse.php #

```

//...

// Tabulation ---------------------------------------------
function t($count) {
	return str_repeat(chr(9), $count);
}

// RSS node tree ---------------------------------------------
function parseArrToRss($rssChannel, $rssPath='', $encoding='iso-8859-1') {
	
	if (!is_array($rssChannel)) return FALSE;
	
	$rss = '<?xml version="1.0" encoding="'.$encoding.'"?>'.chr(13).chr(10);
	$rss .= '<rss version="2.0">'.chr(13).chr(10);
	$rss .= t(1).'<channel>'.chr(13).chr(10);
	
	foreach($rssChannel as $key=>$value) {
		if ($key == 'items') continue;
		$rss .= t(2).'<'.$key.'>'.cleanRss($value, 1, true).'</'.$key.'>'.chr(13).chr(10);
	}
	
	foreach($rssChannel['items'] as $rssItem) { 
		$rss .= t(2).'<item>'.chr(13).chr(10);
		foreach($rssItem as $key=>$value) {
			list($keyEnd) = explode(' ', $key);
			if (!is_array($value)) $rss .= t(3).'<'.$key.'>'.cleanRss($value,1).'</'.$keyEnd.'>'.chr(13).chr(10);
			else {
				$rss .= t(3).'<'.$key.'>'.chr(13).chr(10);
				foreach($value as $k=>$val)  $rss .= t(4).'<'.$k.'>'.cleanRss($val,1).'</'.$k.'>'.chr(13).chr(10);
				$rss .= t(3).'</'.$keyEnd.'>'.chr(13).chr(10);
			}
		}
		$rss.= t(2).'</item>'.chr(13).chr(10);
	}
	$rss .= t(1).'</channel>'.chr(13).chr(10);
	$rss .= '</rss>'.chr(13).chr(10);
	if ($rssPath) return writeFile($rssPath, $rss);
	else return $rss;
}

//...
```

http://molokoloco-coding-project.googlecode.com/svn-history/r94/trunk/SITE_01_SRC/admin/lib/fonctions_parse.php