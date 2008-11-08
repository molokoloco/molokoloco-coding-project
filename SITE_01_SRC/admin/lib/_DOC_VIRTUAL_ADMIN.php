<? 

/*///////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////// VIRTUAL ADMIN V1.0 /////////////////////////////////////////////////////
////////////// Code mixing by Molokoloco... [BETA TESTING FOR EVER] ... (o_O) /////////////////////////////
////////////////////// Contact : molokoloco@gmail.com // CopyLeft  ///////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////*/

/*

	INSTAL ADMIN AND THE FIRST MODULE

	1/	Create a new DB in phpMyAdmin
	2/	Edit the file ./admin/lib/racine.php : $dbase , $dbhost, $dblogin (Ligne 75)
	3/	With phpMyAdmin , import default SQL data ./admin/lib/_SETUP_ADMIN_CMS.SQL in this base
	4/	Open Admin in your browser http://127.0.0.1/www.site.com/admin/
	5/	For each new module (ex : ./mod_clients/) drop it in ./admin/ folder
	6/	Edit ./admin/lib/racine.php : $adminMenuAdmin (Ligne 208) , your module appear in the admin menu
	7/	Edit the data file for custom fields in ./mod_clients/data.php
	8/	When ready, decomment the last row in data.php
	9/	Open the module in your browser http://127.0.0.1/www.site.com/admin/mod_clients/, the table is created
	10/	Comment the last row in data.php
	11/	Admin your clients ;)

	CONFIGURING ADMIN

	1/ Understanding admin squeleton :
		- All the pages in the admin are build with only three class, three types of view. The three class are based on the data description of a module
			admin\lib\class\class_admin_liste.php : print a view of all elements in a module, list of clients for exemple
			admin\lib\class\class_admin_fiche.php : print a editing view of an elements in a module, sheet of the clients for exemple
			admin\lib\class\class_admin_bdd.php : Manage action doable to an elements in a module : Add, update, Delete
		- This three class are surrounded by an overall header and footer
			admin\menu\menu.php : General header, also build the admin menu
			admin\menu\menu_bas.php : General footer
	
	2/ Editing the admin configuration file :
		- ./admin/lib/racine.php : READ AND EDIT WITH ATTENTION !
		- Remember to emphy the session parameters configuration by decommenting the 74' row : ### $_SESSION[SITE_CONFIG]['WWW'] = NULL;, before reloading in your browser
	
	3/ Configuring some admin generals parameters :
		- Going to http://127.0.0.1/www.site.com/admin/admin_parametres/ : Edit the look and feel, logo, theme, etc...

*/



// ------------------ DATA TYPE ELEMENT "TABLE" ------------------------------------------------- //

$R1 = array(
	'table'=>					'alertes_email',							// Nom de la table SQL
	'titre'=>					'Texte de e-mail',						// Titre a afficher
	'titres'=>					'Textes des e-mails',					// Si titre au pluriel est particulier
	'genre'=>					'e',											// '' (un) | 'e' (unE)
	'relation'=>				$R1['table'].':id:titre:cat_id', 	// mode "Catégorie" : parent:2 (2 enfants) | tableCat:champValeur:champTitre:champRelation
	'rubrelation'=>				'categories_offres:id:titre:parent_id', // mode "Rubrique"
	'childRel'=>				'categories_offres:categories_offres_produits:produits:cat_id=id:prod_id=id:titre:titre', // mode "Rubrique" (  | 1)
	'rubLevel'=>				'0:0',										// mode "Rubrique"
	'prodLevel'=>				'0:0',										// mode "Rubrique"
	'wherenot'=>				'cat_id < 1',								// Parametre supp. WHERE
	'postbdd'=>					'create_xml.php', 						// Include apres UPDATE BDD
	'preview'=>					$root.'index2.php?goto=actu_une', 	// To check... fonctionne avec "actif", ajoute "&id=33"
	'ifr '=>					'add_file.php', 							// Iframe sur la page LISTE
	'boutonFiche'=>				$boutonPrint, 								// Code html d'un bouton : Cf + haut
	'boutonListe'=>				$boutonPrint,
	'filtre'=>					array('statut'=>'1','type'=>'todo'), // Filtre d'affichage > "todo" = aucune valeur par defaut
	'ordre'=>					'titre DESC',								// Ordre d'affichae en mode LISTE
	'miseenavant'=>				'sujet_liste',								// Colonne la plus large en mode LISTE
	'fixe'=>					0,												// O (normal) | 1 (pas d'ajout) | 2 (pas de modif)
	'tips '=>					'Une fois que l\'&quot;Actu&quot; est crée, il est possible d\'y attacher des médias', // TIPS sur la page FICHE
	'rep'=>						$rep.'actus/',								// Repertoire ou seront stocker les fichiers et les images
	'sizeimg'=>					array('mini'=>'120x100','medium'=>'240x190','tgrand'=>'520x520xXY') // rep => WIDTH x HEIGHT x RESIZE (ATTENTION optionnel : "tgrand" = valeur particuliere : stock l'image à la racine du rép)
);

