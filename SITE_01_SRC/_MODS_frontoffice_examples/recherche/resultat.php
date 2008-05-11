<?

/*
niak
Arnault, hate de rentrer...   dit :
oublie pas de virer :
"->autoFrontOfficeUpdate(true);",
Julien dit :
aie... ok
Arnault, hate de rentrer...   dit :
et vire 
if( method_exists( $this->app , 'query' ) ) $sql_accessor =& $this->app ;
elseif( method_exists( $this , 'query' ) ) $sql_accessor =& $this ;
mets un accesseur propre
Julien dit :
je me sert pas de ca si ?
j'ai refait dans inirecharche()
initrecherche
Arnault, hate de rentrer...   dit :
ca par contre c nul  
for ($i=$debut; $i<$debut+$offset; $i )
			echo strHighlight($_SESSION[SITE_CONFIG]['results'][$i], $keyword, 0);
dans resultat.php
Julien dit :
mais non ca dechire  
 
 

elle fait 300 requete la recherche normal que je stock en session
Arnault, hate de rentrer...   dit :
foreach( $results $as $sql_table => $res )
{
   if( count( $res ) == 0 ) continue ;
   foreach( $res as $result )
   {
       echo $result ;
   }
}

*/

$keyword = clean($_GET['recherche']);

if (!empty($keyword)) {

	$_SESSION[SITE_CONFIG]['keyword'] = $keyword;

	$obj = initSearch($keyword);
	$results = $obj->execute(false);
	
	$_SESSION[SITE_CONFIG]['results'] = array();
	foreach($results as $table)
		foreach($table as $row)
			if (!empty($row)) $_SESSION[SITE_CONFIG]['results'][] = $row;

}

$total = count($_SESSION[SITE_CONFIG]['results']);
if ($total > 0) { // Pagination
	$pagination = 5;
	$pageArr = makePage($_GET['page'], $total, $pagination, thisPage('','',array('page','recherche')), 'premier', '', '', '<div class="pagination">Pages&nbsp;', '</div>', 'page');
	$pageHtml = $pageArr['pageHtml'];
	$page = $pageArr['page'];
	$offset = $pageArr['offset'];
	$debut = $pageArr['debut'];
}

?><div id="resultats">
	<h1 class="t_page">Recherche</h1>
	<div class="page">
		<h2><span><?=$obj->nbResultats;?></span> résultats pour la recherche <span>&quot;<?=aff($_SESSION[SITE_CONFIG]['keyword']);?>&quot;</span></h2>
		<?=$pageHtml;?>
		<? 
		for ($i=$debut; $i<$debut+$offset; $i++)
			echo strHighlight($_SESSION[SITE_CONFIG]['results'][$i], $keyword, 0);
		?>
		<?=$pageHtml;?>
	</div>
</div>