<? 
include_once("../menu/menu.php"); ?><?

/*if (SITE_CMS) {
	switch ($goto) {
		case 'rub' : require("../rubriques/fiche.php"); break;
		//case 'contenu' : require("../rubriques/contenu.php"); break;
		case 'liste' : require("../rubriques/liste.php"); break;
		case 'propose' : require("../rubriques/liste_proposition.php"); break;
		case 'listeprop' : require("../rubriques/index.php"); break;
		case 'prop_visu' : require("../rubriques/propositions/index.php"); break;
		case 'prop_ligne' : require("../rubriques/propositions/passage.php"); break;
		default: require("../rubriques/liste.php"); break;
	}

	$_SESSION[SITE_CONFIG]['info'] = NULL;
}
else {*/
	?><table width="100%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td height="23" class="table-titre">INDEX</td>
	<td width="67%" class="table-titre2">&nbsp;</td>
	</tr>
	</table>
	<table width="100%" border="0" cellspacing="0" cellpadding="15">
	<tr>
	<td align="center" ><b><span class="texte">Bienvenue sur votre interface d'administration !</span></b>  <p align="left" class="titre"><br />
	&nbsp;<br /></p>
	<table width="50%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td><p tyle="text-align:justify;" class="texte">TIPS ! - TIPS ! - TIPS ! - TIPS ! - TIPS ! - TIPS ! - TIPS ! - TIPS !</span>
	<p style="text-align:justify;"><span class="sstitre">Formats d'image pour l'upload :</span> <span class="texte">JPG, GIF ou .PNG<br />
	<span class="sstitre">Tailles d'image pour l'upload :</span> Les images sont automatiquement mises &agrave; la bonne taille apr&egrave;s l'upload.<br />
	Cependant, afin de garder une bonne qualit&eacute; tout en ayant un temps d'upload le plus court possible, nous conseillons une taille entre <b>800</b> et 2000 pixels maximum. Dans tout les cas, le poids de vos images ne doit pas d&eacute;passer 1 ou 2 MO (300/500 KiloOctets sont amplement suffisant) <br />
	<span class="sstitre">Qualit&eacute; de compression pour le format JPG :</span> Les images sont automatiquement reconverties, suite au redimensionnement, avec la compression la plus ad&eacute;quate. Nous conseillons donc un export de vos JPG avant l'upload avec une assez bonne compression pour &eacute;viter
	trop de d&eacute;t&eacute;rioration. <b>80&nbsp;sur&nbsp;100</b> semble un bon compromis.<br>
	</span></p></td>
	</tr>
	</table></td>
	</tr>
	</table><?
//} 
?><? include_once("../menu/menu_bas.php"); ?>