// Bouton
$boutonFiche = '<table  border="0" cellspacing="0" cellpadding="0"><tr><td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td><td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:void(0);" onclick="window.open(\'commande_impression.php?id='.intval($_GET['id']).'\',\'\',\'width=500,height=580\');" class="menu">IMPRIMER</a></td><td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td></tr></table>';

// Preview
$mode = gpc('mode');
if (($mode == 'fiche' || $mode == 'bdd') && gpc('id') > 0) {
	$id = gpc('id');
	$A =& new Q("SELECT cat_id FROM cms_pages_relation_mod_articles WHERE prod_id='".$id."' LIMIT 1");
	$rub_id = intval($A->V[0]['cat_id']);
	if ($rub_id < 1) $boutonFiche = '<strong>ATTENTION : vous n\'avez pas défini de rubrique(s) pour votre article</strong>';
	else $previewf = $WWW.'article-r'.$rub_id.'-a'.$id.'.html';
}

// ------------------ DATA TYPE ELEMENT "CHAMPS" ------------------------------------------------- //

// MOTS-CLES DES $R1_data[$x]['name']...
// id : 1ere data
// ordre : Affiche les propriétés "monter/descendre" sur la liste d'index
// date : Date par défaut a l'insertion
// titre/nom : Champs par defaut pour la création d'une nouvelle entrée

$R1_data = array(
	array(
		'name'=>				'titre',				// Nom du champs sql, de l'input et, par défaut, titre affiché...
		'titre'=>				'Intilu&eacute;', 		// Le nom du champ par défaut "name" peut être remplacé à l'affichage
		'sqlType'=>				'varchar', 				// float | int | tinyint | varchar | text
		'sqlDefaut'=>			1,						// '' (NULL) | 1 (par defaut a l'insertion) 
		'nbChar'=>				255, 					// Nombre de caractères  : 1-255
		'bilingue'=>			1, 						// Champs en plusieur langues : 0 | 1
		'input'=>				'radio',				// text | textarea | radio | file | checkbox | radio | select | multiselect || '' (hidden en mode FICHE)
		'valeur'=>				array('1','0'), 		// Valeurs des inputs -> radio/select...
		'titrevaleur'=>			array('Oui','Non'), 	// Titre des inputs -> radio/select... : <option value="valeur">titrevaleur</option>
		'wysiwyg'=>				2, 						// Texte avec WYSIWYG 1 2 3 4 5 (style+hauteur dans wisiwg) | longText (hauteur textarea)
		'resize'=>				'XY',					// X, Y ou XY >> force le resize des images, vide par defaut, XY = crop, O = ombres sur PNG to jpg
		'htmDefaut'=>			'',						// '' (normal) | date | datetime | img | fichier | dateMod(modificationMAJ) | couleur | video
		'oblige'=>				1,						// Obligatoire (script vérification du formulaire)
		'disable'=>				1,						// Edition impossible
		'relation'=>			1, 						// 1 (Appartient a une cat) | produits_relation_rea:produits:cat_id=id:prod_id=id:nom-prenom#pid>0' (input multiselect avec htmlDefault)
		'inc'=>					'genres:id:titre', 		// Relation vers une table : tableRel:ChampsVal:ChampsTitre | projet:id:titre:unique:cat_id="'.$cat_id.'"
		'unique'=>				1,						// Si input "radio", cet enregistrement est le seul à pouvoir avoir cette valeur... (à la une)
		'action'=>				'!=:1:==:1:<script>window.open(\'send_mail.php?id='.$id.'\',\'\',\'width=250,height=100\');</script>', // Want fun ?
		'index'=>				1,						// Présence sur page LISTE : 0 | 1 
		'tips'=>				'Liste des <a href="../mod_membres/index.php?mode=fiche&id=\'.$F->V[\'0\'][$this->data[$i][\'name\'].\'_\'.$langue].\'" target="_blank">Profils</a>',													// Infos HTML à afficher sous Le champ (tips cool be with php (eval)
		'separateur'=>			'',						// A mettre dans une data pour séparer les infos suivantes sur la FICHE
	),
	array('name'=>'monchamps', 'sqlDefaut'=>'', 'sqlType'=>'varchar', 'nbChar'=>'100'), // Only create SQL Field
);
$C = new SQL($R1); $C->createSql($R1_data,'1');
$C = new SQL($R1); $C->addSql($R1_data);




