<?php
/*
NB : Dans les méthodes dontCatch() il faut préfixer les champs de l'alias de table B pour les champs de la table du critere de recherche

// nouvelle recherche
$recherche = new db_search($_POST['keyword']);
// Recherche dans la présentation des produits - Utilise l'accroche pour s'afficher
$rchr_prd = new search_clause('catalogue_produits_presentation','texte',_MATCH);
$rchr_prd->fonction = 'htmlentities' ;
$rchr_prd->champDefinition('SELECT id as prd_id, titre As prd_titre, accroche FROM catalogue_produits WHERE id="{cat_id}"');
$rchr_prd->champDefinition('SELECT A.id As gamme_id , A.titre As gamme_titre FROM rubriques_rel_produits As B INNER JOIN catatloque_rubriques As A ON (B.cat_id = A.id) WHERE B.prod_id="{prd_id}"');
$rchr_prd->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=catalogue&gamme_id={gamme_id}&prd_id={prd_id}">{prd_titre}</a></h2><h3>{gamme_titre} / {prd_titre}</h3><p>{accroche}</p>' ;
$recherche->s_query($rchr_prd);

// Recherche dans le titre de la gamme
$rchr_gamme = new search_clause('catatloque_rubriques','texte',_MATCH);
$rchr_gamme->fonction = 'htmlentities' ;
$rchr_gamme->applyFieldFunction('texte','short');
$rchr_gamme->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=catalogue&gamme_id={id}">{titre}</a></h2><p>{texte}</p>' ;
$recherche->s_query($rchr_gamme);

// Recherche dans les actualités
$rchr_actu = new search_clause('une_articles','texte',_MATCH);
$rchr_actu->fonction = 'htmlentities' ;
$rchr_actu->applyFieldFunction('texte','short');
$rchr_actu->champDefinition('SELECT titre As actu_titre FROM une WHERE id="{cat_id}"');
$rchr_actu->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=une&archives=1&actu={cat_id}">{titre}</a></h2><h3>{actu_titre} / {titre}</h3><p>{texte}</p>' ;
$recherche->s_query($rchr_actu);

// Revues de presse
$rchr_revue = new search_clause('presse_revues','texte',_MATCH);
$rchr_revue->fonction = 'htmlentities' ;
$rchr_revue->applyFieldFunction('texte','short');
$rchr_revue->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=presse&rub=revues">{titre}</a></h2><p>{texte}</p>' ;
$recherche->s_query($rchr_revue);

// Recherche sur les communiqués de presse
$rchr_communiques = new search_clause('presse_communique','texte',_LIKE);
$rchr_communiques->fonction = 'htmlentities' ;
$rchr_communiques->applyFieldFunction('texte','short');
$rchr_communiques->applyFieldFunction('date','cleanDate');
$rchr_communiques->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=presse&rub=communique">{titre}</a></h2><h3>{date}</h3><p>{texte}</p>' ;
$recherche->s_query($rchr_communiques);

// Recherche dans le glossaire
$rchr_glossaire = new search_clause('glossaire_lexique','titre',_LIKE);
$rchr_glossaire->duplicateField('titre','lettre','firstLetter');
$rchr_glossaire->applyFieldFunction('texte','short');
$rchr_glossaire->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=lexique&L={lettre}">{titre}</a></h2><p>{texte}</p>' ;
$recherche->s_query($rchr_glossaire);

// Recherche sur la FAQ
$rchr_faq = new search_clause('faq_contenus','titre',_LIKE);
$rchr_faq->applyFieldFunction('texte','short');
$rchr_faq->applyFieldFunction('faq_rubrique','short');
$rchr_faq->champDefinition('SELECT titre As faq_rubrique FROM une WHERE id="{cat_id}"');
$rchr_faq->sqlLinkFormat = '<h2><a class="savoir_plus" href="index2.php?goto=faq&rub={cat_id}">{faq_rubrique}</a></h2><p>{titre}</p>' ;
$recherche->s_query($rchr_faq);

// Execution de la recherche
$results = $recherche->execute(true); // true si avec indexation ou false sinon
// Nombre de résultats par page
$by_page = 5 ;
// Nombre de pages
$nb_pages = ceil($recherche->nbResultats/$by_page);
*/

