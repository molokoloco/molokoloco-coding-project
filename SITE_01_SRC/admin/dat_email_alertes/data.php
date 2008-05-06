<?
$R1 = array(
	'table'=>					'dat_email_alertes',
	'titre'=>					'Texte de e-mail',
	'titres'=>					'Textes des e-mails',
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
	'ordre'=>					'titre DESC',
	'miseenavant'=>				'titre',
	'fixe'=>					0, // 1
	'tips '=>					'',
	'rep'=>						'',
	'sizeimg'=>					''
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
		'bilingue'=>			'',
		'input'=>				'text',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				0,
		'resize'=>				'',
		'htmDefaut'=>			'post',
		'oblige'=>				1,
		'disable'=>				1,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				''
	),
	array(
		'name'=>				'sujet',
		'titre'=>				'Sujet du mail',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				150,
		'bilingue'=>			'',
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
		'index'=>				0,
		'tips'=>				'',
	),
	array(
		'name'=>				'texte',
		'titre'=>				'Texte du mail',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			'',
		'input'=>				'textarea',
		'valeur'=>				'',
		'titrevaleur'=>			'',
		'wysiwyg'=>				1,
		'resize'=>				'',
		'htmDefaut'=>			'',
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'Valeurs pr&eacute;d&eacute;finies et dynamiques :<br />
								<br />Infos de connexion :
								<ul>
									#civilite <b>= Email</b><br />
									#nom <b>= Nom</b><br />
									#prenom <b>= Prénom</b><br />
									#email <b>= Email</b><br />
									#m2p <b>= Mot de passe</b>
								</ul>
								<br />Enregistrement, validation et expédition commande :
								<ul>
									#REF <b>= Référence commande</b><br />
									#COMMANDE <b>= Infos complète sur la commande</b><br />
								</ul>
								<br />Expédition commande :
								<ul>
									#EXPEDITION <b>= Date de l\'expédition</b><br />
								</ul>
								<br />Enregistrement devis :
								<ul>
									#REF <b>= Référence commande</b><br />
									#DEVIS <b>= Infos complète sur le devis</b><br />
								</ul>',
	),
);
//$C = new SQL($R1); $C->createSql($R1_data,'1');
//$C = new SQL($R1); $C->addSql($R1_data);

?>