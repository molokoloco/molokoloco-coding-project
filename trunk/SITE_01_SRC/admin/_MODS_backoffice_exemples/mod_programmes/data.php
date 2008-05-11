<?

$R = array(
	'table'=>					'mod_programmes',
	'titre'=>					'Programme',
	'titres'=>					'',
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
	'ordre'=>					'titre_fr ASC',
	'miseenavant'=>				'',
	'fixe'=>					'',
	'tips '=>					'',
	'rep'=>						'',
	'sizeimg'=>					''
);

$R_data = array(
	array(
		'name'=>				'id'
	),
	array(
		'name'=>				'titre',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				250,
		'bilingue'=>			1,
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
	),

);
//$C = new SQL($R); $C->createSql($R_data,'1');
//$C = new SQL($R); $C->addSql($R_data);

?>