// variables de configuration de la pertinence
define('_EGAL','=');
define('_LIKE','LIKE');
define('_LIKE_BY_WORD','LIKE_BY_WORD');
define('_MATCH','MATCH');

// Variables systeme
define('_IGNORE_RESULT','__IGNORE') ;

/**
 * Noyau du moteur de recherche
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-10-04
 * @version 1.16a
 * 
 * @see search_clause
 * @see db_search_common
 *
 */
class db_search extends db_search_common {
	/**
	 * Contient la liste des objets search_clause
	 *
	 * @var array of search_clause
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
	var $doublonsKeeper ;
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
	 * Objet contenant le SQL accessor par la method query()
	 *
	 * @var object
	 */
	var $app ;
	/**
	 * Url absolu du fichier contenant les fonctions annexes
	 *
	 * @var string (filename)
	 */
	var $log ;
	/**
	 * Liste des filtres a utiliser au niveau des mot cles
	 *
	 * @var array of string
	 */
	var $keywordFilters ;
	/**
	 * Contient la liste des messages de la class
	 *
	 * @var array
	 */
	var $errors ;
	/**
	 * Constructeur, il permet d'initialiser les différents paramètres
	 * par défaut de la classe, il prends en parametre la chaine à rechercher
	 *
	 * @param string $keyword
	 * @return void
	 */
	function db_search($keyword,&$app,$appName='mt_rchr1')
	{
		$this->nbResultats = 0 ;
		$this->sqlLocations = array();
		$this->keywordFilters = array() ;
		$this->sqlOutput = array();
		$this->doublonsKeeper = array();
		$this->app_name = $appName ;
		if(substr($keyword,0,4)=='_dbg'){ $this->isDebug =true ; $keyword = str_replace('_dbg','',$keyword); }
		else $this->isDebug = false ;
		$this->keyword = $keyword ;
		$this->timeExecution = microtime(true);
		if(!is_object($app) && $app!==false){ die('bad Accessor') ; }
		else $this->app =& $app ;
		// if($this->isDebug){ ini_set('display_errors','on'); error_reporting(E_ALL); }
		parent::db_search_common($app);
	}
	function getSqlAccessor()				{ return $this->app ; }
	/* Mise a jour du nom de l'application */
	function setApplicationName($appName)	{ $this->app_name = $appName ; }
	/* Obtention du nom de l'application */
	function getApplicationName()			{ return $this->app_name ; }
	/* En débug ? */
	function getDebug()						{ return $this->isDebug ; }
	function setDebug($bool)				{ $this->isDebug = $bool ; }
	function setMessage($string)			{ $this->log[] = $string ; }
	function getMessage()					{ return $this->log ; }
	function getKeywords()					{ return $this->keyword ; }
	/* Librairie ? */
	function loadLibs($filePath)			{ if(is_file($filePath)){ include($filePath); } }
	
