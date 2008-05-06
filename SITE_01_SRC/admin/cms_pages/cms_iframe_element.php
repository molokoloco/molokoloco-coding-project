<?
require('../lib/racine.php');
require_once('cms_fonctions.php');

$rubrique_id = gpc('id');
$element_id = gpc('element_id');
$element_langue = $_SESSION[SITE_CONFIG]['element_langue'];

$myHtml = getCmsHtml($rubrique_id, $element_id, $element_langue);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Element(s) de page CMS</title>

	<link href="../../css/fr/styles.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../../cms/css/cms.css.php" type="text/css" />
	<style type="text/css">
		#cms {
			width:<?=$frontPageWidth;?>px;
		}
	</style>
	<script type="text/javascript" src="../init.js"></script>

	<!-- FUCKING LIGHT WINDOW FREEZE WINDOW HERE :-/ -->
	<!--<link href="../../css/common/lightwindow.css" rel="stylesheet" type="text/css" />-->
	<!--<script type="text/javascript" src="../../js/plug/lightwindow.js"></script>-->

	<script type="text/javascript">
		var resizeMyFrame = function() {
			var id = 'iframe_element';
			if (!parent.document.getElementById(id)) return;
			var pFrame = parent.document.getElementById(id);
			var yScroll = 500; // taille par defaut si echec fonction
			try {
				if (pFrame.contentDocument && pFrame.contentDocument.body.scrollHeight) yScroll = pFrame.contentDocument.body.scrollHeight;
				else if (pFrame.document.body.scrollHeight) yScroll = pFrame.Document.body.scrollHeight;
				else if (pFrame.offsetHeight) yScroll = pFrame.offsetHeight;
			}
			catch(e) {}
			if (yScroll < 30) yScroll = 30; // Min
			yScroll += 40; // Marge
			pFrame.height = yScroll+'px';
		};
	</script>

</head>
<body>

<?=$myHtml;?>

<?
js("
	$$('a.lightwindow').each(function(e) {
		href = e.getAttribute('href');
		e.onclick = function () { popImg(href); return false; };
	});
	
	resizeMyFrame();
	Event.observe(window, 'load', resizeMyFrame, false);
");
?>
</body>
</html>