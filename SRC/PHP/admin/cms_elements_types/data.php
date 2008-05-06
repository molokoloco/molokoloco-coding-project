<?

$arr_type = array(
	'0'=> 'Titre',
	'1'=> 'Texte',
	'2'=> 'Liste',
	'3'=> 'Média',
	'4'=> 'Divers',
);

$R = array(
	'table'=>					'cms_elements_types',
	'titre'=>					'Type d\'élément',
	'titres'=>					'Types d\'élément',
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
	'filtre'=>					array('actif'=>'1','type'=>'todo'),
	'ordre'=>					'type ASC, titre ASC',
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
		'name'=>				'actif',
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
		'htmDefaut'=>			'1',
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
		'name'=>				'type',
		'titre'=>				'Type',
		'sqlType'=>				'int',
		'sqlDefaut'=>			'',
		'nbChar'=>				8,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				array_keys($arr_type),
		'titrevaleur'=>			array_values($arr_type),
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
		'name'=>				'titre',
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
		'tips'=>				'',
	),
	array(
		'name'=>				'valeurs',
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
		'tips'=>				'Valeurs qui vont servir a construire le formulaire et à injecter le contenu dans le template<br />
								Synthaxe : <strong>ensemble_valeur:titre_valeur|nom_valeur|type_valeur</strong><em>[valeur_1/valeur_2(defaut_valeur</em>,titre_valeur2|...;ensemble_valeur2:...<br />
								Ensemble valeur : un seul <strong>item</strong> ou array <strong>items</strong> pour les valeurs<br />
								Titre valeur : titre champs formulaire - characteres interdits <strong>;:,([/</strong><br />
								Nom valeur : nom de la variable php associée (<strong>value</strong> variable par défaut, si value présente mais vide, ne s\'affiche pas)<br />
								Type valeur : <strong>num | date | text | textarea | html | image | fichier | enum[x/y | func[getPages</strong><br />
								Defaut valeur : <strong>(left</strong><br />
								Ex : item:titre|titre|text,direction|direction|enum[left/right(left,tempo|scrolldelay|num(1<br />
								Cf. &quot;fonction parseAbstractString()&quot; dans &quot;fonctions_cms.php&quot;',
	),

	array(
		'name'=>				'template',
		'titre'=>				'',
		'sqlType'=>				'text',
		'sqlDefaut'=>			'',
		'nbChar'=>				'',
		'bilingue'=>			0,
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
		'index'=>				1,
		'tips'=>				'Synthaxe avec <strong>eval()</strong> du php<br />
								<strong>$html</strong> est la variable de concaténation, $valeur<strong>s</strong> est l\'array d\'une valeur (si items)<br />
								Ex. 1 : &lt;cite&gt;\'.($legende != \'\' ? $legende.\'&lt;br/&gt;\' : \'\').$auteur.\'&lt;/cite&gt;<br />
								Ex. 2 : \'; foreach($auteurs as $auteur) $html .= \'&lt;p&gt;\'.$auteur[\'auteur\'].\'&lt;/p&gt;\'; $html .= \'<br />
								Pour le type <strong>image</strong> : nom, nom_src, nom_taille, nom_grande, nom_titre, nom_legende, nom_popup, nom_credits, nom_auteur, nom_date<br />
								Pour le type <strong>fichier</strong> : nom, nom_src, nom_titre, nom_ext, nom_size<br />
								Variables additionnelles utilisables pour chaque template : <strong>$unique_id</strong>, <strong>$WWW</strong><br />
								<strong>#script#</strong> et <strong>#/script#</strong> permettent d\'insérer une balise de script',
		'separateur'=>			'',
	),
);

//$C = new SQL($R); $C->createSql($R_data,'1');
//$C = new SQL($R); $C->addSql($R_data);

?>