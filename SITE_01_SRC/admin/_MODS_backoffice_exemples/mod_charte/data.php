<?

// -------------------- CATEGORIES --------------------------- //

$R1 = array(
	'table'=>					'mod_charte',
	'titre'=>					'Les 6 engagements',
	'titres'=>					'Les 6 engagements',
	'genre'=>					'',
	'relation'=>				'parent:1',
	'rubrelation'=>			'',
	'childRel'=>				'',
	'rubLevel'=>				'',
	'prodLevel'=>				'',
	'wherenot'=>				'',
	'postbdd'=>					'',
	'preview'=>					'',
	'ifr '=>						'',
	'boutonFiche'=>			'',
	'boutonListe'=>			'',
	'filtre'=>					'',
	'ordre'=>					'ordre DESC',
	'miseenavant'=>			'',
	'fixe'=>						1,
	'tips '=>					'',
	'rep'=>						$rep.'charte/',
	'sizeimg'=>					array('mini'=>'90x90xXY', 'medium'=>'170x120', 'grand'=>'800x600')
);

$R1_data = array(
	array(
		'name'=>					'id'
	),
	array(
		'name'=>					'ordre',
		'titre'=>				'',
		'sqlType'=>				'int',
		'sqlDefaut'=>			1,
		'nbChar'=>				4,
		'bilingue'=>			0,
		'input'=>				'',  //text
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
		'index'=>				'', //1,
		'tips'=>					'',
		'separateur'=>			'',	
	),

	array(
		'name'=>					'titre',
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
		'tips'=>				''
	),
	array(
		'name'=>					'texte',
		'titre'=>				'Description',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			0,
		'input'=>				'textarea',
		'valeur'=>				'',
		'titrevaleur'=>		'',
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
	),
	array(
		'name'=>					'titre_bloc',
		'titre'=>				'Titre bloc',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				150,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>		'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				'',
		'tips'=>					''
	),
	/*array(
		'name'=>				'video',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				70,
		'bilingue'=>			0,
		'input'=>				'file',
		'valeur'=>				'',
		'titrevaleur'=>		'',
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
		'tips'=>				'Formats : flash <strong>FLV</strong>',
	),*/
);
//$C = new SQL($R1); $C->createSQL($R1_data,'1');
//$C = new SQL($R1); $C->addSQL($R1_data);


// -------------------- PRODUITS 1 --------------------------- // 
$R2 = array(
	'table'=>					'mod_charte_blocs',
	'titre'=>					'Bloc',
	'titres'=>					'',
	'genre'=>					'',
	'relation'=>				$R1['table'].':id:titre:charte_id',
	'rubrelation'=>			'',
	'childRel'=>				'',
	'rubLevel'=>				'',
	'prodLevel'=>				'',
	'wherenot'=>				'',
	'postbdd'=>					'',
	'preview'=>					'',
	'ifr '=>						'',
	'boutonFiche'=>			'',
	'boutonListe'=>			'',
	'filtre'=>					array('actif'=>'todo'),
	'ordre'=>					'',
	'miseenavant'=>			'',
	'fixe'=>						0,
	'tips '=>					'',
	'rep'=>						$rep.'charte/',
	'sizeimg'=>					array('mini'=>'90x90xXY', 'medium'=>'170x120', 'grand'=>'800x600')
);
$R2_data = array(
	array(name=>'id'),
	array(
		'name'=>					'ordre',
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
		'name'=>					'actif',
		'titre'=>				'',
		'sqlType'=>				'tinyint',
		'sqlDefaut'=>			1,
		'nbChar'=>				1,
		'bilingue'=>			0,
		'input'=>				'radio',
		'valeur'=>				array('1','0'),
		'titrevaleur'=>		array('Valide'),
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
		'tips'=>				''
	),
	array(
		'name'=>					'charte_id',
		'titre'=>				'Charte',
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
		'tips'=>				''
	),
	array(
		'name'=>					'titre',
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
		'tips'=>				''
	),
	
	array(
		'name'=>					'texte',
		'titre'=>				'Descriptif',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			0,
		'input'=>				'textarea',
		'valeur'=>				'',
		'titrevaleur'=>		'',
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
		'tips'=>					'',
	),
	
	array(
		'name'=>					'visuel',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				70,
		'bilingue'=>			0,
		'input'=>				'file',
		'valeur'=>				'',
		'titrevaleur'=>		'',
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
		'tips'=>					'Formats : jpg/gif/png',
	),

);
//$C = new SQL($R2); $C->createSQL($R2_data,'1');
//$C = new SQL($R2); $C->addSQL($R2_data);

?>