	function addKeyWordFilter($filterName)	{ $this->keywordFilters[] = $filterName ; }
	/**
	 * Fonction permettant de répertorier un objet search_clause au sein de l'objet db_search
	 * En quelque sorte, ca permet d'ajouter un critère à la recherche
	 *
	 * @param object $search_clause (search_clause)
	 * 
	 * @see search_clause
	 */
	function s_query ($search_clause)
	{
		if (is_object($search_clause) ){ //  && $search_clause instanceof search_clause ){ // PHP5 seulement
			$this->sqlLocations[] = $search_clause ;
			$this->doublonsKeeper[$search_clause->sqlTable] = array();
		}
	}
	/**
	 * Retourne la liste des criteres de la recherche
	 *
	 * @return array of object (search_clause)
	 */
	function s_query_list()
	{
		return $this->sqlLocations ;
	}
	/**
	 * Execution de la recherche
	 * Avec ou sans moteur d'indexation
	 *
	 * @param boolean $indexation 
	 * @return CF: déclaration $this->sqlOutput
	 */
	function execute($indexation=false)
	{
		if($indexation)
		{
			$this->IndexationsetUp();
			// Mise à jour de la table d'indexation
			$_params = $this->app->query('SELECT * FROM `'.$this->ixTblName.'_params`');
			if(is_array($_params) && sizeof($_params))
			{
				foreach($_params as $p)
				{
					$params[$p['ix_prm_tbl_name']] = $p['ix_prm_nb_insert'] ;
				}
			}
		}
		// Nettoyage des mots cles avec l'objet de filtre
		$searchFilter =& new db_search_filter($this,$this->keyword);
		if(sizeof($this->keywordFilters))
		{
			foreach($this->keywordFilters as $filter){ $searchFilter->useFilter($filter); }
		}
		// Mise a jour du mot cle
		$this->keyword = $searchFilter->getFilteredKeywods();
		if(sizeof($this->sqlLocations) && strlen(trim($this->keyword))>=1)
		{
			foreach($this->sqlLocations as $search_clause)
			{
				if(strlen($search_clause->fonction) > 1 && (($indexation && $search_clause->fonction!='htmlentities') || !$indexation )) {
					$fonction = $search_clause->fonction ;
					$motcle = $fonction($this->keyword);
				}
				else $motcle = $this->keyword ;
				
				$results = $indexation ? $this->executeWithIndexation($search_clause,$motcle,intval(  $params[$search_clause->sqlTable] ) ) : $this->executeWithoutIndexation($search_clause,$motcle);
				// echo $search_clause->sqlTable.'aaaaaaaaaa, '.$search_clause->sqlChamp.'<br />'.db($search_clause->sqlDefinitions);
				// Pour chaque résultat on créé le lien qui va bien :)
				if(sizeof($results) && is_array($results))
				{
					
					foreach($results as $res)
					{
						// Verification de si ya pas des doublons
						if(!in_array($res[$search_clause->idField],$this->doublonsKeeper[$search_clause->sqlTable]))
						{
							$this->doublonsKeeper[$search_clause->sqlTable][] = $res[$search_clause->idField] ;
							
							if(sizeof($search_clause->sqlDefinitions)>0)
							{
								// D'autres définitions ?
								foreach($search_clause->sqlDefinitions as $def_query)
								{
									$query = preg_replace('!{([^}]+)}!Use', "\$res['$1']", $def_query);
									// echo $query . '<br />' ;
									$this->setMessage($query,'$query') ;
									$def_word = $this->app->query($query);
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
							if(sizeof($search_clause->fieldFunctions))
							{
								foreach($search_clause->fieldFunctions As $champ => $functionToApply)
								{
									if(function_exists($functionToApply))
									{
										$results = $functionToApply($res[$champ]) ;
										if(!is_array($results))
										{
											$res[$champ] = $functionToApply($res[$champ]);
											if($res[$champ] == _IGNORE_RESULT ){ continue(2) ; }
										}
										else 
										{
											foreach ($results As $fieldName => $fieldValue )
											{
												$res[$fieldName] = $fieldValue ;
											}
										}
									}
								}
							}
							// Valeurs par défaut sur les titres
							if(sizeof($search_clause->defaultValues))
							{
								foreach($search_clause->defaultValues as $champ => $defaultValue )
								{
									if(!strlen(trim($res[$champ]))){ $res[$champ] = $defaultValue ; }
								}
							}
							// Duplication des valeurs ?
							if(sizeof($search_clause->duplicateField))
							{
								foreach($search_clause->duplicateField as $champ => $cfgDuplication)
								{
									// $cfgDuplication[0] => Nom de l'alias a créer
									// $cfgDuplication[1] => fonction a utiliser
									// $cfgDuplication[2] => parametres de fonctions
									
									// ou !
									
									// $cfgDuplication[1] => méthode à appeler
									// $cfgDuplication[2] => objet a utiliser
									// $cfgDuplication[3] => parametres de fonctions
									
									if($cfgDuplication[2] && is_object($cfgDuplication[2]))
									{
										$res[$cfgDuplication[0]] = $cfgDuplication[2]->$cfgDuplication[1]($res[$champ],$cfgDuplication[3]) ;
										if($res[$cfgDuplication[0]] == _IGNORE_RESULT ){ continue(2) ; }
									}
									elseif(strlen(trim($cfgDuplication[1])) && function_exists($cfgDuplication[1]))
									{
										$results = $cfgDuplication[1]($res[$champ],$cfgDuplication[2]);
										if($results == _IGNORE_RESULT ){ continue(2) ; }
										if(is_array($results))
										{
											foreach($results as $fieldName => $fieldValue)
											{
												$res[$fieldName] = $fieldValue ;
											}
										}
										else 
										{
											$res[$cfgDuplication[0]] = $results ;
										}
									}
									else
									{
										$res[$cfgDuplication[0]] = $res[$champ];
									}
								}
							}
							$this->nbResultats++ ;
							// v($res,false) ;
							foreach($res as $key => $value)
							$res[$key] = str_replace(chr(92),'',$value);
							// preg_match('!</a>.*{([^}]+)}!Us',$search_clause->sqlLinkFormat,$champ);
							// $res[$champ[1]] = short($res[$champ[1]]);
							$lien = preg_replace('!{([^}]+)}!Use', "\$res['$1']", $search_clause->sqlLinkFormat);
							$this->setMessage($this->my_v($res));
							// echo $lien.'<br /><br />' ;
							$this->sqlOutput[$search_clause->sqlTable][] = $lien ;
						}
					}
				}
			}
			$this->timeExecution = round(microtime(true)-$this->timeExecution,4);
			$this->setMessage('Execution time : '.$this->timeExecution);
			if($this->getDebug()) $this->printMessages() ;
			return $this->sqlOutput ;
		}
		else { if($this->getDebug()){ $this->printMessages() ; } return array(); }
	}
	/**
	 * Execution de la recherche sur un critère précis sans utiliser de table d'indexation
	 * Renvoie un tableau d'enregistrements SQL
	 *
	 * @param object $search_clause (search_clause) par référence
	 * @param string $motcle 
	 * @return array of array
	 * 
	 * @see search_clause
	 */
	function executeWithoutIndexation(&$search_clause,$keywords)
	{
		$requete = "SELECT B.* FROM `".$search_clause->sqlTable."` As B WHERE 1 AND " ;
		if(strlen($search_clause->doNotKeep)) $requete.= $search_clause->doNotKeep." AND " ;
		if($search_clause->sqlPertinence==_MATCH && strlen($motcle)<=3) $sqlPertinence = _LIKE ; else $sqlPertinence = $search_clause->sqlPertinence ;
		switch($sqlPertinence)
		{
			case _MATCH : $requete.= "MATCH (B.".$search_clause->sqlChamp.") AGAINST ('".prefixMatchKeyWords($keywords)."' IN BOOLEAN MODE)" ; break ;
			case _EGAL : $requete.= "B.".$search_clause->sqlChamp."='".$keywords."' ORDER BY B.".$search_clause->sqlChamp." ASC" ; break ;
			case _LIKE_BY_WORD :
				$eachWord = explode(' ',$keywords);
				$eachWord = array_unique($keywords);
				$u = 0;
				foreach($eachWord as $word) {
					if ($u > 0) $requete.= " OR ";
					$requete.= "B.".$search_clause->sqlChamp." LIKE '%".$word."%' OR B.".$search_clause->sqlChamp." LIKE '%".$word."' OR B.".$search_clause->sqlChamp." LIKE '".$word."%'";
					$u++;
				}
				$requete.= " ORDER BY B.".$search_clause->sqlChamp." ASC" ;
			break ;
			case _LIKE :
				$requete.= "B.".$search_clause->sqlChamp." LIKE '%".$keywords."%' OR B.".$search_clause->sqlChamp." LIKE '%".$keywords."' OR B.".$search_clause->sqlChamp." LIKE '".$keywords."%'";
			break ;
		}
		// Execution de la requete
		$rows = $this->app->query($requete);
		$this->setMessage(sizeof($rows),$requete);
		return $rows ;
	}
	
	function printMessages()
	{
		$messages = $this->getMessage() ;
		if(sizeof($messages))
		{
			foreach($messages as $msg)
			{
				echo $msg.' <br />' ;
			}
		}
	}
}
/**
 * Couche d'indexation
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-09-10
 * @version 1.01a
 */
class db_search_common {
	/**
	 * Nom de la table d'indexation a utiliser
	 *
	 * @var string
	 */
	var $ixTblName = 's_indexation';
	/**
	 * Nom d'une fonction a appeler avant l'indexation
	 *
	 * @var string (function name)
	 */
	var $onBeforeIndexationFctName ;
	/**
	 * Nom d'une fonction a appeler apres l'indexation
	 *
	 * @var string (function name)
	 */
	var $onAfterIndexationFctName ;
	/**
	 * Objet contenant le SQL accessor par la method query()
	 *
	 * @var object
	 */
	var $app ;
	/**
	 * Contient le cache SQL, tableau :
	 * premiere dimension : { cle : nom de table SQL , valeur : array { enregistrements SQL }}
	 *
	 * @var array
	 */
	var $sqlCache ;
	/**
	 * Contient un tableau ayant en clé un nom de table SQL, et en valeur true si la table n'a plus besoin d'etre mise a jour
	 *
	 * @var unknown_type
	 */
	var $sqlCheckedTable ;
	/**
	 * Mettre a jour la table d'indexation coté front
	 *
	 * @var unknown_type
	 */
	var $autoFrontUpdate ;
	/**
	 * Constructeur pour l'instant inutile mais peut etre utile une prochaine fois ;)
	 *
	 * @return void
	 */
	function db_search_common(&$app)
	{
		/* Vide pour l'instant ^^ */
		$this->app =& $app ;
		$this->autoFrontUpdate = true ;
	}
	
	function autoFrontOfficeUpdate()
	{
		$this->autoFrontUpdate = true ;
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
		$this->app->query($query);
		$query = 'CREATE TABLE IF NOT EXISTS `'.$this->ixTblName.'_params` (
			`ix_prm_app_idt` VARCHAR( 32 ) NOT NULL  ,
			`ix_prm_tbl_name` VARCHAR( 32 ) NOT NULL ,
			`ix_prm_nb_insert` INT( 8 ) NOT NULL
			) ENGINE = MYISAM ;' ;
		$this->app->query($query);
		// Nombre d'enregistrements dans la table
		$d = $this->app->query('SELECT COUNT(*) As nb_total FROM `'.$this->ixTblName.'`');
		if(intval($d[0]['nb_total'])==0){ $this->processIndexation(); }
	}
	/**
	 * Lancement de la table d'indexation
	 * basé sur la liste des critères de la recherche
	 * 
	 * @see search_clause
	 * @return void
	 */
	function processIndexation()
	{
		if(!empty($this->onBeforeIndexationFctName) && function_exists($this->onBeforeIndexationFctName)){ $fct = $this->onBeforeIndexationFctName ; $fct(); }
		$objetcts_list =& $this->s_query_list();
		// Suppression des anciennes données
		$this->app->query('DELETE FROM `'.$this->ixTblName.'` WHERE ix_app_idt="'.$this->getApplicationName().'"');
		foreach($objetcts_list as $content)
		{
			$this->__autoIndexation($this->app,$this->getApplicationName(),$content->idField,array($content->sqlChamp), $content->sqlTable);
		}
		if(!empty($this->onAfterIndexationFctName) && function_exists($this->onAfterIndexationFctName)){ $fct = $this->onAfterIndexationFctName ; $fct(); }
	}
	/**
	 * Procédure statique d'appel a l'indexation partielle
	 * Le but est de mettre a jour seulement une partue du 
	 * contenu lors par exemple de la modification de contenu
	 * 
	 * @param string $app_idt (identifiant du moteur de recherche)
	 * @param string $tableName (nom de la table a indexer)
	 * @param string $idField (nom de la primary key au sein de cette table)
	 * @param array $colls (Liste des champs a indexer, * ne fonctionne pas ;))
	 * 
	 * @return void
	 */
	function __autoIndexation(&$app,$app_idt,$idField,$colls=array(),$indexed_table)
	{
		$obj_vars = get_class_vars('db_search_common');
		// Verification du cache
		if(isset($this->sqlCache[$indexed_table]))
			$data = $this->sqlCache[$indexed_table] ;
		else
			$data = $app->query('SELECT * FROM `'.$indexed_table.'`');
		$this->setMessage('Requete d\'indexation : SELECT * FROM `'.$indexed_table.'`'.$this->my_v($data,false));
		// Suppression des anciennes donnees
		$sqlColls = array() ; foreach($colls as $col){ $sqlColls[] = "'".$col."'" ; }
		$app->query('DELETE FROM `'.$obj_vars['ixTblName'].'` WHERE ix_app_idt="'.$app_idt.'" AND ix_tbl_name="'.$indexed_table.'" AND ix_col_name IN ('.implode(',',$sqlColls).')');
		foreach($data as $_data)
		{
			foreach($colls as $fieldName)
			{
				// Création de la requete
				$query = "INSERT INTO `".$obj_vars['ixTblName']."` (ix_idt,ix_app_idt,ix_tbl_name,ix_col_name,ix_col_value) VALUES ('".$_data[$idField]."','".$app_idt."','".$indexed_table."','".$fieldName."','".call_user_func(array('db_search_common', 'processStringCleaning'),$_data[$fieldName])."')" ;
				$this->setMessage($query) ;
				$app->query($query);
			}
		}
		// Mise a jour du nombre d'enregistrements
		$check = $app->query('SELECT COUNT(*) as total FROM `'.$obj_vars['ixTblName'].'_params` WHERE ix_prm_app_idt="'.$app_idt.'" AND ix_prm_tbl_name="'.$indexed_table.'"') ;
		if(intval($check[0]['total'])==0)
			$app->query('INSERT INTO `'.$obj_vars['ixTblName'].'_params` (ix_prm_app_idt,ix_prm_tbl_name,ix_prm_nb_insert) VALUES ("'.$app_idt.'","'.$indexed_table.'","'.sizeof($data).'")') ;
		else 
			$app->query('UPDATE `'.$obj_vars['ixTblName'].'_params` SET ix_prm_nb_insert="'.sizeof($data).'" WHERE ix_prm_app_idt="'.$app_idt.'" AND ix_prm_tbl_name="'.$indexed_table.'" LIMIT 1') ;
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
		$stringValue = str_replace(chr(92),'',$stringValue);
		$_stringValue = html_entity_decode(strip_tags($stringValue));
		$_stringValue = str_replace('&rsquo;',"'",$_stringValue);
		return str_replace("'",chr(92)."'",$_stringValue);
	}
	/**
	 * Execution de la recherche sur un critère précis en utilisan la table d'indexation
	 * Renvoie un tableau d'enregistrements SQL identique a la version qui ne l'utilise pas
	 *
	 * @param object $search_clause (search_clause) par référence
	 * @param string $keywords
	 * @param integer $nbOfItems contient le nombre d'items présents dans la table d'indexation pour cette table
	 * @return array of array
	 * 
	 * @see search_clause
	 */
	function executeWithIndexation(&$search_clause,$keywords,$nbOfItems=0)
	{
		// Dois t-on relancer une indexation ?
		if(!$this->sqlCheckedTable[$search_clause->sqlTable][$search_clause->sqlChamp] && $this->autoFrontUpdate===true)
		{
			// Nombre d'item courant
			$where = strlen($search_clause->doNotKeep) ? ' WHERE '.str_replace('B.','',$search_clause->doNotKeep) : '' ;
			$currentNumber = $this->app->query('SELECT COUNT(*) As total FROM `'.$search_clause->sqlTable.'`'.$where) ;
			// Si le nombre est différent
			if(intval($currentNumber[0]['total'])!=$nbOfItems)
			{
				$this->__autoIndexation($this->app,$this->getApplicationName(),$search_clause->idField,array($search_clause->sqlChamp),$search_clause->sqlTable) ;
			}
			// On ne checkera pas au tour suivant ;)
			$this->sqlCheckedTable[$search_clause->sqlTable][$search_clause->sqlChamp] = true ;
			//v($this->sqlCheckedTable,false);
		}
		$requete = "SELECT A.ix_idt, B.* FROM `".$this->ixTblName."` As A INNER JOIN `".$search_clause->sqlTable."` As B ON (A.ix_idt = B.".$search_clause->idField.") WHERE A.ix_app_idt='".$this->getApplicationName()."' AND A.ix_tbl_name='".$search_clause->sqlTable."' AND A.ix_col_name='".$search_clause->sqlChamp ."' AND " ;
		if(strlen($search_clause->doNotKeep)) $requete.= $search_clause->doNotKeep." AND " ;
		if($search_clause->sqlPertinence==_MATCH && strlen($keywords)<=3) $sqlPertinence = _LIKE ; else $sqlPertinence = $search_clause->sqlPertinence ;
		switch($sqlPertinence)
		{
			case _MATCH : $requete.= "MATCH (A.ix_col_value) AGAINST ('".prefixMatchKeyWords($keywords)."' IN BOOLEAN MODE)" ; break ;
			case _EGAL : $requete.= "A.ix_col_value='".$keywords."'" ; break ;
			case _LIKE_BY_WORD :
				$motscles = explode(' ',$keywords);
				$motscles = array_unique($motscles);
				$u = 0;
				foreach($motscles as $motcle) {
					if ($u > 0) $requete.= " OR ";
					$requete.= "A.ix_col_value LIKE '%".$motcle."%' OR A.ix_col_value LIKE '".$motcle."%' OR A.ix_col_value LIKE '%".$motcle."'";
					$u++;
				}
			break ;
			case _LIKE :
				$requete.= "A.ix_col_value LIKE '%".$keywords."%' OR A.ix_col_value LIKE '".$keywords."%' OR A.ix_col_value LIKE '%".$keywords."'";
			break ;
		}
		if($search_clause->sqlPertinence==_EGAL || $search_clause->sqlPertinence==_LIKE)
		{
			$requete.=  " ORDER BY ".$search_clause->sqlChamp." ASC" ;
		}
		// Execution de la requete
		$rows = $this->app->query($requete);
		if ($this->getDebug()) echo $requete.' <b>('.sizeof($rows).')</b><br />';
		return $rows ;
	}
	/**
	 * Appel de la fonction V() mais de facon silencieuse sans renvoi HTML
	 *
	 * @param mixed var $var
	 * @return string (html)
	 */
	function my_v($var)
	{
		ob_start() ;
		if( function_exists( 'v' ) )
		{
			v($var,false) ;
		}
		elseif( function_exists( 'tab' ) )
		{
			tab( $var );
		}
		$data = ob_get_contents();
		ob_end_clean();
		return $data ;
	}
}
/**
 * Descripteur de recherche
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-10-09
 * @version 1.10a
 */
class search_clause {
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
	 * Cette variable contient le template a utiliser pour le rendu dans db_search::sqlOutput
	 * Elle fonctionne simplement avec l'utilisation de tag correspondant a des noms de champs SQL entre accolades
	 *
	 * @var string
	 * @todo eventuellement expliciter le nom d'un fichier de template
	 * @see search_clause::champDefinition()
	 */
	var $sqlLinkFormat ;
	/**
	 * Contient des requetes SQL de définitions SQL
	 *
	 * @var array of string
	 * @see search_clause::champDefinition()
	 */
	var $sqlDefinitions ;
	/**
	 * contient un eventuelle clause WHERE dans la requete SQL
	 *
	 * @var string
	 * @see search_clause::dontCatch()
	 */
	var $doNotKeep ;
	/**
	 * Nom de la fonction a appliquer sur le mot clé avant de l'employer dans la recherche
	 * par exemple pour la mise en forme d'une date
	 * 
	 * @var array of string
	 * @see search_clause::applyFieldFunction()
	 */
	var $fieldFunctions ;
	/**
	 * Stocke le contenu d'une procédure
	 *
	 * @var array of string
	 * @see search_clause::setDefaultValue()
	 */
	var $defaultValues ;
	/**
	 * Stocke le contenu d'une procédure
	 *
	 * @var array or string
	 * @see search_clause::duplicateField()
	 */
	var $duplicateField ;
	/**
	 * Stocke le contenu d'une procédure
	 *
	 * @var string
	 * @see search_clause::setPrimaryKeyFieldName()
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
	 * @param const $sqlPertinence
	 * @return void
	 */
	function search_clause($sqlTable,$sqlChamp,$sqlPertinence)
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
		$this->sqlDefinitions[] = $query ;
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
	 * @param string $champ La valeur du champ de la ligne SQL de nom du champs est passé en premier argument
	 * @param string $newName Le nom du nouveau tag qui va pouvoir etre utilisé dans le template
	 * @param string $function Nom de la fonction a appeler pour créer le nouveau tag
	 * @param array $params tableau optionnel
	 */
	function duplicateField($champ,$newName,$function='',$params=array())
	{
		$this->duplicateField[$champ] = array($newName,$function,$params);
	}
	/**
	 * Dupliquer une colonne d'un enregistrement de résultat SQL suivant ou non l'application d'une methode
	 *
	 * @param string $champ La valeur du champ de la ligne SQL de nom du champs est passé en premier argument
	 * @param string $newName Le nom du nouveau tag qui va pouvoir etre utilisé dans le template
	 * @param string $methodName Nom de la méthode a apppeler au sein de l'argument 4
	 * @param object $object Pointeur vers l'objet contenant la méthode désignée en argument 3
	 * @param array $params tableau optionnel
	 */
	function duplicateField_byMethod($champ,$newName,$methodName,&$object,$params=array())
	{
		$this->duplicateField[$champ] = array($newName,$methodName,&$object,$params);
	}
}
/**
 * Class de gestion des mots a ignorer tel que les articles
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-10-12
 * @version 1.00a
 */
class db_search_filter extends db_search_filter_common {
	/**
	 * Objet contenant le SQL accessor par la method query()
	 *
	 * @var object
	 */
	var $app ;
	/**
	 * Mots cles de la recherche, normalement 
	 *
	 * @var string
	 */
	var $keywords ;
	/**
	 * Contient la liste des parametres sous forme de tableau associatif
	 *
	 * @var array
	 */
	var $params ;
	/**
	 * Contient un tableau avec la liste des methodes a appeler pour filtrer l'expression de recherche
	 *
	 * @var array of string
	 */
	var $filtersTuUse ;
	/**
	 * Permet uniquement de charger le profil
	 *
	 * @param string $keywords
	 * @return void
	 */
	function db_search_filter(&$app,$keywords)
	{
		$this->app =& $app ;
		$this->filtersTuUse = array();
		$this->setProfilParameter('Language','fr') ;
		$this->keywords = $this->cleanString($keywords);
	}
	/**
	 * Nettoyagede la chaine pour avoir une chaine lisible, CED, sans antislashes ni autres merde
	 *
	 * @param string $string
	 */
	function cleanString($string)
	{
		if (!get_magic_quotes_gpc()) $string = addslashes($string);
		else $string = $string ;
		return $string ;
	}
	/**
	 * Options a parametrer, par exemple la langue pour la liste des mots cles a ignorer
	 *
	 * @param string $paramName
	 * @param string $value
	 */
	function setProfilParameter($paramName,$value)
	{
		if(strlen(trim($paramName))){ $this->params[$paramName] =$value ; }
	}
	/**
	 * fonctions antagoniste a celle du dessus
	 *
	 * @param string $paramName
	 * @return mixed var
	 */
	function getProfilParameter($paramName)
	{
		if(!isset($this->params[$paramName])){$this->app->setMessage('['.__CLASS__.'] Parametre '.$paramName.' non trouve !') ; return false ; }
		elseif(isset($this->params[$paramName])) return $this->params[$paramName] ;
	}
	/**
	 * Explicite le nom d'un filtre a utiliser
	 *
	 * @param string $filterName
	 */
	function useFilter($filterName)
	{
		if(method_exists($this,$filterName)){ $this->filtersTuUse[] = $filterName ; }
		else $this->app->setMessage('Filtre inexistant : '.$filterName ) ;
	}
	
