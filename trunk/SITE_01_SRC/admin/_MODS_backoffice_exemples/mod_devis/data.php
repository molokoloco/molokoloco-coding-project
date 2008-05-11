<?
$boutonListe = '';//<table  border="0" cellspacing="0" cellpadding="0"><tr><td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td><td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:void(0);" onclick="window.open(\'../mod_clients/devis_purge.php\',\'\',\'toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=600,height=600\');" class="menu">Purger les devis temporaires</a></td><td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td></tr></table>';

$id = gpc('id');
if ($id > 0) {
	$A = new SQL('mod_devis');
	$A->LireSql(array('*')," id='$id' LIMIT 1 ");
	$mailok = Aff($A->V[0]['mailok']);

	$boutonFiche = '<table  border="0" cellspacing="0" cellpadding="0">
	<tr>
	<td width="1">
	<table  border="0" cellspacing="0" cellpadding="0"><tr><td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td><td nowrap="nowrap" background="../images/images/button_02.png"><a href="javascript:void(0);" onclick="window.open(\'../mod_devis/devis_impression.php?devis_id='.$id.'\',\'\',\'toolbar=yes,location=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,width=600,height=600\');" class="menu">D&eacute;tail du devis (Imprimer)</a></td><td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td></tr></table>
	</td><td>&nbsp;</td><td>
		<table  border="0" cellspacing="0" cellpadding="0">
		<tr>
		<td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td>
		<td nowrap="nowrap" background="../images/images/button_02.png">';
	if ($mailok < 1) {
		$boutonFiche .= '<a href="javascript:void(0);" onClick="window.open(\'../mod_devis/send_mail.php?id='.$id.'\',\'\',\'width=250,height=100\');" class="menu">Envoyer e-mail validation du devis</a>';
	}
	else  {
		$boutonFiche .= '<a href="javascript:void(0);" onClick="window.open(\'../mod_devis/send_mail.php?id='.$id.'&mailok=1\',\'\',\'width=250,height=100\');" class="menu">Re-Envoyer e-mail validation devis</a>';
	}
	$boutonFiche .= '</td>
		<td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td>
		</tr>
		</table></td>';
	
	if ($mailok > 0) {	
		$boutonFiche .= '<td>&nbsp;</td><td width="1">
		<table  border="0" cellspacing="0" cellpadding="0"><tr><td width="1"><img src="../images/images/button_01.png" width="15" height="18" /></td><td nowrap="nowrap" background="../images/images/button_02.png">';
		if ($mailok == 1) {
			$boutonFiche .= '<a href="javascript:void(0);" onClick="window.open(\'../mod_devis/send_mail_expedition.php?id='.$id.'\',\'\',\'width=250,height=100\');" class="menu">Envoyer e-mail validation expédition</a>';
		}
		else  {
			$boutonFiche .= '<a href="javascript:void(0);" onClick="window.open(\'../mod_devis/send_mail_expedition.php?id='.$id.'&mailok=2\',\'\',\'width=250,height=100\');" class="menu">Re-Envoyer e-mail validation expédition</a>';
		}
		$boutonFiche .= '</td><td width="1"><img src="../images/images/button_04.png" width="7" height="18" /></td></tr></table>
		</td>';
	}
	$boutonFiche .= '
	</tr>
	</table>';
}

// -------------------- COMMANDES --------------------------- //
$R1 = array(
	'table' => 					'mod_devis',
	'titre' => 					'Demande de devis',
	'titres' => 				'Demandes de devis',
	'genre'=>					'e',
	'relation'=>				'',
	'rubrelation'=>				'',
	'childRel'=>				'',
	'rubLevel'=>				'',
	'prodLevel'=>				'',
	'wherenot'=>				'',
	'postbdd'=>					'',
	'preview'=>					'',
	'ifr '=>					'',
	'boutonFiche'=>				$boutonFiche,
	'boutonListe'=>				$boutonListe,
	'filtre'=>					array('statut_c'=>'2','statut_p'=>'todo'),
	'ordre'=>					'id DESC',
	'miseenavant'=>				'',
	'fixe'=>					0,
	'tips '=>					'',
	'rep'=>						$rep.'clients_devis/',
	'sizeimg'=>					array(mini=>'95x120',medium=>'100x145',grand=>'360x480',tgrand=>'3600x4800'),
);


