<?

// -------------------- CATEGORIES --------------------------- //

$R1 = array(
	'table'=>					'mod_lexique',
	'titre'=>					'Lexique',
	'titres'=>					'',
	'genre'=>					'',
	'relation'=>				'',
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
	'filtre'=>					array('actif'=>'1'),
	'ordre'=>					'',
	'miseenavant'=>			'',
	'fixe'=>						0,
	'tips '=>					'',
	'rep'=>						'',
	'sizeimg'=>					''
);

$R1_data = array(
	array(name=>'id'),

	array(
		'name'=>					'actif',
		'titre'=>				'',
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
		'tips'=>					'',
		'separateur'=>			'',
	),

	array(
		'name'=>					'texte',
		'titre'=>				'Description',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			'',
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
	
);
//$C = new SQL($R1); $C->createSQL($R1_data,'1');
//$C = new SQL($R1); $C->addSQL($R1_data);

?>