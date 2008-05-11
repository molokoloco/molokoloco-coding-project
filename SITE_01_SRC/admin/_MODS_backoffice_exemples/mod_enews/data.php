<?

// -------------------- CATEGORIES --------------------------- //

$R1 = array(
	'table'=>					'mod_enews',
	'titre'=>					'Inscrit e-news',
	'titres'=>					'Inscrits e-news',
	'genre'=>					'',
	'relation'=>				'',
	'rubrelation'=>				'',
	'childRel'=>				'',
	'rubLevel'=>				'',
	'prodLevel'=>				'',
	'wherenot'=>				'',
	'postbdd'=>					'',
	'preview'=>					'',
	'ifr '=>					'',
	'boutonFiche'=>				'',
	'boutonListe'=>				'',
	'filtre'=>					'',
	'ordre'=>					' id DESC ',
	'miseenavant'=>				'',
	'fixe'=>					0,
	'tips '=>					'',
	'rep'=>						'',
	'sizeimg'=>					'',
);

$R1_data = array(
	array(name=>'id'),
	array(
		'name'=>				'email',
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
		'name'=>				'date',
		'titre'=>				'Date',
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
);
//$C = new SQL($R1); $C->createSQL($R1_data,'1');
//$C = new SQL($R1); $C->addSQL($R1_data);

?>