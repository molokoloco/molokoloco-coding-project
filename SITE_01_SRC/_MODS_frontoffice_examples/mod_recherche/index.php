<?

$keyword = clean($_GET['recherche_recherche']);

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
	<h1 class="t_page"><?=aff(_RECHERCHE_);?></h1>
	
	<div class="page">
		<h2><span><?=$obj->nbResultats;?></span> <?=aff(__RESULTATS_POUR_LA_RECHERCHE__);?> <span>&quot;<?=aff($_SESSION[SITE_CONFIG]['keyword']);?>&quot;</span></h2>
		<?=$pageHtml;?>
		<? 
		for ($i=$debut; $i<$debut+$offset; $i++)
			echo strHighlight($_SESSION[SITE_CONFIG]['results'][$i], $keyword, 0);
		?>
		<?=$pageHtml;?>
	</div>
	
</div>