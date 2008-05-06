<? 
// JAVASCRIPTING ;)
### $_SESSION[SITE_CONFIG]['testNoscript'] = NULL;
if (!isset($_SESSION[SITE_CONFIG]['testNoscript'])) {
	$_SESSION[SITE_CONFIG]['testNoscript'] = 1;
	$nav = getNav();
	if (!$nav->cookies) $_SESSION[SITE_CONFIG]['nocookie'] = true;
	if (!$nav->javascript) $_SESSION[SITE_CONFIG]['noscript'] = true;
	
	js("
	Event.observe(window, 'load', function() { printInfo('Changement de programme...<br /><br />Je ne suis plus en mesure d\'accepter aucun projet, suite &agrave; mon embauche &agrave; la Direction des Nouvelles Technologies de Bouygues Telecom, en tant que d&eacute;veloppeur Web 2.0<br /><br />Keep the good work o_O<br /><br /><br /><div style=\"text-align:right;\">Julien G</div>'); });
	");
}
if ($_SESSION[SITE_CONFIG]['noscript']) {
	?><p><b>Attention, votre navigateur ne supporte pas l'usage de JavaScript<br />Ce site risque de ne pas fonctionner !</b></p><?
}

// SESSION INFOS (_actions.php)
if ($_SESSION[SITE_CONFIG]['info'] != '') {
	js('Event.observe(window, \'load\', function() { printInfo("'.str_replace('"','\"',$_SESSION[SITE_CONFIG]['info']).'"); });');
	$_SESSION[SITE_CONFIG]['info'] = NULL;
}

if ($scriptAlert != '') echo $scriptAlert; 

// Stats
if (!isLocal()) {
	?><script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
	<script type="text/javascript">_uacct = "UA-1944677-3";urchinTracker();</script><?
}

?>
</body>
</html>