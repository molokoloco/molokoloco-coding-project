<h1>Pr&eacute;sentation...</h1>

<div>
	<div id="conception">
		<img src="images/conception.png" alt="BornToBeWeb.fr - Conception de sites Internet - Julien G { Molokoloco }" width="128" height="128"/>
	</div>
	<script type="text/javascript">
		swfobject.embedSWF("swf/conception.swf", "conception", "128", "128", "8.0.0", "", {}, {wmode: "transparent"}, {});
	</script>
	<h2>Conception</h2>
	<p>Simple site  <strong>vitrine de votre entreprise</strong> ou site de <strong>e-commerce s&eacute;curis&eacute;</strong>, je guide <strong>votre projet</strong> selon vos besoins</p>
</div>

<div class="spacer">&nbsp;</div>

<h1>Expertise &amp; services...</h1>
<div>
	<ul>
		<?
//		$P = new Q("SELECT * FROM mod_prestations WHERE type='1' AND actif='1' ORDER BY ordre DESC LIMIT 7");
//		foreach($P->V as $V) {
//			?><li><a href="<?=urlRewrite($V['titre'], 'r'.$S->getRidByType(10).'-p'.$V['id']);?>"><?=htmlentities(aff($V['titre']));?></a></li><?
//		}
		?>
	</ul>
</div>

<div class="spacer"></div>

<? require '_form_contact.php'; ?>