<?
/*
// nouvelle recherche
$recherche = new s_recherche($_POST['keyword']);
// Recherche dans la présentation des produits - Utilise l'accroche pour s'afficher
$rchr_prd = new s_object('catalogue_produits_presentation','texte',_MATCH);
$rchr_prd->fonction = 'htmlentities' ;
$rchr_prd->champDefinition('SELECT id as prd_id, titre As prd_titre, accroche FROM catalogue_produits WHERE id="{cat_id}"');
$rchr_prd->champDefinition('SELECT A.id As gamme_id , A.titre As gamme_titre FROM rubriques_rel_produits As B INNER JOIN catatloque_rubriques As A ON (B.cat_id = A.id) WHERE B.prod_id="{prd_id}"');
$rchr_prd->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=catalogue&gamme_id={gamme_id}&prd_id={prd_id}">{prd_titre}</a></h2><h3>{gamme_titre} / {prd_titre}</h3><p>{accroche}</p>' ;
$recherche->s_query($rchr_prd);

// Recherche dans le titre de la gamme
$rchr_gamme = new s_object('catatloque_rubriques','texte',_MATCH);
$rchr_gamme->fonction = 'htmlentities' ;
$rchr_gamme->applyFieldFunction('texte','short');
$rchr_gamme->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=catalogue&gamme_id={id}">{titre}</a></h2><p>{texte}</p>' ;
$recherche->s_query($rchr_gamme);

// Recherche dans les actualités
$rchr_actu = new s_object('une_articles','texte',_MATCH);
$rchr_actu->fonction = 'htmlentities' ;
$rchr_actu->applyFieldFunction('texte','short');
$rchr_actu->champDefinition('SELECT titre As actu_titre FROM une WHERE id="{cat_id}"');
$rchr_actu->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=une&archives=1&actu={cat_id}">{titre}</a></h2><h3>{actu_titre} / {titre}</h3><p>{texte}</p>' ;
$recherche->s_query($rchr_actu);

// Revues de presse
$rchr_revue = new s_object('presse_revues','texte',_MATCH);
$rchr_revue->fonction = 'htmlentities' ;
$rchr_revue->applyFieldFunction('texte','short');
$rchr_revue->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=presse&rub=revues">{titre}</a></h2><p>{texte}</p>' ;
$recherche->s_query($rchr_revue);

// Recherche sur les communiqués de presse
$rchr_communiques = new s_object('presse_communique','texte',_LIKE);
$rchr_communiques->fonction = 'htmlentities' ;
$rchr_communiques->applyFieldFunction('texte','short');
$rchr_communiques->applyFieldFunction('date','cleanDate');
$rchr_communiques->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=presse&rub=communique">{titre}</a></h2><h3>{date}</h3><p>{texte}</p>' ;
$recherche->s_query($rchr_communiques);

// Recherche dans le glossaire
$rchr_glossaire = new s_object('glossaire_lexique','titre',_LIKE);
$rchr_glossaire->duplicateField('titre','lettre','firstLetter');
$rchr_glossaire->applyFieldFunction('texte','short');
$rchr_glossaire->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=lexique&L={lettre}">{titre}</a></h2><p>{texte}</p>' ;
$recherche->s_query($rchr_glossaire);

// Recherche sur la FAQ
$rchr_faq = new s_object('faq_contenus','titre',_LIKE);
$rchr_faq->applyFieldFunction('texte','short');
$rchr_faq->applyFieldFunction('faq_rubrique','short');
$rchr_faq->champDefinition('SELECT titre As faq_rubrique FROM une WHERE id="{cat_id}"');
$rchr_faq->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=faq&rub={cat_id}">{faq_rubrique}</a></h2><p>{titre}</p>' ;
$recherche->s_query($rchr_faq);

// Execution de la recherche
$results = $recherche->execute();
// Nombre de résultats par page
$by_page = 5 ;
// Nombre de pages
$nb_pages = ceil($recherche->nbResultats/$by_page);
*/

