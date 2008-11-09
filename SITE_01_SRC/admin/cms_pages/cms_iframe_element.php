<?
require('../lib/racine.php');
require_once('cms_fonctions.php');

$rubrique_id = gpc('id');
$element_id = gpc('element_id');
$element_langue = $_SESSION[SITE_CONFIG]['element_langue'];

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Element(s) de page CMS</title>

	<link href="../../css/styles.css" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="../../cms_files/css/cms.css.php" type="text/css" />
	<style type="text/css">
		#cms { width:<?=$frontPageWidth;?>px; }
	</style>
	<script type="text/javascript" src="../init.js"></script>

	<!-- LIGHT WINDOW FREEZE WINDOW HERE :-/ -->
	<!--<link href="../../css/common/lightwindow.css" rel="stylesheet" type="text/css" />-->
	<!--<script type="text/javascript" src="../../js/plug/lightwindow.js"></script>-->

</head>
<body onload="parent.client.frameResize('iframe_element');">
<?
	echo getCmsHtml($rubrique_id, $element_id, $element_langue);
	
	js("
		<!-- LIGHT WINDOW FREEZE WINDOW HERE :-/ -->
		$$('a.lightwindow').each(function(e) {
			href = e.getAttribute('href');
			e.onclick = function () { popImg(href); return false; };
		});
	");
?>
</body>
</html>