	function getFilteredKeywods()
	{
		if(sizeof($this->filtersTuUse))
		{
			foreach($this->filtersTuUse as $filter)
			{
				$this->keywords = $this->$filter($this->keywords) ;
			}
		}
		// Renvoi des mots cles echappes
		return $this->cleanString($this->keywords) ;
	}
}
/**
 * Toutes ces fonctions sont statiques
 * Elles sont appelees en guise de filtre
 * Elles doivent toutes renvoyer l'expression filtree
 * 
 * @author Arnault SOIZEAU, asoizeau@agence-clark.com
 * @copyright 2007-10-12
 * @version 1.00a
 * @see db_search_filter
 */
class db_search_filter_common {
	/**
	 * Supression des articles pour ne pas les prendre en compte dans la recherche
	 *
	 * @param string $string
	 * @return string
	 */
	function removeArticles($string)
	{
		$articles = array(
			'fr' => array(	'le' , 'la' , '\'' , 'les' , 'de' , 'du' , 'un' , 'des' ),
			'uk' => array(	),
			) ;
		if(sizeof($articles[$this->getProfilParameter('Language')]))
		{
			foreach($articles[$this->getProfilParameter('Language')] as $art)
			{
				$string = preg_replace( '!\b('.$art.')\b!' , '' , $string );
			}
		}
		// Supression des espaces superflus
		return preg_replace( '!\s+!',' ', $string ) ;
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
?>