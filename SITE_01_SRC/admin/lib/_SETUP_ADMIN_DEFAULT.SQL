-- phpMyAdmin SQL Dump
-- version 2.9.1.1
-- http://www.phpmyadmin.net
-- 
-- Serveur: localhost
-- Généré le : Jeudi 20 Mars 2008 à 17:09
-- Version du serveur: 5.0.27
-- Version de PHP: 5.2.0
-- 
-- Base de données: `tresorcobelge`
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `admin_parametres`
-- 

INSERT INTO `admin_parametres` (`id`, `email`, `meta_title_fr`, `meta_title_uk`, `meta_url_fr`, `meta_url_uk`, `meta_desc_fr`, `meta_desc_uk`, `meta_key_fr`, `meta_key_uk`, `logoc`, `logoa`, `paginationa`, `pagination`, `fontcolor1`, `fontcolor2`, `linkcolor`, `linkcoloron`, `bgcolor1`, `bgcolor2`, `ligneentete`, `ligne1`, `ligne2`, `ligneon`) VALUES 
(1, 've@alice-evenements.com', 'Salon-immobilier-aix.com', 'Salon-immobilier-aix.com', 'http://www.salon-immobilier-aix.com/', 'http://www.salon-immobilier-aix.com/', 'Nous vous donnons d''ores et déjà rendez-vous pour vivre ensemble le 1er Salon de l''Immobilier qui réunira la majorité des acteurs qui font l''Immobilier sur le Pays d''Aix', 'Nous vous donnons d''ores et déjà rendez-vous pour vivre ensemble le 1er Salon de l''Immobilier qui réunira la majorité des acteurs qui font l''Immobilier sur le Pays d''Aix', 'Agences immobilières, Promoteurs et commercialistateurs, Constructeurs de Maisons Individuelles, Etablissements financiers, Gestion de Patrimoine et défiscalisation, Assurances, Fédérations, Institutions, Syndicats professionnels, Architectes, Presse, Activités annexes à la construction', 'Agences immobilières, Promoteurs et commercialistateurs, Constructeurs de Maisons Individuelles, Etablissements financiers, Gestion de Patrimoine et défiscalisation, Assurances, Fédérations, Institutions, Syndicats professionnels, Architectes, Presse, Activités annexes à la construction', '080311131559_logo1.jpg', '', 50, 10, '#FFFFFF', '#666666', '#C20027', '#C0B15B', '#FFFFFF', '#999999', '#BBBBBB', '#E4E4E4', '#F4F4F4', '#FFFFFF');

-- --------------------------------------------------------

-- 
-- Structure de la table `admin_utilisateurs`
-- 