///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// EXEMPLE DATA TYPE ////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$R1_data = array(
	array(
		'name'=>				'id'
	),
	array(
		'name'=>				'ordre',
		'titre'=>				'',
		'sqlType'=>				'int',
		'sqlDefaut'=>			1,
		'nbChar'=>				4,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'',
		'separateur'=>			'',	
	),
	array(
		'name'=>				'actif',
		'titre'=>				'Visible',
		'sqlType'=>				'tinyint',
		'sqlDefaut'=>			1,
		'nbChar'=>				1,
		'bilingue'=>			0,
		'input'=>				'radio',
		'valeur'=>				array('1','0'),
		'titrevaleur'=>			array('Oui','Non'),
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'',
		'separateur'=>			'',
	),
	array(
		'name'=>				'statut',
		'titre'=>				'',
		'sqlType'=>				'tinyint',
		'sqlDefaut'=>			1,
		'nbChar'=>				1,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				array('0', '1', '2'),
		'titrevaleur'=>			array('Temporaire', 'A&nbsp;valider', 'Valide'),
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'!=:2:==:2:<script>window.open(\'send_mail.php?id='.$id.'\',\'\',\'width=250,height=100\');</script>',
		'index'=>				1,
		'tips'=>				'',
		'separateur'=>			'',
	),
	array(
		'name'=>				'une',
		'titre'=>				'A la une',
		'sqlType'=>				'tinyint',
		'sqlDefaut'=>			1,
		'nbChar'=>				1,
		'bilingue'=>			0,
		'input'=>				'radio',
		'valeur'=>				array('1','0'),
		'titrevaleur'=>			array('Oui','Non'),
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=> 				1,
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'Cette news sera mise à la une',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'civilite',
		'titre'=>				'Civilite',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				'4',
		'bilingue'=>			0,
		'input'=>				'radio',
		'valeur'=>				array('Mr','Mme','Mlle'),
		'titrevaleur'=>			array('Mr','Mme','Mlle'),
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=> 				1,
		'action'=>				'',
		'index'=>				'',
		'tips'=>				'',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'titre',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				150,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'',
		'separateur'=>			'',
	),
	
	// Pour un data R2...
	array(
		'name'=>				'cat_id',
		'titre'=>				'Piece de théâtre',
		'sqlType'=>				'int',
		'sqlDefaut'=>			1,
		'nbChar'=>				8,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			1,
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'',
		'separateur'=>			'',
	),
	
	// Pour une autre table
	array(
		'name'=>				'genre_id',
		'titre'=>				'Genre',
		'sqlType'=>				'int',
		'sqlDefaut'=>			0,
		'nbChar'=>				8,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'genres:id:titre',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'Pour ajouter un genre : <a href="../genres/" target="_blank">Cliquer ici</a>',
		'separateur'=>			'',
	),
	
	// Valeurs a partir d'un array...
	array(
		'name'=>				'type',
		'titre'=>				'Type',
		'sqlType'=>				'int',
		'sqlDefaut'=>			'',
		'nbChar'=>				8,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				array_values($arr_type),
		'titrevaleur'=>			array_keys($arr_type),
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'',
		'separateur'=>			'',
	),
	
	
	// Cas particulier
	array(
		'name'=>				'cms_templates_id',
		'titre'=>				'Template',
		'sqlType'=>				'int',
		'sqlDefaut'=>			0,
		'nbChar'=>				8,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'cms',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'cms_templates:id:titre',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'Pour ajouter un template : <a href="../cms_templates/" target="_blank">Cliquer ici</a>',
		'separateur'=>			'',
	),
	
	
	array(
		'name'=>				'auteur',
		'titre'=>				'Auteur(s)',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				250,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'Séparé par des &quot;,&quot;',
		'separateur'=>			'',
	),
	array(
		'name'=>				'email',
		'titre'=>				'E-mail',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				250,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'',
		'separateur'=>			'',
	),
	array(
		'name'=>				'texte',
		'titre'=>				'',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			0,
		'input'=>				'textarea',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				2,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'prix',
		'titre'=>				'',
		'sqlType'=>				'float',
		'sqlDefaut'=>			'',
		'nbChar'=>				'8,2',
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'En &euro;',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'duree',
		'titre'=>				'Dur&eacute;e',
		'sqlType'=>				'tinyint',
		'sqlDefaut'=>			0,
		'nbChar'=>				3,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'En minute',
		'separateur'=>			'',
	),
	array(
		'name'=>				'date_debut',
		'titre'=>				'Date d&eacute;but',
		'sqlType'=>				'int',
		'sqlDefaut'=>			20070101,
		'nbChar'=>				10,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'date',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>					'Ex. :'.date("d/m/Y"),
		'separateur'=>			'',
	),
	array(
		'name'=>				'date_post',
		'titre'=>				'Date cr&eacute;ation',
		'sqlType'=>				'datetime',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'datetime',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'Ex. : '.getDateTime(),
		'separateur'=>			'',
	),
	
	
	
	
	/*
		$CAT_REL = array(table => 'produits_relation_realisations');
		$CAT_REL_data = array(
			array(name=>'id'),
			array(name=>'cat_id',sqlType=>'int',nbChar=>'8'),
			array(name=>'prod_id',sqlType=>'int',nbChar=>'8'),
			array(name=>'ordre',sqlType=>'int',nbChar=>'4'),
		);
		//$C = new SQL($CAT_REL); $C->createSQL($CAT_REL_data,'1');
	*/
	array(
		'name'=>				'produits_ids',
		'titre'=>				'Produit(s) associ&eacute;(s)',
		'sqlType'=>				'',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			0,
		'input'=>				'multiselect',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'relation',
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'produits_relation_realisations:produits:cat_id=id:prod_id=id:nom_fr',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'',
		'separateur'=>			'',
	),
	array(
		'name'=>				'visuel',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				70,
		'bilingue'=>			0,
		'input'=>				'file',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'img',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'Formats : jpg/gif/png',
	),
	array(
		'name'=>				'document',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				70,
		'bilingue'=>			0,
		'input'=>				'file',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'fichier',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'Formats : pdf, doc, xls, ...',
	),
	array(
		'name'=>				'video',
		'titre'=>				'Vidéo',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				150,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'video',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'Formats : Copier-coller ici le code fournit par l\'hébergeur de la vidéo',
	),
	
	array(
		'name'=>				'titre_lien',
		'titre'=>				'Titre du lien',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				150,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>					'',
		'separateur'=>			'',
	),
	array(
		'name'=>				'lien',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				250,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>					'Externe : <b>http://www.site.com</b> | Interne : <b>ma-page-r8.html</b>',
		'separateur'=>			'',
	),
	array(
		'name'=>				'cible',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				6,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				array('_blank', '_top'),
		'titrevaleur'=>			array('Nouvelle fenêtre', 'Même fenêtre'),
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				1,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				'',
		'tips'=>					'',
		'separateur'=>			'',
	),
);
//$C = new SQL($R1); $C->createSQL($R1_data,'1');
//$C = new SQL($R1); $C->addSQL($R1_data);





