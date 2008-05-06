<?php
/*--------------------------------------------------------------------------------
  *  Fichier de configuration type de la class DatabaseSearch
  *--------------------------------------------------------------------------------
*/
if( method_exists( $this->app , 'query' ) ) $sql_accessor =& $this->app ;
elseif( method_exists( $this , 'query' ) ) $sql_accessor =& $this ;

if( is_file( TPL_PTH.C_SITE_FOLDER.'/search_results_bloc.html' ) ) $templateResultats = join('',file(TPL_PTH.C_SITE_FOLDER.'/search_results_bloc.html')) ;

$lng = method_exists( $this->app , 'getSiteLanguage' )? $this->app->getSiteLanguage() : array( 'fr' , 'uk' ); 

$DataBaseSearch_Param = array("->addKeyWordFilter('removeArticles');") ;
						
$count = 0 ;

foreach( $lng as $language )
{
	if( $language == 'fr' ) $suffixe = '' ;
	else $suffixe = '_'.$language ;
	
	// Liste des différents ids de blocs de contenus dans les onglets historique et présentation de la section Entités détail
	$idList = $___ = $cntverEntitiesIds = array() ;
	$cnt_idt_pres = $sql_accessor->query('SELECT DISTINCT(entdet_pre_id) As cnt_idt FROM `entity_details`') ;
	if(sizeof($cnt_idt_pres)) { foreach($cnt_idt_pres as $pres){ $idList[] = intval($pres['cnt_idt']) ; } }
	$cnt_idt_his = $sql_accessor->query('SELECT DISTINCT(entdet_his_id) As cnt_idt FROM `entity_details`') ;
	if(sizeof($cnt_idt_his)) { foreach($cnt_idt_his as $his){ $idList[] = intval($his['cnt_idt']) ; } }
	// On retire les zeros (donc sans contenus) et on dédoublonne
	$idList = array_unique($idList);
	unset($idList[array_search('0',$idList)]);
	// Obtention des cnt_ver_idt , avec cntver_num A 1 pour un contenu en production, et lng_idt dans la langue courante
	$___ = $sql_accessor->query('SELECT cntver_idt FROM `content_versions` WHERE `cntver_num`="1" AND lng_idt="'.$language.'" AND cnt_idt IN ('.implode(',',$idList).')') ;
	if(sizeof($___)){ foreach($___ as $cnt){ $cntverEntitiesIds[] = intval($cnt['cntver_idt']) ; } }
	if($this->debug==1) db($cntverEntitiesIds);

	// Liste des différents ids de blocs de contenus dans les onglets historique et présentation de la section offres
	$idList = $cntverOffersIds = array() ;
	$cnt_idt_focus = $sql_accessor->query('SELECT DISTINCT(off_focus_id) As cnt_idt FROM `offer`') ;
	if(sizeof($cnt_idt_focus)) { foreach($cnt_idt_focus as $focus){ $idList[] = intval($focus['cnt_idt']) ; } }
	$cnt_idt_sf = $sql_accessor->query('SELECT DISTINCT(off_sf_id) As cnt_idt FROM `offer`') ;
	if(sizeof($cnt_idt_sf)) { foreach($cnt_idt_sf as $sf){ $idList[] = intval($sf['cnt_idt']) ; } }
	$cnt_idt_det = $sql_accessor->query('SELECT DISTINCT(off_det_id) As cnt_idt FROM `offer`') ;
	if(sizeof($cnt_idt_det)) { foreach($cnt_idt_det as $det){ $idList[] = intval($det['cnt_idt']) ; } }
	$cnt_idt_ref = $sql_accessor->query('SELECT DISTINCT(off_ref_id) As cnt_idt FROM `offer`') ;
	if(sizeof($cnt_idt_ref)) { foreach($cnt_idt_ref as $ref){ $idList[] = intval($ref['cnt_idt']) ; } }
	// On retire les zeros (donc sans contenus) et on dédoublonne
	$idList = array_unique($idList);
	unset($idList[array_search('0',$idList)]);
	// Obtention des cnt_ver_idt , avec cntver_num A 1 pour un contenu en production, et lng_idt dans la langue courante
	$___ = $sql_accessor->query('SELECT cntver_idt FROM `content_versions` WHERE `cntver_num`="1" AND lng_idt="'.$language.'" AND cnt_idt IN ('.implode(',',$idList).')') ;
	if(sizeof($___)){ foreach($___ as $cnt){ $cntverOffersIds[] = intval($cnt['cntver_idt']) ; } }
	
	// v( $cntverOffersIds , false );
	// v( $cntverEntitiesIds , false );
	
	$DataBaseSearch_Profil[$count] = new search_clause('entity','ent_title'.$suffixe,_MATCH);
	$DataBaseSearch_Profil[$count]->setPrimaryKeyFieldName('ent_idt');
	$DataBaseSearch_Profil[$count]->duplicateField('ent_title'.$suffixe,'resultat_titre');
	$DataBaseSearch_Profil[$count]->duplicateField('ent_dsc'.$suffixe,'resultat_texte','truncate');
	$DataBaseSearch_Profil[$count]->duplicateField('ent_idt','href','getLinkForEntity');
	$DataBaseSearch_Profil[$count]->dontCatch('( ( B.ent_idt_father="2" AND B.ent_actm="Y" ) OR ( B.ent_idt_father!="2" AND B.ent_acte="Y" ) )');
	$DataBaseSearch_Profil[$count]->sqlLinkFormat = $templateResultats ;
	$DataBaseSearch_Profil[$count]->setDefaultValue('lireSuite',CL_TPL_SEARCH_LIRE_SUITE);
	
	// detail entité dans le CMS
	$DataBaseSearch_Profil[$count+1] = new search_clause('content_components','rubcmp_prm',_MATCH);
	$DataBaseSearch_Profil[$count+1]->setPrimaryKeyFieldName('_cnt_idt');
	$DataBaseSearch_Profil[$count+1]->dontCatch('B.cntver_idt IN ('.implode(',',$cntverEntitiesIds).')');
	$DataBaseSearch_Profil[$count+1]->sqlLinkFormat = $templateResultats ;
	$DataBaseSearch_Profil[$count+1]->duplicateField('_cnt_idt','','GetTitleSearchResultForEntity');
	$DataBaseSearch_Profil[$count+1]->duplicateField('rubcmp_prm','resultat_texte','truncate');
	$DataBaseSearch_Profil[$count+1]->setDefaultValue('lireSuite',CL_TPL_SEARCH_LIRE_SUITE);
	
	// Recherche sur les offres
	$DataBaseSearch_Profil[$count+2] = new search_clause('content_components','rubcmp_prm',_MATCH);
	$DataBaseSearch_Profil[$count+2]->setPrimaryKeyFieldName('_cnt_idt');
	$DataBaseSearch_Profil[$count+2]->dontCatch('B.cntver_idt IN ('.implode(',',$cntverOffersIds).')');
	$DataBaseSearch_Profil[$count+2]->sqlLinkFormat = $templateResultats ;
	$DataBaseSearch_Profil[$count+2]->duplicateField('_cnt_idt','','GetTitleSearchResultForEntity');
	$DataBaseSearch_Profil[$count+2]->duplicateField('rubcmp_prm','resultat_texte','truncate');
	$DataBaseSearch_Profil[$count+2]->setDefaultValue('lireSuite',CL_TPL_SEARCH_LIRE_SUITE);
	
	// Recherche sur les documents de presse
	$DataBaseSearch_Profil[$count+3] = new search_clause('press_document','art_title'.$suffixe,_LIKE) ;
	$DataBaseSearch_Profil[$count+3]->setPrimaryKeyFieldName('art_idt');
	$DataBaseSearch_Profil[$count+3]->duplicateField('art_title','resultat_titre','');
	$DataBaseSearch_Profil[$count+3]->duplicateField('art_dsc'.$suffixe,'resultat_texte','truncate');
	$DataBaseSearch_Profil[$count+3]->duplicateField('art_idt','href','GetTitleSearchResultForPressDocs');
	$DataBaseSearch_Profil[$count+3]->sqlLinkFormat = $templateResultats ;
	$DataBaseSearch_Profil[$count+3]->setDefaultValue('lireSuite',CL_TPL_SEARCH_LIRE_SUITE);
	
	$count += 4 ;
}
?>