// variables de configuration
define('_EGAL','=');
define('_LIKE','LIKE');
define('_MATCH','MATCH');


/**
 * Noyau du moteur de recherche
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-09-10
 * @version 1.0
 * 
 * @see s_object
 * @see s_recherche_common
 *
 */
class s_recherche extends s_recherche_common {
	/**
	 * Contient la liste des objets s_object
	 *
	 * @var array of s_object
	 */
	var $sqlLocations ;
	/**
	 * Contient un tableau avec tous les enregistrements qui matchent sur l'ensemble
	 * de la recherche avec l'ensemble des criteres mis en forme avec les templates
	 *
	 * @var array of string
	 */
	var $sqlOutput ;
	/**
	 * Mot(s) clé(s) de la recherche
	 *
	 * @var string
	 */
	var $keyword ;
	/**
	 * Nombre de résultats de cette recherche
	 *
	 * @var integer
	 */
	var $nbResultats ;
	/**
	 * Utilisée en interne et qui dresse la liste des ID qui ont matché pour la table en cours
	 * De sorte a eviter les doublons lors de l'utilisation de deux criteres différents
	 * mais basés sur la meme table
	 * Ex : array{  
	 * [table_1] => array { 
	 *    [0] => int(1)
	 *    [1] => int(32)
	 *    [2] => int(84)
	 *     }
	 *  }
	 *
	 * @var array of array of integer
	 */
	var $doublonsKepper ;
	/**
	 * Nom du moteur de recherche
	 * La table d'indexation etant commune a tout moteur de recherche
	 * Cette donnée permet de dissocier les données nécessaires a un
	 * moteur de recherche de celles nécessaires a un autre
	 *
	 * @var string
	 */
	var $app_name ;
	/**
	 * Active ou non les requetes et le temps d'execution de l'ensemble
	 *
	 * @var boolean
	 */
	var $isDebug ;
	/**
	 * Contiendra le temps d'exécution en nombre flotant de 4 décimales
	 *
	 * @var float
	 */
	var $timeExecution ;
	/**
	 * Constructeur, il permet d'initialiser les différents paramètres
	 * par défaut de la classe, il prends en parametre la chaine à rechercher
	 *
	 * @param $keyword string
	 * @return void
	 */
	function s_recherche($keyword)
	{
		$this->nbResultats = 0 ;
		$this->sqlLocations = array();
		$this->sqlOutput = array();
		$this->doublonsKepper = array();
		$this->app_name = 'mt_rchr1' ;
		$this->isDebug = false ;
		$this->timeExecution = microtime(true);
		if(strlen(trim($keyword)))
		{
			if(get_magic_quotes_gpc()) $this->keyword = $keyword ;
			else $this->keyword = str_replace("'",chr(92)."'",$keyword);
		}
		$this->s_recherche_common();
	}
	/* Mise a jour du nom de l'application */
	function setApplicationName($appName)	{ $this->app_name = $appName ; }
	/* Obtention du nom de l'application */
	function getApplicationName()			{ return $this->app_name ; }
	/* En débug ? */
	function getDebug()						{ return $this->isDebug ; }
	function setDebug($bool)				{ $this->isDebug = $bool ; }
	/**
	 * Fonction permettant de répertorier un objet s_object au sein de l'objet s_recherche
	 * En quelque sorte, ca permet d'ajouter un critère à la recherche
	 *
	 * @param object $s_object (s_object)
	 * 
	 * @see s_object
	 */
	function s_query ($s_object)
	{
		if (is_object($s_object) ){ //  && $s_object instanceof s_object ){ // PHP5 seulement
			$this->sqlLocations[] = $s_object ;
			$this->doublonsKepper[$s_object->sqlTable] = array();
		}
	}
	/**
	 * Retourne la liste des criteres de la recherche
	 *
	 * @return array of object (s_object)
	 */
	function s_query_list()
	{
		return $this->sqlLocations ;
	}
	/**
	 * Execution de la recherche
	 * Avec ou sans moteur d'indexation
	 *
	 * @param $indexationboolean 
	 * @return CF: déclaration $this->sqlOutput
	 */
	function execute($indexation=true)
	{
		if($indexation){ $this->IndexationsetUp(); }
		if(sizeof($this->sqlLocations) && strlen(trim($this->keyword))>=1)
		{
			foreach($this->sqlLocations as $s_object)
			{
				if(strlen($s_object->fonction) > 1 && (($indexation && $s_object->fonction!='htmlentities') || !$indexation )) {
					$fonction = $s_object->fonction ;
					$motcle = $fonction($this->keyword);
				}
				else $motcle = $this->keyword ;
				$results = $indexation ? $this->executeWithIndexation($s_object,$motcle) : $this->executeWithoutIndexation($s_object,$motcle);

				// Pour chaque résultat on créé le lien qui va bien :)
				if(sizeof($results))
				{
					foreach($results as $res)
					{
						// Verification de si ya pas des doublons
						if(!in_array($res['id'],$this->doublonsKepper[$s_object->sqlTable]))
						{
							$this->doublonsKepper[$s_object->sqlTable][] = $res['id'] ;
							if(sizeof($s_object->sqlDefinitions)>0)
							{
								// D'autres définitions ?
								foreach($s_object->sqlDefinitions as $def_query)
								{
									$query = preg_replace('!{([^}]+)}!Use', "\$res['$1']", $def_query);
									//echo $query . '<br />' ;
									$def_word = Exe($query,'row');
									if(sizeof($def_word))
									{
										$champs = array_keys($def_word[0]);
										foreach($champs as $champ)
										{
											// Gestion des magicQuotes
											$res[$champ] = Aff($def_word[0][$champ]);
										}
									}
								}
							}
							// Fonctions a apliquer sur des champs ?
							if(sizeof($s_object->fieldFunctions))
							{
								foreach($s_object->fieldFunctions As $champ => $functionToApply)
								{
									if(function_exists($functionToApply))
									{
										$res[$champ] = $functionToApply($res[$champ]);
									}
								}
							}
							// Valeurs par défaut sur les titres
							if(sizeof($s_object->defaultValues))
							{
								foreach($s_object->defaultValues as $champ => $defaultValue )
								{
									if(!strlen(trim($res[$champ]))){ $res[$champ] = $defaultValue ; }
								}
							}
							// Duplication des valeurs ?
							if(sizeof($s_object->duplicateField))
							{
								foreach($s_object->duplicateField as $champ => $cfgDuplication)
								{
									// $cfgDuplication[0] => Nom de l'alias a créer
									// $cfgDuplication[1] => fonction a utiliser
									if(strlen(trim($cfgDuplication[1])) && function_exists($cfgDuplication[1]))
									$res[$cfgDuplication[0]] = $cfgDuplication[1]($res[$champ]);
									else
									$res[$cfgDuplication[0]] = $res[$champ];
								}
							}
							$this->nbResultats++ ;
							foreach($res as $key => $value)
							$res[$key] = str_replace(chr(92),'',$value);
							/*preg_match('!</a>.*{([^}]+)}!Us',$s_object->sqlLinkFormat,$champ);
							$res[$champ[1]] = short($res[$champ[1]]);*/
							$lien = preg_replace('!{([^}]+)}!Use', "\$res['$1']", $s_object->sqlLinkFormat);
							//echo $lien.'<br /><br />' ;
							$this->sqlOutput[$s_object->sqlTable][] = $lien ;
						}
					}
				}
			}
			$this->timeExecution = round(microtime(true)-$this->timeExecution,4);
			if($this->getDebug()) db('Execution time : '.$this->timeExecution);
			return $this->sqlOutput ;
		}
		else return array();
	}
	/**
	 * Execution de la recherche sur un critère précis sans utiliser de table d'indexation
	 * Renvoie un tableau d'enregistrements SQL
	 *
	 * @param $s_object object (s_object) par référence
	 * @param $motclestring 
	 * @return array of array
	 * 
	 * @see s_object
	 */
	function executeWithoutIndexation(&$s_object,$motcle)
	{
		$requete = "SELECT * FROM `".$s_object->sqlTable."` WHERE 1 AND " ;
		if(strlen($s_object->doNotKeep)) $requete.= $s_object->doNotKeep." AND " ;
		switch($s_object->sqlPertinence)
		{
			case _MATCH : $requete.= "MATCH (".$s_object->sqlChamp.") AGAINST ('".prefixMatchKeyWords($motcle)."' IN BOOLEAN MODE)" ; break ;
			case _EGAL : $requete.= $s_object->sqlChamp."='".$motcle."' ORDER BY ".$s_object->sqlChamp." ASC" ; break ;
			case _LIKE :
				$motscles = explode(' ',$motcle);
				$motscles = array_unique($motscles);
				$u = 0;
				foreach($motscles as $motcle) {
					if ($u > 0) $requete.= " OR ";
					$requete.= $s_object->sqlChamp." LIKE '%".$motcle."%'";
					$u++;
				}
				$requete.=  " ORDER BY ".$s_object->sqlChamp." ASC" ;
				break ;
		}
		// Execution de la requete
		$rows = Exe($requete,'row');
		if ($this->getDebug()) db(sizeof($rows),$requete);
		return $rows ;
	}
}
/**
 * Couche d'indexation
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-09-10
 * @version 1.0
 */
