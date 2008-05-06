<h1>Pr&eacute;sentation...</h1>

<div style="padding:10px 10px 0 10px;width:180px;float:left;">
	<div id="conception">
		<img src="images/conception.png" alt="BornToBeWeb.fr - Conception de sites Internet - Julien Guézennec { Molokoloco }" width="128" height="128"/>
	</div>
	<script type="text/javascript">
		swfobject.embedSWF("swf/conception.swf", "conception", "128", "128", "8.0.0", "", {}, {wmode: "transparent"}, {});
	</script>
	<h2>Conception</h2>
	<p>Simple site  <strong>vitrine de votre entreprise</strong> ou site de <strong>e-commerce s&eacute;curis&eacute;</strong>, je guide <strong>votre projet</strong> selon vos besoins</p>
</div>
<div style="padding:10px 10px 0 10px;width:180px;float:left;">
	<p><img src="images/realisations.png" alt="BornToBeWeb.fr - r&eacute;alisation de sites Internet - Julien Guézennec { Molokoloco }" width="128" height="128"/></p>
	<h2>R&eacute;alisation</h2>
	<p>G&eacute;rez facilement votre site Internet (<strong>applications &agrave; la demande</strong>) gr&acirc;ce &agrave; une <strong>administration simple</strong> et intuitive</p>
</div>
<div style="padding:10px 10px 0 10px;width:180px;float:left;">
	<div id="caddie">
		<img src="images/evolution.png" alt="BornToBeWeb.fr - &Eacute;volution de sites Internet - Julien Guézennec { Molokoloco }" width="128" height="128"/>
	</div>
	<script type="text/javascript">
		swfobject.embedSWF("swf/caddie.swf", "caddie", "128", "128", "8.0.0", "", {}, {wmode: "transparent"}, {});
	</script>
	<h2>&Eacute;volution</h2>
	<p>Construit de mani&egrave;re <strong>modulaire</strong>, votre site <strong>Web&nbsp;2.0</strong>. permet une grande souplesse d'&eacute;volution</p>
</div>

<div class="breaker">&nbsp;</div>

<h2>Travailleur ind&eacute;pendant</h2>

<p><a href="<?=$S->arbo[$S->getRidByType(8)]['url'];?>" title="Julien Gu&eacute;zennec Freelance d&eacute;veloppement Internet LAMP"><img src="images/julien-guezennec.png" alt="Molokoloco" width="128" height="128" border="0" class="right" /></a><strong><a href="cv.php">D&eacute;veloppeur Internet</a></strong> depuis 10 ans, dont 3 ans en agence (<a href="http://www.agence-clark.com/">Agence Clark</a>, Paris), je suis aujourd'hui travailleur ind&eacute;pendant (auteur de logiciel affili&eacute; &agrave; l'<acronym title="Association pour la GEstion de la S&eacute;curit&eacute; Sociale des Auteurs">AGESSA</acronym>).<br />
<br />
Fort de l'exp&eacute;rience acquise en programmation (<acronym title="Linux, Apache, mySQL, PHP">LAMP</acronym>) et en gestion de projet, j'ai acquis une grande souplesse dans les projets &agrave; r&eacute;aliser. Je dispose aussi de mes propres <strong>librairies de d&eacute;veloppement</strong> (Php, Flash, Javascript).<br />
<br />
Au besoin, je <strong>travaille en collaboration</strong> avec des directeurs artistiques, r&eacute;f&eacute;renceurs, r&eacute;dacteurs, travailleurs ind&eacute;pendants et soci&eacute;t&eacute;s.<br />
J'administre un <strong>serveur d&eacute;di&eacute;</strong>, chez <a href="http://www.ovh.com" target="_blank">OVH</a>, pour l'h&eacute;bergement des sites.</p>
<p>Simple envie d'assurer votre <strong>visibilit&eacute; sur Internet</strong> ou grand <strong>projet de  communication</strong>... vous ne serez pas d&eacute;&ccedil;u !</p>
<p><strong><a href="<?=$S->arbo[12]['url'];?>">Contactez-moi</a>, </strong>et nous &eacute;tudierons votre projet ensemble.</p>

<div class="breaker">&nbsp;</div>

<h1>Expertise &amp; services...</h1>
<div class="float50">
	<ul>
		<?
		$P = new Q("SELECT * FROM mod_prestations WHERE type='1' AND actif='1' ORDER BY ordre DESC LIMIT 7");
		foreach($P->V as $V) {
			?><li><a href="<?=urlRewrite($V['titre'], 'r'.$S->getRidByType(10).'-p'.$V['id']);?>"><?=htmlentities(aff($V['titre']));?></a></li><?
		}
		?>
	</ul>
</div>
<div class="float50">
	<ul>
		<?
		$P = new Q("SELECT * FROM mod_prestations WHERE type='0' AND actif='1' ORDER BY ordre DESC LIMIT 7");
		foreach($P->V as $V) {
			?><li><a href="<?=urlRewrite($V['titre'], 'r'.$S->getRidByType(6).'-p'.$V['id']);?>"><?=htmlentities(aff($V['titre']));?></a></li><?
		}
		?>
	</ul>
</div>

<div class="spacer"></div>

<? require '_form_contact.php'; ?>