<?

require '../cms_pages/data.php';
$R0 = NULL;
$R0_data = NULL;

// -------------------- CATEGORIES --------------------------- //

$R1['childRel'] = NULL;
$R1['prodLevel'] = NULL;

$R1_data[] = array(
	'name'=>				'rubrique_ids',
	'titre'=>				'Rubrique(s) associ&eacute;(s)',
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
	'relation'=>			'cms_pages_relation_mod_articles:cms_pages:prod_id=id:cat_id=id:titre_fr#(type_id=6 OR type_id=12)',
	'inc'=>					'',
	'unique'=>				'',
	'action'=>				'',
	'index'=>				0,
	'tips'=>				'',
	'separateur'=>			'Rubrique(s) de l\'article',
);

?>