class s_recherche_common {
	/**
	 * Nom de la table d'indexation a utiliser
	 *
	 * @var string
	 */
	var $ixTblName = 's_indexation';
	/**
	 * Constructeur pour l'instant inutile mais peut etre utile une prochaine fois ;)
	 *
	 * @return void
	 */
	function s_recherche_common()
	{
		/* Vide pour l'instant ^^ */
	}
	/**
	 * Création de la table d'indexation si nécessaire
	 * 
	 *@return void
	 */
	function IndexationsetUp()
	{
		$query = 'CREATE TABLE IF NOT EXISTS `'.$this->ixTblName.'` (
			`ix_idt` INT( 6 ) NOT NULL  ,
			`ix_app_idt` VARCHAR( 32 ) NOT NULL  ,
			`ix_tbl_name` VARCHAR( 32 ) NOT NULL ,
			`ix_col_name` VARCHAR( 32 ) NOT NULL ,
			`ix_col_value` TEXT NOT NULL ,
			INDEX (
				`ix_idt`
				)
			) ENGINE = MYISAM ;' ;
		Exe($query,false);
		// Nombre d'enregistrements dans la table
		$d = Exe('SELECT COUNT(*) As nb_total FROM `'.$this->ixTblName.'`','row');
		if(intval($d[0]['nb_total'])==0){ $this->processIndexation(); }
	}
	/**
	 * Lancement de la table d'indexation
	 * basé sur la liste des critères de la recherche
	 * 
	 * @see s_object
	 * @return void
	 */
	function processIndexation()
	{
		$objetcts_list =& $this->s_query_list();
		// Suppression des anciennes données
		Exe('DELETE FROM `'.$this->ixTblName.'` WHERE ix_app_idt="'.$this->getApplicationName().'"',false);
		foreach($objetcts_list as $content)
		{
			// Récupération des données de la table
			$data = Exe('SELECT `'.$content->idField.'`,`'.$content->sqlChamp.'` FROM `'.$content->sqlTable.'`','row');
			if ($this->getDebug()) db($data,'$data').'<br />' ;
			foreach($data as $_data)
			{
				// Création de la requete
				$query = "INSERT INTO `".$this->ixTblName."` (ix_idt,ix_app_idt,ix_tbl_name,ix_col_name,ix_col_value) VALUES ('".$_data[$content->idField]."','".$this->getApplicationName()."','".$content->sqlTable."','".$content->sqlChamp."','".$this->processStringCleaning($_data[$content->sqlChamp])."')" ;
				if ($this->getDebug()) db($query,'$query').'<br />' ;
				Exe($query,false);
			}
		}
	}
	/**
	 * Procédure statique d'appel a l'indexation partielle
	 * Le but est de mettre a jour seulement une partue du 
	 * contenu lors par exemple de la modification de contenu
	 * 
	 * @param $app_idt string (identifiant du moteur de recherche)
	 * @param $tableName string (nom de la table a indexer)
	 * @param $idField string (nom de la primary key au sein de cette table)
	 * @param $colls array of string (Liste des champs a indexer, * ne fonctionne pas ;))
	 * 
	 * @return void
	 */
	function __autoIndexation($app_idt,$tableName,$idField,$colls=array())
	{
		$obj_vars = get_class_vars('s_recherche_common');
		$data = Exe('SELECT * FROM `'.$content->sqlTable.'`','row');
		foreach($data as $_data)
		{
			foreach($colls as $fieldName)
			{
				// Création de la requete
				$query = "INSERT INTO `".$obj_vars['ixTblName']."` (ix_idt,ix_app_idt,ix_tbl_name,ix_col_name,ix_col_value) VALUES ('".$_data[$idField]."','".$app_idt."','".$tableName."','".$fieldName."','".call_user_func(array('s_recherche_common', 'processStringCleaning'),$_data[$fieldName])."')" ;
				// if ($this->getDebug()) echo $query.'<br />' ;
				Exe($query,false);
			}
		}
	}
	/**
	 *  nettoyage des différentes strings pour virer tous les accents
	 * cette procédure sera appelée au moment de "nettoyer" le contenu a indexer
	 * par exemple pour enlever les baleses HTML et les encodages HTML
	 * 
	 * @param string $stringValue (chaine a nettoyer)
	 * @return string $string (chaine traitée)
	 */
	function processStringCleaning($stringValue)
	{
		$_stringValue = html_entity_decode(strip_tags($stringValue));
		$_stringValue = str_replace('&rsquo;',"'",$_stringValue);
		return str_replace("'",chr(92)."'",$_stringValue);
	}
	/**
	 * Execution de la recherche sur un critère précis en utilisan la table d'indexation
	 * Renvoie un tableau d'enregistrements SQL identique a la version qui ne l'utilise pas
	 *
	 * @param object $s_object (s_object) par référence
	 * @param string $keywords
	 * @return array of array
	 * 
	 * @see s_object
	 */
	function executeWithIndexation(&$s_object,$keywords)
	{
		$requete = "SELECT A.ix_idt, B.* FROM `".$this->ixTblName."` As A INNER JOIN `".$s_object->sqlTable."` As B ON (A.ix_idt = B.".$s_object->idField.") WHERE A.ix_app_idt='".$this->getApplicationName()."' AND A.ix_tbl_name='".$s_object->sqlTable."' AND A.ix_col_name='".$s_object->sqlChamp ."' AND " ;
		// if(strlen($s_object->doNotKeep)) $requete.= $s_object->doNotKeep." AND " ;
		switch($s_object->sqlPertinence)
		{
			case _MATCH : $requete.= "MATCH (A.ix_col_value) AGAINST ('".prefixMatchKeyWords($keywords)."' IN BOOLEAN MODE)" ; break ;
			case _EGAL : $requete.= "A.ix_col_value='".$keywords."'" ; break ;
			case _LIKE :
				$motscles = explode(' ',$keywords);
				$motscles = array_unique($motscles);
				$u = 0;
				foreach($motscles as $motcle) {
					if ($u > 0) $requete.= " OR ";
					$requete.= "A.ix_col_value LIKE '%".$motcle."%'";
					$u++;
				}

				break ;
		}
		if($s_object->sqlPertinence==_EGAL || $s_object->sqlPertinence==_LIKE)
		{
			$requete.=  " ORDER BY ".$s_object->sqlChamp." ASC" ;
		}
		// Execution de la requete
		$rows = Exe($requete,'row');
		if ($this->getDebug()) echo $requete.' <b>('.sizeof($rows).')</b><br />';
		return $rows ;
	}
}
/**
 * Descripteur de recherche
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-09-10
 * @version 1.0
 */
