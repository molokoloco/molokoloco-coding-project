<? header("Content-type: text/css"); require_once('../../admin/lib/racine.php'); ?>
/* ///////////////// GLOBALS CMS ///////////////// */
#cms {
	text-align:left;
	font-size:12px;
}
#cms a, #cms a:visited, #cms a:active {}
#cms a:hover {}
#cms h1, h2, h3, h4, h5, h6 {}
#cms ul{}
#cms li{}

/* ///////////////// WYSIWYG ADMIN ///////////////// */
.titre_rouge {
	color: #CD0C1C;
	background:#e0ded7;
	marging:4px;
	padding:4px;
	font-weight:bold;
	text-transform:uppercase;
	font-size:13px;
}

/* ///////////////// UTILITIES ///////////////// */
.centre { text-align:center; }
.gauche { float:left; margin:0 20px 2px 0; }
.droite { float:right; margin:0 0 2px 20px; }
.block { display:block; clear:right; }
.inline { display:inline; }

/* ///////////////// ALL PORTLETS ///////////////// */
<? 
$dir = $wwwRoot.'cms_files/css/portlets/';
$filesRep = getFile($dir, 'file');
foreach((array)$filesRep as $cssFile) {
	if (strpos($cssFile, '.css') !== false && strpos($cssFile, 'BAK') === false) {
		require($dir.$cssFile);
		echo chr(13).chr(10);
	}
}
?>