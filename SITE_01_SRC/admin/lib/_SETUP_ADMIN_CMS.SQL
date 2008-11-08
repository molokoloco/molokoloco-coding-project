-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Samedi 08 Novembre 2008 à 17:37
-- Version du serveur: 5.0.27
-- Version de PHP: 5.2.0
-- 
-- Base de données: `virtual`
-- 

-- --------------------------------------------------------

-- 
-- Structure de la table `admin_parametres`
-- 

DROP TABLE IF EXISTS `admin_parametres`;
CREATE TABLE `admin_parametres` (
  `id` int(8) NOT NULL auto_increment,
  `email` varchar(250) default NULL,
  `meta_title_fr` varchar(65) default NULL,
  `meta_title_uk` varchar(65) default NULL,
  `meta_url_fr` varchar(250) default NULL,
  `meta_url_uk` varchar(250) default NULL,
  `meta_desc_fr` text,
  `meta_desc_uk` text,
  `meta_key_fr` text,
  `meta_key_uk` text,
  `logoc` varchar(70) default NULL,
  `logoa` varchar(70) default NULL,
  `paginationa` tinyint(3) default NULL,
  `pagination` tinyint(3) default NULL,
  `fontcolor1` varchar(7) NOT NULL default '#FFFFFF',
  `fontcolor2` varchar(7) NOT NULL default '#666666',
  `linkcolor` varchar(7) NOT NULL default '#016AC5',
  `linkcoloron` varchar(7) NOT NULL default '#FF0000',
  `bgcolor1` varchar(7) NOT NULL default '#FFFFFF',
  `bgcolor2` varchar(7) NOT NULL default '#999999',
  `ligneentete` varchar(7) NOT NULL default '#BBBBBB',
  `ligne1` varchar(7) NOT NULL default '#E4E4E4',
  `ligne2` varchar(7) NOT NULL default '#F4F4F4',
  `ligneon` varchar(7) NOT NULL default '#FFFFFF',
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `admin_parametres`
-- 

INSERT INTO `admin_parametres` (`id`, `email`, `meta_title_fr`, `meta_title_uk`, `meta_url_fr`, `meta_url_uk`, `meta_desc_fr`, `meta_desc_uk`, `meta_key_fr`, `meta_key_uk`, `logoc`, `logoa`, `paginationa`, `pagination`, `fontcolor1`, `fontcolor2`, `linkcolor`, `linkcoloron`, `bgcolor1`, `bgcolor2`, `ligneentete`, `ligne1`, `ligne2`, `ligneon`) VALUES 
(1, 'test@test.com', 'Titre du site', 'Salon-immobilier-aix.com', 'http://www.site.com/', 'http://www.salon-immobilier-aix.com/', 'Nous vous donnons d''ores et déjà rendez-vous pour vivre ensemble le 1er Salon de l''Immobilier qui réunira la majorité des acteurs qui font l''Immobilier sur le Pays d''Aix', 'Nous vous donnons d''ores et déjà rendez-vous pour vivre ensemble le 1er Salon de l''Immobilier qui réunira la majorité des acteurs qui font l''Immobilier sur le Pays d''Aix', 'Agences immobilières, Promoteurs et commercialistateurs, Constructeurs de Maisons Individuelles, Etablissements financiers, Gestion de Patrimoine et défiscalisation, Assurances, Fédérations, Institutions, Syndicats professionnels, Architectes, Presse, Activités annexes à la construction', 'Agences immobilières, Promoteurs et commercialistateurs, Constructeurs de Maisons Individuelles, Etablissements financiers, Gestion de Patrimoine et défiscalisation, Assurances, Fédérations, Institutions, Syndicats professionnels, Architectes, Presse, Activités annexes à la construction', '080311131559_logo1.jpg', '', 50, 10, '#FFFFFF', '#666666', '#C20027', '#C0B15B', '#FFFFFF', '#999999', '#BBBBBB', '#E4E4E4', '#F4F4F4', '#FFFFFF');

-- --------------------------------------------------------

-- 
-- Structure de la table `admin_utilisateurs`
-- 

DROP TABLE IF EXISTS `admin_utilisateurs`;
CREATE TABLE `admin_utilisateurs` (
  `id` int(8) NOT NULL auto_increment,
  `actif` tinyint(1) NOT NULL default '1',
  `type` tinyint(1) NOT NULL default '1',
  `email` varchar(250) default NULL,
  `password` varchar(250) default NULL,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `admin_utilisateurs`
-- 

INSERT INTO `admin_utilisateurs` (`id`, `actif`, `type`, `email`, `password`) VALUES 
(1, 1, 3, 'admin@site.com', 'admin');

-- --------------------------------------------------------

-- 
-- Structure de la table `cms_blocs`
-- 

DROP TABLE IF EXISTS `cms_blocs`;
CREATE TABLE `cms_blocs` (
  `id` int(8) NOT NULL auto_increment,
  `titre` varchar(250) default NULL,
  `lien` varchar(250) default NULL,
  `visuel` varchar(70) default NULL,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=3 ;

-- 
-- Contenu de la table `cms_blocs`
-- 

INSERT INTO `cms_blocs` (`id`, `titre`, `lien`, `visuel`) VALUES 
(1, 'Borntobeweb.fr', 'http://www.borntobeweb.fr', '080228225853__banniere.gif'),
(2, 'Reflex Immo', 'http://www.site.com', '080228225934_51bb2ea46c7f23d6.jpg');

-- --------------------------------------------------------

-- 
-- Structure de la table `cms_elements_types`
-- 

DROP TABLE IF EXISTS `cms_elements_types`;
CREATE TABLE `cms_elements_types` (
  `id` int(8) NOT NULL auto_increment,
  `actif` tinyint(1) NOT NULL default '1',
  `ordre` int(4) NOT NULL default '1',
  `type` int(8) default NULL,
  `titre` varchar(250) default NULL,
  `valeurs` varchar(250) default NULL,
  `template` text,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=45 ;

-- 
-- Contenu de la table `cms_elements_types`
-- 

INSERT INTO `cms_elements_types` (`id`, `actif`, `ordre`, `type`, `titre`, `valeurs`, `template`) VALUES 
(1, 1, 250, 4, 'Espacement vertical', '', '<div class="spacer">&nbsp;</div>'),
(2, 1, 160, 4, 'Ligne de séparation', '', '<hr class="cms_hr" />'),
(3, 1, 190, 0, 'Titre 1', 'item:Libellé|titre|text', '<div class="cms_h1">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h1>''.$titre.''</h1>\r\n		</div>\r\n	</div>\r\n</div>'),
(4, 1, 180, 0, 'Titre 2', 'item:Libellé|titre|text', '<div class="cms_h2">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h2>''.$titre.''</h2>\r\n		</div>\r\n	</div>\r\n</div>'),
(5, 1, 170, 0, 'Titre 3', 'item:Libellé|titre|text', '<div class="cms_h3">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h3>''.$titre.''</h3>\r\n		</div>\r\n	</div>\r\n</div>'),
(6, 0, 150, 0, 'Titre 4', 'item:Libellé|titre|text', '<div class="cms_h4">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h4>''.$titre.''</h4>\r\n		</div>\r\n	</div>\r\n</div>'),
(7, 1, 70, 1, 'Texte simple', 'item:Texte*|texte|textarea', '<div class="cms_p">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<p>''.$texte.''</p>\r\n		</div>\r\n	</div>\r\n</div>'),
(8, 0, 120, 1, 'Texte important', 'item:Texte*|texte|textarea', '<div class="cms_p_strong">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<p><strong>''.$texte.''</strong></p>\r\n		</div>\r\n	</div>\r\n</div>'),
(9, 0, 80, 1, 'Texte italique', 'item:Texte*|texte|textarea', '<div class="cms_p_note">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<p><em>''.$texte.''</em></p>\r\n		</div>\r\n	</div>\r\n</div>'),
(10, 1, 100, 1, 'Texte défilant', 'item:Texte*|texte|textarea,Direction|direction|enum[left/right/up/down,Distance|scrollamount|num(2,Délai|scrolldelay|num(1', '<div class="cms_marquee">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			#script#\r\n				// <![CDATA[\r\n				echo(\\''<\\''+\\''marquee direction="''.$direction.''" scrollamount="''.$scrollamount.''" scrolldelay="''.$scrolldelay.''" >\\'');\r\n				// ]]>\r\n			#/script#\r\n				<p>''.$texte.''</p>\r\n			#script#\r\n				// <![CDATA[\r\n				echo(\\''</\\''+\\''marquee>\\'');\r\n				// ]]>\r\n			#/script#\r\n		</div>\r\n	</div>\r\n</div>'),
(11, 0, 230, 1, 'Affiche du code', 'item:Code|texte|textarea', '<div class="cms_code">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<pre><code>''.$texte.''</code></pre>\r\n		</div>\r\n	</div>\r\n</div>'),
(12, 1, 20, 2, 'Liste à  puce', 'items:Item de liste*|item|text', '<div class="cms_ul">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<ul>'';\r\n			foreach((array)$items as $item)\r\n				$html .= ''<li>''.$item[''item''].''</li>'';\r\n			$html .= ''</ul>\r\n		</div>\r\n	</div>\r\n</div>'),
(13, 1, 90, 2, 'Liste numérotée', 'items:Item de liste*|value|text', '<div class="cms_ol">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<ol>'';\r\n			foreach((array)$values as $value)\r\n				$html .= ''<li>''.$value[''value''].''</li>'';\r\n			$html .= ''</ol>\r\n		</div>\r\n	</div>\r\n</div>'),
(14, 0, 220, 2, 'Liste de définition', 'items:Titre*|titre|text,Définition*|definition|textarea', '<div class="cms_definition">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<d1>'';\r\n			foreach((array)$titres as $k=>$titre)\r\n				$html .= ''<dt>''.$titre[''titre''].''</dt>\r\n				<dd>''.$definitions[$k][''definition''].''</dd>'';\r\n			$html .= ''</d1>\r\n		</div>\r\n	</div>\r\n</div>'),
(15, 1, 240, 1, 'Texte citation', 'item:Citation*|citation|textarea,Auteur|auteur|text', '<div class="cms_quotation">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<blockquote>\r\n				''.(!empty($citation) ? ''<p>''.$citation.''</p>'' : '''').''\r\n				''.(!empty($auteur) ? ''<cite>''.$auteur.''</cite>'' : '''').''\r\n			</blockquote>\r\n		</div>\r\n	</div>\r\n</div>'),
(16, 1, 60, 1, 'Texte html', 'item:Texte*|texte|html', '<div class="cms_rte">\r\n	''.$texte.''\r\n</div>'),
(17, 1, 50, 1, 'Texte html et image', 'item:Texte*|texte|html,Image*|image|image,Alignement|alignement|enum[gauche/droite(gauche', '<div class="cms_txt_img">\r\n	<div class="visu_''.$alignement.''">\r\n		''.( $image_popup == 1 ? ''<a href="''.$image_grande.''" class="lightwindow"  title="''.$image_legende.''">'' : '''').\r\n		''<img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/>''.\r\n		( $image_popup == 1 ? ''</a>'' : '''').\r\n		(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n		(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n		(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n		(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''')\r\n		.''\r\n	</div>\r\n	''.$texte.''\r\n</div>'),
(18, 1, 130, 3, 'Image', 'item:Image*|image|image,Alignement|alignement|enum[Gauche flottant/Gauche/Centre/Droite/Droite flottant', '<div class="cms_img ''.($alignement == ''Gauche flottant'' || $alignement == ''Droite flottant'' ? ''inline'' : ''block'').'' ''.($alignement == ''Gauche flottant'' || $alignement == ''Gauche'' ? ''gauche'' : ($alignement == ''Droite flottant'' || $alignement == ''Droite'' ? ''droite'' : ($alignement == ''Centre'' ? ''centre'' : ''''))).''">\r\n	''.($image_popup == 1 ? ''<a href="''.$image_grande.''" class="lightwindow">'' : '''').''<img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/>''.( $image_popup == 1 ? ''</a>'' : '''').''\r\n	''.(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n	(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n	(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n	(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''').''\r\n</div>'),
(19, 0, 30, 4, 'Table 6 cols', 'items:Ligne col. 1*|col1|text, Ligne col. 2|col2|text, Ligne col. 3|col3|text, Ligne col. 4|col4|text, Ligne col. 5|col5|text, Ligne col. 6|col6|text', '<div class="cms_tableau">\r\n	<table border="0" cellspacing="0" cellpadding="0" summary="">'';\r\n	foreach($col1s as $k=>$c)\r\n		$html .= ''<tr>\r\n	  	<th>''.($col1s[$k][''col1''] ? $col1s[$k][''col1''] : '' '').''</th>\r\n		<td>''.($col2s[$k][''col2''] ? $col2s[$k][''col2''] : '' '').''</td>\r\n		<td>''.($col3s[$k][''col3''] ? $col3s[$k][''col3''] : '' '').''</td>\r\n		<td>''.($col4s[$k][''col4''] ? $col4s[$k][''col4''] : '' '').''</td>\r\n		<td>''.($col5s[$k][''col5''] ? $col5s[$k][''col5''] : '' '').''</td>\r\n		<td>''.($col6s[$k][''col6''] ? $col1s[$k][''col6''] : '' '').''</td>\r\n	  </tr>'';\r\n	$html .= ''</table>\r\n</div>'),
(20, 0, 200, 1, 'Texte avec Focus + Fichier', 'item:Titre*|titre|text,Texte|texte|html,Lien|url_href|text,Titre du lien|url_titre|text,Document*|document|fichier', '<div class="cms_focus">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h1>''.$titre.''</h1>\r\n			''.$texte. ( !empty($url_href) || !empty($document) ? \r\n			''<ul class="liens">''.\r\n			( !empty($url_href)  ? ''<li class="url"><a href="''.$url_href.''" target="_blank">''.($url_titre ? $url_titre : $url_href).''</a></li>'' : '''' ). \r\n			( !empty($document)  ? ''<li class="doc"><a href="''.$document_src.''" target="_blank">''.($document_titre?$document_titre:$document).''</a></li>'': '''' ).\r\n			''</ul>''\r\n			: '''' ).''\r\n		</div>\r\n	</div>\r\n</div>'),
(21, 1, 280, 4, 'Lien simple', 'item:Lien*|lien|text,Titre du lien|titrelien|text,Cible|cible|enum[_blank/_self(_blank', '<div class="cms_a_download">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<a href="''.$lien.''" target="''.$cible.''">''.($titrelien ? $titrelien : $lien).''</a>\r\n		</div>\r\n	</div>\r\n</div>'),
(31, 0, 300, 3, 'Galerie d''images', 'items:Visuel|image|image', '<div class="cms_galerie">\r\n	<div class="d1">\r\n		<div class="d2">'';\r\n			foreach($images as $image)\r\n				$html .= ''<div style="float:left; width:90px; height:70px; overflow:hidden; margin:10px;"><img src="''.$image[''image_src''].''"></div>'';\r\n		$html .= ''</div>\r\n	</div>\r\n</div>'),
(23, 1, 110, 3, 'Document', 'item:Document*|document|fichier', '<div class="cms_a_download">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<a href="''.$document_src.''" target="_blank">''.($document_titre != '''' ? $document_titre : $document).''</a> (''.$document_ext.'' - ''.$document_size.'')\r\n		</div>\r\n	</div>\r\n</div>'),
(24, 1, 270, 2, 'Liste de liens', 'items:Lien*|lien|text,Titre du lien|titrelien|text,Cible|cible|enum[_blank/_self(_blank', '<div class="cms_a_list">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<ul>'';\r\n			foreach($liens as $k=>$lien)\r\n				$html .= ''<li><a href="''.$lien[''lien''].''" target="''.$cible.''">''.($titreliens[$k][''titrelien''] ? $titreliens[$k][''titrelien''] : $lien[''lien'']).''</a></li>'';\r\n			$html .= ''</ul>\r\n		</div>\r\n	</div>\r\n</div>'),
(25, 1, 290, 2, 'Liste de documents', 'items:Document*|document|fichier', '<div class="cms_a_list">\r\n	<div class="d1">\r\n		<div class="d2">		\r\n			<ul>'';\r\n			foreach((array)$documents as $document)\r\n				$html .= ''<li><a href="''.$document[''document_src''].''">''.($document[''document_titre''] ? $document[''document_titre''] : $document[''document'']).''</a> (''.$document[''document_ext''].'' - ''.$document[''document_size''].'')</li>'';\r\n			$html .= ''</ul>\r\n		</div>\r\n	</div>\r\n</div>'),
(26, 0, 10, 3, 'Flash Vidéo Clark (flv)', 'item:Fichier flv*|source|fichier', '<div class="cms_video">\r\n	<div id="flash_swf_''.$unique_id.''"><p>Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a> pour lire cette vidéo</p></div>\r\n	#script#\r\n	// <![CDATA[\r\n		var so = new SWFObject("''.$WWW.''swf/common/player.swf", "swf_flash_''.$unique_id.''", "322", "271", "8", "");	\r\n		''.(!empty($bgcolor) ? ''so.addParam("bgcolor", "''.$bgcolor.''");'' : '''').''\r\n		so.addParam("quality", "high");\r\n		so.addParam("salign", "TL");\r\n		so.addParam("allowFullScreen", "true");	\r\n		so.addVariable("sPathFlv", "''.$source_src.''");\r\n		so.write("flash_swf_''.$unique_id.''");\r\n	// ]]>\r\n	#/script#\r\n</div>'),
(27, 1, 140, 4, 'Iframe', 'item:Source*|source|text,Largeur*|width|text(100%,Hauteur*|height|text(320,Scroll|scrolling|enum[auto/no/yes(auto', '<div class="cms_iframe">\r\n	#iframe# src="''.$source.''" id="''.$unique_id.''_frame" name="''.$unique_id.''_frame" width="''.$width.''" height="''.$height.''" scrolling="''.$scrolling.''" frameborder="0" allowtransparency="1">#/iframe#\r\n</div>'),
(28, 0, 210, 3, 'Flash externe (swf)', 'item:Fichier swf distant|url|text,Largeur|width|num,Hauteur|height|num,Version|version|enum[7/8/9(8,Couleur fond|bgcolor|text', '<div class="cms_flash">\r\n	<div id="flash_swf_''.$unique_id.''"><p>Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a> pour lire cette animation</p></div>\r\n	#script#\r\n	// <![CDATA[\r\n	swf_src = "''.$url.''";\r\n	params = swf_src.split("&amp;");\r\n	swf_src = params[0];\r\n	videoVars = {};\r\n	for(i=1; i<=params.length; i++) {\r\n	if(params[i]) {\r\n		keyval = params[i].split("=");\r\n		videoVars[keyval[0]] = keyval[1];\r\n	}}\r\n	swfobject.embedSWF(swf_src, "flash_swf_''.$unique_id.''", "''.$width.''", "''.$height.''", "''.$version.''.0.0", "", videoVars, {wmode: "transparent"}, {});\r\n\r\ndb(videoVars);\r\n\r\n	// ]]>\r\n	#/script#\r\n</div>'),
(34, 1, 330, 3, 'Flash (swf)', 'item:Fichier swf*|source|fichier,Largeur*|width|num,Hauteur*|height|num,Version|version|enum[7/8/9(8,Couleur fond|bgcolor|text', '<div class="cms_flash2">\r\n	<div id="div_swf_''.$unique_id.''">\r\n		<p>Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a> pour lire cette animation</p>\r\n	</div>\r\n	#script#\r\n	// <![CDATA[\r\n	var so = new SWFObject("''.$source_src.''", "swf_''.$unique_id.''", "''.$width.''", "''.$height.''", "''.$version.''", "");	\r\n	so.addParam("quality", "high");\r\n	so.addParam("scale", "exactfit");\r\n	so.addParam("bgcolor", "''.(!empty($bgcolor)? $bgcolor : '''').''");\r\n	so.addParam("wmode", "''.(empty($bgcolor)? ''transparent'' : '''').''");\r\n	//so.addVariable("", "");\r\n	so.write("div_swf_''.$unique_id.''");\r\n	// ]]>\r\n	#/script#\r\n</div>'),
(29, 0, 260, 4, 'Bouton', '', ''),
(30, 0, 40, 4, 'Sommaire', '', ''),
(32, 1, 310, 3, 'Flash Vidéo (flv)', 'item:Fichier flv*|source|fichier,Largeur*|width|num,Hauteur*|height|num,autoStart|autostart|enum[true/false(true,Boutton FS|showfsbutton|enum[true/false(true', '<div class="cms_video2">\r\n	<div id="flash_swf_''.$unique_id.''"><p>Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a> pour lire cette vidéo</p></div>\r\n	#script#\r\n	// <![CDATA[\r\n		var so = new SWFObject("''.$WWW.''cms_files/anarchy-source-files/flvplayer.swf", "swf_flash_''.$unique_id.''", "''.$width.''", "''.$height.''", "7", "");	\r\n		so.addParam("wmode","transparent");\r\n		so.addVariable("file", "''.$source_src.''");\r\n		so.addVariable("click", "false");\r\n		so.addVariable("autoStart", "''.$autostart.''");\r\n		so.addVariable("preview", "true");\r\n		so.addVariable("showfsbutton", "''.$showfsbutton.''");	\r\n		so.write("flash_swf_''.$unique_id.''");\r\n	// ]]>\r\n	#/script#\r\n</div>'),
(33, 0, 320, 3, 'Vidéo (avi, mov, ...)', 'item:Fichier vidéo|source|fichier,Largeur|width|num,Hauteur|height|num', '<div class="cms_video">\r\n	#object# width="''.$width.''" height="''.$height.''">\r\n		#embed# type="application/x-mplayer2" src="''.$source_src.''" autostart="true" controller="false" width="''.$width.''" height="''.$height.''">#/embed#\r\n	#/object#\r\n</div>'),
(35, 0, 340, 4, 'Include PHP', 'item:Fichier|ficherphp|enum[_form_contact.php/_google_map.html', '''; $html .= @file_get_contents($wwwRoot.$ficherphp); $html .='''),
(36, 0, 350, 1, 'Texte avec Focus + Image', 'item:Titre*|titre|text,Texte|texte|html,Lien|url_href|text,Titre du lien|url_titre|text,Image*|image|image,Alignement|alignement|enum[gauche/droite(gauche', '<div class="cms_focus">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h1>''.$titre.''</h1>\r\n			<div class="visu_''.$alignement.''">\r\n				''.( $image_popup == 1 ? ''<a href="''.$image_grande.''" class="lightwindow"  title="''.$image_legende.''">'' : '''').''\r\n				<img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/>\r\n				''.( $image_popup == 1 ? ''</a>'' : '''').\r\n				(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n				(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n				(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n				(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''').''\r\n			</div>\r\n			''.$texte. ( !empty($url_href) ? \r\n			''<ul class="liens">''\r\n			.( !empty($url_href)  ? ''<li class="url"><a href="''.$url_href.''" target="_blank">''.($url_titre ? $url_titre : $url_href).''</a></li>'' : '''' ). \r\n			''</ul>''\r\n			: '''' ).''\r\n		</div>\r\n	</div>\r\n</div>'),
(37, 1, 360, 4, 'Google Maps Iframe', 'item:Google map Url*|source|textarea,Largeur*|width|text(100%,Hauteur*|height|text(320', '<div class="cms_iframe">\r\n	#iframe# src="''.$source.''" id="''.$unique_id.''_frame" name="''.$unique_id.''_frame" width="''.$width.''" height="''.$height.''" scrolling="no" frameborder="0" allowtransparency="1" marginheight="0" marginwidth="0">#/iframe#\r\n	<div style="text-align:center;">\r\n		\r\n\r\n		<a href="''.$source.''" target="_blank">Agrandir le plan</a>\r\n	</div>\r\n</div>'),
(38, 1, 370, 3, 'Image avec lien', 'item:Image*|image|image,Lien*|lien|text,Cible|cible|enum[_blank/_self(_blank', '<div class="cms_img">\r\n	<a href="''.$lien.''" target="''.$cible.''"><img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/></a>\r\n	''.(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n	(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n	(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n	(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''').''\r\n</div>'),
(39, 1, 380, 4, 'Formulaire préinscription', 'item:Titre*|titre|text,Texte*|texte|html,Image|image|image', '<div class="breaker"> </div>'';\r\nob_start();\r\nrequire($wwwRoot.''_demande.php'');\r\n$html .= ob_get_contents();\r\nob_end_clean();\r\n$html .=''\r\n<div class="contact_colonne">\r\n	<div style="text-align:right">\r\n		''.( $image_popup == 1 ? ''<a href="''.$image_grande.''" class="lightwindow"  title="''.$image_legende.''">'' : '''').\r\n		''<img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/>''.\r\n		( $image_popup == 1 ? ''</a>'' : '''').\r\n		(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n		(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n		(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n		(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''')\r\n		.''\r\n	</div>\r\n	\r\n\r\n	''.$texte.''\r\n</div>\r\n<div class="breaker"> </div>'),
(40, 1, 390, 1, 'Pavé focus : Titre + Texte', 'item:Titre*|titre|text,Texte*|texte|html,Alignement*|alignement|enum[gauche/droite(gauche', '<div class="cms_encart1 ''.$alignement.''">\r\n	<div class="cms_encart1_body">\r\n		<h1>''.$titre.''</h1>\r\n		<div class="cms_encart1_content">\r\n			''.$texte.''\r\n			<div class="clear"> </div>\r\n		</div>\r\n	</div>\r\n</div>'),
(41, 1, 400, 1, 'Encart focus : Titre + Texte', 'item:Titre*|titre|text,Texte*|texte|html,Alignement*|alignement|enum[gauche/droite(gauche', '<div class="cms_encart2 ''.$alignement.''">\r\n	<div class="cms_encart2_body">\r\n		<h1>''.$titre.''</h1>\r\n		<div class="cms_encart2_content">\r\n			''.$texte.''\r\n			<div class="clear"> </div>\r\n		</div>\r\n	</div>\r\n</div>'),
(42, 1, 410, 1, 'Colonne focus : Texte', 'item:Texte*|texte|html,Alignement*|alignement|enum[gauche/droite(gauche', '<div class="cms_encart3 ''.$alignement.''">\r\n	<div class="cms_encart3_body">\r\n		''.$texte.''\r\n		<div class="clear"> </div>\r\n	</div>\r\n</div>'),
(43, 1, 420, 4, 'Formulaire contact', 'item:Titre*|titre|text,Texte*|texte|html,Image|image|image', '<div class="breaker"> </div>'';\r\nob_start();\r\nrequire($wwwRoot.''_contact.php'');\r\n$html .= ob_get_contents();\r\nob_end_clean();\r\n$html .=''\r\n<div class="contact_colonne">\r\n	<div style="text-align:right">\r\n		''.( $image_popup == 1 ? ''<a href="''.$image_grande.''" class="lightwindow"  title="''.$image_legende.''">'' : '''').\r\n		''<img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/>''.\r\n		( $image_popup == 1 ? ''</a>'' : '''').\r\n		(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n		(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n		(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n		(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''')\r\n		.''\r\n	</div>\r\n	\r\n\r\n	''.$texte.''\r\n</div>\r\n<div class="breaker"> </div>'),
(44, 1, 430, 4, 'Lien page interne', 'item:Page*|page|func[getPages,Titre du lien|titrelien|text,Cible|cible|enum[_blank/_self(_blank', '<div class="cms_a_download">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<a href="''.$page.''">''.($titrelien ? $titrelien : $page).''</a>\r\n		</div>\r\n	</div>\r\n</div>');

-- --------------------------------------------------------

-- 
-- Structure de la table `cms_pages`
-- 

DROP TABLE IF EXISTS `cms_pages`;
CREATE TABLE `cms_pages` (
  `id` int(8) NOT NULL auto_increment,
  `ordre` int(4) NOT NULL default '1',
  `actif` tinyint(1) NOT NULL default '1',
  `pid` int(8) default NULL,
  `type_id` tinyint(8) default NULL,
  `lien_fr` varchar(250) default NULL,
  `lien_uk` varchar(250) default NULL,
  `menu` tinyint(1) NOT NULL default '1',
  `plan` tinyint(1) NOT NULL default '1',
  `titre_fr` varchar(150) default NULL,
  `titre_uk` varchar(150) default NULL,
  `titre_page_fr` varchar(250) default NULL,
  `titre_page_uk` varchar(250) default NULL,
  `meta_titre_fr` varchar(150) default NULL,
  `meta_titre_uk` varchar(150) default NULL,
  `meta_url_fr` varchar(150) default NULL,
  `meta_url_uk` varchar(150) default NULL,
  `meta_description_fr` text,
  `meta_description_uk` text,
  `meta_key_fr` text,
  `meta_key_uk` text,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`),
  KEY `type_id` (`type_id`)
)  AUTO_INCREMENT=73 ;

-- 
-- Contenu de la table `cms_pages`
-- 

INSERT INTO `cms_pages` (`id`, `ordre`, `actif`, `pid`, `type_id`, `lien_fr`, `lien_uk`, `menu`, `plan`, `titre_fr`, `titre_uk`, `titre_page_fr`, `titre_page_uk`, `meta_titre_fr`, `meta_titre_uk`, `meta_url_fr`, `meta_url_uk`, `meta_description_fr`, `meta_description_uk`, `meta_key_fr`, `meta_key_uk`) VALUES 
(1, 60, 1, 0, 1, '', '', 0, 1, 'Accueil', 'Home', 'Bienvenu sur notre site', '', 'Salon Immobilier du Pays d''Aix 2008', 'Salon Immobilier du Pays d''Aix 2008', '', '', 'Nous vous donnons d''ores et dÃ©jÃ  rendez-vous pour vivre ensemble le 1er Salon de l''Immobilier qui rÃ©unira la majoritÃ© des acteurs qui font l''Immobilier sur le Pays d''Aix', 'Nous vous donnons d''ores et dÃ©jÃ  rendez-vous pour vivre ensemble le 1er Salon de l''Immobilier qui rÃ©unira la majoritÃ© des acteurs qui font l''Immobilier sur le Pays d''Aix', 'Agences immobiliÃ¨res, Promoteurs et commercialistateurs, Constructeurs de Maisons Individuelles, Etablissements financiers, Gestion de Patrimoine et dÃ©fiscalisation, Assurances, FÃ©dÃ©rations, Institutions, Syndicats professionnels, Architectes, Presse, ActivitÃ©s annexes Ã  la construction', 'Agences immobiliÃ¨res, Promoteurs et commercialistateurs, Constructeurs de Maisons Individuelles, Etablissements financiers, Gestion de Patrimoine et dÃ©fiscalisation, Assurances, FÃ©dÃ©rations, Institutions, Syndicats professionnels, Architectes, Presse, ActivitÃ©s annexes Ã  la construction'),
(67, 30, 1, 0, 4, '', '', 0, 1, 'CGU', 'CGU', '', '', 'Conditions gÃ©nÃ©rales d''utilisations', 'Conditions gÃ©nÃ©rales d''utilisations', '', '', '', '', '', ''),
(68, 50, 1, 0, 13, '', '', 0, 0, 'Visiteurs', '', '', '', 'Visiteurs', '', '', '', '', '', '', ''),
(59, 40, 1, 0, 14, '', '', 0, 1, 'Exposants', '', '', '', '', '', '', '', '', '', '', ''),
(60, 10, 1, 68, 4, '', '', 1, 1, 'Contacts', '', '', '', '', '', '', '', '', '', '', ''),
(61, 20, 1, 59, 4, '', '', 1, 1, 'Devenez exposant', '', '', '', '', '', '', '', '', '', '', ''),
(62, 20, 1, 68, 15, '', '', 1, 1, 'Conférences', '', '', '', '', '', '', '', '', '', '', ''),
(63, 30, 1, 59, 4, '', '', 1, 1, 'Informations pratiques', '', '', '', '', '', '', '', '', '', '', ''),
(64, 30, 1, 68, 6, '', '', 1, 1, 'Exposants', '', '', '', '', '', '', '', '', '', '', ''),
(65, 40, 1, 68, 4, '', '', 1, 1, 'Informations pratiques', '', '', '', '', '', '', '', '', '', '', ''),
(66, 40, 1, 59, 4, '', '', 1, 1, 'Accueil', 'Accueil exposant', '', '', '', '', '', '', '', '', '', ''),
(71, 10, 1, 0, 16, '', '', 0, 0, 'Les partenaires du salon', '', '', '', '', '', '', '', '', '', '', ''),
(69, 50, 1, 68, 4, '', '', 1, 0, 'Accueil', 'Home', '', '', '', '', '', '', '', '', '', ''),
(70, 10, 1, 59, 4, '', '', 1, 0, 'Contacts', '', '', '', '', '', '', '', '', '', '', ''),
(72, 20, 1, 0, 4, '', '', 0, 0, 'Pré-inscription', '', '', '', 'Formulaire de pré-inscription', '', '', '', '', '', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `cms_pages_elements`
-- 

DROP TABLE IF EXISTS `cms_pages_elements`;
CREATE TABLE `cms_pages_elements` (
  `id` int(8) NOT NULL auto_increment,
  `ordre` int(4) NOT NULL default '1',
  `actif` tinyint(1) NOT NULL default '1',
  `pid` int(8) NOT NULL default '1',
  `type_id` tinyint(8) default NULL,
  `langue` char(2) default NULL,
  `valeurs` text,
  `motcle` text,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `cms_pages_elements`
-- 


-- --------------------------------------------------------

-- 
-- Structure de la table `cms_pages_types`
-- 

DROP TABLE IF EXISTS `cms_pages_types`;
CREATE TABLE `cms_pages_types` (
  `id` int(8) NOT NULL auto_increment,
  `actif` tinyint(1) NOT NULL default '1',
  `titre` varchar(250) default NULL,
  `repertoire` varchar(250) default NULL,
  `description` text,
  `style` varchar(250) default NULL,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=17 ;

-- 
-- Contenu de la table `cms_pages_types`
-- 

INSERT INTO `cms_pages_types` (`id`, `actif`, `titre`, `repertoire`, `description`, `style`) VALUES 
(1, 1, '[*] Page d''accueil', '', 'Page d''index du site', './css/home.css'),
(2, 1, '[*] Redirection sous-rubrique', '', 'Permet de rediriger le lien d''une rubrique vers sa premiÃ¨re sous-rubrique', ''),
(3, 1, '[*] Redirection lien', '', 'Rediriger une rubrique vers un lien', ''),
(4, 1, '[*] Page CMS', '', 'Module par defaut : utlise le contenu CMS associÃ© Ã  la page', './css/cms.css'),
(5, 0, '[*] Liste sous-rubriques', '', 'Affiche les sous-rubriques d''une rubrique', ''),
(6, 1, 'Module Liste participants', 'mod_participants', '', ''),
(11, 0, 'Module plan du site', 'mod_plan', '', ''),
(12, 0, 'Module recherche', 'mod_recherche', '', ''),
(13, 1, 'Module Visiteurs', '', '', ''),
(14, 1, 'Module Exposants', '', '', ''),
(15, 1, 'Module Liste conférences', '', '', ''),
(16, 1, 'Module Liste partenaires', '', '', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `dat_bibliotheque_fichiers`
-- 

DROP TABLE IF EXISTS `dat_bibliotheque_fichiers`;
CREATE TABLE `dat_bibliotheque_fichiers` (
  `id` int(8) NOT NULL auto_increment,
  `cat_id` int(8) NOT NULL default '1',
  `titre_fr` varchar(150) default NULL,
  `titre_uk` varchar(150) default NULL,
  `description` text,
  `date` datetime default NULL,
  `fichier` varchar(70) default NULL,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=12 ;

-- 
-- Contenu de la table `dat_bibliotheque_fichiers`
-- 

INSERT INTO `dat_bibliotheque_fichiers` (`id`, `cat_id`, `titre_fr`, `titre_uk`, `description`, `date`, `fichier`) VALUES 
(8, 1, 'Télécharger le Règlement général', 'Télécharger le Règlement général', '', '2008-03-14 17:17:00', '080314171750_reglement-salon.doc'),
(9, 1, 'Télécharger les Mesures de sécurité', '', '', '2008-03-14 17:17:00', '080314171811_mesuresdesecurite06.pdf'),
(10, 1, 'Télécharger le Plan du salon', '', '', '2008-03-14 17:18:00', '080314171833_plan.pdf'),
(11, 1, 'Télécharger la plaquette commerciale du salon', '', '', '2008-03-14 17:18:00', '080314171906_plaquette-commerciale.zip');

-- --------------------------------------------------------

-- 
-- Structure de la table `dat_bibliotheque_fichiers_cat`
-- 

DROP TABLE IF EXISTS `dat_bibliotheque_fichiers_cat`;
CREATE TABLE `dat_bibliotheque_fichiers_cat` (
  `id` int(8) NOT NULL auto_increment,
  `ordre` int(4) NOT NULL default '1',
  `titre` varchar(150) default NULL,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=4 ;

-- 
-- Contenu de la table `dat_bibliotheque_fichiers_cat`
-- 

INSERT INTO `dat_bibliotheque_fichiers_cat` (`id`, `ordre`, `titre`) VALUES 
(1, 20, 'Documents pdf'),
(3, 10, 'Test');

-- --------------------------------------------------------

-- 
-- Structure de la table `dat_bibliotheque_images`
-- 

DROP TABLE IF EXISTS `dat_bibliotheque_images`;
CREATE TABLE `dat_bibliotheque_images` (
  `id` int(8) NOT NULL auto_increment,
  `cat_id` int(8) NOT NULL default '1',
  `legende_fr` text,
  `legende_uk` text,
  `credits_fr` text,
  `credits_uk` text,
  `auteur` varchar(255) default NULL,
  `date` datetime default NULL,
  `image` varchar(70) default NULL,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=6 ;

-- 
-- Contenu de la table `dat_bibliotheque_images`
-- 

INSERT INTO `dat_bibliotheque_images` (`id`, `cat_id`, `legende_fr`, `legende_uk`, `credits_fr`, `credits_uk`, `auteur`, `date`, `image`) VALUES 
(1, 1, '<p>Alice &eacute;v&eacute;nements</p>', '', '', '', '', '2008-02-28 17:13:00', '080311193916_logo_alice.gif'),
(2, 1, '<p>E-fiche miniature</p>', '', '', '', '', '2008-02-28 18:57:00', '080314171451_e-fiche_off.gif'),
(5, 1, '<p>Formulaire de pr&eacute;inscription</p>', '', '', '', '', '2008-03-14 17:26:00', '080314172741_formulaire_off.gif'),
(3, 1, '<p>T&eacute;l&eacute;chargez les plaquettes du salon</p>', '', '', '', '', '2008-02-29 17:07:00', '080314171305_telechargez_off.gif'),
(4, 1, '<p>E-fiche exemple</p>', '', '', '', '', '2008-03-06 15:03:00', '080314171527_e-fiche.gif');

-- --------------------------------------------------------

-- 
-- Structure de la table `dat_bibliotheque_images_cat`
-- 

DROP TABLE IF EXISTS `dat_bibliotheque_images_cat`;
CREATE TABLE `dat_bibliotheque_images_cat` (
  `id` int(8) NOT NULL auto_increment,
  `ordre` int(4) default NULL,
  `titre` varchar(255) default NULL,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `dat_bibliotheque_images_cat`
-- 

INSERT INTO `dat_bibliotheque_images_cat` (`id`, `ordre`, `titre`) VALUES 
(1, 10, 'Visuels articles');

-- --------------------------------------------------------

-- 
-- Structure de la table `dat_email_alertes`
-- 

DROP TABLE IF EXISTS `dat_email_alertes`;
CREATE TABLE `dat_email_alertes` (
  `id` int(8) NOT NULL auto_increment,
  `titre` varchar(150) default NULL,
  `sujet` varchar(150) default NULL,
  `texte` text,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `dat_email_alertes`
-- 

INSERT INTO `dat_email_alertes` (`id`, `titre`, `sujet`, `texte`) VALUES 
(1, 'Envois des informations de connexion', 'Vos codes d''accès pour le site salon-immobilier-aix.com / Your acces code to the site salon-immobilier-aix.com', '<p>Bonjour,</p>\r\n<p>&nbsp;</p>\r\n<p>Vos informations de connexion sur le site http://www.salon-immobilier-aix.com</p>\r\n<p>sont les suivantes :</p>\r\n<p>&nbsp;</p>\r\n<p>Identifiant : #LOGIN</p>\r\n<p>Mot de passe : #MDP</p>\r\n<p>&nbsp;</p>\r\n<p>Bonne visite sur notre site</p>\r\n<p>&nbsp;</p>\r\n<p>L''&eacute;quipe salon-immobilier-aix.com</p>\r\n<p>&nbsp;</p>\r\n<p>___________________________________________________</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>Hi,</p>\r\n<p>&nbsp;</p>\r\n<p>Your login information on the site http://www.salon-immobilier-aix.com <br /> Are as follows :</p>\r\n<p>&nbsp;</p>\r\n<p>Login : #LOGIN</p>\r\n<p>Password : #MDP</p>\r\n<p>&nbsp;</p>\r\n<p>Enjoy your visit to our site</p>\r\n<p>&nbsp;</p>\r\n<p>&nbsp;</p>\r\n<p>The salon-immobilier-aix.com team</p>');

-- --------------------------------------------------------

-- 
-- Structure de la table `dat_trad_mots`
-- 

DROP TABLE IF EXISTS `dat_trad_mots`;
CREATE TABLE `dat_trad_mots` (
  `id` int(8) NOT NULL auto_increment,
  `titre` varchar(150) default NULL,
  `trad_fr` tinytext,
  `trad_uk` tinytext,
  PRIMARY KEY  (`id`)
)  AUTO_INCREMENT=46 ;

-- 
-- Contenu de la table `dat_trad_mots`
-- 

INSERT INTO `dat_trad_mots` (`id`, `titre`, `trad_fr`, `trad_uk`) VALUES 
(1, 'Demande par formulaire', 'Demande par formulaire', ''),
(2, 'Pour accéder à cette page', 'Pour accéder à cette page', ''),
(3, 'Nom', 'Nom', ''),
(4, 'Prénom', 'Prénom', ''),
(5, 'Société', 'Société', ''),
(6, 'Téléphone', 'Téléphone', ''),
(7, 'E-mail', 'E-mail', ''),
(8, 'Message', 'Message', ''),
(9, 'Envoyer', 'Envoyer', ''),
(10, 'Identifiant', 'Identifiant', ''),
(11, 'Mot de passe', 'Mot de passe', ''),
(12, 'Le nom est obligatoire', 'Le nom est obligatoire', ''),
(13, 'Le prénom est obligatoire', 'Le prénom est obligatoire', ''),
(14, 'Le mail est obligatoire et doit être valide', 'Le mail est obligatoire et doit être valide', ''),
(15, 'Le message est obligatoire', 'Le message est obligatoire', ''),
(16, 'L''identifiant est obligatoire', 'L''identifiant est obligatoire', ''),
(17, 'Le mot de passe est obligatoire', 'Le mot de passe est obligatoire', ''),
(18, 'Pour demander un accés', 'Votre demande de préinscription', ''),
(19, 'Votre message nous a bien été transmis', 'Votre message nous a bien été transmis.\r\nVous recevrez vos informations de connexion par email.\r\nMerci !', ''),
(20, 'Vous êtes maintenant connecté', 'Vous êtes maintenant connecté', ''),
(21, 'Exposants', 'Exposants', ''),
(22, 'Visiteurs', 'Visiteurs', ''),
(23, 'Votre mail est déjà inscrit', 'Votre mail est déjà inscrit', ''),
(24, 'Numéro de stand', 'Numéro de stand', ''),
(25, 'Adresse', 'Adresse', ''),
(26, 'Code postale', 'Code postal', 'Postal Code'),
(27, 'Ville', 'Ville', ''),
(28, 'Fax', 'Fax', ''),
(29, 'Nom prénom', 'Prénom, nom', ''),
(30, 'Le sujet est obligatoire', 'Le sujet est obligatoire', ''),
(31, 'Sujet', 'Sujet', ''),
(32, 'Merci de remplir le formulaire...', 'Merci de remplir le formulaire suivant pour votre demande d''accès.\r\n(Les champs avec * sont obligatoires)', ''),
(33, 'Contactez-nous', 'Contactez-nous', ''),
(34, 'Conférences 2008', 'Conférences 2008', ''),
(35, 'Horaires', 'Horaires', ''),
(36, 'Sujets', 'Sujets', ''),
(37, 'Intervenants', 'Intervenants', ''),
(38, 'Secteur', 'Secteur d''activité', ''),
(39, 'La société est obligatoire', 'La société est obligatoire', ''),
(40, 'L''adresse est obligatoire', 'L''adresse est obligatoire', ''),
(41, 'Le code postal est obligatoire', 'Le code postal est obligatoire', ''),
(42, 'La ville est obligatoire', 'La ville est obligatoire', ''),
(43, 'Le téléphone est obligatoire', 'Le téléphone est obligatoire', ''),
(44, 'Le secteur est obligatoire', 'Le secteur est obligatoire', ''),
(45, 'Accédez à votre espace personnalisé', 'Accédez à votre espace personnalisé pour télécharger les documents techniques', '');

-- --------------------------------------------------------

-- 
-- Structure de la table `dat_trad_textes`
-- 

DROP TABLE IF EXISTS `dat_trad_textes`;
CREATE TABLE `dat_trad_textes` (
  `id` int(8) NOT NULL auto_increment,
  `titre` varchar(150) default NULL,
  `trad_fr` text,
  `trad_uk` text,
  PRIMARY KEY  (`id`)
) AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `dat_trad_textes`
-- 