// ------------------ EXEMPLES DE CREATION D'UN BOUTON POUR L'ADMIN ------------------ //
$boutonPrint = '<table  border="0" cellspacing="0" cellpadding="0"><tr><td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td><td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:void(0);" onclick="window.open(\'commande_impression.php?id='.intval($_GET['id']).'\',\'\',\'width=500,height=580\');" class="menu">IMPRIMER</a></td><td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td></tr></table>';





///////////////////////////////////////////////// FICHIER INDEX.PHP A METTRE A COTE DATA //////////////////////////



<?  include_once("../menu/menu.php"); ?><?
include_once("data.php");

$cat_id = intval($_GET['cat_id']);
$id = intval($_GET['id']);
$child = intval($_GET['child']);
$action = $_GET['action'];
$mode = $_GET['mode'];

switch ($mode) { // $R1 -> CAT // $RX -> SSCAT
	case 'fiche' :
	if ($cat_id > 0) {
		switch($child) {
			case '1' : $A = new FICHE($R2,$R2_data,$id); break;
			case '2' : $A = new FICHE($R3,$R3_data,$id); break;
			case '3' : $A = new FICHE($R4,$R4_data,$id); break;
			case '4' : $A = new FICHE($R5,$R5_data,$id); break;
			default : $A = new FICHE($R2,$R2_data,$id); break;
		}
	}
	else $A = new FICHE($R1,$R1_data,$id);
	$A->cat_id = $cat_id;
	$A->child = $child;
	$A->createFICHE();
	break;
	
	case 'bdd' :
	if ($cat_id > 0) {
		switch($child) {
			case '1' : $A = new BDD($R2,$R2_data,$action,$id); break;
			case '2' : $A = new BDD($R3,$R3_data,$action,$id); break;
			case '3' : $A = new BDD($R4,$R4_data,$action,$id); break;
			case '4' : $A = new BDD($R5,$R5_data,$action,$id); break;
			default : $A = new BDD($R2,$R2_data,$action,$id); break;
		}
	}
	else $A = new BDD($R1,$R1_data,$action,$id);
	$A->cat_id = $cat_id;
	$A->from = $cat_id > 0 ? 'index.php?mode=liste' : 'index.php?mode=fiche'; // Pour les cat reste sur la fiche validation et envois du mail
	$A->child = $child;
	$A->createBDD();
	break;
	
	default : // 'liste'
	if ($cat_id > 0) {
		switch($child) {
			case '1' : $A = new LISTE($R2,$R2_data,$id); break;
			case '2' : $A = new LISTE($R3,$R3_data,$id); break;
			case '3' : $A = new LISTE($R4,$R4_data,$id); break;
			case '4' : $A = new LISTE($R5,$R5_data,$id); break;
			default : $A = new LISTE($R2,$R2_data,$id); break;
		}
	}
	else $A = new LISTE($R1,$R1_data,$id);
	$A->cat_id = $cat_id;
	$A->ordre = ' id DESC ';
	$A->child = $child;
	$A->createLISTE();
	break;
}