$R1_data = array(
	array(name=>'id'),
	
	array(name=>'statut_c',titre=>'Statut du devis',sqlType=>'tinyint',nbChar=>'1',sqlDefaut=>'1',input=>'select',valeur=>array('1','2','3','4'),titrevaleur=>array('Non valide','Attente du fichier','A traiter','Trait&eacute;e'),index=>'1'),
	array(name=>'statut_p',titre=>'Statut du paiement',sqlType=>'tinyint',nbChar=>'1',sqlDefaut=>'1',input=>'select',valeur=>array('0','1'),titrevaleur=>array('Attente','Valide'),index=>'1','tips'=>'Valid&eacute; par la banque ou l\'admin, suivant le moyen de paiement'),
	
	array(name=>'mailok',titre=>'Email envoy&eacute;',sqlType=>'tinyint',nbChar=>'1',sqlDefaut=>'1',input=>'radio',valeur=>array('0','1'),titrevaleur=>array('Non','Oui'),index=>'1',disable=>'1','tips'=>'Le mail avertissant de la validation du devis est envoy&eacute;'),
	
	array(
		'name'=>				'dateexpe',
		'titre'=>				'Exp&eacute;di&eacute;e le',
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
		'oblige'=>				'',
		'disable'=>				'',
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'A pr&eacute;ciser, pour pass&eacute;e le devis en statut &quot;Trait&eacute;e&quot;',
		'separateur'=>			'',
	),
	
	array(name=>'client_id',titre=>'Client',sqlType=>'int',nbChar=>'8',input=>'select',inc=>'mod_clients:id:nom-prenom',index=>'1',disable=>'1', tips=>'Fiche client : <a href="../mod_clients/index.php?mode=fiche&id=\'.$F->V[\'0\'][$this->data[$i][\'name\']].\'" target="_blank">Cliquer ici</a>', 'separateur'=>'INFOS DEVIS'),
	
	array(name=>'titre',titre=>'R&eacute;f&eacute;rence',sqlType=>'varchar',nbChar=>'250',input=>'text',htmDefaut=>'post',index=>'1',oblige=>'1',disable=>'1'),
	
	array(
		'name'=>				'datecrea',
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
		'disable'=>				1,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				1,
		'tips'=>				'',
		'separateur'=>			'',
	),
	
	array(
		'name'=>				'souhaits',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				4,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				array('1','2','3'),
		'titrevaleur'=>			array('Mat&eacute;rialiser un fichier 3D', 'Cr&eacute;er une r&eacute;plique', 'Cr&eacute;er un fichier 3D'),
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
		'tips'=>				'',
		'separateur'=>			''
	),
	array(
		'name'=>				'nom_fichier',
		'titre'=>				'Nom du fichier',
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
		'index'=>				'',
		'tips'=>				''
	),

	array(
		'name'=>				'format_fichier',
		'titre'=>				'Format fichier',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				20,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				array_keys($arr_Fichier_Formats),
		'titrevaleur'=>			array_values($arr_Fichier_Formats),
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
		'tips'=>				'',
		'separateur'=>			''
	),
	array(
		'name'=>				'fichier',
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
		'tips'=>				'Formats : vrml, ...',
	),

	
	array(
		'name'=>				'civilite',
		'titre'=>				'',
		'sqlType'=>				'varchar',
		'sqlDefaut'=>			'',
		'nbChar'=>				4,
		'bilingue'=>			0,
		'input'=>				'select',
		'valeur'=>				array('Mr','Mme','Mlle'),
		'titrevaleur'=>			array('Mr','Mme','Mlle'),
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
		'tips'=>				'',
		'separateur'=>			'CLIENT'
	),
	array(
		'name'=>				'nom',
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
		'index'=>				'',
		'tips'=>				''
	),
	array(
		'name'=>				'prenom',
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
		'index'=>				'',
		'tips'=>				''
	),

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
		'index'=>				'',
		'tips'=>				''
	),
	
	array(
		'name'=>				'adresse',
		'titre'=>				'Adresse',
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
		'index'=>				0,
		'tips'=>				'',
		'separateur'=>			'Adresse',
	),
	array(
		'name'=>				'cp',
		'titre'=>				'Code postal',
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
	),
	array(
		'name'=>				'ville',
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
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'',
	),
	array(
		'name'=>				'pays',
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
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'',
	),

	array(
		'name'=>				'tel',
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
		'oblige'=>				0,
		'disable'=>				0,
		'relation'=>			'',
		'inc'=>					'',
		'unique'=>				'',
		'action'=>				'',
		'index'=>				0,
		'tips'=>				'',
	),
	
	array(name=>'commentaire',titre=>'Commentaire admin.',sqlType=>'text',input=>'textarea',wysiwyg=>'0', 'separateur'=>'CHAMPS ADMIN'),

);
//$C = new SQL($R1); $C->createSQL($R1_data,'1');
//$C = new SQL($R1); $C->addSQL($R1_data);

?>