class s_object {
	/**
	 * Nom de la table SQL sur lequel effectuer la recherche
	 *
	 * @var string
	 */
	var $sqlTable ;
	/**
	 * Nom du champ de la table concerné par la recherche
	 *
	 * @var string
	 */
	var $sqlChamp ;
	/**
	 * La pertinence a utiliser pour la recherche
	 * par défaut on la choisi parmi les trois constantes en haut
	 *
	 * @var constante (_EGAL, _MATCH, ou _LIKE)
	 */
	var $sqlPertinence ;
	/**
	 * Cette variable contient le template a utiliser pour le rendu dans s_recherche::sqlOutput
	 * Elle fonctionne simplement avec l'utilisation de tag correspondant a des noms de champs SQL entre accolades
	 *
	 * @var string
	 * @todo eventuellement expliciter le nom d'un fichier de template
	 * @see s_object::champDefinition()
	 */
	var $sqlLinkFormat ;
	/**
	 * Contient des requetes SQL de définitions SQL
	 *
	 * @var array of string
	 * @see s_object::champDefinition()
	 */
	var $sqlDefinitions ;
	/**
	 * contient un eventuelle clause WHERE dans la requete SQL
	 *
	 * @var string
	 * @see s_object::dontCatch()
	 */
	var $doNotKeep ;
	/**
	 * Nom de la fonction a appliquer sur le mot clé avant de l'employer dans la recherche
	 * par exemple pour la mise en forme d'une date
	 * 
	 * @var array of string
	 * @see s_object::applyFieldFunction()
	 */
	var $fieldFunctions ;
	/**
	 * Stocke le contenu d'une procédure
	 *
	 * @var array of string
	 * @see s_object::setDefaultValue()
	 */
	var $defaultValues ;
	/**
	 * Stocke le contenu d'une procédure
	 *
	 * @var array or string
	 * @see s_object::duplicateField()
	 */
	var $duplicateField ;
	/**
	 * Stocke le contenu d'une procédure
	 *
	 * @var string
	 * @see s_object::setPrimaryKeyFieldName()
	 */
	var $idField ;
	/**
	 * Constructeur, 
	 * 
	 * Nom de la table ou chercher, 
	 * Nom du champ a soliciter, 
	 * Pertinence a utiliser
	 *
	 * @param string $sqlTable
	 * @param string $sqlChamp
	 * @param constante $sqlPertinence
	 * @return void
	 */
	function s_object($sqlTable,$sqlChamp,$sqlPertinence)
	{
		$this->sqlDefinitions = array();
		$this->doNotKeep = '';
		$this->idField = 'id' ;
		$this->sqlTable = $sqlTable ;
		$this->sqlChamp = $sqlChamp ;
		$this->sqlPertinence = $sqlPertinence ;
		$this->fieldFunctions = array();
		$this->defaultValues = array();
		$this->duplicateField = array();
	}
	/**
	 * Partie assez importante, cette partie permet , apres extraction des données qui matchent avec la recherches
	 * de déclarer d'autre données en utilisant des jointures basées sur des valeurs en de champ en accolades
	 * Voir l'exemple pour plus de détails
	 * 
	 * @param string $query
	 */
	function champDefinition($query)
	{
		$this->sqlDefinitions[count($this->sqlDefinitions)] = $query ;
	}
	/**
	 * Ajoute une clause WHERE dans la requete SQL de recherche
	 *
	 * @param string $sqlExpr
	 */
	function dontCatch($sqlExpr)
	{
		if(strlen($sqlExpr)){ $this->doNotKeep = $sqlExpr ; }
	}
	/**
	 * Nom de la fonction a appliquer sur le mot clé avant de l'employer dans la recherche
	 * par exemple pour la mise en forme d'une date
	 * 
	 * @param string $champ champ concerné
	 * @param string $functionName nom de la fonction a appliquer au champ
	 */
	function applyFieldFunction($champ,$functionName)
	{
		$this->fieldFunctions[$champ] = $functionName ;
	}
	/**
	 * Permet d'attribuer une valeur a un champ issu de la base de donnée si celui ci est vide
	 * Par exemple si un commentaire de blog n'a pas de nom d'auteur, on poura mettre "VIDE"
	 *
	 * @param string $champ champ concerné
	 * @param string $value valeur a attribuer
	 */
	function setDefaultValue($champ,$value)
	{
		$this->defaultValues[$champ] = $value ;
	}
	/**
	 * Nom de la primary key de la table et du champ
	 *
	 * @param string $fieldName
	 */
	function setPrimaryKeyFieldName($fieldName)
	{
		$this->idField = $fieldName ;
	}
	/**
	 * Dupliquer une colonne d'un enregistrement de résultat SQL suivant ou non l'application d'une fonction
	 *
	 * @param string $champ
	 * @param string $newName
	 * @param string $function
	 */
	function duplicateField($champ,$newName,$function='')
	{
		$this->duplicateField[$champ] = array($newName,$function);
	}
}
/**
 * Fonctions annexes
 */