DROP TABLE IF EXISTS `admin_utilisateurs`;
CREATE TABLE `admin_utilisateurs` (
  `id` int(8) NOT NULL auto_increment,
  `actif` tinyint(1) NOT NULL default '1',
  `type` tinyint(1) NOT NULL default '1',
  `login` varchar(250) default NULL,
  `nom` varchar(250) default NULL,
  `prenom` varchar(250) default NULL,
  `password` varchar(250) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Contenu de la table `admin_utilisateurs`
-- 

INSERT INTO `admin_utilisateurs` (`id`, `actif`, `type`, `login`, `nom`, `prenom`, `password`) VALUES 
(1, 1, 3, 'immoaix', '', '', 'immo');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=45 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=73 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=44 ;

-- 
-- Contenu de la table `cms_pages_elements`
-- 

INSERT INTO `cms_pages_elements` (`id`, `ordre`, `actif`, `pid`, `type_id`, `langue`, `valeurs`, `motcle`) VALUES 
(1, 10, 1, 1, 40, 'uk', 'a:3:{s:5:"titre";s:24:"TGVzIGNoaWZmcmVzIDIwMDg=";s:5:"texte";s:260:"PHA+MjAwMCBtJnN1cDI7IGQnZXhwb3NpdGlvbjxiciAvPiAtLS0tLS0tLTxiciAvPiA5MDAgbSZzdXAyOyBkZSBzdGFuZHM8YnIgLz4gLS0tLS0tLS08YnIgLz4gUGx1cyBkZSA3MCBzb2NpJmVhY3V0ZTt0JmVhY3V0ZTtzIGV4cG9zYW50ZXM8YnIgLz4gLS0tLS0tLS08YnIgLz4gUGx1cyBkZSAxMDAwMCB2aXNpdGV1cnMgYXR0ZW5kdXM8L3A+";s:10:"alignement";s:8:"Z2F1Y2hl";}', 'Les chiffres 2008<br />2000 mÂ² d''exposition -------- 900 mÂ² de stands -------- Plus de 70 sociÃ©tÃ©s exposantes -------- Plus de 10000 visiteurs attendus <br />gauche<br />'),
(2, 10, 1, 69, 40, 'uk', 'a:3:{s:5:"titre";s:24:"TGVzIGNoaWZmcmVzIDIwMDg=";s:5:"texte";s:260:"PHA+MjAwMCBtJnN1cDI7IGQnZXhwb3NpdGlvbjxiciAvPiAtLS0tLS0tLTxiciAvPiA5MDAgbSZzdXAyOyBkZSBzdGFuZHM8YnIgLz4gLS0tLS0tLS08YnIgLz4gUGx1cyBkZSA3MCBzb2NpJmVhY3V0ZTt0JmVhY3V0ZTtzIGV4cG9zYW50ZXM8YnIgLz4gLS0tLS0tLS08YnIgLz4gUGx1cyBkZSAxMDAwMCB2aXNpdGV1cnMgYXR0ZW5kdXM8L3A+";s:10:"alignement";s:8:"Z2F1Y2hl";}', 'Les chiffres 2008<br />2000 mÂ² d''exposition -------- 900 mÂ² de stands -------- Plus de 70 sociÃ©tÃ©s exposantes -------- Plus de 10000 visiteurs attendus <br />gauche<br />'),
(3, 20, 1, 69, 40, 'fr', 'a:3:{s:5:"titre";s:24:"TGVzIGNoaWZmcmVzIDIwMDg=";s:5:"texte";s:320:"PHAgc3R5bGU9InRleHQtYWxpZ246IGNlbnRlcjsiPjxzdHJvbmc+MjAwMCBtJnN1cDI7IGQnZXhwb3NpdGlvbjxiciAvPiAtLS0tLS0tLTxiciAvPiA5MDAgbSZzdXAyOyBkZSBzdGFuZHM8YnIgLz4gLS0tLS0tLS08YnIgLz4gUGx1cyBkZSA3MCBzb2NpJmVhY3V0ZTt0JmVhY3V0ZTtzIGV4cG9zYW50ZXM8YnIgLz4gLS0tLS0tLS08YnIgLz4gUGx1cyBkZSAxMDAwMCB2aXNpdGV1cnMgYXR0ZW5kdXM8L3N0cm9uZz48L3A+";s:10:"alignement";s:8:"Z2F1Y2hl";}', 'Les chiffres 2008<br />2000 mÂ² d''exposition -------- 900 mÂ² de stands -------- Plus de 70 sociÃ©tÃ©s exposantes -------- Plus de 10000 visiteurs attendus <br />gauche<br />'),
(4, 10, 1, 69, 41, 'fr', 'a:3:{s:5:"titre";s:36:"RGVzdGluYXRpb24gbGUgcGF5cyBkJ0FpeA==";s:5:"texte";s:800:"PHA+PHN0cm9uZz5Ob3VzIHZvdXMgZG9ubm9ucyBkJ29yZXMgZXQgZCZlYWN1dGU7aiZhZ3JhdmU7IHJlbmRlei12b3VzIGR1IDEgYXUgMyBqdWluIHByb2NoYWluIHBvdXIgdml2cmUgZW5zZW1ibGUgbGUgMWVyIFNhbG9uIGRlIGwnSW1tb2JpbGllciBxdWkgciZlYWN1dGU7dW5pcmEgbGEgbWFqb3JpdCZlYWN1dGU7IGRlcyBhY3RldXJzIHF1aSBmb250IGwnSW1tb2JpbGllciBzdXIgbGUgUGF5cyBkJ0FpeCA6PC9zdHJvbmc+PGJyIC8+IDxiciAvPiBBZ2VuY2VzIGltbW9iaWxpJmVncmF2ZTtyZXMsIFByb21vdGV1cnMgZXQgY29tbWVyY2lhbGlzdGF0ZXVycywgQ29uc3RydWN0ZXVycw0KZGUgTWFpc29ucyBJbmRpdmlkdWVsbGVzLCBFdGFibGlzc2VtZW50cyBmaW5hbmNpZXJzLCBHZXN0aW9uIGRlDQpQYXRyaW1vaW5lIGV0IGQmZWFjdXRlO2Zpc2NhbGlzYXRpb24sIEFzc3VyYW5jZXMsIEYmZWFjdXRlO2QmZWFjdXRlO3JhdGlvbnMsIEluc3RpdHV0aW9ucywNClN5bmRpY2F0cyBwcm9mZXNzaW9ubmVscywgQXJjaGl0ZWN0ZXMsIFByZXNzZSwgQWN0aXZpdCZlYWN1dGU7cyBhbm5leGVzICZhZ3JhdmU7IGxhDQpjb25zdHJ1Y3Rpb24sIC4uLjwvcD4=";s:10:"alignement";s:8:"ZHJvaXRl";}', 'Destination le pays d''Aix<br />Nous vous donnons d''ores et dÃ©jÃ  rendez-vous du 1 au 3 juin prochain pour vivre ensemble le 1er Salon de l''Immobilier qui rÃ©unira la majoritÃ© des acteurs qui font l''Immobilier sur le Pays d''Aix :  Agences immobiliÃ¨res, Promoteurs et commercialistateurs, Constructeurs de Maisons Individuelles, Etablissements financiers, Gestion de Patrimoine et dÃ©fiscalisation, Assurances, FÃ©dÃ©rations, Institutions, Syndicats professionnels, Architectes, Presse, ActivitÃ©s annexes Ã  la construction, ... <br />droite<br />'),
(5, 10, 1, 65, 42, 'fr', 'a:2:{s:5:"texte";s:1336:"PHAgY2xhc3M9InRpdHJlX3JvdWdlIj5EYXRlczwvcD4NCjxwPjxiciAvPlZlbmRyZWRpIDEsIHNhbWVkaSAyIGV0IGRpbWFuY2hlIDMganVpbiAyMDA3PC9wPg0KPHA+PGJyIC8+PGJyIC8+PHNwYW4gY2xhc3M9InRpdHJlX3JvdWdlIj5MaWV1PC9zcGFuPjwvcD4NCjxwPjxiciAvPjxzdHJvbmc+UEFTSU5PPC9zdHJvbmc+LCBuaXZlYXUgMTxiciAvPjIxLCBhdmVudWUgZGUgbCdldXJvcGUgLSBCUCA0PGJyIC8+MTM2MDEgQUlYIEVOIFBST1ZFTkNFIENFREVYIDE8L3A+DQo8cD48YnIgLz48YnIgLz48c3BhbiBjbGFzcz0idGl0cmVfcm91Z2UiPkhvcmFpcmVzIGQnb3V2ZXJ0dXJlIGF1IGdyYW5kIHB1YmxpYzwvc3Bhbj48L3A+DQo8cD48YnIgLz48c3Ryb25nPlZlbmRyZWRpIDEganVpbiA6PC9zdHJvbmc+Jm5ic3A7Jm5ic3A7Jm5ic3A7IDE0aDAwICZhZ3JhdmU7IDE5aDAwPGJyIC8+PHN0cm9uZz5TYW1lZGkgMiBqdWluIDo8L3N0cm9uZz4mbmJzcDsmbmJzcDsmbmJzcDsgMTBoMDAgJmFncmF2ZTsgMTloMDA8YnIgLz48c3Ryb25nPkRpbWFuY2hlIDMganVpbiA6PC9zdHJvbmc+Jm5ic3A7Jm5ic3A7Jm5ic3A7IDEwaDAwICZhZ3JhdmU7IDE5aDAwPC9wPg0KPHA+PGJyIC8+PGJyIC8+PHNwYW4gY2xhc3M9InRpdHJlX3JvdWdlIj5UYXJpZjwvc3Bhbj48L3A+DQo8cD48YnIgLz5FbnRyJmVhY3V0ZTtlIDxzdHJvbmc+R1JBVFVJVEU8L3N0cm9uZz48L3A+DQo8cD4mbmJzcDs8L3A+DQo8cD48YnIgLz48c3BhbiBjbGFzcz0idGl0cmVfcm91Z2UiPlBhcmtpbmcgZHUgUGFzaW5vPC9zcGFuPjwvcD4NCjxwPiZuYnNwOzwvcD4NCjxwPkdyYXR1aXQgZXQgc3VydmVpbGwmZWFjdXRlOyAoNTAwIHBsYWNlcyk8L3A+DQo8cD48YnIgLz48YnIgLz48c3BhbiBjbGFzcz0idGl0cmVfcm91Z2UiPkhhbmRpY2FwJmVhY3V0ZTtzPC9zcGFuPjwvcD4NCjxwPjxiciAvPkxlIG5pdmVhdSAxIGR1IFBhc2lubyBlc3QgYWNjZXNzaWJsZSBhdXggaGFuZGljYXAmZWFjdXRlOzwvcD4=";s:10:"alignement";s:8:"Z2F1Y2hl";}', 'Dates  Vendredi 1, samedi 2 et dimanche 3 juin 2007  Lieu  PASINO, niveau 121, avenue de l''europe - BP 413601 AIX EN PROVENCE CEDEX 1  Horaires d''ouverture au grand public  Vendredi 1 juin :Â Â Â  14h00 Ã  19h00Samedi 2 juin :Â Â Â  10h00 Ã  19h00Dimanche 3 juin :Â Â Â  10h00 Ã  19h00  Tarif  EntrÃ©e GRATUITE  Â   Parking du Pasino  Â   Gratuit et surveillÃ© (500 places)  HandicapÃ©s  Le niveau 1 du Pasino est accessible aux handicapÃ© <br />gauche<br />'),
(6, 20, 1, 65, 41, 'fr', 'a:3:{s:5:"titre";s:28:"Q29tbWVudCBzJ3kgcmVuZHJlID8=";s:5:"texte";s:420:"PHA+PHN0cm9uZz5QYXIgbGEgcm91dGUgOjwvc3Ryb25nPiB2b2lyIHBsYW4gYXV0b3JvdXRpZXI8YnIgLz48YnIgLz48c3Ryb25nPlRyYWluIDo8L3N0cm9uZz4gU05DRiAtIEdhcmUgU2FpbnQgQ2hhcmxlczxiciAvPlJlbnNlaWduZW1lbnRzIHZveWFnZXVycyA6IDA4IDkyIDM1IDM1IDM1PGJyIC8+PGJyIC8+TSZlYWN1dGU7dHJvIGV0IGJ1cyA6IFJUTSAoUiZlYWN1dGU7c2VhdXggZGVzIFRyYW5zcG9ydHMgTWFyc2VpbGxhaXMpPGJyIC8+UmVuc2VpZ25lbWVudHMgdm95YWdldXJzIDogMDQgOTEgOTEgOTIgMTA8YnIgLz53d3cucnRtLmZyPC9wPg==";s:10:"alignement";s:8:"ZHJvaXRl";}', 'Comment s''y rendre ?<br />Par la route : voir plan autoroutierTrain : SNCF - Gare Saint CharlesRenseignements voyageurs : 08 92 35 35 35MÃ©tro et bus : RTM (RÃ©seaux des Transports Marseillais)Renseignements voyageurs : 04 91 91 92 10www.rtm.fr <br />droite<br />'),
(7, 30, 1, 65, 41, 'fr', 'a:3:{s:5:"titre";s:16:"U2VydmljZXMuLi4=";s:5:"texte";s:408:"PHA+PHN0cm9uZz5SZXN0YXVyYW50IDo8L3N0cm9uZz4gTGUgUGFzaW5vIHZvdXMgcHJvcG9zZSAzIHJlc3RhdXJhbnRzICZhZ3JhdmU7IHRoJmVncmF2ZTttZXMgKG9yaWVudGFsLCBpdGFsaWVuIGV0IGphcG9uYWlzKTxiciAvPjxiciAvPjxzdHJvbmc+Q29mZmVlIExvdW5nZSA6PC9zdHJvbmc+IE5vdXMgdm91cyBwcm9wb3NvbnMgJmVhY3V0ZTtnYWxlbWVudCB1biBzZXJ2aWNlIGRlIHJlc3RhdXJhdGlvbiByYXBpZGUgYXUgbml2ZWF1IGR1IEJhciAmYWdyYXZlOyBsJnJzcXVvO2ludCZlYWN1dGU7cmlldXIgZHUgU2Fsb24uPC9wPg==";s:10:"alignement";s:8:"ZHJvaXRl";}', 'Services...<br />Restaurant : Le Pasino vous propose 3 restaurants Ã  thÃ¨mes (oriental, italien et japonais)Coffee Lounge : Nous vous proposons Ã©galement un service de restauration rapide au niveau du Bar Ã  l&rsquo;intÃ©rieur du Salon. <br />droite<br />'),
(16, 50, 1, 60, 43, 'fr', 'a:9:{s:5:"titre";s:24:"Q29udGFjdGV6LW5vdXMuLi4=";s:5:"texte";s:436:"PHAgc3R5bGU9InRleHQtYWxpZ246IHJpZ2h0OyI+PHN0cm9uZz5BbGljZSBFdiZlYWN1dGU7bmVtZW50czwvc3Ryb25nPjxiciAvPiAyLCBBdmVudWUgRWxzYSBUcmlvbGV0PC9wPg0KPHAgc3R5bGU9InRleHQtYWxpZ246IHJpZ2h0OyI+QlAgNjI3PGJyIC8+IDEzMjY5IE1hcnNlaWxsZSBDZWRleCAwODxiciAvPiA8YnIgLz4gVCZlYWN1dGU7bC4gOiAwNCA5MSAxNiA1MiA1MjxiciAvPiBGYXggOiAwNCA5MSA3MiA1MSA1MTxiciAvPiA8YSBocmVmPSIuLi93d3cuYWxpY2UtZXZlbmVtZW50cy5jb20iPnd3dy5hbGljZS1ldmVuZW1lbnRzLmNvbTwvYT48L3A+DQombmJzcDs=";s:5:"image";s:4:"MQ==";s:12:"image_taille";s:8:"bWluaQ==";s:11:"image_popup";s:4:"MA==";s:13:"image_legende";s:4:"MA==";s:13:"image_credits";s:4:"MA==";s:12:"image_auteur";s:4:"MA==";s:10:"image_date";s:4:"MA==";}', 'Contactez-nous...<br />Alice Evénements 2, Avenue Elsa Triolet  BP 627 13269 Marseille Cedex 08  Tél. : 04 91 16 52 52 Fax : 04 91 72 51 51 www.alice-evenements.com   <br />1<br />mini<br />0<br />0<br />0<br />0<br />0<br />'),
(18, 10, 1, 66, 42, 'fr', 'a:2:{s:5:"texte";s:760:"PHAgY2xhc3M9InRpdHJlX3JvdWdlIj5KdWluIDIwMDggc2UgcHJvZmlsZSAmYWdyYXZlOyBsJ2hvcml6b248L3A+DQo8cD4mbmJzcDs8L3A+DQo8cD48c3Ryb25nPlVuZSBub3V2ZWxsZSBkYXRlLCB1biBub3V2ZWF1IHJlbmRlei12b3VzIGltYWdpbiZlYWN1dGU7IGV0IGNyJmVhY3V0ZTsmZWFjdXRlOyBwb3VyIHZvdXMuLi4gVW5lIGV4aWdlbmNlLCB1bmUgciZlYWN1dGU7YWxpdCZlYWN1dGU7Ljwvc3Ryb25nPjwvcD4NCjxwPiZuYnNwOzwvcD4NCjxwPkImZWFjdXRlO24mZWFjdXRlO2ZpY2lhbnQNCmR1IHNvdXRpZW4gZGUgbCdlbnNlbWJsZSBkZXMgZiZlYWN1dGU7ZCZlYWN1dGU7cmF0aW9ucyBldCBkZXMgaW5zdGl0dXRpb25zDQpwcm9mZXNzaW9ubmVsbGVzIGRlIGwnSW1tb2JpbGllciBldCBkZSBsYSByZWNvbm5haXNzYW5jZSogZGUgdm90cmUNCnByb2Zlc3Npb24sIGNldCAmZWFjdXRlO3YmZWdyYXZlO25lbWVudCB1bmlxdWUgc3VyIDIwMDBtMiBlc3QgdW5lIHImZWFjdXRlO2VsbGUgcGx1cyB2YWx1ZSB0YW50ICZlYWN1dGU7Y29ub21pcXVlIHF1ZSBtJmVhY3V0ZTtkaWF0aXF1ZS48L3A+";s:10:"alignement";s:8:"Z2F1Y2hl";}', 'Juin 2008 se profile à l''horizon     Une nouvelle date, un nouveau rendez-vous imaginé et créé pour vous... Une exigence, une réalité.     Bénéficiant du soutien de l''ensemble des fédérations et des institutions professionnelles de l''Immobilier et de la reconnaissance* de votre profession, cet évènement unique sur 2000m2 est une réelle plus value tant économique que médiatique. <br />gauche<br />'),
(17, 10, 1, 70, 43, 'fr', 'a:9:{s:5:"titre";s:24:"Q29udGFjdGV6LW5vdXMuLi4=";s:5:"texte";s:444:"PHAgc3R5bGU9InRleHQtYWxpZ246IHJpZ2h0OyI+PHN0cm9uZz5BbGljZSBFdiZlYWN1dGU7bmVtZW50czwvc3Ryb25nPjxiciAvPiAyLCBBdmVudWUgRWxzYSBUcmlvbGV0PC9wPg0KPHAgc3R5bGU9InRleHQtYWxpZ246IHJpZ2h0OyI+QlAgNjI3PGJyIC8+IDEzMjY5IE1hcnNlaWxsZSBDZWRleCAwODxiciAvPiA8YnIgLz4gVCZlYWN1dGU7bC4gOiAwNCA5MSAxNiA1MiA1MjxiciAvPiBGYXggOiAwNCA5MSA3MiA1MSA1MTxiciAvPiA8YSBocmVmPSIuLi93d3cuYWxpY2UtZXZlbmVtZW50cy5jb20iPnd3dy5hbGljZS1ldmVuZW1lbnRzLmNvbTwvYT48L3A+DQo8cD4mbmJzcDs8L3A+";s:5:"image";s:4:"MQ==";s:12:"image_taille";s:8:"bWluaQ==";s:11:"image_popup";s:4:"MA==";s:13:"image_legende";s:4:"MA==";s:13:"image_credits";s:4:"MA==";s:12:"image_auteur";s:4:"MA==";s:10:"image_date";s:4:"MA==";}', 'Contactez-nous...<br />Alice Evénements 2, Avenue Elsa Triolet  BP 627 13269 Marseille Cedex 08  Tél. : 04 91 16 52 52 Fax : 04 91 72 51 51 www.alice-evenements.com    <br />1<br />mini<br />0<br />0<br />0<br />0<br />0<br />'),
(19, 20, 1, 66, 41, 'fr', 'a:3:{s:5:"titre";s:52:"UHJvZml0ZXogZGUgY2UgcmVuZGV6LXZvdXMgc3VyIDMgam91cnM=";s:5:"texte";s:300:"PHVsPg0KPGxpPlJlbmNvbnRyZXogdm90cmUgY29ldXIgZGUgY2libGU8L2xpPg0KPGxpPlByb21vdXZvaXIgdm9zIHByb2R1aXRzIGV0IHZhbG9yaXNlciB2b3RyZSBpbWFnZTwvbGk+DQo8bGk+TXVsdGlwbGllciB2b3MgY29udGFjdHMgZXQgQm9vc3RlciB2b3RyZSBhY3Rpdml0JmVhY3V0ZTsgZGFucyB1biBlbnZpcm9ubmVtZW50IHBlcmZvcm1hbnQgZXQgZHluYW1pcXVlPC9saT4NCjwvdWw+";s:10:"alignement";s:8:"ZHJvaXRl";}', 'Profitez de ce rendez-vous sur 3 jours<br /> Rencontrez votre coeur de cible  Promouvoir vos produits et valoriser votre image  Multiplier vos contacts et Booster votre activité dans un environnement performant et dynamique   <br />droite<br />'),
(20, 30, 1, 66, 41, 'fr', 'a:3:{s:5:"titre";s:48:"RS1maWNoZSA6IG9wdGltaXNleiB2b3RyZSBwculzZW5jZQ==";s:5:"texte";s:248:"PHVsPg0KPGxpPkNvbW11bmlxdWVyIGVuIGFtb250LCBwZW5kYW50IGV0IGFwciZlZ3JhdmU7cyBsZSBzYWxvbjwvbGk+DQo8bGk+R2FnbmVyIGVuIHZpc2liaWxpdCZlYWN1dGU7PC9saT4NCjxsaT5NZXR0cmUgc291cyBsZSBmZXUgZGVzIHByb2plY3RldXJzIHZvcyBwcm9kdWl0cyBldCBzZXJ2aWNlczwvbGk+DQo8L3VsPg==";s:10:"alignement";s:8:"ZHJvaXRl";}', 'E-fiche : optimisez votre présence<br /> Communiquer en amont, pendant et après le salon  Gagner en visibilité  Mettre sous le feu des projecteurs vos produits et services   <br />droite<br />'),
(21, 10, 1, 63, 42, 'fr', 'a:2:{s:5:"texte";s:1356:"PHA+PHNwYW4gY2xhc3M9InRpdHJlX3JvdWdlIj5Wb3RyZSBwYXJ0aWNpcGF0aW9uIGNvbXByZW5kPC9zcGFuPjwvcD4NCjxwPiZuYnNwOzwvcD4NCjx1bD4NCjxsaT5JbnNjcmlwdGlvbiBkZSB2b3RyZSBzb2NpJmVhY3V0ZTt0JmVhY3V0ZTsgc3VyIGxlIHNpdGUgSW50ZXJuZXQsIGRhbnMgbGUgJmxhcXVvOyBEb3NzaWVyIFNwJmVhY3V0ZTtjaWFsIFNhbG9uICZyYXF1bzsgZGUgTEEgUFJPVkVOQ0UgZXQgVE9QIEFOTk9OQ0VTLCBkYW5zIGxhIGxpc3RlIGRlcyBleHBvc2FudHMgZGlzdHJpYnUmZWFjdXRlO2UgJmFncmF2ZTsgdG91cyBsZXMgdmlzaXRldXJzPC9saT4NCjxsaT5CYWRnZXMgZXhwb3NhbnRzIHBhciB0cmFuY2hlIGRlIDltMiAoc3RhbmQgJmVhY3V0ZTtxdWlwJmVhY3V0ZTspPC9saT4NCjxsaT5BY2MmZWdyYXZlO3MgcGFya2luZyBwYXIgdHJhbmNoZSBkZSA5bTIgKHN0YW5kICZlYWN1dGU7cXVpcCZlYWN1dGU7KTwvbGk+DQo8bGk+TW9iaWxpZXIgZXQgJmVhY3V0ZTtsZWN0cmljaXQmZWFjdXRlOyAocG91ciBzdGFuZCAmZWFjdXRlO3F1aXAmZWFjdXRlOyAtIHZvaXIgbGEgQnJvY2h1cmUpPC9saT4NCjxsaT5OZXR0b3lhZ2UgZGVzIHN0YW5kczwvbGk+DQo8L3VsPg0KPHA+Jm5ic3A7PC9wPg0KPHA+Jm5ic3A7PC9wPg0KPHA+PHNwYW4gY2xhc3M9InRpdHJlX3JvdWdlIj5Eb3NzaWVyIHRlY2huaXF1ZTwvc3Bhbj48L3A+DQo8cD4mbmJzcDs8L3A+DQo8cD5DZSBkb2N1bWVudCB2b3VzIGRvbm5lIHRvdXMgbGVzDQpyZW5zZWlnbmVtZW50cyBldCBwcmVzdGF0aW9ucyB1dGlsZXMgJmFncmF2ZTsgbCdvcmdhbmlzYXRpb24gZGUgdm90cmUNCnByJmVhY3V0ZTtzZW5jZS4gRCZlZ3JhdmU7cyByJmVhY3V0ZTtjZXB0aW9uIGRlIHZvdHJlIERlbWFuZGUgZGUgUGFydGljaXBhdGlvbiwgbm91cyB2b3VzDQphZHJlc3NvbnMgdW4gY291cnJpZXIgYXZlYyB2b3RyZSBsb2dpbiBldCBtb3QgZGUgcGFzc2UgcHJpdiZlYWN1dGU7cyBwb3VyIHkNCmFjYyZlYWN1dGU7ZGVyPC9wPg==";s:10:"alignement";s:8:"Z2F1Y2hl";}', 'Votre participation comprend      Inscription de votre société sur le site Internet, dans le « Dossier Spécial Salon » de LA PROVENCE et TOP ANNONCES, dans la liste des exposants distribuée à tous les visiteurs  Badges exposants par tranche de 9m2 (stand équipé)  Accès parking par tranche de 9m2 (stand équipé)  Mobilier et électricité (pour stand équipé - voir la Brochure)  Nettoyage des stands          Dossier technique     Ce document vous donne tous les renseignements et prestations utiles à l''organisation de votre présence. Dès réception de votre Demande de Participation, nous vous adressons un courrier avec votre login et mot de passe privés pour y accéder <br />gauche<br />'),
(22, 20, 1, 63, 16, 'fr', 'a:1:{s:5:"texte";s:804:"PHA+Jm5ic3A7PC9wPg0KPHA+PHNwYW4gY2xhc3M9InRpdHJlX3JvdWdlIj5EYXRlcyBldCBob3JhaXJlcyB1dGlsZTwvc3Bhbj48L3A+DQo8cD4mbmJzcDs8L3A+DQo8cD48c3Ryb25nPk1vbnRhZ2UgZGVzIHN0YW5kczwvc3Ryb25nPjxiciAvPiBEJmVhY3V0ZTttb250YWdlIGRlcyBzdGFuZHM8YnIgLz4gPHN0cm9uZz48YnIgLz4gT3V2ZXJ0dXJlIGF1IHB1YmxpYzwvc3Ryb25nPjxiciAvPiBWZW5kcmVkaSA6IGRlIDE0aDAwICZhZ3JhdmU7IDE5aDAwPGJyIC8+IFNhbWVkaSAmYW1wOyBEaW1hbmNoZSA6IGRlIDEwaDAwICZhZ3JhdmU7IDE5aDAwPC9wPg0KPHA+Jm5ic3A7PC9wPg0KPHA+Jm5ic3A7PC9wPg0KPHA+PHNwYW4gY2xhc3M9InRpdHJlX3JvdWdlIj5MaWV1PC9zcGFuPjwvcD4NCjxwPiZuYnNwOzwvcD4NCjxwPlBBU0lOTywgbml2ZWF1IDEsPC9wPg0KPHA+MjEsIEF2ZW51ZSBkZSBsJ0V1cm9wZSAtIEJQIDQ8L3A+DQo8cD4xMzYwMSBBSVggRU4gUFJPVkVOQ0UgQ0VERVggMTwvcD4NCjxwPiZuYnNwOzwvcD4NCjxwPiZuYnNwOzwvcD4NCjxwPjxzcGFuIGNsYXNzPSJ0aXRyZV9yb3VnZSI+Q29tbWVudCBzJ3kgcmVuZHJlID88L3NwYW4+PC9wPg==";}', '   Dates et horaires utile     Montage des stands Démontage des stands  Ouverture au public Vendredi : de 14h00 à 19h00 Samedi & Dimanche : de 10h00 à 19h00        Lieu     PASINO, niveau 1,  21, Avenue de l''Europe - BP 4  13601 AIX EN PROVENCE CEDEX 1        Comment s''y rendre ? <br />'),
(23, 30, 1, 63, 37, 'fr', 'a:3:{s:6:"source";s:476:"aHR0cDovL21hcHMuZ29vZ2xlLmNvbS9tYXBzP2Y9cSZhbXA7aGw9ZnImYW1wO2dlb2NvZGU9JmFtcDtxPVBBU0lOTywrMjEsK0F2ZW51ZStkZStsJ0V1cm9wZSwrQUlYK0VOK1BST1ZFTkNFLCtGUkFOQ0UmYW1wO3NsbD00My41MjgxNCw1LjQzOTE3NyZhbXA7c3Nwbj0wLjA0NTI0MSwwLjA5MTQ5NiZhbXA7aWU9VVRGOCZhbXA7Y2lkPTQzNTIzMzc5LDU0Mzk0NTYsMTYwNTc1NDI1MjM1Mzk0NTgzMCZhbXA7cz1BQVJUc0pvMWt1TzRqdTNQd2xWNWZCWGpGaWxxRWMwQWRnJmFtcDtsbD00My41NDE0NTYsNS40NDU0NDImYW1wO3Nwbj0wLjA0MzU1MiwwLjA3Mjk1NiZhbXA7ej0xMyZhbXA7aXdsb2M9QSZhbXA7b3V0cHV0PWVtYmVk";s:5:"width";s:8:"MTAwJQ==";s:6:"height";s:4:"MzMw";}', 'http://maps.google.com/maps?f=q&hl=fr&geocode=&q=PASINO,+21,+Avenue+de+l''Europe,+AIX+EN+PROVENCE,+FRANCE&sll=43.52814,5.439177&sspn=0.045241,0.091496&ie=UTF8&cid=43523379,5439456,1605754252353945830&s=AARTsJo1kuO4ju3PwlV5fBXjFilqEc0Adg&ll=43.541456,5.445442&spn=0.043552,0.072956&z=13&iwloc=A&output=embed<br />100%<br />330<br />'),
(25, 10, 1, 61, 3, 'fr', 'a:1:{s:5:"titre";s:24:"RGV2ZW5leiBleHBvc2FudA==";}', 'Devenez exposant<br />'),
(26, 20, 1, 61, 18, 'fr', 'a:8:{s:5:"image";s:4:"Mw==";s:12:"image_taille";s:8:"bWVkaXVt";s:11:"image_popup";s:4:"MA==";s:13:"image_legende";s:4:"MA==";s:13:"image_credits";s:4:"MA==";s:12:"image_auteur";s:4:"MA==";s:10:"image_date";s:4:"MA==";s:10:"alignement";s:20:"R2F1Y2hlIGZsb3R0YW50";}', '3<br />medium<br />0<br />0<br />0<br />0<br />0<br />Gauche flottant<br />'),
(27, 40, 1, 61, 25, 'fr', 'a:2:{s:8:"document";a:4:{i:0;s:4:"MTE=";i:1;s:4:"OA==";i:2;s:4:"OQ==";i:3;s:4:"MTA=";}s:14:"instance_count";a:1:{i:0;s:4:"Mw==";}}', '<br /><br />'),
(28, 30, 1, 61, 5, 'fr', 'a:1:{s:5:"titre";s:44:"UG91ciBy6XNlcnZlciB2b3RyZSBwcm9wcmUgc3RhbmQ=";}', 'Pour réserver votre propre stand<br />'),
(29, 60, 1, 61, 3, 'fr', 'a:1:{s:5:"titre";s:64:"VmFsb3Jpc2V6IHZvdHJlIHBy6XNlbmNlIHN1ciBJbnRlcm5ldCA6IGUtRmljaGU=";}', 'Valorisez votre présence sur Internet : e-Fiche<br />'),
(30, 80, 1, 61, 5, 'fr', 'a:1:{s:5:"titre";s:52:"T3B0aW1pc2V6IHZvdHJlIHBy6XNlbmNlIHN1ciBub3RyZSBzaXRl";}', 'Optimisez votre présence sur notre site<br />'),
(31, 90, 1, 61, 12, 'fr', 'a:2:{s:4:"item";a:3:{i:0;s:88:"Q29tbXVuaXF1ZXIgZW4gYW1vbnQsIHBlbmRhbnQgZXQgYXBy6HMgbGUgc2Fsb24gc3VyIHZvdHJlIGFjdGl2aXTp";i:1;s:28:"R2FnbmVyIGVuIHZpc2liaWxpdOk=";i:2;s:80:"TWV0dHJlIHNvdXMgbGUgZmV1IGRlcyBwcm9qZWN0ZXVycyB2b3MgcHJvZHVpdHMgZXQgc2VydmljZXM=";}s:14:"instance_count";a:1:{i:0;s:4:"Mg==";}}', '<br /><br />'),
(32, 100, 1, 61, 5, 'fr', 'a:1:{s:5:"titre";s:72:"ImUtRmljaGUiIGRhbnMgbGUgZm9ybXVsYWlyZSBkZSBkZW1hbmRlIGQnaW5zY3JpcHRpb24=";}', '"e-Fiche" dans le formulaire de demande d''inscription<br />'),
(33, 120, 1, 61, 3, 'fr', 'a:1:{s:5:"titre";s:16:"SW5zY3JpcHRpb24=";}', 'Inscription<br />'),
(34, 70, 1, 61, 18, 'fr', 'a:8:{s:5:"image";s:4:"Mg==";s:12:"image_taille";s:8:"bWVkaXVt";s:11:"image_popup";s:4:"MA==";s:13:"image_legende";s:4:"MA==";s:13:"image_credits";s:4:"MA==";s:12:"image_auteur";s:4:"MA==";s:10:"image_date";s:4:"MA==";s:10:"alignement";s:20:"R2F1Y2hlIGZsb3R0YW50";}', '2<br />medium<br />0<br />0<br />0<br />0<br />0<br />Gauche flottant<br />'),
(35, 130, 1, 61, 18, 'fr', 'a:8:{s:5:"image";s:4:"NQ==";s:12:"image_taille";s:8:"bWVkaXVt";s:11:"image_popup";s:4:"MA==";s:13:"image_legende";s:4:"MA==";s:13:"image_credits";s:4:"MA==";s:12:"image_auteur";s:4:"MA==";s:10:"image_date";s:4:"MA==";s:10:"alignement";s:20:"R2F1Y2hlIGZsb3R0YW50";}', '5<br />medium<br />0<br />0<br />0<br />0<br />0<br />Gauche flottant<br />'),
(36, 140, 1, 61, 16, 'fr', 'a:1:{s:5:"texte";s:256:"PHA+VmV1aWxsZXogcmVtcGxpciBsZSBmb3JtdWxhaXJlIGFmaW4gcXVlIG5vdXMgcHVpc3Npb25zIHZvdXMgY29udGFjdGVyDQpldCB2b3VzIGFkcmVzc2VyIHVuZSBwcm9wb3NpdGlvbiBhZGFwdCZlYWN1dGU7ZSAmYWdyYXZlOyB2b3Mgb2JqZWN0aWZzIGF2ZWMgbGENCmRlbWFuZGUgZGUgcGFydGljaXBhdGlvbiAoY29udHJhdCk8L3A+";}', 'Veuillez remplir le formulaire afin que nous puissions vous contacter et vous adresser une proposition adaptée à vos objectifs avec la demande de participation (contrat) <br />'),
(37, 50, 1, 61, 1, 'fr', NULL, NULL),
(38, 110, 1, 61, 1, 'fr', NULL, NULL),
(43, 10, 1, 72, 39, 'fr', 'a:9:{s:5:"titre";s:36:"Rm9ybXVsYWlyZSBwculpbnNjcmlwdGlvbg==";s:5:"texte";s:488:"PHAgc3R5bGU9InRleHQtYWxpZ246IHJpZ2h0OyI+PHN0cm9uZz5BbGljZSBFdiZlYWN1dGU7bmVtZW50czwvc3Ryb25nPjxiciAvPiAyLCBBdmVudWUgRWxzYSBUcmlvbGV0PC9wPg0KPHAgc3R5bGU9InRleHQtYWxpZ246IHJpZ2h0OyI+QlAgNjI3PGJyIC8+IDEzMjY5IE1hcnNlaWxsZSBDZWRleCAwODxiciAvPiA8YnIgLz4gVCZlYWN1dGU7bC4gOiAwNCA5MSAxNiA1MiA1MjxiciAvPiBGYXggOiAwNCA5MSA3MiA1MSA1MTxiciAvPiA8YSBocmVmPSJodHRwOi8vd3d3LnNhbG9uLWltbW9iaWxpZXItYWl4LmNvbS93d3cuYWxpY2UtZXZlbmVtZW50cy5jb20iPnd3dy5hbGljZS1ldmVuZW1lbnRzLmNvbTwvYT48L3A+DQo8cD4mbmJzcDs8L3A+";s:5:"image";s:4:"MQ==";s:12:"image_taille";s:8:"bWluaQ==";s:11:"image_popup";s:4:"MA==";s:13:"image_legende";s:4:"MA==";s:13:"image_credits";s:4:"MA==";s:12:"image_auteur";s:4:"MA==";s:10:"image_date";s:4:"MA==";}', 'Formulaire préinscription<br />Alice Evénements 2, Avenue Elsa Triolet  BP 627 13269 Marseille Cedex 08  Tél. : 04 91 16 52 52 Fax : 04 91 72 51 51 www.alice-evenements.com    <br />1<br />mini<br />0<br />0<br />0<br />0<br />0<br />'),
(41, 150, 1, 61, 44, 'fr', 'a:3:{s:4:"page";s:32:"cHJlLWluc2NyaXB0aW9uLXI3Mi5odG1s";s:9:"titrelien";s:40:"Rm9ybXVsYWlyZSBkZSBwcuktaW5zY3JpcHRpb24=";s:5:"cible";s:8:"X3NlbGY=";}', 'pre-inscription-r72.html<br />Formulaire de pré-inscription<br />_self<br />');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

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
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- 
-- Contenu de la table `dat_trad_textes`
-- 

