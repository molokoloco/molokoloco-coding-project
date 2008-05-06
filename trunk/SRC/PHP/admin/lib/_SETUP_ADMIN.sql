-- --------------------------------------------------------
--
-- LISTE DES TABLES
--
-- admin_parametres -- 
-- admin_utilisateurs -- 
-- cms_blocs -- 
-- cms_pages -- 
-- cms_pages_elements -- 
-- cms_pages_relation_cms_blocs --
-- cms_pages_types --
-- dat_bibliotheque_fichiers -- 
-- dat_bibliotheque_fichiers_cat -- 
-- dat_bibliotheque_images -- 
-- dat_bibliotheque_images_cat -- 
-- dat_email_alertes -- 
--
-- version SQL 2.11.1
-- Base de données: `vad_cms` -- 
--
-- --------------------------------------------------------

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Structure de la table 'admin_parametres'
--

DROP TABLE IF EXISTS admin_parametres;
CREATE TABLE admin_parametres (
  id int(8) NOT NULL auto_increment,
  email varchar(250) default NULL,
  meta_title_fr varchar(65) default NULL,
  meta_url_fr varchar(250) default NULL,
  meta_desc_fr text,
  meta_key_fr text,
  logoc varchar(70) default NULL,
  logoa varchar(70) default NULL,
  paginationa tinyint(3) default NULL,
  pagination tinyint(3) default NULL,
  fontcolor1 varchar(7) NOT NULL default '#FFFFFF',
  fontcolor2 varchar(7) NOT NULL default '#666666',
  linkcolor varchar(7) NOT NULL default '#016AC5',
  linkcoloron varchar(7) NOT NULL default '#FF0000',
  bgcolor1 varchar(7) NOT NULL default '#FFFFFF',
  bgcolor2 varchar(7) NOT NULL default '#999999',
  ligneentete varchar(7) NOT NULL default '#BBBBBB',
  ligne1 varchar(7) NOT NULL default '#E4E4E4',
  ligne2 varchar(7) NOT NULL default '#F4F4F4',
  ligneon varchar(7) NOT NULL default '#FFFFFF',
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table 'admin_parametres'
--

INSERT INTO admin_parametres (id, email, meta_title_fr, meta_url_fr, meta_desc_fr, meta_key_fr, logoc, logoa, paginationa, pagination, fontcolor1, fontcolor2, linkcolor, linkcoloron, bgcolor1, bgcolor2, ligneentete, ligne1, ligne2, ligneon) VALUES
(1, 'jguezennec@agence-clark.com', 'VAD SITE REF CMS', 'http://clarkprod/VAD_SITE_REF_CMS/SITE_DEV/', 'Méta description par défaut des pages', 'Précisez\r\nchaque\r\nmot\r\nsur une ligne', '', '', 0, 0, '#FFFFFF', '#666666', '#016AC5', '#FF0000', '#FFFFFF', '#999999', '#BBBBBB', '#E4E4E4', '#F4F4F4', '#FFFFFF');

-- --------------------------------------------------------

--
-- Structure de la table 'admin_utilisateurs'
--

DROP TABLE IF EXISTS admin_utilisateurs;
CREATE TABLE admin_utilisateurs (
  id int(8) NOT NULL auto_increment,
  actif tinyint(1) NOT NULL default '1',
  `type` tinyint(1) NOT NULL default '1',
  email varchar(250) default NULL,
  `password` varchar(250) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table 'admin_utilisateurs'
--

INSERT INTO admin_utilisateurs (id, actif, type, email, password) VALUES
(1, 1, 3, 'admin@admin.com', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table 'cms_blocs'
--

DROP TABLE IF EXISTS cms_blocs;
CREATE TABLE cms_blocs (
  id int(8) NOT NULL auto_increment,
  titre varchar(250) default NULL,
  description text,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table 'cms_blocs'
--

INSERT INTO cms_blocs (id, titre, description) VALUES
(1, 'Inscription Newsletter', ''),
(2, '[MENU] Sous-rubriques', 'Liste les sous-rubriques ou les éléments de module en cours');

-- --------------------------------------------------------

--
-- Structure de la table 'cms_elements_types'
--

DROP TABLE IF EXISTS cms_elements_types;
CREATE TABLE cms_elements_types (
  id int(8) NOT NULL auto_increment,
  actif tinyint(1) NOT NULL default '1',
  ordre int(4) NOT NULL default '1',
  `type` int(8) default NULL,
  titre varchar(250) default NULL,
  valeurs varchar(250) default NULL,
  template text,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Contenu de la table 'cms_elements_types'
--

INSERT INTO cms_elements_types (id, actif, ordre, type, titre, valeurs, template) VALUES
(1, 1, 250, 4, 'Espacement vertical', '', '<br class="portlet cms_br" />'),
(2, 1, 160, 4, 'Ligne de séparation', '', '<hr class="portlet cms_hr" />'),
(3, 1, 190, 0, 'Titre 1', 'item:Libellé|titre|text', '<div class="portlet cms_h1">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h1>''.$titre.''</h1>\r\n		</div>\r\n	</div>\r\n</div>'),
(4, 1, 180, 0, 'Titre 2', 'item:Libellé|titre|text', '<div class="portlet cms_h2">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h2>''.$titre.''</h2>\r\n		</div>\r\n	</div>\r\n</div>'),
(5, 1, 170, 0, 'Titre 3', 'item:Libellé|titre|text', '<div class="portlet cms_h3">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h3>''.$titre.''</h3>\r\n		</div>\r\n	</div>\r\n</div>'),
(6, 1, 150, 0, 'Titre 4', 'item:Libellé|titre|text', '<div class="portlet cms_h4">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h4>''.$titre.''</h4>\r\n		</div>\r\n	</div>\r\n</div>'),
(7, 1, 70, 1, 'Texte simple', 'item:Texte|texte|textarea', '<div class="portlet cms_p">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<p>''.$texte.''</p>\r\n		</div>\r\n	</div>\r\n</div>'),
(8, 1, 120, 1, 'Texte important', 'item:Texte|texte|textarea', '<div class="portlet cms_p_strong">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<p><strong>''.$texte.''</strong></p>\r\n		</div>\r\n	</div>\r\n</div>'),
(9, 1, 80, 1, 'Texte italique', 'item:Texte|texte|textarea', '<div class="portlet cms_p_note">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<p><em>''.$texte.''</em></p>\r\n		</div>\r\n	</div>\r\n</div>'),
(10, 1, 100, 1, 'Texte défilant', 'item:Texte|texte|textarea,Direction|direction|enum[left/right,Distance|scrollamount|num(2,Délai|scrolldelay|num(1', '<div class="portlet cms_marquee">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<marquee direction="''.$direction.''" scrollamount="''.$scrollamount.''" scrolldelay="''.$scrolldelay.''" >\r\n				<p>''.$texte.''</p>\r\n			</marquee>\r\n		</div>\r\n	</div>\r\n</div>'),
(11, 1, 230, 1, 'Texte format code', 'item:Code|texte|textarea', '<div class="portlet cms_code">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<pre><code>''.$texte.''</code></pre>\r\n		</div>\r\n	</div>\r\n</div>'),
(12, 1, 20, 2, 'Liste à puce', 'items:Item de liste|item|text', '<div class="portlet cms_ul">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<ul>'';\r\n			foreach((array)$items as $item)\r\n				$html .= ''<li>''.$item[''item''].''</li>'';\r\n			$html .= ''</ul>\r\n		</div>\r\n	</div>\r\n</div>'),
(13, 1, 90, 2, 'Liste numérotée', 'items:Item de liste|value|text', '<div class="portlet cms_ol">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<ol>'';\r\n			foreach((array)$values as $value)\r\n				$html .= ''<li>''.$value[''value''].''</li>'';\r\n			$html .= ''</ol>\r\n		</div>\r\n	</div>\r\n</div>'),
(14, 1, 220, 2, 'Liste de définition', 'items:Titre|titre|text,Définition|definition|textarea', '<div class="portlet cms_definition">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<d1>'';\r\n			foreach((array)$titres as $k=>$titre)\r\n				$html .= ''<dt>''.$titre[''titre''].''</dt>\r\n				<dd>''.$definitions[$k][''definition''].''</dd>'';\r\n			$html .= ''</d1>\r\n		</div>\r\n	</div>\r\n</div>'),
(15, 1, 240, 1, 'Texte citation', 'item:Citation|citation|textarea,Auteur|auteur|text', '<div class="portlet cms_quotation">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<blockquote>\r\n				''.(!empty($citation) ? ''<p>''.$citation.''</p>'' : '''').''\r\n				''.(!empty($auteur) ? ''<cite>''.$auteur.''</cite>'' : '''').''\r\n			</blockquote>\r\n		</div>\r\n	</div>\r\n</div>'),
(16, 1, 60, 1, 'Texte html', 'item:texte|texte|html', '<div class="portlet cms_rte">\r\n	''.$texte.''\r\n</div>'),
(17, 1, 50, 1, 'Texte html et image', 'item:Texte|texte|html,Image|image|image,Alignement|alignement|enum[gauche/droite(gauche', '<div class="portlet cms_txt_img">\r\n	<div class="visu_''.$alignement.''">\r\n		''.( $popup == 1 ? ''<a href="''.$image_grande.''" rel="popimg">'' : '''').\r\n		''<img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/>''.\r\n		( $popup == 1 ? ''</a>'' : '''').\r\n		(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n		(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n		(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n		(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''')\r\n		.''\r\n	</div>\r\n	''.$texte.''\r\n</div>'),
(18, 1, 130, 3, 'Image', 'item:Image|image|image', '<div class="portlet cms_img">\r\n	''.($popup == 1 ? ''<a href="''.$image_grande.''" rel="popimg">'' : '''').''<img src="''.$image_src.''" alt="''.$image_titre.''"  border="0"/>''.( $popup == 1 ? ''</a>'' : '''').''\r\n	''.(!empty($image_legende) ? ''<cite>''.$image_legende.''</cite>'' : '''').\r\n	(!empty($image_auteur) ? ''<cite>''.$image_auteur.''</cite>'' : '''').\r\n	(!empty($image_credits) ? ''<cite>''.$image_credits.''</cite>'' : '''').\r\n	(!empty($image_date) ? ''<cite>''.$image_date.''</cite>'' : '''').''\r\n</div>'),
(19, 0, 30, 4, 'Table', '', ''),
(20, 1, 200, 1, 'Texte avec Focus', 'item:Titre|titre|text,Texte|texte|html,Lien|url_href|text,Titre du lien|url_titre|text,Document|document|fichier', '<div class="portlet cms_focus">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<h1>''.$titre.''</h1>\r\n			''.$texte. ( !empty($url_href) || !empty($document) ? \r\n			''<ul class="liens">''.\r\n			( !empty($url_href)  ? ''<li class="url"><a href="''.$url_href.''" target="_blank">''.($url_titre ? $url_titre : $url_href).''</a></li>'' : '''' ). \r\n			( !empty($document)  ? ''<li class="doc"><a href="''.$document_src.''" target="_blank">''.($document_titre?$document_titre:$document).''</a></li>'': '''' ).\r\n			''</ul>''\r\n			: '''' ).''\r\n		</div>\r\n	</div>\r\n</div>'),
(21, 1, 280, 4, 'Lien simple', 'item:Lien|lien|text,Titre du lien|titrelien|text,Cible|cible|enum[_blank/_self(_blank', '<div class="portlet cms_a_download">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<a href="''.$lien.''" target="''.$cible.''">''.($titrelien ? $titrelien : $lien).''</a>\r\n		</div>\r\n	</div>\r\n</div>'),
(31, 1, 300, 3, 'Galerie d''images', 'items:Visuel|image|image', '<div class="portlet cms_galerie">\r\n	<div class="d1">\r\n		<div class="d2">'';\r\n			foreach($images as $image)\r\n				$html .= ''<div style="float:left; width:90px; height:70px; overflow:hidden; margin:10px;"><img src="''.$image[''image_src''].''"></div>'';\r\n		$html .= ''</div>\r\n	</div>\r\n</div>'),
(23, 1, 110, 3, 'Lien document', 'item:Document|document|fichier', '<div class="portlet cms_a_download">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<a href="''.$document_src.''" target="_blank">''.($document_titre != '''' ? $document_titre : $document).''</a> (''.$document_ext.'' - ''.$document_size.'')\r\n		</div>\r\n	</div>\r\n</div>'),
(24, 1, 270, 2, 'Liste de liens', 'items:Lien|lien|text,Titre du lien|titrelien|text,Cible|cible|enum[_blank/_self(_blank', '<div class="portlet cms_a_list">\r\n	<div class="d1">\r\n		<div class="d2">\r\n			<ul>'';\r\n			foreach($liens as $k=>$lien)\r\n				$html .= ''<li><a href="''.$lien[''lien''].''" target="''.$cible.''">''.($titreliens[$k][''titrelien''] ? $titreliens[$k][''titrelien''] : $lien[''lien'']).''</a></li>'';\r\n			$html .= ''</ul>\r\n		</div>\r\n	</div>\r\n</div>'),
(25, 1, 290, 2, 'Liste de documents', 'items:Document|document|fichier', '<div class="portlet cms_a_list">\r\n	<div class="d1">\r\n		<div class="d2">		\r\n			<ul>'';\r\n			foreach((array)$documents as $document)\r\n				$html .= ''<li><a href="''.$document[''document_src''].''">''.($document[''document_titre''] ? $document[''document_titre''] : $document[''document'']).''</a> (''.$document[''document_ext''].'' - ''.$document[''document_size''].'')</li>'';\r\n			$html .= ''</ul>\r\n		</div>\r\n	</div>\r\n</div>'),
(26, 1, 10, 3, 'Flash Vidéo (flv)', 'item:Fichier flv|source|fichier,Couleur fond|bgcolor|text', '<div class="portlet cms_video">\r\n	<div id="flash_swf_''.$unique_id.''"><p>Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a> pour lire cette vidéo</p></div>\r\n	#script#\r\n	// <![CDATA[\r\n		var so = new SWFObject("''.$WWW.''cms_files/player_320x240.swf", "swf_flash_''.$unique_id.''", "322", "271", "9", "");	\r\n		''.(!empty($bgcolor) ? ''so.addParam("bgcolor", "''.$bgcolor.''");'' : '''').''\r\n		so.addParam("quality", "high");\r\n		so.addParam("salign", "TL");\r\n		so.addParam("allowFullScreen", "true");	\r\n		so.addVariable("sPathFlv", "''.$source_src.''");\r\n		so.write("flash_swf_''.$unique_id.''");\r\n	// ]]>\r\n	#/script#\r\n</div>'),
(27, 1, 140, 4, 'Iframe', 'item:Source|source|text,Largeur|width|text(100%,Hauteur|height|text(320,Scroll|scrolling|enum[auto/no/yes(auto', '<div class="portlet cms_iframe">\r\n	#iframe# src="''.$source.''" id="''.$unique_id.''_frame" name="''.$unique_id.''_frame" width="''.$width.''" height="''.$height.''" scrolling="''.$scrolling.''" frameborder="0" allowtransparency="1">#/iframe#\r\n</div>'),
(28, 1, 210, 3, 'Flash (swf)', 'item:Fichier swf|source|fichier,Largeur|width|num,Hauteur|height|num,Version|version|num,Couleur fond|bgcolor|text', '<div class="portlet cms_flash">\r\n	<div id="flash_swf_''.$unique_id.''"><p>Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a> pour lire cette animation</p></div>\r\n	#script#\r\n	// <![CDATA[\r\n		var so = new SWFObject("''.$source_src.''", "swf_flash_''.$unique_id.''", "''.$width.''", "''.$height.''", "''.$version.''", "");	\r\n		''.(!empty($bgcolor) ? ''so.addParam("bgcolor", "''.$bgcolor.''");'' : ''so.addParam(''wmode'',''transparent'');'').''\r\n		so.write("flash_swf_''.$unique_id.''");\r\n	// ]]>\r\n	#/script#\r\n</div>'),
(29, 0, 260, 4, 'Bouton', '', ''),
(30, 0, 40, 4, 'Sommaire', '', ''),
(32, 1, 310, 3, 'Flash Vidéo II (flv)', 'item:Fichier flv|source|fichier,Largeur|width|num,Hauteur|height|num,autoStart|autostart|enum[true/false(true,Boutton FS|showfsbutton|enum[true/false(true', '<div class="portlet cms_video">\r\n	<div id="flash_swf_''.$unique_id.''"><p>Vous devez disposer du <a href="http://fpdownload.macromedia.com/get/flashplayer/current/install_flash_player.exe" target="_blank">Player Flash</a> pour lire cette vidéo</p></div>\r\n	#script#\r\n	// <![CDATA[\r\n		var so = new SWFObject("''.$WWW.''cms_files/anarchy-source-files/flvplayer.swf", "swf_flash_''.$unique_id.''", "''.$width.''", "''.$height.''", "7", "");	\r\n		so.addParam("wmode","transparent");\r\n		so.addVariable("file", "''.$source_src.''");\r\n		so.addVariable("click", "false");\r\n		so.addVariable("autoStart", "''.$autostart.''");\r\n		so.addVariable("preview", "true");\r\n		so.addVariable("showfsbutton", "''.$showfsbutton.''");	\r\n		so.write("flash_swf_''.$unique_id.''");\r\n	// ]]>\r\n	#/script#\r\n</div>'),
(33, 1, 320, 3, 'Vidéo (avi, mov, ...)', 'item:Fichier vidéo|source|fichier,Largeur|width|num,Hauteur|height|num', '<div class="portlet cms_video">\r\n	#object# width="''.$width.''" height="''.$height.''">\r\n		#embed# type="application/x-mplayer2" src="''.$source_src.''" autostart="true" controller="false" width="''.$width.''" height="''.$height.''">#/embed#\r\n	#/object#\r\n</div>');

-- --------------------------------------------------------

--
-- Structure de la table 'cms_pages'
--

DROP TABLE IF EXISTS cms_pages;
CREATE TABLE cms_pages (
  id int(8) NOT NULL auto_increment,
  ordre int(4) NOT NULL default '1',
  actif tinyint(1) NOT NULL default '1',
  pid int(8) default NULL,
  type_id tinyint(8) default NULL,
  lien_fr varchar(250) default NULL,
  menu tinyint(1) NOT NULL default '1',
  plan tinyint(1) NOT NULL default '1',
  titre_fr varchar(150) default NULL,
  titre_page_fr varchar(250) default NULL,
  meta_titre_fr varchar(150) default NULL,
  meta_url_fr varchar(150) default NULL,
  meta_description_fr text,
  meta_key_fr text,
  PRIMARY KEY  (id),
  KEY pid (pid),
  KEY type_id (type_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table 'cms_pages'
--

INSERT INTO cms_pages (id, ordre, actif, pid, type_id, lien_fr, menu, plan, titre_fr, titre_page_fr, meta_titre_fr, meta_url_fr, meta_description_fr, meta_key_fr) VALUES
(1, 30, 1, 0, 1, '', 1, 1, 'Accueil', 'Bienvenu sur notre site', NULL, NULL, '', ''),
(2, 20, 1, 0, 2, '', 1, 1, 'Actualités', 'On vous informe', NULL, NULL, '', ''),
(3, 20, 1, 2, 4, '', 1, 1, 'Présentation', '', NULL, NULL, '', ''),
(4, 10, 1, 2, 6, '', 1, 1, 'Actualités', '', NULL, NULL, '', ''),
(5, 10, 1, 0, 4, '', 1, 1, 'Qui somme nous ?', 'Présentation', NULL, NULL, '', '');

-- --------------------------------------------------------

--
-- Structure de la table 'cms_pages_elements'
--

DROP TABLE IF EXISTS cms_pages_elements;
CREATE TABLE cms_pages_elements (
  id int(8) NOT NULL auto_increment,
  ordre int(4) NOT NULL default '1',
  actif tinyint(1) NOT NULL default '1',
  pid int(8) NOT NULL default '1',
  type_id tinyint(8) default NULL,
  langue char(2) default NULL,
  valeurs text,
  PRIMARY KEY  (id),
  KEY pid (pid)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=143 ;

--
-- Contenu de la table 'cms_pages_elements'
--


-- --------------------------------------------------------

--
-- Structure de la table 'cms_pages_relation_cms_blocs'
--

DROP TABLE IF EXISTS cms_pages_relation_cms_blocs;
CREATE TABLE cms_pages_relation_cms_blocs (
  id int(8) NOT NULL auto_increment,
  cat_id int(8) default NULL,
  prod_id int(8) default NULL,
  ordre int(4) default NULL,
  PRIMARY KEY  (id),
  KEY cat_id (cat_id),
  KEY prod_id (prod_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Contenu de la table 'cms_pages_relation_cms_blocs'
--


-- --------------------------------------------------------

--
-- Structure de la table 'cms_pages_types'
--

DROP TABLE IF EXISTS cms_pages_types;
CREATE TABLE cms_pages_types (
  id int(8) NOT NULL auto_increment,
  titre varchar(250) default NULL,
  repertoire varchar(250) default NULL,
  description text,
  style varchar(250) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Contenu de la table 'cms_pages_types'
--

INSERT INTO cms_pages_types (id, titre, repertoire, description, style) VALUES
(1, '[PAGE ACCUEIL]', '', 'Page d''index du site', './css/home.css'),
(2, '[REDIRECTION] 1ère sous-rubrique', '', 'Permet de rediriger le lien d''une rubrique vers sa première sous-rubrique', ''),
(3, '[REDIRECTION] Lien', '', 'Rediriger une rubrique vers un lien', ''),
(4, '[PAGE CMS]', '', 'Module par defaut : utlise le contenu CMS associé à la page', './css/cms.css'),
(5, '[CONTENEUR] Liste sous-rubriques', '', 'Affiche les sous-rubriques d''une rubrique', ''),
(6, 'Module actualités', 'mod_actualites', 'Affiche la liste des actualités classé par date\r\nFicha actualité accessible en cliquant sur le lien "En savoir plus"', './css/mod_actualites.css'),
(7, '[STOCK] non-visible', '', 'La rubrique avec le type [STOCK] (rubrique conteneur) permet des stocker X pages sans les afficher directement dans le site.\r\nCela peut servir a avoir une page CMS, éditable, pour l''afficher au sein d''un module spécifique.', '');

-- --------------------------------------------------------

--
-- Structure de la table 'dat_bibliotheque_fichiers'
--

DROP TABLE IF EXISTS dat_bibliotheque_fichiers;
CREATE TABLE dat_bibliotheque_fichiers (
  id int(8) NOT NULL auto_increment,
  cat_id int(8) NOT NULL default '1',
  titre varchar(150) default NULL,
  `date` datetime default NULL,
  fichier varchar(70) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Contenu de la table 'dat_bibliotheque_fichiers'
--

-- --------------------------------------------------------

--
-- Structure de la table 'dat_bibliotheque_fichiers_cat'
--

DROP TABLE IF EXISTS dat_bibliotheque_fichiers_cat;
CREATE TABLE dat_bibliotheque_fichiers_cat (
  id int(8) NOT NULL auto_increment,
  ordre int(4) NOT NULL default '1',
  titre varchar(150) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table 'dat_bibliotheque_fichiers_cat'
--

INSERT INTO dat_bibliotheque_fichiers_cat (id, ordre, titre) VALUES
(1, 10, 'Documents pdf');

-- --------------------------------------------------------

--
-- Structure de la table 'dat_bibliotheque_images'
--

DROP TABLE IF EXISTS dat_bibliotheque_images;
CREATE TABLE dat_bibliotheque_images (
  id int(8) NOT NULL auto_increment,
  cat_id int(8) NOT NULL default '1',
  legende text,
  credits text,
  auteur varchar(255) default NULL,
  `date` datetime default NULL,
  image varchar(70) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table 'dat_bibliotheque_images'
--

-- --------------------------------------------------------

--
-- Structure de la table 'dat_bibliotheque_images_cat'
--

DROP TABLE IF EXISTS dat_bibliotheque_images_cat;
CREATE TABLE dat_bibliotheque_images_cat (
  id int(8) NOT NULL auto_increment,
  ordre int(4) default NULL,
  titre varchar(255) default NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Contenu de la table 'dat_bibliotheque_images_cat'
--

INSERT INTO dat_bibliotheque_images_cat (id, ordre, titre) VALUES
(1, 10, 'Produits du site');

-- --------------------------------------------------------

--
-- Structure de la table 'dat_email_alertes'
--

DROP TABLE IF EXISTS dat_email_alertes;
CREATE TABLE dat_email_alertes (
  id int(8) NOT NULL auto_increment,
  titre varchar(150) default NULL,
  sujet_fr varchar(150) default NULL,
  texte_fr text,
  PRIMARY KEY  (id)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Contenu de la table 'dat_email_alertes'
--