?><? include_once("../menu/menu_bas.php"); ?>













///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////// ALREADY DONE :P ///////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
	//_________________________ ALBUM PHOTOS + PAGES  //_________________________
	Serveur\web\CALAIS\actualite
	
	
	//_________________________ VIRTUAL ADMIN  //_________________________
	Serveur\web\FMVM
	
	
	//_________________________ HTML AREA WYSIWYG  //_________________________
	Serveur\web\MEDIFROID\SITE\admin\lib\wysiwyg
	Serveur\web\NEUT\SITE\admin\actualites\
	
	Cross browser // max caract // style // to do best : cleanWord
	
	//_________________________ CATALOGUE COMPLEXE  //_________________________
	SERVEUR\web\Jean_Brel\SITE\
	
	> CAT - SSCAT (- SSSSCAT) - PRODUITS + DOC ATTACHES
	> Login utilisateur pour download
	
	//_________________________ CATALOGUE SIMPLE  //____________________________
	http://serveur/BOCH%20FRERES/SITE/admin/conseil/
	
	> CAT - SSCAT - PRODUITS (titre+texte)
	
	
	//_________________________ CATALOGUE TRES SIMPLE  //____________________________
	SERVEUR\web\ikaba\SITE\
	
	> CAT - PRODUITS + Pagination
	
	//_____________________________ XML CATALOGUE //_____________________________
	SERVEUR\web\COLORADO\SITE\admin\catalogue
	
	> Catalogue xml
	> Download PDF....
	> espace Medias/clients/login
	
	//_____________________________ FORUM ECHANGE //_____________________________
	SERVEUR\web\CAPTELIS\SITE
	
	> Admin clients (Login, email..)
	> Forum clients
	> Gestion Fichiers
	
	//_____________________________ RH - CURRICULUM //____________________________
	
	\\SERVEUR\web\CAPTELIS\SITE
	
	> Admin candidature
	> Formulaire candidature + dépot CV
	
	//_____________________________ NEWSLETTER //_____________________________
	Serveur\comiris_tech
	Serveur\Calais --------------> Clean et multicolonnes
	
	> Editeur de newsletter

	________________________ LISTING EMAIL NEWSLETTER _______________________
	Serveur\web\MEDIFROID_NEW\SITE\admin\newsletter
	
	> Listing mail + MINI TEXTE
	
	//_____________________________ CLASS MAIL //_____________________________
	Serveur\web\COMIRIS_CAPITAL\SITE
	
	> Emailleur // little securised
	
	//_____________________________ BDD EMAIL SIMPLEX //__________________________
	SERVEUR\web\CAPTELIS\SITE\news
	
	> Admin email
	
	//_____________________________ FEUILLE CALCUL CA coef ______________________
	Serveur\web\COMIRIS_CAPITAL\SITE
	
	> GRILLE Multi row/ multi col
	> Simulateur coefficient espace privée
	> Multi array
	
	_____________________ PANIER/CADDIE + CATALOGUE SIMPLE __________________
	Serveur\web\TOPSYGEL\SITE
	
	> Paiment sécurisé
	
	
	______________________ PDF CATEGORIES + DOWNLOAD _______________________
	SERVEUR\web\COMIRIS\SITE\admin
	
	> PDF admin + CAT...
	
*/