function short($str)
{
	$tailleMax = 150 ;
	if(strlen($str)<$tailleMax){ return aff(strip_tags($str)); }
	return aff(truncate(strip_tags($str),0,150));
}
function cleanDate($date)
{
	return 'Le '.substr($date,6,2).'/'.substr($date,4,2).'/'.substr($date,0,4);
}
function firstLetter($str)
{
	return strtolower($str{0});
}
function prefixMatchKeyWords($keyWords)
{
	$eachOne = explode(' ',$keyWords);
	$newWord = array();
	foreach ($eachOne as $word)
	{
		$newWord[] = '+'.$word ;
	}
	return implode(' ',$newWord);
}
if(!function_exists('Exe'))
{
	function Exe($Query,$Return)
	{
		// Le parametre return vaut (res|row|no) pour renvoyer mysql_fetch_array , mysql_num_rows ou rien
		if(!is_array($Query))
		{
			$ExecuteQuery = mysql_query($Query) or die(mysql_error());
			// Si requete ex?cut?e
			if( !$ExecuteQuery)
			{
				// on renvoie faux en guise d'erreur en plus du log
				$return = false ;
			} else {
				// S'il s'agit d'une requete SELECT , on renvoie le contenu
				if(ereg("^SELECT",$Query) && $Return == 'row' )
				{
					while($Row = mysql_fetch_array($ExecuteQuery, MYSQL_ASSOC) )
					$return[] = $Row ;
				}
				elseif(ereg("^SELECT",$Query) && $Return == 'res')
					$return = mysql_num_rows($ExecuteQuery);
				elseif( ereg("^UPDATE",$Query) && $Return = 'res')
					$return = mysql_affected_rows();
				elseif(ereg("^SELECT",$Query) && $Return == 'all') {
					while($Row = mysql_fetch_array($ExecuteQuery, MYSQL_ASSOC) )
					{
						$Array[] = $Row ;
					}
					$return['rows']  = $Array ;
					$return['total'] = mysql_num_rows($ExecuteQuery);
				}
				elseif(ereg("^INSERT",$Query))
					$return = true; //mysql_insert_id();
				else
				// Ce n'est pas une requete SELECT , on renvoie juste true en guise de requete bien effectu?e
					$return = true ;
			}
		}
		else
		{
			$return = array();
			if(sizeof($Return) == sizeof($Query)) { $SetResult=true; }else{ $SetResult=false; }
			// La variable ci-dessus passeras a false en cs d'erreur
			foreach($Query As $key => $SQLQuery )
			{
				$return[] = Exe($SQLQuery,$Return[$key]);
			}
			return $return ;
		}
		// Renvoi
		//$this->DBClose($this->Handle);
		return $return ;
	}
}
?>