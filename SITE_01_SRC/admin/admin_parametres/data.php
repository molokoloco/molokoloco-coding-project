<?

$R = array(
	'table'=>					'admin_parametres',
	'titre'=>					'Paramètre',
	'titres'=>					'',
	'genre'=>					'',
	'relation'=>				'',
	'rubrelation'=>				'',
	'childRel'=>				'',
	'rubLevel'=>				'',
	'prodLevel'=>				'',
	'wherenot'=>				'',
	'postbdd'=>					'update.php',
	'preview'=>					'',
	'ifr '=>					'',
	'boutonFiche'=>				'',
	'boutonListe'=>				'',
	'filtre'=>					'',
	'ordre'=>					'',
	'miseenavant'=>				'',
	'fixe'=>					0,
	'tips '=>					'',
	'rep'=>						$rep.'parametres/',
	'sizeimg'=>					array('mini'=>'120x81xXY', 'medium'=>'300x170', 'grand'=>'600x350')
);


$R_data = array(
	array(
		'name'=>				'id'
	),
	array(
		'name'=>				'email',
		'titre'=>				'E-mail contact',
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
		'tips'=>				'Email par d&eacute;faut du site',
		'separateur'=>			'',
	),

	array(
		'name'=>				'meta_title',
		'titre'=>				'M&eacute;ta Title',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				65,
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
		'index'=>				0,
		'tips'=>				'65 charact&egrave;res maximum',
		'separateur'=>			'R&eacute;f&eacute;rencement',
	),
	
	array(
		'name'=>				'meta_url',
		'titre'=>				'M&eacute;ta URL',
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
		'index'=>				0,
		'tips'=>				'M&eacute;ta url par d&eacute;faut du site<br>
								Ex. : '.$WWW,
		'separateur'=>			'',
	),

	array(
		'name'=>				'meta_desc',
		'titre'=>				'M&eacute;ta description',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			1,
		'input'=>				'textarea',
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
		'tips'=>				'M&eacute;ta description par d&eacute;faut des pages<br>
								250 charact�res maximum',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'meta_key',
		'titre'=>				'M&eacute;ta  mots-cl&eacute;',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			1,
		'input'=>				'textarea',
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
		'tips'=>				'Pr&eacute;cisez chaque mot sur une ligne<br>
								Entre 10 et 20 mots conseill&eacute;',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'logoc',
		'titre'=>				'Logo Client',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				70,
		'bilingue'=>			0,
		'input'=>				'file',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				1,
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
		'separateur'=>			'Style de l\'administration',
	),
	array(
		'name'=>				'logoa',
		'titre'=>				'Logo Admin',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				70,
		'bilingue'=>			0,
		'input'=>				'file',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				1,
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
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'paginationa',
		'titre'=>				'Pagination admin',
		'sqlType'=>				'tinyint',
		'sqlDefaut'=>			0,
		'nbChar'=>				3,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				1,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'D&eacute;faut : 50',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'pagination',
		'titre'=>				'Pagination site',
		'sqlType'=>				'tinyint',
		'sqlDefaut'=>			0,
		'nbChar'=>				3,
		'bilingue'=>			0,
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				1,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				'',
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'D&eacute;faut : 10',
		'separateur'=>			'',
	),

	array(name=>'fontcolor1',titre=>'Couleur police titre',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#FFFFFF',tips=>'D&eacute;faut : #FFFFFF'),
	array(name=>'fontcolor2',titre=>'Couleur police texte',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#666666',tips=>'D&eacute;faut : #666666'),
	
	array(name=>'linkcolor',titre=>'Couleur lien',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#016AC5',tips=>'D&eacute;faut : #016AC5'),
	array(name=>'linkcoloron',titre=>'Couleur lien survol',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#FF0000',tips=>'D&eacute;faut : #FF0000'),
	
	array(name=>'bgcolor1',titre=>'Couleur du fond 1',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#FFFFFF',tips=>'D&eacute;faut : #FFFFFF'),
	array(name=>'bgcolor2',titre=>'Couleur du fond 2',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#999999',tips=>'D&eacute;faut : #999999'),
	
	array(name=>'ligneentete',titre=>'Couleur ligne entete',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#BBBBBB',tips=>'D&eacute;faut : #BBBBBB'),
	array(name=>'ligne1',titre=>'Couleur ligne 1',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#E4E4E4',tips=>'D&eacute;faut : #E4E4E4'),
	array(name=>'ligne2',titre=>'Couleur ligne 2',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#F4F4F4',tips=>'D&eacute;faut : #F4F4F4'),
	array(name=>'ligneon',titre=>'Couleur ligne survol',htmDefaut=>'couleur',sqlType=>'varchar',nbChar=>'7',input=>'text',sqlDefaut=>'#FFFFFF',tips=>'D&eacute;faut : #FFFFFF'),
	
);
//$C = new SQL($R); $C->createSql($R_data,'1');
//$C = new SQL($R); $C->addSql($R_data);

if (!is_dir($wwwRoot.$R['rep'])) { 
	mkdir($wwwRoot.$R['rep'], 0755); chmod($wwwRoot.$R['rep'], 0777);
	mkdir($wwwRoot.$R['rep'].$grand, 0755); chmod($wwwRoot.$R['rep'].$grand, 0777);
	mkdir($wwwRoot.$R['rep'].$medium, 0755); chmod($wwwRoot.$R['rep'].$medium, 0777);
	mkdir($wwwRoot.$R['rep'].$mini, 0755); chmod($wwwRoot.$R['rep'].$mini, 0777);
}
?>