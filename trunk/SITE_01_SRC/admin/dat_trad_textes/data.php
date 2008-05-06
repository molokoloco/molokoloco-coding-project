<?
$R1 = array(
	'table'=>					'dat_trad_textes',
	'titre'=>					'Traduction de texte',
	'titres'=>					'Traductions de texte',
	'genre'=>					'e',
	'relation'=>				'',
	'rubrelation'=>				'',
	'childRel'=>				'',
	'rubLevel'=>				'',
	'prodLevel'=>				'',
	'wherenot'=>				'',
	'postbdd'=>					'generate.php',
	'preview'=>					'',
	'ifr '=>					'',
	'boutonFiche'=>				'',
	'boutonListe'=>				'',
	'filtre'=>					'',
	'ordre'=>					'',
	'miseenavant'=>				'',
	'fixe'=>					0,
	'tips '=>					'',
	'rep'=>						'',
	'sizeimg'=>					'',
);

$R1_data = array(

	array(
		'name'=>				'id'
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
		'htmDefaut'=>			'post',
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
		'name'=>				'trad',
		'titre'=>				'Traduction',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			1,
		'input'=>				'textarea',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				2,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				''
	),
);
//$C = new SQL($R1); $C->createSQL($R1_data,'1');
//$C = new SQL($R1); $C->addSQL($R1_data);

?>